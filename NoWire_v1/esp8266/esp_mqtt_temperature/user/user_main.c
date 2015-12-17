/*
 *  Example of working sensor DHT22 (temperature and humidity) and send data to MQTT
 *
 *  For a single device, connect as follows:
 *  DHT22 1 (Vcc) to Vcc (3.3 Volts)
 *  DHT22 2 (DATA_OUT) to ESP Pin GPIO2
 *  DHT22 3 (NC)
 *  DHT22 4 (GND) to GND
 *
 *  Between Vcc and DATA_OUT needs to connect a pull-up resistor of 10 kOhm.
 *
 *  (c) 2015 by Mikhail Grigorev <sleuthhound@gmail.com>
 *
 */
#include "ets_sys.h"
#include "driver/uart.h"
#include "driver/dht22.h"
#include "osapi.h"
#include "include/mqtt.h"
#include "wifi.h"
#include "config.h"
#include "include/debug.h"
#include "user_interface.h"
#include "mem.h"
#include "espconn.h"

MQTT_Client mqttClient;
LOCAL os_timer_t dhtTimer;
LOCAL os_timer_t hbTimer;

static char topic_temp[100];
static char topic_hum[100];

void pb1Transmit();

LOCAL void ICACHE_FLASH_ATTR application_heartbeat(void){



	struct ip_info ipConfig;
	wifi_get_ip_info(STATION_IF, &ipConfig);

	char tempreg[200];
	uint32_t newIP = 0;

	newIP |= ((ipConfig.ip.addr & 0xFF) << 24);
	newIP |= ((ipConfig.ip.addr & 0xFF00) << 8);
	newIP |= ((ipConfig.ip.addr & 0xFF0000) >> 8);
	newIP |= ((ipConfig.ip.addr & 0xFF000000) >> 24);

	os_sprintf(tempreg, MQTT_CLIENT_ID_ONL, system_get_chip_id(), newIP);

	MQTT_Publish(&mqttClient, MQTT_WATCHDOG, tempreg, strlen(tempreg), 0, 0);

	pb1Transmit();
}

void wifi_connect_cb(uint8_t status)
{
	if(status == STATION_GOT_IP){
		MQTT_Connect(&mqttClient);
	} else {
		MQTT_Disconnect(&mqttClient);
	}
}
void mqtt_connected_cb(uint32_t *args)
{
	MQTT_Client* client = (MQTT_Client*)args;
	INFO("MQTT: Connected\r\n");

	char tempreg[200];
	os_sprintf(tempreg, MQTT_TOPICOUT, system_get_chip_id());

    MQTT_Subscribe(client, MQTT_WATCHDOG, 0);
    MQTT_Subscribe(client, tempreg, 1);

	application_heartbeat();
}

void mqtt_disconnected_cb(uint32_t *args)
{
	MQTT_Client* client = (MQTT_Client*)args;
	INFO("MQTT: Disconnected\r\n");
}

void mqtt_published_cb(uint32_t *args)
{
	MQTT_Client* client = (MQTT_Client*)args;
	INFO("MQTT: Published\r\n");
}

void mqtt_data_cb(uint32_t *args, const char* topic, uint32_t topic_len, const char *data, uint32_t data_len)
{
	char *topicBuf = (char*)os_zalloc(topic_len+1),
			*dataBuf = (char*)os_zalloc(data_len+1);

	MQTT_Client* client = (MQTT_Client*)args;

	os_memcpy(topicBuf, topic, topic_len);
	topicBuf[topic_len] = 0;

	os_memcpy(dataBuf, data, data_len);
	dataBuf[data_len] = 0;

	INFO("Receive topic: %s, data: %s \r\n", topicBuf, dataBuf);

	// fetch if light needs to be set to on
	char tempreg[200];
	os_sprintf(tempreg, MQTT_TOPICOUT, system_get_chip_id());

	// if string matches check payload
	if(strcmp(topicBuf, tempreg) == 0){
		// topic matches, check payload

		if(strcmp(dataBuf, "1-1")){
			GPIO_OUTPUT_SET(13, 1);
		} else if(strcmp(dataBuf, "1-0")){
			GPIO_OUTPUT_SET(13, 0);
		}
	}

	os_free(topicBuf);
	os_free(dataBuf);
}

LOCAL void ICACHE_FLASH_ATTR dhtCb(void *arg)
{
	static char data[256];
	static char temp[10];
	static char hum[10];
	uint8_t status;
	os_timer_disarm(&dhtTimer);
	struct dht_sensor_data* r = DHTRead();
	float curTemp = r->humidity;
	float curHum = r->temperature;
	//static float lastTemp;
	//static float lastHum;
	//uint8_t topic[32];
	if(r->success)
	{
		os_sprintf(temp, "1-%d.%d",(int)(curTemp),(int)((curTemp - (int)curTemp)*100));
		os_sprintf(hum, "1-%d.%d",(int)(curHum),(int)((curHum - (int)curHum)*100));
		INFO("Temperature: %s *C, Humidity: %s %%\r\n", temp, hum);
		if (mqttClient.connState == MQTT_DATA) {
			//os_sprintf(topic, "%s%s", config.mqtt_topic, "temperature");
			MQTT_Publish(&mqttClient, topic_temp, temp, strlen(temp), 0, 0);
			//lastTemp = curTemp;
		}
		if (mqttClient.connState == MQTT_DATA) {
			//os_sprintf(topic, "%s%s", config.mqtt_topic, "humidity");
			MQTT_Publish(&mqttClient, topic_hum, hum, strlen(hum), 0, 0);
			//lastHum = curHum;
		}
	}
	else
	{
		INFO("Error reading temperature and humidity.\r\n");
	}


	os_timer_setfn(&dhtTimer, (os_timer_func_t *)dhtCb, (void *)0);
	os_timer_arm(&dhtTimer, DELAY, 1);
}

void pb1Transmit(){
	char tempreg[200];
	os_sprintf(tempreg, MQTT_TOPICIN, system_get_chip_id());

	if(GPIO_INPUT_GET(4)){
		MQTT_Publish(&mqttClient, tempreg, "1-1", strlen("1-1"), 0, 0);
	} else {
		MQTT_Publish(&mqttClient, tempreg, "1-0", strlen("1-0"), 0, 0);
	}
}

void interrupt_test(){
	// disable interrupts
	ETS_GPIO_INTR_DISABLE();

	uint32_t gpio_status = GPIO_REG_READ(GPIO_STATUS_ADDRESS);
	GPIO_REG_WRITE(GPIO_STATUS_W1TC_ADDRESS, gpio_status);

	// publish that button has been pressed
	INFO("Button pressed!!");

	pb1Transmit();

	//gpio_pin_intr_state_set(GPIO_ID_PIN(4), GPIO_PIN_INTR_POSEDGE);

	// enable interrupts
	ETS_GPIO_INTR_ENABLE();
}

void user_init(void)
{
	uart_init(BIT_RATE_230400, BIT_RATE_230400);
	system_set_os_print(1); // enable/disable operating system printout

	os_sprintf(topic_temp, MQTT_TOPICTEMP, system_get_chip_id());
	os_sprintf(topic_hum, MQTT_TOPICHUM, system_get_chip_id());

	PIN_FUNC_SELECT(PERIPHS_IO_MUX_MTCK_U, FUNC_GPIO13);


	   ETS_GPIO_INTR_DISABLE(); // Disable gpio interrupts
	   ETS_GPIO_INTR_ATTACH(interrupt_test, 4);
	   PIN_FUNC_SELECT(PERIPHS_IO_MUX_GPIO4_U, FUNC_GPIO4);
	   gpio_output_set(0, 0, 0, GPIO_ID_PIN(4)); // Set GPIO12 as input
	   GPIO_REG_WRITE(GPIO_STATUS_W1TC_ADDRESS, BIT(4));
	   gpio_pin_intr_state_set(GPIO_ID_PIN(4), GPIO_PIN_INTR_ANYEDGE);
	   ETS_GPIO_INTR_ENABLE(); // Enable gpio interrupts

	config_load();

	DHTInit(DHT11);

	MQTT_InitConnection(&mqttClient, config.mqtt_host, config.mqtt_port, config.security);
	MQTT_InitClient(&mqttClient, config.device_id, config.mqtt_user, config.mqtt_pass, config.mqtt_keepalive, 1);
	MQTT_InitLWT(&mqttClient, "lwt/", "offline", 0, 0);
	MQTT_OnConnected(&mqttClient, mqtt_connected_cb);
	MQTT_OnDisconnected(&mqttClient, mqtt_disconnected_cb);
	MQTT_OnPublished(&mqttClient, mqtt_published_cb);
	MQTT_OnData(&mqttClient, mqtt_data_cb);

	WIFI_Connect(config.sta_ssid, config.sta_pwd, wifi_connect_cb);

	os_timer_disarm(&dhtTimer);
	os_timer_setfn(&dhtTimer, (os_timer_func_t *)dhtCb, (void *)0);
	os_timer_arm(&dhtTimer, DELAY, 1);

	os_timer_disarm(&hbTimer);
	os_timer_setfn(&hbTimer, (os_timer_func_t *)application_heartbeat, (void *)0);
	os_timer_arm(&hbTimer, 60000, 1);



	INFO("\r\nSystem started ...\r\n");
}

void user_rf_pre_init(void) {}
