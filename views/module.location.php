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
$tellinked .= '<i class="fa fa-phone i-class-custom-footer"></i>' . $tellinkedp . '';

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
$mob_linked .= '<i class="fa fa-phone i-class-custom-footer"></i>' . $mob_linkedp . '';

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
$classhide = (empty($configRec->whatsapp)) ? "hide" : "";

$tpres .= '
<!-- <style>
    .d-inline{display: inline !important;}
    .i-class-custom-footer {
        color: #ffffff;
        margin-bottom: 14px;
        font-size: 20px;
        width: 20px;
    }
</style> -->
<!-- <div class="floating-buttons right-buttons">
    <a href="https://virtualtour.airliftventures.com/riverbank-jungle-resort/" class="btn" target="_blank" rel="noopener noreferrer" title="Virtual Tour">
        <img src="template/web/assets/img/360-virutal.png" height="50" width="50" alt="Virtual Tour">
    </a>
</div> -->

<!--whatsapp -->
     <div id="chatbot" class="'.$classhide.'">
  <div class="popup-box chat-popup">
    <div class="chatbot-wrapper">
      <div class="popup-head">
        <div class="botInfo-wrapper">
          <img src="'.BASE_URL.'template/web/assets/img/whatsapp.png" alt="WhatsApp" class="botImage" />
          <div class="AIBotInfo">
            <div class="title">WhatsApp Message</div>
          </div>
        </div>
      </div>
       <div class="popup-messages"><ul>
        <li>Hi River Bank, I would like to enquire about...</li>
    </ul></div>
      <div class="chatArea">

<div class="popup-footer">
    <textarea
        id="textInput"
        class="input-box"
        name="msg"
        required
        placeholder=""
        rows="1"></textarea>

    <i id="chat-icon" onclick="sendWhatsApp();" style="color: #333;" class="fa fa-fw fa-send"></i>
</div>
      </div>
    </div>
  </div>
  <div class="floating-logo" id="floating-button">
    <div></div>
  </div>
</div>
<script>
        function sendWhatsApp(){
    let phoneNumber = "'.$configRec->whatsapp.'";
    let message = document.getElementById("textInput").value;
        if(message !== ""){
            let url = "https://wa.me/" + phoneNumber + "?text=" + encodeURIComponent(message);
            window.open(url, "_blank");
        }else{
            alert("Message is required before sending!");

        }

}
</script>

<!--whatsapp end-->




<div class="container">
    <div class="row footer-row">
        <div class="col-lg-4 col-sm-6 contact--footer">
            <h4>Contact Address</h4>
            <div class="footer-contact">
                <ul>
                    <li><a target="_blank" rel="noopener noreferrer" href="https://maps.app.goo.gl/zGQW2VfhALDefCGf8"><i class="fa fa-map-marker"></i> ' . strtoupper($configRec->fiscal_address) . '</a></li>
                    <li class="d-flex gap-3 align-items-center">' . $tellinked . '</li>
                    <li class="d-flex gap-3 align-items-center">' . $mob_linked . '</li>
                    <li class="text-lowercase d-flex gap-3 align-items-center"><a href="mailto:' . $emlAddress . '"><i class="fa-envelope fa i-class-custom-footer"></i> ' . strtoupper($emlAddress) . '</a></li>
                </ul>
            </div>
            ' . $jVars['module:socilaLinkbtm'] . '

        </div>


        <div class="col-lg-4 col-sm-6 riverbank-flex custom-mt-5">
            <div class="sales__office">
                            <h4>Sales Office</h4>
            <div class="footer-contact">
                <ul>
                    <li><a><i class="fa fa-map-marker"></i> ' . strtoupper($configRec->city_address) . '</a></li>
                    <li>' . $ktmtellinked . '</li>
                    <li class="text-lowercase"><a href="mailto:' . $emlAddresscty . '"><i class="fa fa-envelope"></i> ' . strtoupper($emlAddresscty) . '</a></li>
                </ul>
            </div>


        <div class="online-reservation custom-mt-5 mt-5">
            <h4 class="mb-20">Online Reservations</h4>
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
            </div>
        </div>


            </div>

        </div>
        <div class="col-lg-4 custom-mt-5 col-sm-5">
                      <!-- Newsletter Subscribe Form -->
        <div class="newsletter-form">
            <h4 class="mb-20">Subscribe for Newsletter</h4>
        <form action="https://riverbankjungleresort.us19.list-manage.com/subscribe/post?u=5b5bc5738b6946540eac13de5&amp;id=67a8b5ef77&amp;f_id=008c1de7f0" method="post" target="_blank" id="subscribe-form">
                <div class="input-group bg-white p-2 rounded-3">
                  <input type="email" name="EMAIL" class="form-control rounded-start-2" placeholder="Enter your email" id="mce-EMAIL" value="" required>
                   <span class="input-group-btn"> <button class="btn btn--rocket" type="submit" aria-label="Subscribe" >
                    Submit
                  </button></span>
                </div>
              </form>
        </div>
       </div>
    </div>
    <div class="footer-section mt-5">
            <div class="devBy">
                <p>' . $jVars['site:copyright'] . ' Developed By <a target="blank" rel="noopener noreferrer" href="http://longtail.info/">Longtail e-media</a></p>
            </div>
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
