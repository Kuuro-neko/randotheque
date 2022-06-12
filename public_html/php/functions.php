<?php
// Function that parse the waypoints of a GPX file in gpx/ given it's name and return an array of waypoints
function parse_waypoints($gpx_file) {
	$waypoints = array();
	$gpx_file = simplexml_load_file($gpx_file);
	foreach ($gpx_file->wpt as $waypoint) {
		$waypoints[] = array(
			'lat' => $waypoint->attributes()->lat,
			'lon' => $waypoint->attributes()->lon,
			'name' => $waypoint->name,
			'ele' => $waypoint->ele,
		);
	}
	unset($gpx_file);
	return $waypoints;
}
?>