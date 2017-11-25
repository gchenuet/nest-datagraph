# Nest - Data Graph

This project try to fill the biggest Nest Thermostat gap, data history using Python and LAMP stack.   

It allow you to browse your Nest thermostat history data with some pretty charts, analyze your consumption and keep a eye on your environment.

Feel free to create a pull request, open an issue or fork it !

## Features

* Polls Nest API to fetch thermostat data
* Consumptions data are stored in a local database
* Generates (Google) Chart with your consumptions
* Select specific date ranges
* Hover over charts to get the exact timestamp and temperature
* Verify your Nest Protect devices state
* And more !

![Overview](https://github.com/gchenuet/nest-datagraph/raw/master/README/nest-datagraph.jpg "Overview")   

## Installation

### Prerequisite

* [Nest](https://nest.com) Account
* [OpenWeatherMap](http://openweathermap.org/) API Key

### Setup

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
	sudo apt-get install nginx php5-fpm php5-mysql mariadb-server mariadb-client git
	```
			
* Clone the repository:
	```
	cd /opt && git clone git@github.com:gchenuet/nest-datagraph.git
	```
			
* Setup NGinx Virtual Host:
	```
	sudo cp /opt/nest-datagraph/config/nginx/nest-datagraph.conf /etc/nginx/sites-available/
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
	sudo mysql -h <HOST> -u <USER> -p<PASSWORD> nest_datagraph < /opt/nest-datagraph/config/db/nest-datagraph.sql
	```
			
* Setup Nest DataGraph backend
	```
	cp /opt/nest-datagraph/backend/settings.py.example /opt/nest-datagraph/backend/settings.py
	vi /opt/nest-datagraph/backend/settings.py
	```
			
* Fill in variables with your parameters:         
    * `OWM` - OpenWeatherMap API Key
    * `OWM_CITY` - Your city (Ex: 'Paris,fr')
    * `NEST_ID` - Nest login
    * `NEST_PWD` - Nest password
    * `NEST_SN` - Nest Thermostat serial number
    * `DB_USER` - MySQL user
    * `DB_PWD` - MySQL password
    * `DB_HOST` = IP or FQDN of your MySQL server
    * `DB_NAME` = Database name
			
* Open the crontab and add the line at the end of the file:
	```
	crontab -e
	```
			
	```
	0   *   *   *   *   /usr/bin/python /opt/nest-datagraph/backend/poller.py
	```
			
* Setup Nest DataGraph frontend
	```
	cp /opt/nest-datagraph/frontend/php/params.ini.example /opt/nest-datagraph/frontend/php/params.ini
	vi /opt/nest-datagraph/frontend/php/params.ini
	```
			
* Fill in variables with your parameters:
    * `timezone` - Your timezone (Ex: 'Europe/Paris')
    * `protect` - Set to 'true' if you have one or more Nest Protect device
    * `nest_username` - Nest login
    * `nest_password` - Nest password
    * `mysql_hostname` = IP or FQDN of your MySQL server
    * `mysql_database` = Database name
    * `mysql_username` = MySQL user
    * `mysql_password` = MySQL password
			
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
