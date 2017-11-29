// In milliseconds, defines how often we send coordinates
const INTERVAL = 10000;

function uploadCoords(position) {
  var data = {
    latitude:  position.coords.latitude,
    longitude: position.coords.longitude,
    timestamp: position.timestamp
  };

  $.post("php/track.php", data, function(data, status, xhr) {
    console.log("Track - " + String(xhr.status));
  });
}

function callPeriodicFunctions() {
  getLocation();
  retrievePoints();
}

function getLocation() {
  navigator.geolocation.getCurrentPosition(uploadCoords);
}

function retrievePoints() {
  // TODO: Add get params to filter points
  $.get("php/retrieve-points.php", null, function(data, status, xhr) {
    console.log("Retrieve - " + String(xhr.status));
  });
}

$(document).ready(function() {
  if (navigator.geolocation) {
    setInterval(callPeriodicFunctions, INTERVAL);
  }
  else {
    var warning = "<div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Your browswer does not support geolocation, and will therefore not upload location data.</div>";
    $("#main-container").prepend(warning);
  }
});
