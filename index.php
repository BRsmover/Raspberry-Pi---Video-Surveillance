<?php

// Require the Twig Autoloader
require_once('libraries/Twig/lib/Twig/Autoloader.php');
// Require the functions
require_once('functions.php');

Twig_Autoloader::register();

$site = getSite();

// Home
if($site == "home") {
    if(isset($_GET['stream'])) {
        if ($_GET['stream'] == true) {
            echo(parseSite('home', array("stream" => $_GET['stream'])));
        }
    } else {
        echo(parseSite('home', array()));
    }
}

// Archiv
else if($site == "archiv") {
    echo(parseSite('archiv', array("videos" => getVideos())));
}

// About
else if($site == "help") {
    echo(parseSite('help', array()));
}

// No videos
else if($site == "none") {
    echo(parseSite('none', array()));
}

// Error
else {
    echo(parseSite('error', array()));
}

?>
