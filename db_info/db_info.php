<?php

/*

データベース名：hurakome


com_table -> コメント情報を格納するテーブル

CREATE TABLE IF NOT EXISTS `com_table` (
  `id` int(255) NOT NULL,
  `comment` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display` tinyint(1) DEFAULT NULL,
  `reply` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `com_table`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `com_table`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;



user_table -> ユーザー情報を格納するテーブル

CREATE TABLE IF NOT EXISTS `user_table` (
  `id` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `user_table`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;


*/

$host = 'localhost';
$user = 'root';
$pass = 'root';
$db_name = 'hurakome';
