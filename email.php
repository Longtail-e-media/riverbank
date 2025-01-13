<?php

define('GALLERY_PAGE', 1);// Track Article page.
define('JCMSTYPE', 0); // Track Current site language.

 require_once("includes/initialize.php");
// $template 			= "images/preference/other/esignature";
$siteRegulars = Config::find_by_id(1);

// pr('asd');
header('Location: '.IMAGE_PATH.'preference/other/'.$siteRegulars->other_upload);
// explode('.', $imagename);
$zipdata= explode('.', $siteRegulars->other_upload);
if($zipdata[1]=='zip'){
    echo preference/other/esignature;
}
else{
    echo preference/other/esignature;
}
//  redirect_to(IMAGE_PATH.'preference/other/'.$siteRegulars->other_upload);

exit();

// $currentTemplate	= Config::getCurrentTemplate('template');
// $jVars 				= array();




?>