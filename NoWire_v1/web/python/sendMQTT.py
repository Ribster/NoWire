#!/usr/bin/python
import paho.mqtt.client as mqtt
import sys

mqttc = mqtt.Client("python_pub")
mqttc.connect("localhost", 7777)
mqttc.publish(sys.argv[1], sys.argv[2])
mqttc.loop(2)

sys.exit(0)