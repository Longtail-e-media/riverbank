<?php

define('HOMEPAGE', 0); // Track homepage.
define('JCMSTYPE', 0); // Track Current site language.

require_once("includes/initialize.php");

$currentTemplate	= Config::getCurrentTemplate('template');
$jVars 				= array();
$template 			= "template/{$currentTemplate}/testimonial.html";

require_once('views/modules.php');

template($template, $jVars, $currentTemplate);

?>