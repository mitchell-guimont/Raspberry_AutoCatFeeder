#!/usr/bin/python

# Automatic Cat Feeder
# Version 1.0
# By Mitchell Guimont
#
# Usage: ./feed_cat.py api_key is_extra early_feed person_id

import logging
log_format = '%(levelname)s | %(asctime)-15s | %(message)s'
logging.basicConfig(filename='/var/www/python_scripts/logs/feed_cat.log', filemode='a', format=log_format, level=logging.DEBUG)
from RPIO import PWM
import sys
import datetime
import time
import sqlite3

apiKey=""

# GPIO pin for both servos 
# Direction reference: looking straight at dispenser
leftServo=23
leftServoTime=.5
rightServo=18
rightServoTime=.7

def servo_CW(ServoPIN):
	# Set servo on ServoPIN to 1200us (1.2ms)
	# This rotates the servo CW.
	servo = PWM.Servo()
	servo.set_servo(ServoPIN, 1200)
	if ServoPIN == leftServo:
		time.sleep(leftServoTime)
	else:
		time.sleep(rightServoTime)
	servo.stop_servo(ServoPIN)
	time.sleep(.25)
	
def feed_cat(is_extra, early_feed, person_id):
	if is_extra == '1':
		isExtra=1
	else:
		isExtra=0
		
	if early_feed == '1':
		earlyFeed=1
	else:
		earlyFeed=0

	currentTime = datetime.datetime.now().time()

	conn = sqlite3.connect('/var/www/automatic_cat_feeder_v1_0.db')
	c = conn.cursor()

	# Get last feed log
	c.execute("SELECT log_datetime, is_extra, is_early, side FROM feed_log ORDER BY log_datetime DESC LIMIT 1")

	# Set which side to use based on previous side
	useSide="leftServo"
	lastFeedWasEarly=0
	for row in c:
		lastFeedWasEarly = int(row[2])
		if row[3] == "leftServo":
			useSide="rightServo"

	feedCat=False

	if isExtra == 1 or earlyFeed == 1:
		feedCat=True
	else:
		# Check feeding schedule
		c.execute("SELECT feed_schedule_id FROM feed_schedule WHERE feed_hour = ? AND feed_minute = ?", (currentTime.hour, currentTime.minute))
		feed_schedule=c.fetchone()
		if feed_schedule is not None:
			# Time to feed but first check to see if cat was fed early
			if lastFeedWasEarly == 0:
				feedCat=True
			
			# Cat was feed early but we still need to made an entry in the log but set is_cat_feed = 0
			if not feedCat:
				# Reset side back to previous side because no food was dispensed
				if useSide == "leftServo":
					sameSide="rightServo"
				else:
					sameSide="leftServo"
				
				c.execute("INSERT INTO feed_log (log_datetime, is_extra, is_early, side, person_id, is_cat_feed) VALUES (datetime(CURRENT_TIMESTAMP, 'localtime'),0,0,?,?,0)", (sameSide, int(person_id)))
				conn.commit()

	if feedCat:
		logging.info("Feeding cat")
		servo_CW(eval(useSide))
		c.execute("DELETE FROM feed_log WHERE log_datetime < DATETIME('now', '-1 month')")
		c.execute("INSERT INTO feed_log (log_datetime, is_extra, is_early, side, person_id, is_cat_feed) VALUES (datetime(CURRENT_TIMESTAMP, 'localtime'),?,?,?,?,1)", (isExtra, earlyFeed, useSide, int(person_id)))
		conn.commit()

	conn.close()
		
if __name__ == '__main__':	
	logging.info("Automatic Cat Feeder v1.0 - Start")
	if len(sys.argv) != 5:
		logging.error("Usage: ./feed_cat.py api_key is_extra early_feed person_id")
		sys.exit(2)
	else:
		if sys.argv[1] != apiKey:
			logging.error("Permission denied.")
		else:
			feed_cat(sys.argv[2], sys.argv[3], sys.argv[4])