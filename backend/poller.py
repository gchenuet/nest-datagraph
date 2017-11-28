#!/usr/bin/python

# poller.py -- a python interface for polling Nest Thermostat data
# into a database.
# by Guillaume Chenuet, guillaume@chenuet.fr, http://yauuu.me/
#
# Usage:
#    'poller.py' will fill a new row in the database
#
# Licensing:
#    This is distributed unider the Creative Commons 3.0 Non-commecrial,
#    Attribution, Share-Alike license. You can use the code for noncommercial
#    purposes. You may NOT sell it. If you do use it, then you must make an
#    attribution to me (i.e. Include my name and thank me for the hours I spent
#    on this)

import pyowm
import datetime
from nest import Nest
import mysql.connector
import ConfigParser
import os

config = ConfigParser.ConfigParser()
config.read(os.path.join(os.path.dirname(__file__), "../config/settings.ini"))
OWM = config.get('owm', 'owm_key').strip('\"\'')
OWM_CITY = config.get('owm', 'owm_city').strip('\"\'')
NEST_ID = config.get('nest', 'nest_username').strip('\"\'')
NEST_PWD = config.get('nest', 'nest_password').strip('\"\'')
NEST_SN = config.get('nest', 'nest_sn').strip('\"\'')
DB_USER = config.get('mysql', 'mysql_username').strip('\"\'')
DB_PWD = config.get('mysql', 'mysql_password').strip('\"\'')
DB_HOST = config.get('mysql', 'mysql_hostname').strip('\"\'')
DB_NAME = config.get('mysql', 'mysql_database').strip('\"\'')

def polling(n, w, d):
    nstat = n.show_status()
    query = "INSERT INTO status(date,city_curr_temp,city_curr_hum, \
             nest_curr_temp,nest_targ_temp,nest_curr_hum,nest_targ_hum, \
             nest_heat_state, current_schedule_mode, leaf,auto_away, \
             time_to_target) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
    args = (datetime.datetime.now(),
            w.get_temperature('celsius')['temp'],
            w.get_humidity(),
            nstat['current_temperature'],
            nstat['target_temperature'],
            nstat['current_humidity'],
            nstat['target_humidity'],
            int(nstat['hvac_heater_state']),
            nstat['current_schedule_mode'],
            int(nstat['leaf']),
            int(nstat['auto_away']),
            nstat['time_to_target']);
    d.execute(query, args)

def main():
    #Setup Nest account
    n = Nest(NEST_ID, NEST_PWD, NEST_SN, 0, "C")
    n.login()
    n.get_status()
    # Setup OpenWeatherMap account
    owm = pyowm.OWM(OWM)
    observation = owm.weather_at_place(OWM_CITY)
    w = observation.get_weather()
    # Connect to DB
    cnx = mysql.connector.connect(user=DB_USER, password=DB_PWD,
                                  host=DB_HOST, database=DB_NAME)
    d = cnx.cursor()
    polling(n, w, d)
    cnx.commit()
    d.close()

if __name__ == "__main__":
    main()
