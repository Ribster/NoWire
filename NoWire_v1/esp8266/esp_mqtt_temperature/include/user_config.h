#ifndef _USER_CONFIG_H_
#define _USER_CONFIG_H_

#define CFG_HOLDER	0x00FF55A4	/* Change this value to load default configurations */
#define CFG_LOCATION	0x3C	/* Please don't change or if you know what you doing */
#define CLIENT_SSL_ENABLE

/*DEFAULT CONFIGURATIONS*/

#define MQTT_HOST			"10.0.1.75"
#define MQTT_PORT			7777
#define MQTT_BUF_SIZE		1024
#define MQTT_KEEPALIVE		60	 /*second*/

#define MQTT_CLIENT_ID		"ESP%ld"
#define MQTT_CLIENT_ID_ONL		"Online ESP%ld IP:%ld"
#define MQTT_WATCHDOG			"sensors"
#define MQTT_TOPICHUM			"sensors/ESP%ld/temp"
#define MQTT_TOPICTEMP			"sensors/ESP%ld/hum"
#define MQTT_TOPICOUT			"sensors/ESP%ld/outputdig"
#define MQTT_TOPICIN			"sensors/ESP%ld/inputdig"
#define MQTT_USER			"test"
#define MQTT_PASS			"secret"

#define STA_SSID "STATION_SSID"
#define STA_PASS "STATION_PW"
#define STA_TYPE AUTH_WPA2_PSK

#define MQTT_RECONNECT_TIMEOUT 	5	/*second*/

#define DEFAULT_SECURITY	0
#define QUEUE_BUFFER_SIZE		 		2048

#define PROTOCOL_NAMEv31	/*MQTT version 3.1 compatible with Mosquitto v0.15*/
//PROTOCOL_NAMEv311			/*MQTT version 3.11 compatible with https://eclipse.org/paho/clients/testing/*/
#endif

#define DELAY 5000	/* milliseconds */
