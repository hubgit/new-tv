<?php

require_once 'config.inc.php';

mysql_connect('localhost', $db['user'], $db['pass']); // SERVER, DB USERNAME, DB PASSWORD
mysql_select_db('newtv'); // DATABASE
mysql_query('SET NAMES utf8');
