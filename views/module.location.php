<?php $configRec  = Config::find_by_id(1);
/*
* Top Location
*/
$tpres='';
// pr($configRec);
$emlAddress = str_replace('@','&#64;',$configRec->email_address);
$emlAddresscty = str_replace('@','&#64;',$configRec->city_mail_info);
 $tellinked = '';
    $telno = explode("/", $siteRegulars->contact_info);
    $lastElement = array_shift($telno);
    $tellinked .= '<a href="tel:' . $lastElement . '" target="_blank"><i class="fa fa-phone"></i>' . $lastElement . '</a>';
    foreach ($telno as $tel) {
        
        $tellinked .= '<a href="tel:+977-' . $tel . '" target="_blank">+977  ' . $tel . '</a>';
        if(end($telno)!= $tel){
        $tellinked .= '/';
        }
    }
        
   $ktmtellinked = '';
$ktmtelno = explode("/", $siteRegulars->city_contact_info);
$ktmlastElement = array_shift($ktmtelno);
$ktmtellinked .= '<a href="tel:' . $ktmlastElement . '" target="_blank"><i class="fa fa-phone"></i>' . $ktmlastElement . '</a>';
foreach ($ktmtelno as $ktmktmtel) {
    
    $ktmtellinked .= '<a href="tel:+977-' . $ktmtel . '" target="_blank">' . $ktmtel . '</a>';
    if(end($ktmtelno)!= $ktmtel){
    $ktmtellinked .= '/';
    }   
}
$tpres.='
<div class="container">
<div class="row">
    <div class="col-md-4">
        <h4>Contact Address</h4>
        <div class="footer-contact">
            <ul>
                <li><a target="_blank" href="https://maps.app.goo.gl/zGQW2VfhALDefCGf8"><i class="fa fa-map-marker"></i> '.strtoupper($configRec->fiscal_address).'</a></li>
                <li>'.$tellinked.'</li>                    
                <li class="text-lowercase"><a href="mailto:'.$emlAddress.'"><i class="fa fa-envelope"></i> '.strtoupper($emlAddress).'</a></li>
            </ul>
        </div>
        '.$jVars['module:socilaLinkbtm'].'
    </div>
    
    <div class="col-md-4">
        <h4>Booking Engines</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="footer-contact ota__footer">
                    <ul>
                        <li><a href="https://www.booking.com/hotel/np/river-bank-jungle-resort.en-gb.html?aid=356980&label=gog235jc-1DCAsoqwFCGHJpdmVyLWJhbmstanVuZ2xlLXJlc29ydEgzWANoqwGIAQGYAQm4ARfIAQzYAQPoAQGIAgGoAgO4Apu7lbQGwAIB0gIkMGVmZTNiOTgtNzYwMC00MjNmLTljYzMtMjlkZGQ2ZDY3NDdk2AIE4AIB&sid=278d1014ff7613d58fce5893f8065fc4&dist=0&keep_landing=1&sb_price_type=total&type=total&#tab-main" target="_blank"><img src="'.BASE_URL.'template/web/assets/img/icons/bo.png" alt="booking"></a></li>
                        <li><a href="https://www.tripadvisor.com/Hotel_Review-g2407100-d27121850-Reviews-River_Bank_Jungle_Resort-Bharatpur_Chitwan_District_Narayani_Zone_Central_Region.html" target="_blank"><img src="'.BASE_URL.'template/web/assets/img/icons/ta.png" alt="trip"></a></li>              
                        <li><a href="https://www.makemytrip.com/hotels-international/nepal/chitwan-hotels/river_bank_jungle_resort-details.html" target="_blank"><img src="'.BASE_URL.'template/web/assets/img/icons/ma.png" alt="booking"></a></li>
                    </ul>
                </div>
            </div>
            
            <!--<div class="col-md-6">
                <div class="footer-contact">
                    <ul>
                        
                        
                    </ul>
                </div>
            </div> -->
        </div>
    </div>
    
    <div class="col-md-4">
        <h4>Sales Office</h4>
        <div class="footer-contact">
            <ul>
                <li><a target="_blank" href="https://maps.app.goo.gl/zGQW2VfhALDefCGf8"><i class="fa fa-map-marker"></i> '.strtoupper($configRec->city_address).'</a></li>
                <li>'.$ktmtellinked.'</li>                    
                <li class="text-lowercase"><a href="mailto:'.$emlAddresscty.'"><i class="fa fa-envelope"></i> '.strtoupper($emlAddresscty).'</a></li>
            </ul>
        </div>
    </div>
    
    <!--<div class="col-md-12">
        <div class="booking-engines">
            <ul class="card1">
                <li>Booking Engines</li>
                <li><img src="template/web/assets/img/icons/bo.png" alt="booking"></li>
                <li><img src="template/web/img/icon/american.webp" alt="american"></li>
                <li><img src="template/web/img/icon/mastercard.webp" alt="mastercard"></li>
                <li><img src="template/web/img/icon/union.webp" alt="union"></li>
            </ul>
        </div>
    </div>-->
    
    
    
    <div class="col-md-12">
        <div class="devBy">
            <p>© Copyright 2024. River Bank Jungle Resort. All Rights Reserved .Developed By <a target="blank" href="http://longtail.info/n/">Longtail e-media</a></p>
        </div>
    </div>
    
    
    
    <!--<div class="col-sm-9">
        <div class="footer-copyright">
            <p>'.strtoupper($jVars['site:copyright']).'</p>
        </div>
        
        <div class="footer-contact">
            <ul>
                <li><a target="_blank" href="https://www.google.com/maps/place/Hotel+Ambassador,+Lazimpat/@27.719019,85.317721,17z/data=!4m5!3m4!1s0x0:0xa6547a5c10ff5032!8m2!3d27.7190189!4d85.317721?hl=en-US"><i class="fa fa-map-marker"></i> '.strtoupper($configRec->fiscal_address).'</a></li>
                <li><a ><i class="fa fa-phone"></i> '.strtoupper($configRec->contact_info).'</a></li>                    
                <li><a href="mailto:'.$emlAddress.'"><i class="fa fa-paper-plane"></i> '.strtoupper($emlAddress).'</a></li>
            </ul>
        </div>
    </div>-->
</div>            
</div>

<!--<div class="partof">
    PART OF <br/>
   <a href="http://www.acehotelsnepal.com/" target="blank">
   <a href="'.BASE_URL.'ace-hotels-introduction" target="blank"> <img src="'.IMAGE_PATH.'static/ace_footer_logo.png" alt="ace" class="img-responsive" width="100px" style="padding-left:10px"/></a>
</div>-->
 ';

$jVars['module:footer-location'] = $tpres;




$reslocinfo='';
$resgmap='';
$resbrief='';

if($configRec) {

	/*
	* Office location
	*/
    $reslocinfo.='<ul>
        <li><i class="icon icon-map-white"></i>'.$configRec->fax.', '.$configRec->fiscal_address.'</li>
        <li><i class="icon icon-phone"></i><a href="tel:'.$configRec->contact_info.'">'.$configRec->contact_info.'</a></li>
        <li><a href="mailto:'.$emlAddress.'"><i class="icon icon-mail"></i>'.$emlAddress.'</a></li>
    </ul>';

	/*
	* Google map
	*/

    if($configRec->location_type==1) {
        $resgmap.='<div class="full-map point-block" id="overlay">
            <iframe id="map" width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src='.$configRec->location_map.'></iframe>
        </div>';
    }
    else {
        $resgmap.='<div class="full-map point-block" id="overlay">
		<a style="position:absolute;" class="btn btn-primary btn-sm" href="https://goo.gl/maps/Cvq9qL64CPz" target="_blank">View Google Map</a>
            <img src="'.IMAGE_PATH.'preference/locimage/'.$configRec->location_image .'" alt="'.$configRec->sitetitle.'" class="img-responsive">
        </div>';
    }

    if(!empty($configRec->breif)) {
        $resbrief.= $configRec->breif;
    }

}

$jVars['module:office_information'] = $reslocinfo;
$jVars['module:office_map'] = $resgmap;
$jVars['module:office_brief'] = $resbrief;
?>