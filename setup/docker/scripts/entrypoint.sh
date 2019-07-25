#!/bin/bash
set -eo pipefail

[[ "${DEBUG}" == true ]] && set -x

check_database_connection() {
  echo "Attempting to connect to database ..."
  prog="mysqladmin -h ${DB_HOST} -u ${DB_USERNAME} ${DB_PASSWORD:+-p$DB_PASSWORD} -P ${DB_PORT} status"
  timeout=60
  while ! ${prog} >/dev/null 2>&1
  do
    timeout=$(( timeout - 1 ))
    if [[ "$timeout" -eq 0 ]]; then
      echo
      echo "Could not connect to database server! Aborting..."
      exit 1
    fi
    echo -n "."
    sleep 1
  done
  echo
}

init_mysqldb() {
    echo "Import DB Schema"
    mysql -h "${DB_HOST}" -u "${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}" < /opt/nest-datagraph/setup/db/nest-datagraph.sql
}

initialize_system() {
  echo "Initializing Nest Datagraph container ..."

  DB_HOST=${DB_HOST:-mariadb}
  DB_DATABASE=${DB_DATABASE:-nest_datagraph}
  DB_USERNAME=${DB_USERNAME:-nest_datagraph}
  DB_PASSWORD=${DB_PASSWORD:-n3st_d4t4gr4ph}

  # configure env file
  crudini --set --existing /opt/nest-datagraph/frontend/conf/settings.ini mysql mysql_hostname "${DB_HOST}"
  crudini --set --existing /opt/nest-datagraph/frontend/conf/settings.ini mysql mysql_database "${DB_DATABASE}"
  crudini --set --existing /opt/nest-datagraph/frontend/conf/settings.ini mysql mysql_username "${DB_USERNAME}"
  crudini --set --existing /opt/nest-datagraph/frontend/conf/settings.ini mysql mysql_password "${DB_PASSWORD}"
}

poller_conf() {
  echo "Running Nest data poller 1st start ..."
  /usr/bin/python /opt/nest-datagraph/backend/poller.py
  touch /etc/crontab /etc/cron.*/*
  service cron start
}

start_system() {
  initialize_system
  check_database_connection
  init_mysqldb
  poller_conf
  echo "Starting Nest Datagraph! ..."
  /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
}

start_system

exit 0
