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

import configparser
import datetime
import mysql.connector
from nest import Nest
import os
import pyowm
import sys


def to_f(temp):
    return temp*1.8 + 32.0


def polling(c, n, w, d):
    nstat = n.show_status()
    if c['common']['units'] == "F":
        owmTemp = to_f(w.get_temperature('celsius')['temp'])
        nestCurrent = to_f(nstat['current_temperature'])
        nestTarget = to_f(nstat['target_temperature'])
    else:
        owmTemp = w.get_temperature('celsius')['temp']
        nestCurrent = nstat['current_temperature']
        nestTarget = nstat['target_temperature']

    query = "INSERT INTO status(date,city_curr_temp,city_curr_hum, \
             nest_curr_temp,nest_targ_temp,nest_curr_hum,nest_targ_hum, \
             nest_heat_state, current_schedule_mode, leaf,auto_away, \
             time_to_target) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
    args = (datetime.datetime.now(),
            owmTemp,
            w.get_humidity(),
            nestCurrent,
            nestTarget,
            nstat['current_humidity'],
            nstat['target_humidity'],
            int(nstat['hvac_heater_state']),
            nstat['current_schedule_mode'],
            int(nstat['leaf']),
            int(nstat['auto_away']),
            nstat['time_to_target'])
    d.execute(query, args)


def main():
    try:
        c = configparser.ConfigParser()
        c.read(os.path.join(os.path.abspath(os.path.dirname(__file__)),
                            '../frontend/conf',
                            'settings.ini'))

        # Setup Nest account
        n = Nest(c['nest']['nest_username'],
                 c['nest']['nest_password'],
                 c['nest']['nest_sn'],
                 c['nest']['nest_index'],
                 units=c['common']['units'])
        n.login()
        n.get_status()
        # Setup OpenWeatherMap account
        owm = pyowm.OWM(c['owm']['owm_id'])
        observation = owm.weather_at_id(int(c['owm']['owm_city_id']))
        w = observation.get_weather()
        # Connect to DB
        cnx = mysql.connector.connect(user=c['mysql']['mysql_username'],
                                      password=c['mysql']['mysql_password'],
                                      host=c['mysql']['mysql_hostname'],
                                      database=c['mysql']['mysql_database'])
        d = cnx.cursor()
        polling(c, n, w, d)
        cnx.commit()
        d.close()
    except Exception:
        print(sys.exc_info()[1])

if __name__ == "__main__":
    main()
