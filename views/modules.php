<?php
// SITE REGULARS
$jVars['site:header'] 		= Config::getField('headers',true);
$jVars['site:footer'] 		= Config::getField('footer',true);
$siteRegulars 				= Config::find_by_id(1);
$jVars['site:copyright']	= str_replace('{year}',date('Y'),$siteRegulars->copyright);
$jVars['site:fevicon']		=  '<link rel="shortcut icon" href="'.IMAGE_PATH.'preference/'.$siteRegulars->icon_upload.'"> 
							    <link rel="apple-touch-icon" href="'.IMAGE_PATH.'preference/'.$siteRegulars->icon_upload.'"> 
							    <link rel="apple-touch-icon" sizes="72x72" href="'.IMAGE_PATH.'preference/'.$siteRegulars->icon_upload.'"> 
							    <link rel="apple-touch-icon" sizes="114x114" href="'.IMAGE_PATH.'preference/'.$siteRegulars->icon_upload.'">';
$jVars['site:logo']			= '<a href="'.BASE_URL.'home"><img alt="'.$siteRegulars->sitetitle.'" src="'.IMAGE_PATH.'preference/'.$siteRegulars->logo_upload.'" alt="logo"></a>';				    
$jVars['site:seotitle'] = MetaTagsFor_SEO();

// view modules 
require_once("views/module.social.php");
require_once("views/module.contact.php");
require_once("views/module.booking.php");
require_once("views/module.reservation.php");
require_once("views/module.bookpkgs.php");

// SITE MODULES
$modulesList = Module::getAllmode();
foreach($modulesList as $module):	
	$fileName = "module.".$module->mode.".php";
	if(file_exists("views/".$fileName)){
	  	require_once("views/".$fileName);
	}
endforeach;
?>