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
$count = 0;
$query = "SELECT * FROM $table";

if (!empty($_GET['dateFrameStart'])) {
  $query .= ' WHERE time_stamp >= "' . mysqli_real_escape_string($link, $_GET['dateFrameStart']) . '"';

  $count++;
}
if (!empty($_GET['dateFrameEnd'])) {
  $query .= ($count == 0 ? ' WHERE' : '');
  $query .= ($count > 0 ? ' AND' : '');
  $query .= ' time_stamp <= "' . mysqli_real_escape_string($link, $_GET['dateFrameEnd']) . '"';

  $count++;
}
if (!empty($_GET['timeFrameStart'])) {
  $query .= ($count == 0 ? ' WHERE' : '');
  $query .= ($count > 0 ? ' AND' : '');
  $query .= ' TIME(time_stamp) >= "' . mysqli_real_escape_string($link, $_GET['timeFrameStart']) . ':00"';

  $count++;
}
if (!empty($_GET['timeFrameEnd'])) {
  $query .= ($count == 0 ? ' WHERE' : '');
  $query .= ($count > 0 ? ' AND' : '');
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
