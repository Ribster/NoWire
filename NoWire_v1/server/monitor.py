#!/usr/bin/python

import paho.mqtt.client as mqtt
import MySQLdb
import time
from time import sleep
from array import *

ts = time.time()

db = MySQLdb.connect(host="localhost", # your host, usually localhost
                     user="nowire", # your username
                      passwd="secret", # your password
                      db="NoWire") # name of the data base
db.autocommit(True)



# The callback for when the client receives a CONNACK response from the server.
def on_connect(client, userdata, flags, rc):
    print("Connected with result code "+str(rc))

    # Subscribing in on_connect() means that if we lose the connection and
    # reconnect then subscriptions will be renewed.
    client.subscribe("sensors")

# The callback for when a PUBLISH message is received from the server.
def on_message(client, userdata, msg):
	#print("the client is: " + str(client))
	#print("the userdata is: " + str(userdata))
	if(msg.topic == "sensors"):

		# split the payload in their seperate parts
		words = str(msg.payload).split();
		print("got heartbeat from: " + words[1])

		# set the module online
		setOnline(words[1], db)

		# look up the module in the online list
		test = 0
		cur = db.cursor()
		cur.execute("SELECT moduleIdentifier FROM `NoWire`.`wifimodule_online`")
		for row in cur.fetchall():
			if(row[0] == words[1]):
				test = 1

		if(test == 1):
			# already in list, update timestamp online list
			cur = db.cursor()
			cur.execute("UPDATE `NoWire`.`wifimodule_online` SET `timestamp` =" + str(time.time()) + " WHERE moduleIdentifier='" + words[1] + "'")
		else:
			# not yet in the list, add module to the list
			temp = words[2]
			cur = db.cursor()
			cur.execute("INSERT INTO `NoWire`.`wifimodule_online` (`moduleIdentifier`, `ipv4`, `timestamp`) VALUES ('" + words[1] + "', " + str(temp[3:len(temp)]) + ", " + str(time.time()) + ")")
			
			# subscribe to all topics
			subscribeTopicsSensors(words[1], db)
		
		# get all the outputs of this module, send state current database state to the module
		publishOutputStates(words[1], db)

		
	else:
		# get every esp topic online in query
		# loop over each and upon match relay them to the database
		cur = db.cursor()
		cur.execute("SELECT concat_ws('/','sensors',`wifimodule`.`moduleIdentifier`,`sensortype`.`topic`) FROM `NoWire`.`sensor`" +
			"LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`" +
			"LEFT JOIN `NoWire`.`wifimodule` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID`")
		for row in cur.fetchall():
			# got the right message
			if(msg.topic == row[0]):
				# monitor the actions
				monitorActions(db, msg.topic, msg.payload)
				#print(str(msg.topic) + ": " + str(msg.payload))

				words = str(msg.topic).split("/");
					# 0 is sensors
					# 1 is ESPxxxxx
					# 2 is sensor topic
				# get a list for sensors matching the ESP and sensor topic
				querySQL = ("SELECT `sensor`.`ID`"
							"FROM NoWire.sensor "
							"LEFT JOIN `NoWire`.`wifimodule` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID`"
							"LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`"
							"WHERE `wifimodule`.`moduleIdentifier` = '"
							+ words[1] +
							"' AND"
							"`sensortype`.`topic` = '"
							+ words[2] +
							"'ORDER BY `sensor`.`ID`")
				cur2 = db.cursor()
				cur2.execute(querySQL)

				# split the payload on "-"
				payloadSplit = str(msg.payload).split("-")
					# 0 is iteration of sensor
					# 1 is the value
				# get the iteration
				iterMatch = int(payloadSplit[0])

				iterCounter = 0

				for row2 in cur2.fetchall():
					iterCounter = iterCounter + 1
					if(iterCounter == iterMatch):
						# we have a match on the ID of sensor
						idSensor = row2[0]
						# now we can update the value of this sensor
						querySQLUpdate = ("UPDATE `NoWire`.`sensor`"
											"SET"
											"`value` = '"
											+ payloadSplit[1] +
											"'"
											"WHERE `sensor`.`ID`='"
											+ str(idSensor) +
											"';")
						cur3 = db.cursor()
						cur3.execute(querySQLUpdate)
						# get last entry of sensor in sensor_data
						querySQLSecond = ("SELECT `sensor_data`.`ID`, `sensor_data`.`IDsensor`, `sensor_data`.`value`, `sensor_data`.`from`, `sensor_data`.`to` "
									"FROM NoWire.sensor_data "
									"WHERE `sensor_data`.`IDsensor` = '"
									+ str(idSensor) +
									"' AND `sensor_data`.`to` IS NULL ORDER BY `sensor_data`.`from` DESC "
									"LIMIT 1")
						cur4 = db.cursor()
						cur4.execute(querySQLSecond)
						if(cur4.rowcount == 1):
							for row4 in cur4.fetchall():
								#print row4[4]
								if(row4[4]):
									# if the to time is filled in, insert new
									querySQLFourth = ("INSERT INTO `NoWire`.`sensor_data`"
														"(`IDsensor`, `value`, `from`) VALUES ('"
														+ str(idSensor) +
														"', '"
														+ payloadSplit[1] +
														"', NOW());")
									cur6 = db.cursor()
									cur6.execute(querySQLFourth)
								else:
									# if the to time is null
										# if the value is the same, do nothing
									if(float(row4[2]) != float(payloadSplit[1])):
										# if the value is different, get the ID of the sensor_data and close the entry


										querySQLThird = ("UPDATE `NoWire`.`sensor_data`"
															"SET `to`=NOW(), `from`=`from` WHERE `ID`='"
															+ str(row4[0]) +
															"';")
										cur5 = db.cursor()
										cur5.execute(querySQLThird)
											# make new entry with value, id en from
										querySQLFourth = ("INSERT INTO `NoWire`.`sensor_data`"
															"(`IDsensor`, `value`, `from`) VALUES ('"
															+ str(idSensor) +
															"', '"
															+ payloadSplit[1] +
															"', NOW()+0.1);")
										cur6 = db.cursor()
										cur6.execute(querySQLFourth)
						else:
							querySQLFourth = ("INSERT INTO `NoWire`.`sensor_data`"
												"(`IDsensor`, `value`, `from`) VALUES ('"
												+ str(idSensor) +
												"', '"
												+ payloadSplit[1] +
												"', NOW());")
							cur6 = db.cursor()
							cur6.execute(querySQLFourth)


client = mqtt.Client()
client.on_connect = on_connect
client.on_message = on_message
client.connect("127.0.0.1", 7777, 60)

def check_onlinestates():
	print("checking online states")
	#check every module in online database list and compare the timestamp
	cur = db.cursor()
	cur.execute("SELECT ID, moduleIdentifier, ipv4, timestamp FROM NoWire.wifimodule_online")
	for row in cur.fetchall():
		# if 1:30 has passed
		if(row[3] < (time.time() - 90)):
			# delete online entry
			cur2 = db.cursor()
			cur2.execute("DELETE FROM wifimodule_online WHERE ID=" + str(row[0]))
			# update wifi module online status
			cur2 = db.cursor()
			cur2.execute("UPDATE `NoWire`.`wifimodule` SET `online` = 0 WHERE moduleIdentifier='" + row[1] + "'")
			# update open entry wifi online status
			setOffline(row[1], db)
			# update open entry sensor values
			setOfflineSensors(row[1], db)
			# send message
			print "The module "+row[1]+" is set to the offline state"

def getModuleID(moduleIdentifier, database):
	cur = database.cursor()
	cur.execute("SELECT `wifimodule`.`ID` FROM NoWire.wifimodule WHERE `wifimodule`.`moduleIdentifier` = '" + str(moduleIdentifier) +"'")
	for row in cur.fetchall():
		return int(row[0])

def setOnline(moduleIdentifier, database):
	cur = database.cursor()
	cur.execute("UPDATE `NoWire`.`wifimodule` SET `online` = 1 WHERE moduleIdentifier=\'" + moduleIdentifier + "'")

	modID = getModuleID(moduleIdentifier, database)
	if(modID != 0):
		# got an existing module
		cur = database.cursor()
		cur.execute("SELECT `wifimodule_data`.`ID`, `wifimodule_data`.`fromOnline`, `wifimodule_data`.`toOnline` "
					"FROM NoWire.wifimodule_data "
					"WHERE `wifimodule_data`.`IDwifimodule` = '" + str(modID) + "'"
					" ORDER BY `wifimodule_data`.`fromOnline` DESC "
					"LIMIT 1")
		if(cur.rowcount == 0):
			cur2 = database.cursor()
			cur2.execute("INSERT INTO `NoWire`.`wifimodule_data` "
						"(`IDwifimodule`,`fromOnline`) VALUES ('" + str(modID) + "', NOW());")
		else:	
			for row in cur.fetchall():
				if row[2] != None:
					# should insert a new value
					cur2 = database.cursor()
					cur2.execute("INSERT INTO `NoWire`.`wifimodule_data` "
								"(`IDwifimodule`,`fromOnline`) VALUES ('" + str(modID) + "', NOW());")

def subscribeTopicsSensors(moduleIdentifier, database):
	cur = db.cursor()
	cur.execute("SELECT distinct(`sensortype`.`topic`) FROM `NoWire`.`sensor`" + \
		" LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`" + \
		" LEFT JOIN `NoWire`.`wifimodule` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID` WHERE `wifimodule`.`moduleIdentifier` = '" + moduleIdentifier + "'")
	for row in cur.fetchall():
		#subscribe to all the topics
		client.subscribe("sensors/" + moduleIdentifier + "/" + str(row[0]))

def setOffline(moduleIdentifier, database):
	modID = getModuleID(moduleIdentifier, database)
	if(modID != 0):
		# got an existing module
		cur = database.cursor()
		cur.execute("SELECT `wifimodule_data`.`ID`, `wifimodule_data`.`fromOnline`, `wifimodule_data`.`toOnline` "
					"FROM NoWire.wifimodule_data "
					"WHERE `wifimodule_data`.`IDwifimodule` = '" + str(modID) + "'"
					" ORDER BY `wifimodule_data`.`fromOnline` DESC "
					"LIMIT 1")
		for row in cur.fetchall():
			if row[2] is None:
				print ("closing module " + moduleIdentifier)
				# should insert a new value
				cur2 = database.cursor()
				cur2.execute("UPDATE `NoWire`.`wifimodule_data` SET `toOnline` = NOW() WHERE `wifimodule_data`.`ID` = '" + str(row[0]) + "';")

def publishOutputStates(moduleIdentifier, database):
	moduleID = getModuleID(moduleIdentifier, database)
	# get a list of all output sensors
	cur = database.cursor()
	cur.execute("SELECT `ID` FROM `NoWire`.`sensor` WHERE `sensor`.`IDwifimodule` = " + str(moduleID))
	for row in cur.fetchall():
		# every sensor ID from the module
		sensID = int(row[0])
		# get the sensor type
		sensSoort = getSensorSoort(sensID, database)
		if (sensSoort == "licht"):
			# get the topic	
			sensTopic = getSensorTopic(sensID, database)
			# get the payload
			sensPayload = getSensorPayload(sensID, database)
			# publish
			client.publish(sensTopic, sensPayload)
					

def getSensorPayload(sensorID, database):
	# get the sensor nth occurence
	nthOccurence = 0
	nIterator = 1
	sensTypeID = getSensorTypeID(sensorID, database)
	sensWifiID = getSensorWifiModuleID(sensorID, database)
	# get the iterator
	cur = database.cursor()
	cur.execute("SELECT `sensor`.`ID` FROM `NoWire`.`sensor` WHERE `sensor`.`IDwifimodule` = " + str(sensWifiID) + " AND `sensor`.`IDtype` = " + str(sensTypeID) + " ORDER BY `sensor`.`ID`;")
	for row in cur.fetchall():
		#count
		if(int(row[0]) == int(sensorID)):
			nthOccurence = nIterator
		else:
			nIterator = nIterator + 1

	# get the sensor current value (int)
	curValue = int(getSensorValue(int(sensorID), database))

	# return composite
	return str(nthOccurence) + "-" + str(curValue)

def getSensorTypeID(sensorID, database):
	cur = database.cursor()
	cur.execute("SELECT `IDtype` FROM `NoWire`.`sensor` WHERE ID=" + str(sensorID) + " LIMIT 1")
	for row in cur.fetchall():
		return str(row[0])

def getSensorValue(sensorID, database):
	cur = database.cursor()
	cur.execute("SELECT `value` FROM `NoWire`.`sensor` WHERE `sensor`.`ID` = " + str(sensorID))
	for row in cur.fetchall():
		return float(row[0])

def getSensorSoort(sensorID, database):
	cur = database.cursor()
	cur.execute("SELECT `sensorsoort`.`soort` "
				"FROM `NoWire`.`sensor` "
				"LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID` "
				"LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID` "
				"WHERE `sensor`.`ID` = " + str(sensorID))
	for row in cur.fetchall():
		return row[0]

def getSensorTopic(sensorID, database):
	wifiID = getSensorWifiModuleID(sensorID, database)
	cur = database.cursor()
	cur.execute("SELECT concat_ws('/', 'sensors', `wifimodule`.`moduleIdentifier`, '') as topic FROM NoWire.wifimodule WHERE `wifimodule`.`ID`=" + str(wifiID))
	topicPrefix = ""
	for row in cur.fetchall():
		topicPrefix = row[0]

	cur = db.cursor()
	cur.execute("SELECT `sensortype`.`topic` FROM NoWire.sensor LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID` WHERE `sensor`.`ID` = " + str(sensorID))
	for row in cur.fetchall():
		topicPrefix = topicPrefix + row[0]
	
	return topicPrefix

def getSensorWifiModuleID(sensorID, database):
	cur = database.cursor()
	cur.execute("SELECT `IDwifimodule` FROM `NoWire`.`sensor` WHERE `sensor`.`ID` =" + str(sensorID))
	wifiID = 0
	for row in cur.fetchall():
		wifiID = int(row[0])

	return wifiID


def setOfflineSensors(moduleIdentifier, database):
	modID = getModuleID(moduleIdentifier, database)
	if(modID != 0):
		# got an existing module
		cur = database.cursor()
		cur.execute("UPDATE `NoWire`.`sensor_data` LEFT JOIN `NoWire`.`sensor` ON `sensor_data`.`IDsensor` = `sensor`.`ID` SET `to` = NOW() "
					"WHERE `sensor_data`.`to` IS NULL AND `sensor`.`IDwifimodule` = '" + str(modID) + "'")

def monitorActions(database, topic, payload):
	# get all the topics and payloads
	cur = database.cursor()
	cur.execute("SELECT `ID` FROM `NoWire`.`wifimodule`;");
	# make variables
	wifiModules = array('i', [])
	arr_sensorID = []
	arr_payload_prefix = []
	# get the wifi module ids
	for row in cur.fetchall():
		wifiModules.append(int(row[0]))
	# get the sens id, payload and payload prefixes
	for i in wifiModules:
		cur2 = database.cursor()
		cur2.execute("SELECT `sensor`.`IDtype` "
					" FROM `NoWire`.`sensor` "
					" WHERE IDwifimodule=" + str(i) +
					" GROUP BY `sensor`.`IDtype`")
		for row in cur2.fetchall():
			cur = database.cursor()
			cur.execute("SET @rank=0;")
			cur = database.cursor()
			cur.execute("SELECT `sensor`.`ID`,"
						"concat_ws('-', @rank:=@rank+1, `sensor`.`value`) as payload, "
						"concat_ws('-', @rank, '') as payloadPrefix "
						"FROM `NoWire`.`sensor` "
						"LEFT JOIN `NoWire`.`wifimodule` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID` "
						"LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID` "
						"WHERE `wifimodule`.`ID`=" + str(i) + " AND `sensortype`.`ID`= " + str(row[0]))
			for roww in cur.fetchall():
			# add all this stuff in the arrays
				arr_sensorID += [int(roww[0])]
				arr_payload_prefix += [str(roww[2])]
	arrLength = len(arr_sensorID)
	for i in xrange(0, arrLength):
		#print "sensor id: " + str(arr_sensorID[i]) + ", sensor payload: " + str(arr_payload[i]) + ", payload prefix: " + str(arr_payload_prefix[i])
		cur = database.cursor()
		cur.execute("SELECT "
					"sSource.ID, "
				    "`sensor_koppeling`.`source_trigger_value` as sVal, "
				    "concat_ws('/', 'sensors', sMod.moduleIdentifier, tSource.`topic`) as sTopic, "
				    "`sensor_koppeling`.`target_assign_value` as tVal, "
					"sTarget.ID, "
				    "concat_ws('/', 'sensors', tMod.moduleIdentifier, tTarget.`topic`) as tTopic, "
				    "`koppelingstype`.`ID` as couplingID "
				    "FROM NoWire.sensor_koppeling "
				    "LEFT JOIN `NoWire`.`sensor` sSource ON sSource.ID = `sensor_koppeling`.`IDsensorBron` "
				    "LEFT JOIN `NoWire`.`sensor` sTarget ON sTarget.ID = `sensor_koppeling`.`IDsensorDoel` "
				    "LEFT JOIN `NoWire`.`sensortype` tSource ON tSource.ID = sSource.IDtype "
				    "LEFT JOIN `NoWire`.`sensortype` tTarget ON tTarget.ID = sTarget.IDtype "
				    "LEFT JOIN `NoWire`.`koppelingstype` ON `koppelingstype`.`ID` = `sensor_koppeling`.`IDkoppelingstype` "
				    "LEFT JOIN `NoWire`.`wifimodule_gebruikers` sUser ON sSource.IDwifimodule = sUser.`IDwifimodule` "
				    "LEFT JOIN `NoWire`.`wifimodule_gebruikers` tUser ON sTarget.IDwifimodule = tUser.`IDwifimodule` "
				    "LEFT JOIN `NoWire`.`wifimodule` sMod ON sUser.IDwifimodule = sMod.`ID` "
				    "LEFT JOIN `NoWire`.`wifimodule` tMod ON tUser.IDwifimodule = tMod.`ID`")
		
		for row in cur.fetchall():
			# get topic from query
			source_topic = str(row[2])
			
			# if topic match
			if (source_topic == topic):
				#print "topic match: " + str(source_topic)
				# get source sensor ID from query
				source_sensID = int(row[0])
				# get sensor ID from arr_sensorID
				for j in xrange(0, arrLength):
					if(int(arr_sensorID[j]) == int(source_sensID)):
						#print "sensor " + str(arr_sensorID[j]) + " matching " + str(source_sensID)
						arrIndex = j
						# temp variable
						targetID = 0
						# get target sensor ID
						target_sensID = int(row[4])
						for k in xrange(0, arrLength):
								if(arr_sensorID[k] == target_sensID):
									targetID = k
									# end for loop
									k = arrLength

						if targetID != 0:
							# get target value
							target_sensVal = row[3]
							# get the target trigger
							source_payloadTrigger = str(arr_payload_prefix[targetID])+str(int(target_sensVal))
							# if payload match
							#print "does " + str(source_topic) + "_" + str(source_payloadTrigger) + " match " + str(topic) + "_" + str(payload)
							if(source_payloadTrigger == payload):
								#print "yes"
								# get payload prefix
								#print "publishing: " + row[5] + ", " + str(arr_payload_prefix[targetID]) + str(int(target_sensVal))
								client.publish(str(row[5]), str(arr_payload_prefix[targetID]) + str(int(target_sensVal)))
						# end for loop
						j = arrLength

#all the modules that are online, clear them
cur = db.cursor()
cur.execute("SELECT ID, moduleIdentifier, ipv4, timestamp FROM NoWire.wifimodule_online")
for row in cur.fetchall():
	setOffline(row[1], db)

cur = db.cursor()
cur.execute("TRUNCATE `NoWire`.`wifimodule_online`")

#set all current modules to offline
cur = db.cursor()
cur.execute("UPDATE `NoWire`.`wifimodule` SET `online` = 0 WHERE 1=1")


# Blocking call that processes network traffic, dispatches callbacks and
# handles reconnecting.
# Other loop*() functions are available that give a threaded interface and a
# manual interface.
while client.loop(0, 0) == 0:
	if (ts < (time.time() - 90) ):
		#time is outdated, call online check states
		check_onlinestates()
		ts = time.time()
    #sleep(0.5)
	pass
