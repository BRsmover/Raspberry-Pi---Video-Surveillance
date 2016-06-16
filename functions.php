<?php
// Get current site
function getSite() {
	$site = "home";
	if(isset($_GET['site'])) {
		$site = $_GET['site'];
	}
	return $site;
}

// Parse site
function parseSite($site, $data) {
	$loader = new Twig_Loader_Filesystem('html');
	$twig = new Twig_Environment($loader, array('debug' => true));
	$twig->addExtension(new Twig_Extension_Debug());
	$template = $twig->loadTemplate($site . ".html");
	return $template->render($data);
}

// Get camera mode
function getMode() {
	// Check if file exists
	$exists = file_exists('mode.txt');
	if ($exists == true) {
		// If it does, get the content
		$mode = file_get_contents('mode.txt');
		return $mode;
	} else {
		return "mode not set";
	}
}

// Switch to decide which method to call to start bash script
function callSpecifiedFunction($mode) {
	switch($mode) {
		case 'stream':
			writeModeToFile($mode);
			startStream();
			break;
		case 'motion':
			writeModeToFile($mode);
			startMotion();
			break;
		case 'stop':
			writeModeToFile($mode);
			endAll();
			header("Location: index.php");
			die();
			break;
	}
}

// Save selected mode by writing it into the mode.txt
function writeModeToFile($mode) {
	file_put_contents('mode.txt', $mode);
}

// Get videos for archive
function getVideos() {
    $videos = array_diff(scandir("../videos/"), array(".", ".."));
    if(!empty($videos)) {
        return $videos;
     } else {
        header("Location: index.php?site=none");
        die();
     }
}

// Format names of videos into dates
function getDates($videos) {
	$formattedDates = array();

	foreach ($videos as $video) {
		// Remove unused parts
		// Split by dash
		$dash = explode("-", $video);
		// Remove extension
		$extension = explode(".", $dash[1]);

		// Define the parts
		// Separate the year
		$year = substr($extension[0], 0, 4);
		// Separate the month
		$month = substr($extension[0], 4, 2);
		// Separate the day
		$day = substr($extension[0], 6, 2);
		// Separate the hour
		$hour = substr($extension[0], 8, 2);
		// Separate the minute
		$minute = substr($extension[0], 10, 2);
		// Separate the second
		$second = substr($extension[0], 12, 2);

		// Make it one big string
		$formattedDate = $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minute . ':' . $second;
		array_push($formattedDates, $formattedDate);
	}
	return $formattedDates;
}

// Start streaming
function startStream() {
	echo 'startStream';
	// More information: http://www.instructables.com/id/Raspberry-Pi-Video-Streaming/?ALLSTEPS
	endAll();
	exec("/var/www/html/152-Pi-BJ/scripts/stream.sh");
	header("Location: index.php");
	die();
}

// Start motion detection
function startMotion() {
	echo 'startMotion';
	// More information: http://strobelstefan.org/?p=5328
	endAll();
	exec("/var/www/html/152-Pi-BJ/scripts/motion.sh");
	header("Location: index.php");
	die();
}

// End all processes
function endAll() {
	exec("/var/www/html/152-Pi-BJ/scripts/stop.sh");
}
?>
