<?php

// DB Params
require "db-connect.php";

// Connect to DB
$link = mysqli_connect($host, $user, $password, $db);

if (mysqli_connect_errno()) {
  http_response_code(204);
  return;
}

// Santized Post Params
$latitude  = mysqli_real_escape_string($link, $_POST["latitude"]);
$longitude = mysqli_real_escape_string($link, $_POST["longitude"]);
$timestamp = mysqli_real_escape_string($link, $_POST["timestamp"]);

// Convert to MySQL datetime
$timestamp = date('Y-m-d H:i:s', $timestamp / 1000);

$query = "INSERT INTO $table (latitude, longitude, time_stamp)
  VALUES ($latitude, $longitude, '$timestamp')";

if (!mysqli_query($link, $query)) {
  http_response_code(204);
}

mysqli_close($link);

?>
