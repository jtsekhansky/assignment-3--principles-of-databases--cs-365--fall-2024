DROP DATABASE IF EXISTS student_passwords;

CREATE DATABASE student_passwords DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

CREATE USER IF NOT EXISTS 'passwords_user'@'localhost' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON student_passwords.* TO 'passwords_user'@'localhost';

USE student_passwords;
SET block_encryption_mode = 'aes-256-cbc';
SET @key_str = UNHEX(SHA2('secret password secret password ', 256));
SET @init_vector = X'C777333AAFABDFEA1988998985546789';

CREATE TABLE websites (
  website_name    VARCHAR(100)    ,
  website_url     VARCHAR(128)    NOT NULL,
  PRIMARY KEY (website_name)
);

CREATE TABLE users (
  user_name        VARCHAR(45)    ,
  first_name      VARCHAR(45)     NOT NULL,
  last_name       VARCHAR(45)     NOT NULL,
  email           VARCHAR(45)    NOT NULL,
  PRIMARY KEY (user_name)
);

CREATE TABLE logins (
  user_name        VARCHAR(45)    ,
  website_name     VARCHAR(100)    NOT NULL,
  password        VARBINARY(512)    NOT NULL,
  comment         VARCHAR(2000)    NOT NULL,
  update_time     DATETIME    NOT NULL,
  PRIMARY KEY (user_name, website_name)
);

INSERT INTO websites
  (website_name, website_url)
Values
  ("bing", "https://www.bing.com"),
  ("hartford", "https://www.hartford.edu"),
  ("cheshire high school", "https://www.cheshire.k12.ct.us"),
  ("olympic taekwondo academy", "https://otacheshire.com"),
  ("cheshire public library", "https://www.cheshirelibrary.org"),
  ("toyota", "https://www.toyota.com"),
  ("BJs wholesale club", "https://www.bjs.com"),
  ("Stop and Shop", "https://stopandshop.com"),
  ("Costco", "https://www.costco.com"),
  ("Amazon", "https://www.amazon.com");

INSERT INTO users
  (user_name, first_name, last_name, email)
VALUES
  ("jtisawesome", "jacob", "tsekhansky", "j@bing.com"),
  ("collegegood", "jacob", "tsekhansky", "j@h0rtfurd.com"),
  ("schoolgreat", "david", "tsekhansky", "d@cheshire.com"),
  ("blackbelt", "joe", "schmoe", "j@schmoe.com"),
  ("bookwarrior", "rahn", "kazowski", "rkazowski@books.org"),
  ("bestdriver", "lexi", "brimmings", "lbrims@toyota.com"),
  ("gurl479", "leah", "stephens", "stephens@gmail.com"),
  ("saleshopstop", "brenna", "mullings", "mulls@stopshop.com"),
  ("savingbrands", "rahn", "kazowski", "kaz@brand.com"),
  ("warehouselifter", "lexi", "brimmings", "bestlift@atoz.com");

INSERT INTO logins
  (user_name, website_name, password, comment, update_time)
VALUES
  ("jtisawesome", "bing", aes_encrypt('dirtman', @key_str, @init_vector), "no comment", '2024-09-28 17:00:00'),
  ("collegegood", "hartford", aes_encrypt('roomdorm', @key_str, @init_vector), "nice school", '2024-09-28 17:00:00'),
  ("schoolgreat", "cheshire high school", aes_encrypt('moreclass', @key_str, @init_vector), "nice school", '2024-09-28 17:00:00'),
  ("blackbelt", "olympic taekwondo academy", aes_encrypt('nobelt', @key_str, @init_vector), "many different rankings", '2014-08-26 08:00:00'),
  ("bookwarrior", "cheshire public library", aes_encrypt('bookmark', @key_str, @init_vector), "too many books", '2021-06-03 08:00:00'),
  ("bestdriver", "toyota", aes_encrypt('goodspeed', @key_str, @init_vector), "comfortable cars", '2023-06-09 08:00:00'),
  ("gurl479", "BJs wholesale club", aes_encrypt('dropshop', @key_str, @init_vector), "variety of fruit", '2022-05-14 08:00:00'),
  ("saleshopstop", "Stop and Shop", aes_encrypt('shopstop', @key_str, @init_vector), "lots of food", '2016-12-01 08:00:00'),
  ("savingbrands", "Costco", aes_encrypt('coupon', @key_str, @init_vector), "lots of food", '2008-04-15 08:00:00'),
  ("warehouselifter", "Amazon", aes_encrypt('boxes', @key_str, @init_vector), "free shipping", '2020-06-10 08:00:00');

