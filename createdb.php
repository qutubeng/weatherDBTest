<?php

$db_name = "weather";
$table = "summaryWeather";

// change the them if it is required
$host_name = "localhost";
$user_name = "root";
$passwrd = "12345678";

$con=mysqli_connect($host_name,$user_name,$passwrd);
// Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Create database
$sql="CREATE DATABASE IF NOT EXISTS ".$db_name;
if (mysqli_query($con,$sql)) {
	echo "Database ". $db_name ." created successfully";
} else {
	echo "Error creating database: " . mysqli_error($con);
}


if(!mysqli_select_db($con, $db_name)) die(mysql_error());

$result = "CREATE TABLE IF NOT EXISTS ". $table ." (
          `Id` int(11) NOT NULL AUTO_INCREMENT,
          `Month` varchar(10) COLLATE utf8_unicode_ci UNIQUE,
          `HighAvg` double NOT NULL,
          `LowAvg` double NOT NULL,
          `Coldest` tinyint(1) DEFAULT '0',
          `Warmest` tinyint(1) DEFAULT '0',
           PRIMARY KEY (`Id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($con, $result)) {
	echo "TABLE created.";
}
else {
	echo "Error in CREATE TABLE.";
}
