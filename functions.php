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

// Get videos for archive
function getVideos() {
    $videos = array_diff(scandir("videos/"), array(".", ".."));
    if(!empty($videos)) {
        return $videos;
     } else {
        header("Location: index.php?site=error");
        die();
     }
}

// Start streaming
function startStream() {
	// More information: http://www.instructables.com/id/Raspberry-Pi-Video-Streaming/?ALLSTEPS
	endAll();
	shell_exec("sudo uv4l --driver raspicam --auto-video_nr");
	header("Location: index.html?stream=true");
}

// Start motion detection
function startMotion() {
	// More information: http://strobelstefan.org/?p=5328
	endAll();
	shell_exec("sudo uv4l --driver raspicam --auto-video_nr");
	shell_exec("sudo start motion -c /home/pi/motion-backup.conf-n");
}

// End all processes
function endAll() {
	shell_exec("sudo pkill uv4l");
	shell_exec("sudo pkill motion");
}
?>
