<?php

// DB Params
require "db-connect.php";

// Connect to DB
$link = mysqli_connect($host, $user, $password, $db);

if (mysqli_connect_errno()) {
  http_response_code(204);
  return;
}

// Form query
$param_count = 0;
$query = "SELECT latitude,longitude,time_stamp FROM $table";

if (!empty($_GET['dateFrameStart'])) {
  $query .= ' WHERE time_stamp >= "' . mysqli_real_escape_string($link, $_GET['dateFrameStart']) . ' 00:00:00"';

  $param_count++;
}
if (!empty($_GET['dateFrameEnd'])) {
  $query .= ($param_count == 0 ? ' WHERE' : '');
  $query .= ($param_count > 0 ? ' AND' : '');
  $query .= ' time_stamp <= "' . mysqli_real_escape_string($link, $_GET['dateFrameEnd']) . ' 23:59:59"';

  $param_count++;
}
if (!empty($_GET['timeFrameStart'])) {
  $query .= ($param_count == 0 ? ' WHERE' : '');
  $query .= ($param_count > 0 ? ' AND' : '');
  $query .= ' TIME(time_stamp) >= "' . mysqli_real_escape_string($link, $_GET['timeFrameStart']) . ':00"';

  $param_count++;
}
if (!empty($_GET['timeFrameEnd'])) {
  $query .= ($param_count == 0 ? ' WHERE' : '');
  $query .= ($param_count > 0 ? ' AND' : '');
  $query .= ' TIME(time_stamp) <= "' . mysqli_real_escape_string($link, $_GET['timeFrameEnd']). ':59"';
}

error_log($query);

// Store query result in array
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
