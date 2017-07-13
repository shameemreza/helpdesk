<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

define('HOST', $host);
define('PORT', $port);
define('USER', $username);
define('PASSWORD', $password);

require 'core/db.php';
require 'core/users.php';
require 'core/time.php';
require 'core/tickets.php';
require 'core/admin.php';

$database = new db;
$users = new users;
$time = new time;
$tickets = new tickets;
$admin = new admin;

if($users->signed_in()){
	  $users->account_exists();
	  $users->is_locked();
}
