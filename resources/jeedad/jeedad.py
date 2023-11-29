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

import logging
import string
import sys
import os
import time
import datetime
import traceback
import re
import signal
from optparse import OptionParser
from os.path import join
import json
import argparse
import threading
import globals
import asyncio
from aiohttp import ClientSession


# Verification dependance skoda connect
try:
    from skodaconnect import Connection
except ModuleNotFoundError as e:
    print(f"Unable to import library: {e}")
    sys.exit(1)

try:
	from jeedom.jeedom import *
except ImportError:
	print("Error: importing module jeedom.jeedom")
	sys.exit(1)

class jeedaDemon:
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
	TOKENS = {
		'technical': 'TOKENDATA',
		'connect': 'TOKENDATA',
		'vwg': None,    # You can store and restore this refresh token but it's more robust to reauth the vwg client
		'cabs': None,   # Not sure if this is used and in that case for what
		'dcs': None,    # Smartlink
	}

	def __init__(self) -> None:
		logging.debug("Create new deamon")
		self.state = 1

	# Ajout des informations de type dictionnaire dans le modele JSON
	def addDictData(self, prop, func, attrJson,vehicle):
		if (prop != "attrs"):
			dct = eval(func)
			for key,value in dct.items():            
				attrJson[f"{prop}_{key}"] = value

	def getDataFromSkoda(self):
		logging.debug("Start thread")
		self.login_success = False
		try:
			loop = asyncio.new_event_loop()
			asyncio.set_event_loop(loop)
		#	logging.debug("Retrieve HTTP Session")
		#	self.session = ClientSession(headers={'Connection': 'keep-alive'})
		#	logging.debug("Connect to skoda")
		#	self.connection = Connection(self.session, globals.user, globals.pwd, self.PRINTRESPONSE)
		#	asyncio.get_event_loop().run_until_complete(self.connection.doLogin())

		#	self.login_success = True
		#	logging.debug("Connexion ok")
		except Exception as e:
			logging.error(f'Error encountered during connexion to skoda ' + str(e))
			self.login_success = False
			raise e

		while self.state:
			logging.debug("Retrieve HTTP Session")
			self.session = ClientSession(headers={'Connection': 'keep-alive'})
			logging.debug("Connect to skoda")
			self.connection = Connection(self.session, globals.user, globals.pwd, self.PRINTRESPONSE)

			if asyncio.get_event_loop().run_until_complete(self.connection.restore_tokens(self.TOKENS)):
				logging.debug("Token restore succeeded")
				self.login_success = True
			if not self.login_success:
				asyncio.get_event_loop().run_until_complete(self.connection.doLogin())
				self.login_success = True
				logging.debug("Connexion ok")

			if self.login_success:
				logging.debug("Wake up, retrieve information from Skoda server, running %s" , self.state)
				try:
					if self.login_success:
						asyncio.get_event_loop().run_until_complete(self.connection.get_vehicles())
						logging.debug("Update data from all vehicules")
						asyncio.get_event_loop().run_until_complete(self.connection.update_all())
						vehiculesJson = []
						for vehicle in self.connection.vehicles:
							vehiculeJson = {}
							featuresJson = {}
							methodJson = []
							attrJson = {}
							logging.info("\tVIN: %s", vehicle.vin)
							logging.info("\tModel: %s", vehicle.model)
							logging.info("\tManufactured: %s", vehicle.model_year)
							logging.info("\tConnect service deactivated: %s", vehicle.deactivated)
							attrJson["VIN"] = vehicle.vin
							
							for prop in dir(vehicle):
								if not "__" in prop:
									try:
										if not prop.startswith("_"):
											func = f"vehicle.{prop}"
											if not "is_" in prop:                                    
												typ = str(type(eval(func)))
												if typ.startswith("<class 'int'>") or typ.startswith("<class 'str'>") or typ.startswith("<class 'float'>") or typ.startswith("<class 'bool'>"):
													func2 = f"vehicle.is_{prop}_supported"                                    
													if eval(func2):
														attrJson[prop] = eval(func)
												elif typ.startswith("<class 'method'>"):
													if prop.startswith("set_"):
														methodJson.append(prop)
												elif typ.startswith("<class 'dict'>"):
													self.addDictData(prop, func, attrJson, vehicle)
												else:
													logging.info("\tProperty not supporter : %s - %s", prop,typ)
											elif prop.startswith("is_"):
												if eval(func) is not None:
													featuresJson[prop] = eval(func)
									except:
										pass
							vehiculeJson["attrs"] = attrJson
							logging.debug('Moving : %s', attrJson["vehicle_moving"])
							if not attrJson["vehicle_moving"] == None:
								if attrJson["vehicle_moving"] == False:
									globals.CYCLE = globals.PARKING_INFO_CYCLE
									logging.info("Sleeping for parking mode")
									if not attrJson["charging"] == None:
										if attrJson["charging"] == True:
											if attrJson["charging_power"] != None:
												if attrJson["charging_power"] > 11000:
													globals.CYCLE = globals.DRIVE_INFO_CYCLE
													logging.info("Sleeping for drive mode because charging over 11KW")
								else:
									globals.CYCLE = globals.DRIVE_INFO_CYCLE
									logging.info("Sleeping for drive mode")
							else:
								globals.CYCLE = globals.PARKING_INFO_CYCLE
							vehiculeJson["features"] = featuresJson
							vehiculeJson["methods"] = methodJson
							vehiculesJson.append(vehiculeJson)
						# send data to skoda plugin 
						jeedom_com.send_change_immediate({'skodaData' : json.dumps(vehiculesJson, default=str) })
				except Exception as e:
					logging.error('Erreur reading socket : %s', e)
			else:
				logging.debug("Wake up but not connected !!!" , self.state)
			time.sleep(globals.CYCLE)

	def terminate(self):
		logging.debug("Terminate deamon")
		loop = asyncio.get_event_loop()
		asyncio.set_event_loop(loop)
		asyncio.get_event_loop().run_until_complete(self.connection.logout())
		asyncio.get_event_loop().run_until_complete(self.connection.terminate() )
		asyncio.get_event_loop().run_until_complete(self.session.close())
		self.state = 0

def read_socket():
	global JEEDOM_SOCKET_MESSAGE
	if not JEEDOM_SOCKET_MESSAGE.empty():
		try:
			logging.debug("Message received in socket JEEDOM_SOCKET_MESSAGE")
			message = json.loads(jeedom_utils.stripped(JEEDOM_SOCKET_MESSAGE.get()))
			if message['apikey'] != _apikey:
				logging.error("Invalid apikey from socket: %s", message)
				return
			try:
				print ('read')
			except Exception as e:
				logging.error('Send command to demon error: %s' ,e)
		except Exception as e:
			logging.error('Erreur reading socket : %s', e)

def listen():
	jeedom_socket.open()
	globals.jeedaDemon = jeedaDemon()
	try:
		x = threading.Thread(target=globals.jeedaDemon.getDataFromSkoda)
		x.start()
		while 1:
			time.sleep(0.5)
			read_socket()
	except KeyboardInterrupt:
		shutdown()

# ----------------------------------------------------------------------------

def handler(signum=None, frame=None):
	logging.debug("Signal %i caught, exiting...", int(signum))
	shutdown()

def shutdown():
	logging.debug("Shutdown")
	logging.debug("Removing PID file %s", _pidfile)
	try:
		logging.debug("Close connexion to Skoda server")
		globals.jeedaDemon.terminate()
	except e:
		logging.debug("Error while stopping connexion to Skoda Server %s", e)
		pass
	try:
		os.remove(_pidfile)
	except:
		pass
	try:
		jeedom_socket.close()
	except:
		pass
	logging.debug("Exit 0")
	sys.stdout.flush()
	os._exit(0)

# ----------------------------------------------------------------------------

_log_level = "error"
_socket_port = 51978
_socket_host = 'localhost'
#_device = 'auto'
_pidfile = '/tmp/demond.pid'
_apikey = ''
_callback = ''


parser = argparse.ArgumentParser(description='Jeeda Daemon for Jeeda plugin')
#parser.add_argument("--device", help="Device", type=str)
parser.add_argument("--loglevel", help="Log Level for the daemon", type=str)
parser.add_argument("--callback", help="Callback", type=str)
parser.add_argument("--apikey", help="Apikey", type=str)
parser.add_argument("--standardCycle", help="Cycle to pull data in standard mode", type=str)
parser.add_argument("--driveCycle", help="Cycle to pull data in drive mode", type=str)
parser.add_argument("--pid", help="Pid file", type=str)
parser.add_argument("--socketport", help="Port for Zigbee server", type=str)
parser.add_argument("--user", help="login pour le serveur skoda", type=str)
parser.add_argument("--pwd", help="Mot de passe pour le serveur skoda", type=str)
args = parser.parse_args()

#if args.device:
#	_device = args.device
if args.loglevel:
    _log_level = args.loglevel
if args.callback:
    _callback = args.callback
if args.apikey:
    _apikey = args.apikey
if args.pid:
    _pidfile = args.pid

if args.socketport:
	_socketport = args.socketport

if args.user:
	globals.user = args.user

if args.pwd:
	globals.pwd = args.pwd

if args.standardCycle:
	globals.PARKING_INFO_CYCLE = int(args.standardCycle)

if args.driveCycle:
	globals.DRIVE_INFO_CYCLE = int(args.driveCycle)

_socket_port = int(_socket_port)

jeedom_utils.set_log_level(_log_level)

logging.info('Start demond')
logging.info('Log level: %s', _log_level)
logging.info('Socket port: %s', _socket_port)
logging.info('Socket host: %s', _socket_host)
logging.info('PID file: %s', _pidfile)
logging.info('Apikey: %s', _apikey)
#logging.info('Device: %s', _device)

signal.signal(signal.SIGINT, handler)
signal.signal(signal.SIGTERM, handler)

try:
	jeedom_utils.write_pid(str(_pidfile))
	jeedom_com = jeedom_com(apikey = _apikey,url = _callback,cycle=0.3)
	if not jeedom_com.test():
		logging.error('Network communication issues. Please fixe your Jeedom network configuration.')
		shutdown()
	jeedom_socket = jeedom_socket(port=_socket_port,address=_socket_host)
	listen()
except Exception as e:
	logging.error('Fatal error: %s', e)
	logging.info(traceback.format_exc())
	shutdown()
