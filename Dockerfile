FROM debian:jessie-slim
MAINTAINER Guillaume Chenuet <guillaume@chenuet.fr>

# Update base image & install packages
RUN apt-get update && apt-get install -y \
    nginx \
    php5-fpm \
    php5-mysql \
    php5-curl \
    mariadb-client \
    supervisor \
    build-essential \
    python-dev \
    python-pip \
    crudini

# Tweak nginx config
RUN sed -i -e"s/worker_processes  1/worker_processes 5/" /etc/nginx/nginx.conf && \
sed -i -e"s/keepalive_timeout\s*65/keepalive_timeout 2/" /etc/nginx/nginx.conf && \
sed -i -e"s/keepalive_timeout 2/keepalive_timeout 2;\n\tclient_max_body_size 100m/" /etc/nginx/nginx.conf && \
echo "daemon off;" >> /etc/nginx/nginx.conf

# Tweak php-fpm config
RUN sed -i -e "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g" /etc/php5/fpm/php.ini && \
sed -i -e "s/upload_max_filesize\s*=\s*2M/upload_max_filesize = 100M/g" /etc/php5/fpm/php.ini && \
sed -i -e "s/post_max_size\s*=\s*8M/post_max_size = 100M/g" /etc/php5/fpm/php.ini && \
sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php5/fpm/php-fpm.conf && \
sed -i -e "s/;catch_workers_output\s*=\s*yes/catch_workers_output = yes/g" /etc/php5/fpm/pool.d/www.conf && \
sed -i -e "s/pm.max_children = 5/pm.max_children = 9/g" /etc/php5/fpm/pool.d/www.conf && \
sed -i -e "s/pm.start_servers = 2/pm.start_servers = 3/g" /etc/php5/fpm/pool.d/www.conf && \
sed -i -e "s/pm.min_spare_servers = 1/pm.min_spare_servers = 2/g" /etc/php5/fpm/pool.d/www.conf && \
sed -i -e "s/pm.max_spare_servers = 3/pm.max_spare_servers = 4/g" /etc/php5/fpm/pool.d/www.conf && \
sed -i -e "s/pm.max_requests = 500/pm.max_requests = 200/g" /etc/php5/fpm/pool.d/www.conf

# Fix ownership of sock file for php-fpm
RUN sed -i -e "s/;listen.mode = 0660/listen.mode = 0750/g" /etc/php5/fpm/pool.d/www.conf && \
find /etc/php5/cli/conf.d/ -name "*.ini" -exec sed -i -re 's/^(\s*)#(.*)/\1;\2/g' {} \;

# Apply NGinx configuration
RUN rm -f /etc/nginx/sites-available/default
ADD setup/nginx/nest-datagraph.conf /etc/nginx/sites-available/default.conf
RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf

# Copy project
RUN mkdir /opt/nest-datagraph
ADD . /opt/nest-datagraph/

# Setup Python modules
ADD setup/requirements.txt setup/requirements.txt
RUN pip install -r setup/requirements.txt

# Create crontab
ADD setup/docker/crontab /etc/cron.hourly/nest-datagraph

# Supervisor Config
ADD setup/docker/supervisord.conf /etc/supervisor/supervisord.conf

# Start Supervisord
ADD setup/docker/scripts/entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh

CMD ["/bin/bash", "/entrypoint.sh"]
