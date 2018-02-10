# Nest DataGraph

This project try to fill the biggest Nest Thermostat gap, data history using Python and LAMP stack.   

It allow you to browse your Nest thermostat history data with some pretty charts, analyze your consumption and keep a eye on your environment.

Feel free to create a pull request, open an issue or fork it !

## Features

* Polls Nest API to fetch thermostat data
* Consumptions data are stored in a local database
* Generates (Google) Charts with your consumptions
* Select specific date ranges
* Hover over charts to get the exact timestamp and temperature
* Verify your Nest Protect devices state
* And more !

![Overview](https://github.com/gchenuet/nest-datagraph/raw/master/README/nest-datagraph.jpg "Overview")   

## Installation

### Prerequisite

* [Nest](https://nest.com) Account
* [OpenWeatherMap](http://openweathermap.org/) API Key

For Docker version:
* [Docker](https://docs.docker.com/engine/installation/)
* [Docker Compose](https://docs.docker.com/compose/install/)

### Docker Setup

If you want to use Nest Datagraph with Docker, please follow these steps:

* Fill the `frontend/conf/settings.ini` file with your configuration:
    * `timezone` - Your timezone (Ex: 'Europe/Paris')
    * `units` - Choose your temperature units (C or F)
    * `owm_id` - OpenWeatherMap API Key
    * `owm_city_id` - Your city ID (Ex: '2988507')
    * `nest_username` - Nest login
    * `nest_password` - Nest password
    * `nest_sn` - Nest Thermostat serial number
    * `nest_protect` - Set true if you Nest Protect product
    * `mysql_username` - MySQL user
    * `mysql_password` - MySQL password
    * `mysql_hostname` = IP or FQDN of your MySQL server
    * `mysql_database` = Database name


* _(Opt)_ Edit the docker-compose.yml file to specify your ENV variables.

* Build Docker image:

	```
	docker-compose build
	```

* Run Project
	```
	docker-compose up -d
	```

### Classic Setup

_This example is based on Debian Jessie with NGinx/PHP-FPM web server and MariaDB database engine._

* Update your package lists and any pending updates before starting:					

	```
	sudo apt-get update
	```

	```
	sudo apt-get upgrade -y
	```

* Install required packages:

	```
	sudo apt-get install nginx php5-fpm php5-mysql php5-curl mariadb-server mariadb-client git python-pip python-dev build-essential
	```

	```
	pip install -r setup/requirements.txt
	```

* Clone the repository:
	```
	cd /opt && git clone git@github.com:gchenuet/nest-datagraph.git
	```

* Setup NGinx Virtual Host:
	```
	sudo cp /opt/nest-datagraph/setup/nginx/nest-datagraph.conf /etc/nginx/sites-available/
	```

	```
	sudo ln -s /etc/nginx/sites-available/nest-datagraph.conf /etc/nginx/sites-enabled/nest-datagraph.conf
	```

* Modify the `server_name` parameter with your FQDN in _nest-datagraph.conf_	:
	```
	vim /etc/nginx/sites-enabled/nest-datagraph.conf
	```

* Reload NGinx				
	```
	sudo service nginx reload
	```

* Create a new MySQL user:
	```
	CREATE USER '[username]'@'localhost' IDENTIFIED BY '[password]';
	```

	```
	GRANT ALL PRIVILEGES ON * . * TO '[username]'@'localhost';
	```

* Create the database and grant permissions:
	```
	CREATE DATABASE nest_datagraph;
	```

	```
	GRANT ALL PRIVILEGES ON nest_datagraph.* TO '[username]'@'localhost';
	```

* Import the DB schema:
	```
	sudo mysql -h <HOST> -u <USER> -p<PASSWORD> nest_datagraph < /opt/nest-datagraph/setup/db/nest-datagraph.sql
	```



* Open the crontab and add the line at the end of the file:
	```
	crontab -e
	```

	```
	0   *   *   *   *   /usr/bin/python /opt/nest-datagraph/backend/poller.py
	```


* Fill in variables with your parameters in `frontend/conf/settings.ini`:
  * `timezone` - Your timezone (Ex: 'Europe/Paris')
  * `units` - Choose your temperature units (C or F)
  * `owm_id` - OpenWeatherMap API Key
  * `owm_city_id` - Your city ID (Ex: '2988507')
  * `nest_username` - Nest login
  * `nest_password` - Nest password
  * `nest_sn` - Nest Thermostat serial number
  * `nest_protect` - Set true if you Nest Protect product
  * `mysql_username` - MySQL user
  * `mysql_password` - MySQL password
  * `mysql_hostname` = IP or FQDN of your MySQL server
  * `mysql_database` = Database name

      
* Enjoy (and wait a hour) !

## Features & Known Issues

You can find and post new features or known issues in the ``Issues`` tab.

## Contributing     

1. Fork it
2. Create your feature branch ``(git checkout -b my-new-feature)``
3. Commit your changes ``(git commit -am 'Added some feature')``
4. Push to the branch ``(git push origin my-new-feature)``
5. Create new Pull Request

## Report a bug   

1. Create an new issue
2. Explain with details your issue
3. Add your configuration (OS, Software version, etc)
4. (Opt) Attach files
5. Submit !

## Acknowledgements

* Scott M Baker, http://www.smbaker.com/ for https://github.com/smbaker/pynest
* Guillaume Boudreau, https://www.pommepause.com for https://github.com/gboudreau/nest-api
* Google for Google Chart, https://developers.google.com/chart/
* Nest for images and logos, https://nest.com
