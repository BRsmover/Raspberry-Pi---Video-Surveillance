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

// Start streaming
function startStream() {
	echo 'startStream';
	// More information: http://www.instructables.com/id/Raspberry-Pi-Video-Streaming/?ALLSTEPS
	endAll();
	exec("/var/www/html/152-Pi-BJ/scripts/stream.sh");
	header("Location: index.php?stream=true");
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
