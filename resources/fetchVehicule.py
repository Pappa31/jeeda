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
import json
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
sys.path.insert(0, parentdir+"/resources/jeedad/")

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
     
PRINTRESPONSE = True
MILES = False
INTERVAL = 20
_log_level = "debug"
RESOURCES = [
		"position",
        "request_in_progress",
        "request_results",
        "requests_remaining",
        "model"
]

def addDictData(prop, func, attrJson,vehicle):
    if (prop != "attrs"):
        dct = eval(func)
        for key,value in dct.items():            
            attrJson[f"{prop}_{key}"] = value

async def main():
    try:
        jeedom_utils.set_log_level(_log_level)
        if len(sys.argv) == 3:
            logging.debug("Retrieve HTTP Session")
            session = ClientSession(headers={'Connection': 'keep-alive'})
            logging.debug("Connected to skoda")
            connection = Connection(session, sys.argv[1], sys.argv[2], PRINTRESPONSE)
            await connection.doLogin()
            logging.debug("Connexion ok")
            logging.debug("Get vehicule associated to "+ sys.argv[1])
            await connection.get_vehicles()
            logging.debug("Update data from all vehicules")
            await connection.update_all()
            vehiculesJson = []
            
            for vehicle in connection.vehicles:
                vehiculeJson = {}
                featuresJson = {}
                methodJson = []
                attrJson = {}
                logging.info("\tVIN: %s", vehicle.vin)
                logging.info("\tModel: %s", vehicle.model)
                logging.info("\tManufactured: %s", vehicle.model_year)
                logging.info("\tConnect service deactivated: {vehicle.deactivated}")
                attrJson["VIN"] = vehicle.vin
                
                for prop in dir(vehicle):
                    if not "__" in prop:
                        try:
                            if not prop.startswith("_"):
                                func = f"vehicle.{prop}"
                                if not "is_" in prop:                                    
                                    typ = str(type(eval(func)))
                                    #if isinstance(eval(func), (str, int, float)):
                                    if typ.startswith("<class 'int'>") or typ.startswith("<class 'str'>") or typ.startswith("<class 'float'>") or typ.startswith("<class 'bool'>"):
                                        func2 = f"vehicle.is_{prop}_supported"                                    
                                        if eval(func2):
                                            attrJson[prop] = eval(func)
                                    elif typ.startswith("<class 'method'>"):
                                        if prop.startswith("set_"):
                                            methodJson.append(prop)
                                    elif typ.startswith("<class 'dict'>"):
                                        addDictData(prop, func, attrJson, vehicle)
                                    else:
                                        logging.info("\tProperty not supporter :{prop} - {typ}")
                                elif prop.startswith("is_"):
                                    if eval(func) is not None:
                                        featuresJson[prop] = eval(func)
                        except:
                            pass
                vehiculeJson["attrs"] = attrJson
                vehiculeJson["features"] = featuresJson
                vehiculeJson["methods"] = methodJson
                vehiculesJson.append(vehiculeJson)
            print(json.dumps(vehiculesJson, default=str))
            await connection.logout()
            await connection.terminate() 
            await session.close()
        else:
            logging.error("Error : missed login / pwd")
            exit(2)
    except Exception as e:
        logging.error(f'Error encountered ' + str(e))
        print(f'Error encountered ' + str(e))
        exit(1)

if __name__ == "__main__":
    loop = asyncio.new_event_loop()
    loop.run_until_complete(main())
