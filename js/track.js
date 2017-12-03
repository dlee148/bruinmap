// In milliseconds, defines how often we send coordinates
const INTERVAL = 10000;

function parseData(data) {
  data = JSON.parse(data);
  data.map(function(e) {
    e.latitude = Number(e.latitude);
    e.longitude = Number(e.longitude);
  });

  return data;
}

function calculateCenter(data) {
  // TODO: Moving average

  var long = {min: data[0].longitude, max: data[0].longitude};
  var lat  = {min: data[0].latitude, max: data[0].latitude};

  for (var i = 0; i < data.length; i++) {
    long.min = Math.min(long.min, data[i].longitude);
    long.max = Math.max(long.max, data[i].longitude);

    lat.min = Math.min(lat.min, data[i].latitude);
    lat.max = Math.max(lat.max, data[i].latitude);
  }

  return {
    longitude: (long.min + long.max) / 2,
    latitude: (lat.min + lat.max) / 2
  };
}

function uploadCoords(position) {
  var data = {
    latitude:  position.coords.latitude,
    longitude: position.coords.longitude,
    timestamp: position.timestamp - 32400000
  };

  $.post("php/track.php", data, function(data, status, xhr) {
    console.log("Track - " + String(xhr.status));
    // TODO: do something with status
  });
}

function getLocation() {
  navigator.geolocation.getCurrentPosition(uploadCoords);
}

function setCenter(data) {
  var center = calculateCenter(data);
  map.setCenter(new google.maps.LatLng(center.latitude, center.longitude));
}

function clearPoints() {
  markers.forEach(function(point) {
    point.setMap(null);
  });

  markers = [];
}

function populateMap(points, animate) {
  clearPoints();

  if (animate) {
    for (var i = 0; i < points.length; i++) {
      (function(point, i, interval) {
        setTimeout(function() {
          markers.push(new google.maps.Marker({
            position: {lat: point.latitude, lng: point.longitude},
            map: map,
            icon: 'img/marker.png'
          }));

          if (i === points.length - 1) {
            $('#refresh').prop('disabled', false);
          }
        }, interval);
      })(points[i], i, 5000*i / points.length);
    }
  }
  else {
    points.forEach(function(point) {
      markers.push(new google.maps.Marker({
        position: {lat: point.latitude, lng: point.longitude},
        map: map,
        icon: 'img/marker.png'
      }));
    });

    $('#refresh').prop('disabled', false);
  }
}

function retrievePoints() {
  $('.loading').show();
  $('#refresh').prop('disabled', true);

  var parameters = {
    timeFrameStart: $('#timeFrameStart').val(),
    timeFrameEnd:   $('#timeFrameEnd').val(),
    dateFrameStart: $('#dateFrameStart').val(),
    dateFrameEnd:   $('#dateFrameEnd').val(),
  };

  var animate = $('#animate').is(':checked');

  $.get("php/retrieve-points.php", parameters, function(data, status, xhr) {
    data = parseData(data);
    if (data.length === 0) {
      $('.loading').hide();
      $('#refresh').prop('disabled', false);
      return;
    }

    setCenter(data);
    populateMap(data, animate);

    $('.loading').hide();
  });
}

$(document).ready(function() {
  $('#refresh').on('click', retrievePoints);

  retrievePoints();

  if (navigator.geolocation) {
    setInterval(getLocation, INTERVAL);
  }
  else {
    var warning = "<div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Your browswer does not support geolocation, and will therefore not upload location data.</div>";
    $("#main-container").prepend(warning);
  }
});
