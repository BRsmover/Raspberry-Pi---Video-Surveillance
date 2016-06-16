<?php

// Require the Twig Autoloader
require_once('libraries/Twig/lib/Twig/Autoloader.php');
// Require the functions
require_once('functions.php');

Twig_Autoloader::register();

$site = getSite();

// Home
if($site == "home") {
    // read mode from mode.txt
    $mode = getMode();

    // parse site with or without stream
    if(isset($_GET['stream'])) {
        if ($_GET['stream'] == true) {
            echo(parseSite('home', array("mode" => $mode, "stream" => $_GET['stream'])));
        }
    } else {
        echo(parseSite('home', array("mode" => $mode)));
    }

    // if get value function is set call
    if (isset($_GET['function'])) {
        callSpecifiedFunction($_GET['function']);
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
