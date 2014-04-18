# Pi Feeder
=======================

## About
Pi Feeder is a automatic feeding web application designed to dispense food at scheduled times. The web interface allows a user to preform an early feed, extra feed, and view the feeding log. In order to interact with the feeder, a user must first login.

## Database Schema
The database is setup using SQLite3, with the following schema:
```sql
CREATE TABLE "feed_log" ("feed_log_id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "log_datetime" DATETIME NOT NULL, "is_extra" BOOL NOT NULL  DEFAULT 0, "is_early" BOOL NOT NULL  DEFAULT 0, side VARCHAR NOT NULL, person_id INTEGER NOT NULL, is_cat_feed BOOL NOT NULL);

CREATE TABLE "feed_schedule" ("feed_schedule_id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "feed_hour" INTEGER NOT NULL, feed_minute INTEGER NOT NULL);

CREATE TABLE person (person_id INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , username VARCHAR NOT NULL  UNIQUE , password VARCHAR NOT NULL , is_active BOOL NOT NULL  DEFAULT 0, salt varchar);
```