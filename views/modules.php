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
							    
$jVars['site:googleframe'] = '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-THJK6772"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
							    
$img_Section = responsiveImage('preference/' . $siteRegulars->logo_upload, $siteRegulars->sitetitle, [85, 136], [
                                    'width' => '136',
                                    'height' => '84',
                                    'class' => 'logo-img'
                                ], 'full');
$jVars['site:logo']			= '<a href="'.BASE_URL.'home" class="block">
                                 '.$img_Section.'
                               </a>';
$jVars['site:seotitle'] = MetaTagsFor_SEO();

$jVars['site:promocode'] = '
    <div class="nd_booking_alert_msg" style="display: block;" data-url="' . BASE_URL . 'result.php?hotel_code=5gU2lD7&hotel_promo_code=RBJR@10">
        <div class="promo1 glow-offer">
            <div class="promo1-inner">
                <h5>Staycation Offer! <span style="font-size:25px;"> 10% </span> Discount <br/> on your Reservation </h5>
                <a id="promo-code" href="' . BASE_URL . 'result.php?hotel_code=5gU2lD7&hotel_promo_code=RBJR@10" target="_blank" rel="noopener noreferrer">Limited Offer!</a>
                <div class="close-up on_close"><i class="fa fa-close"></i></div>
            </div>
        </div>
    </div>
';

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