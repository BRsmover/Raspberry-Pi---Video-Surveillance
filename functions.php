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
	if (isset($_GET['mode'])) {
		$mode = '>>' . $_GET['mode'] . '<<';
		callSpecifiedFunction($mode);
		return $mode;
	} else {
		return ">>Nicht gesetzt<<";
	}
}

// Switch to decide which method to call to start bash script
function callSpecifiedFunction($mode) {
	switch($mode) {
		case 'stream' : startStream();
		case 'motion' : startMotion();
		case 'aus' : endAll();
	}
}

// Get videos for archive
function getVideos() {
    $videos = array_diff(scandir("../videos/"), array(".", ".."));
    if(!empty($videos)) {
		//var_dump($videos);
        return $videos;
     } else {
        header("Location: index.php?site=none");
        die();
     }
}

// Start streaming
function startStream() {
	// More information: http://www.instructables.com/id/Raspberry-Pi-Video-Streaming/?ALLSTEPS
	endAll();
	exec("/var/www/html/152-Pi-BJ/scripts/stream.sh");
	header("Location: index.html?stream=true&mode=stream");
}

// Start motion detection
function startMotion() {
	// More information: http://strobelstefan.org/?p=5328
	endAll();
	exec("/var/www/html/152-Pi-BJ/scripts/motion.sh");
	header("Location: index.html?mode=motion");
}

// End all processes
function endAll() {
	exec("/var/www/html/152-Pi-BJ/scripts/stop.sh");
	header("Location: index.html?mode=aus");
}
?>
