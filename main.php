<?php

define('SITE_ROOT', dirname(__FILE__));

require '/opt/libapi/main.php';
$api = new API;

require SITE_ROOT . '/config.php'; // sets db config variables
$db = new DB;
