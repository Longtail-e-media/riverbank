<?php $configRec = Config::find_by_id(1);
/*
* Top Location
*/
$tpres = '';
// pr($configRec);
$emlAddress = str_replace('@', '&#64;', $configRec->email_address);
$emlAddresscty = str_replace('@', '&#64;', $configRec->city_mail_info);

$tellinked = $tellinkedp = '';
$telno = explode(",", $siteRegulars->contact_info);
//$lastElement = array_shift($telno);
foreach ($telno as $tel) {
    $tellinkedp .= '<a href="tel:' . $tel . '" target="_blank" rel="noopener noreferrer" class="d-inline">  ' . $tel;
    if (end($telno) != $tel) {
        $tellinkedp .= ', ';
    }
    $tellinkedp .= '</a>';
}
$tellinked .= '<p><i class="fa fa-phone i-class-custom-footer"></i>' . $tellinkedp . '</p>';

$mob_linked = $mob_linkedp = '';
$telno = explode(",", $siteRegulars->mobile_info);
//$lastElement = array_shift($telno);
foreach ($telno as $tel) {
    $mob_linkedp .= '<a href="tel:' . $tel . '" target="_blank" rel="noopener noreferrer" class="d-inline">' . $tel;
    if (end($telno) != $tel) {
        $mob_linkedp .= ', ';
    }
    $mob_linkedp .= '</a>';
}
$mob_linked .= '<p><i class="fa fa-mobile i-class-custom-footer"></i>' . $mob_linkedp . '</p>';

$ktmtellinked = '';
$ktmtelno = explode("/", $siteRegulars->city_contact_info);
$ktmlastElement = array_shift($ktmtelno);
$ktmtellinked .= '<a href="tel:' . $ktmlastElement . '" target="_blank" rel="noopener noreferrer"><i class="fa fa-phone"></i>' . $ktmlastElement . '</a>';
foreach ($ktmtelno as $ktmktmtel) {
    $ktmtellinked .= '<a href="tel:+977-' . $ktmtel . '" target="_blank" rel="noopener noreferrer">' . $ktmtel . '</a>';
    if (end($ktmtelno) != $ktmtel) {
        $ktmtellinked .= '/';
    }
}

$tpres .= '
<style>
    .d-inline{display: inline !important;}
    .i-class-custom-footer {
        color: #ffffff;
        margin-bottom: 14px;
        font-size: 20px;
        width: 20px;
    }
</style>
<div class="floating-buttons right-buttons">
    <a href="https://virtualtour.airliftventures.com/riverbank-jungle-resort/" class="btn" target="_blank" rel="noopener noreferrer" title="Virtual Tour">
        <img src="template/web/assets/img/360-virutal.png" height="50" width="50" alt="Virtual Tour">
    </a>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h4>Contact Address</h4>
            <div class="footer-contact">
                <ul>
                    <li><a target="_blank" rel="noopener noreferrer" href="https://maps.app.goo.gl/zGQW2VfhALDefCGf8"><i class="fa fa-map-marker"></i> ' . strtoupper($configRec->fiscal_address) . '</a></li>
                    <li>' . $tellinked . '</li>
                    <li>' . $mob_linked . '</li>
                    <li class="text-lowercase"><a href="mailto:' . $emlAddress . '"><i class="fa fa-envelope"></i> ' . strtoupper($emlAddress) . '</a></li>
                </ul>
            </div>
            ' . $jVars['module:socilaLinkbtm'] . '
        </div>

        <div class="col-md-4">
            <h4>Online Reservations</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-contact ota__footer">
                        <ul>
                            <li><a href="https://www.booking.com/hotel/np/river-bank-jungle-resort.en-gb.html?aid=356980&label=gog235jc-1DCAsoqwFCGHJpdmVyLWJhbmstanVuZ2xlLXJlc29ydEgzWANoqwGIAQGYAQm4ARfIAQzYAQPoAQGIAgGoAgO4Apu7lbQGwAIB0gIkMGVmZTNiOTgtNzYwMC00MjNmLTljYzMtMjlkZGQ2ZDY3NDdk2AIE4AIB&sid=278d1014ff7613d58fce5893f8065fc4&dist=0&keep_landing=1&sb_price_type=total&type=total&#tab-main" target="_blank" rel="noopener noreferrer"><img src="' . BASE_URL . 'template/web/assets/img/icons/bo.webp" alt="booking"></a></li>
                            <li><a href="https://www.tripadvisor.com/Hotel_Review-g2407100-d27121850-Reviews-River_Bank_Jungle_Resort-Bharatpur_Chitwan_District_Narayani_Zone_Central_Region.html" target="_blank" rel="noopener noreferrer"><img src="' . BASE_URL . 'template/web/assets/img/icons/ta.webp" alt="trip"></a></li>
                            <li><a href="https://www.makemytrip.com/hotels-international/nepal/chitwan-hotels/river_bank_jungle_resort-details.html" target="_blank" rel="noopener noreferrer"><img src="' . BASE_URL . 'template/web/assets/img/icons/ma.webp" alt="booking"></a></li>
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
                    <li><a><i class="fa fa-map-marker"></i> ' . strtoupper($configRec->city_address) . '</a></li>
                    <li>' . $ktmtellinked . '</li>
                    <li class="text-lowercase"><a href="mailto:' . $emlAddresscty . '"><i class="fa fa-envelope"></i> ' . strtoupper($emlAddresscty) . '</a></li>
                </ul>
            </div>
        </div>

        <!--<div class="col-md-12">
            <div class="booking-engines">
                <ul class="card1">
                    <li>Booking Engines</li>
                    <li><img src="template/web/assets/img/icons/bo.webp" alt="booking"></li>
                    <li><img src="template/web/img/icon/american.webp" alt="american"></li>
                    <li><img src="template/web/img/icon/mastercard.webp" alt="mastercard"></li>
                    <li><img src="template/web/img/icon/union.webp" alt="union"></li>
                </ul>
            </div>
        </div>-->

        <div class="col-md-12">
            <div class="award-book-mobile">
                <a href="' . BASE_URL . 'template/web/assets/img/award/Professional-Printed-Award-TRA-2025.pdf" target="_blank" rel="noopener noreferrer">
                    <img src="' . BASE_URL . 'template/web/assets/img/award/Digital-Award-TRA-2025.png" alt="award_external">
                </a>
            </div>
        </div>

        <div class="col-md-12">
            <div class="devBy">
                <p>' . $jVars['site:copyright'] . ' Developed By <a target="blank" rel="noopener noreferrer" href="http://longtail.info/">Longtail e-media</a></p>
            </div>
        </div>

        <!--<div class="col-sm-9">
            <div class="footer-copyright">
                <p>' . strtoupper($jVars['site:copyright']) . '</p>
            </div>

            <div class="footer-contact">
                <ul>
                    <li><a target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/place/Hotel+Ambassador,+Lazimpat/@27.719019,85.317721,17z/data=!4m5!3m4!1s0x0:0xa6547a5c10ff5032!8m2!3d27.7190189!4d85.317721?hl=en-US"><i class="fa fa-map-marker"></i> ' . strtoupper($configRec->fiscal_address) . '</a></li>
                    <li><a ><i class="fa fa-phone"></i> ' . strtoupper($configRec->contact_info) . '</a></li>
                    <li><a href="mailto:' . $emlAddress . '"><i class="fa fa-paper-plane"></i> ' . strtoupper($emlAddress) . '</a></li>
                </ul>
            </div>
        </div>-->
    </div>
</div>
';

$jVars['module:footer-location'] = $tpres;


$reslocinfo = '';
$resgmap = '';
$resbrief = '';

if ($configRec) {
    /*
    * Office location
    */
    $reslocinfo .= '<ul>
        <li><i class="icon icon-map-white"></i>' . $configRec->fax . ', ' . $configRec->fiscal_address . '</li>
        <li><i class="icon icon-phone"></i><a href="tel:' . $configRec->contact_info . '">' . $configRec->contact_info . '</a></li>
        <li><a href="mailto:' . $emlAddress . '"><i class="icon icon-mail"></i>' . $emlAddress . '</a></li>
    </ul>';

    /*
    * Google map
    */

    if ($configRec->location_type == 1) {
        $resgmap .= '<div class="full-map point-block" id="overlay">
            <iframe id="map" width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src=' . $configRec->location_map . '></iframe>
        </div>';
    } else {
        $resgmap .= '<div class="full-map point-block" id="overlay">
		<a style="position:absolute;" class="btn btn-primary btn-sm" href="https://goo.gl/maps/Cvq9qL64CPz" target="_blank" rel="noopener noreferrer">View Google Map</a>
            <img src="' . IMAGE_PATH . 'preference/locimage/' . $configRec->location_image . '" alt="' . $configRec->sitetitle . '" class="img-responsive">
        </div>';
    }

    if (!empty($configRec->breif)) {
        $resbrief .= $configRec->breif;
    }

}

$jVars['module:office_information'] = $reslocinfo;
$jVars['module:office_map'] = $resgmap;
$jVars['module:office_brief'] = $resbrief;
?>
