<?php

// DB Params
require "db-connect.php";

// Connect to DB
$link = mysqli_connect($host, $user, $password, $db);

if (mysqli_connect_errno()) {
  http_response_code(204);
  return;
}

// TODO: Add and handle GET params to filter data
$query = "SELECT * FROM $table";
$rows = array();

if ($result = mysqli_query($link, $query)) {
  while ($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
  }
}
else {
  mysqli_close($link);
  http_response_code(204);
  return;
}

mysqli_close($link);

echo json_encode($rows);

?>
