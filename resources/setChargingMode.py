#!/usr/bin/env python3

# This file is part of plugin Jeeda  for Jeedom. 
#
#   Jeeda is free software: you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation, either version 3 of the License, or
#  (at your option) any later version.
# 
#  Jeeda is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY. See the
#  GNU General Public License for more details.
# 
#  You should have received a copy of the GNU General Public License. 
#  If not, see <http://www.gnu.org/licenses/>.
#


import asyncio
import logging
import inspect
import time
import sys
import os
from aiohttp import ClientSession


# Recupere l'ensemble des informations du compte skoda
# retourne sur la console un tableau JSON avec tous les véhicules du compte
# Pour chaque véhicule fournit 
# - attrs : l'ensemble des informations récupérées, 
# - methods : la liste des méthodes qui déclenche une action sur la voiture 
# - features: les fonctionnalités de la voiture


# ajout du repertoire courant dans le path systeme pour detecter le module dans repertoire skodaconnect
currentdir = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe())))
parentdir = os.path.dirname(currentdir)
sys.path.insert(0, parentdir)

# Verification dependance skoda connect
try:
    from skodaconnect import Connection
except ModuleNotFoundError as e:
    print(f"Unable to import library: {e}")
    sys.exit(1)
#Verification dependance Jeedom
try:
	from jeedom.jeedom import jeedom_utils
except ImportError:
	print("Error: importing module jeedom.jeedom")
	sys.exit(1)
     
PRINTRESPONSE = False
MILES = False
INTERVAL = 20
_log_level = "error"


async def main():
    print(len(sys.argv))
    try:
        jeedom_utils.set_log_level(_log_level)
        if len(sys.argv) == 5:
            logging.debug("Retrieve HTTP Session")
            session = ClientSession(headers={'Connection': 'keep-alive'})
            logging.debug("Connected to skoda")
            connection = Connection(session, sys.argv[1], sys.argv[2], PRINTRESPONSE)
            await connection.doLogin()
            logging.debug("Connexion ok")
            print(f"Unable to import library: {sys.argv[3]} mode {sys.argv[4]}")
            connection.setCharging(sys.argv[3],"start")
            time.sleep(20)
            await connection.logout()
            await connection.terminate() 
            await session.close()
        else:
            logging.error("Error parameters")
            exit(2)
    except Exception as e:
        logging.error(f'Error encountered ' + str(e))
        print(f'Error encountered ' + str(e))
        exit(1)

if __name__ == "__main__":
    loop = asyncio.new_event_loop()
    loop.run_until_complete(main())