<?php 
/*
* Services 
*/
$ressimg='';
$resrvs='';

if(defined('SERVICES_PAGE')) {
    $ressimg.=IMAGE_PATH.'service-img.jpg';

	$rescont.='<h1>Our Services</h1>
    <div class="text-center des-ico">
        <img src="'.IMAGE_PATH.'stroke.png" alt="img-border" class="img-respons">
    </div>
    <div class="row centered custom_wid">';
        $record = Services::getservice_list();
        if(!empty($record)) {
            foreach($record as $recRow) {
                if($recRow->image != "a:0:{}") { 
                    $imageList = unserialize($recRow->image);
                    $imgno = array_rand($imageList);
                    $file_path = SITE_ROOT.'images/services/'.$imageList[$imgno];
                    if(file_exists($file_path)) {
                        $imglink = IMAGE_PATH.'services/'.$imageList[$imgno];
                        $rescont.='<div class="main_facility col-md-3 col-sm-6">
                            <img alt="'.$recRow->title.'" class="facility_img" src="'.$imglink.'">
                            <p>'.$recRow->title.'</p>
                        </div>';
                    }
                }
            }
        }

    $rescont.='</div>';

}

$jVars['module:service_img'] = $ressimg;
$jVars['module:service-list'] = $rescont;