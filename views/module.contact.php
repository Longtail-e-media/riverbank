<?php 
/*
* Contact form
*/
$configRec  = Config::find_by_id(1);
// pr($configRec);

   $tellinked = '';
    $telno = explode("/", $siteRegulars->contact_info);
    $lastElement = array_shift($telno);
    $tellinked .= '<a href="tel:' . $lastElement . '" target="_blank">' . $lastElement . '</a>';
    foreach ($telno as $tel) {
        
        $tellinked .= '<a href="tel:+977-' . $tel . '" target="_blank">+977  ' . $tel . '</a>';
        if(end($telno)!= $tel){
        $tellinked .= '/';
        }   
}
   $mobileinked = '';
    $mobileno = explode("/", $siteRegulars->mobile_info);
    $lastmobileElement = array_shift($telno);
    $mobileinked .= '<a href="tel:' . $lastmobileElement . '" target="_blank">' . $lastmobileElement . '</a>';
    foreach ($mobileno as $mobile) {
        
        $mobileinked .= '<a href="tel:+977-' . $mobile . '" target="_blank">+977  ' . $mobile . '</a>';
        if(end($mobileno)!= $mobile){
        $mobileinked .= '/';
        }   
}


$ktmtellinked = '';
$ktmtelno = explode("/", $siteRegulars->city_contact_info);
$ktmlastElement = array_shift($ktmtelno);
$ktmtellinked .= '<a href="tel:' . $ktmlastElement . '" target="_blank">' . $ktmlastElement . '</a>';
foreach ($ktmtelno as $ktmktmtel) {
    
    $ktmtellinked .= '<a href="tel:+977-' . $ktmtel . '" target="_blank">' . $ktmtel . '</a>';
    if(end($ktmtelno)!= $ktmtel){
    $ktmtellinked .= '/';
    }   
}

$ktmcontinked = '';
$ktmtelno = explode("/", $siteRegulars->city_tell_info);
$ktmcontlastElement = array_shift($ktmtelno);
$ktmcontinked .= '<a href="tel:' . $ktmcontlastElement . '" target="_blank">' . $ktmcontlastElement . '</a>';
foreach ($ktmtelno as $ktmktmtel) {
    
    $ktmcontinked .= '<a href="tel:+977-' . $ktmtel . '" target="_blank">' . $ktmtel . '</a>';
    if(end($ktmtelno)!= $ktmtel){
    $ktmcontinked .= '/';
    }   
}
$rescont='';

if(defined('CONTACT_PAGE')) {
	$rescont.='
	<div class="section">
            <div class="container">
                <div class="wrapper-inner">
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Contact Info -->
                            <div class="widget-contact-info">
                                <!-- Location brief -->
                                <h4>
	<span style="color:#00843b;"><strong>'.$configRec->sitetitle.'</strong></span></h4>
<p>
	<strong>Address</strong>: '.$configRec->fiscal_address.'</p>
<p>
	<strong>Tel</strong>: '.$tellinked.'</p>
<p>
	<strong>Mob</strong>: '.$mobileinked.'</p>
<p>
	<strong>E-mail</strong>: <a href="mailto:'.$configRec->email_address.'">'.$configRec->email_address.'</a></p>
'.$jVars['module:socilaLinktop'].'
<hr />
<p>
	&nbsp;</p>
<h4>
	<strong style="color:#00843b;">Sales Office</strong></h4>
<p>
	<strong>Address</strong>: '.$configRec->city_address.'</p>
<p>
	<strong>Tel</strong>: '.$ktmcontinked.'</p>
<p>
	<strong>Mob</strong>: '.$ktmtellinked.'</p>
<p>
	<strong>E-mail</strong>: <a href="mailto:'.$configRec->city_mail_info.'">'.$configRec->city_mail_info.'</a></p>
                            </div>
                            <!-- Contact Info End -->
                        </div>
                        <div class="col-lg-6">
                            <!-- Contact Form -->
                            <div class="widget-contact-form">
                                <h5>Get In Touch</h5>
                                <p>We are eager to hear from you. Please fill in your contact information and our staff members will contact you shortly.</p>
                                <div class="data-form">
                                    <!-- Contact module -->
	
	
	<form id="frm-contact" class="label-placeholder" action="" method="post">
        <div class="row">
            <div class="col-md-12">
                <input type="text" name="fullname" class="input-control" placeholder="Fullname">
            </div>
            <div class="col-md-6">
                <input type="text" name="mailaddress" class="input-control" placeholder="Email Address">
            </div>
            <div class="col-md-6">
                <input type="text" name="phoneno" class="input-control" placeholder="Phone Number">
            </div>
            
            <div class="col-md-12">
                <textarea  id="contact-textarea"  class="input-control" placeholder="Your message" name="message"></textarea>
            </div>
            <div class="col-md-12">
            <div id="g-recaptcha-response" class="g-recaptcha" data-sitekey="6LeFNRAqAAAAAPHZU1kPnAcElVtYug0gkJcAuXSt"></div>
            </div>
            <!--<div class="col-md-4">
                <img src="'.BASE_URL.'captcha/imagebuilder.php?rand=310333" border="1" class="form-control" onclick="updateCaptcha(this);"> 
            </div>
            <div class="col-md-6">
                <input placeholder="Enter Security Code" type="text" class="input-control" name="userstring" maxlength="5" />
            </div>
            <div class="col-md-2">&nbsp;</div>
            <div class="col-md-6">
                <p class="note">* Please fill in all of the required fields</p>
            </div>-->
            
            <div class="col-md-12 ">
                <button id="btn-contact" type="submit" class="btn btn-alter btn-border btn-border-brown" id="submit-contact">Submit</button>
            </div>
        </div>
    </form>
     </div>
                            </div>
                            <!-- Contact Form End -->
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Contact End -->';

}

$jVars['module:conatact-us'] = $rescont;