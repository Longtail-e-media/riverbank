<?php
/*
* Top Social Links
*/
$socialRec = SocialNetworking::getSocialNetwork();

$resocl='';

$resocl.='<ul class="contact-social">';
        if(!empty($socialRec)) {
        	foreach($socialRec as $socialRow) {
        		$resocl.='<li>
		<a href="'.$socialRow->linksrc.'" target="_blank" rel="noopener noreferrer"><i class="'.$socialRow->image.'"></i></a></li>';
            }
        }
        $resocl.='</ul>';

$jVars['module:socilaLinktop']= $resocl;


/*
* Home social link
*/
$ressl='';

if(!empty($socialRec)) {
    $ressl.='
    <!--<div class="row disclaim">
        <div class="col-lg-12">
            <h5>LOWEST RATE GUARANTEED:</h5>
            <h6>WE BELIEVE YOU WON\'T FIND A BETTER PUBLICLY AVAILABLE RATE FOR OUR HOTELS ANYWHERE ELSE. IF YOU MAKE A RESERVATION ON ambassadornepal.com AND THEN FIND A LOWER RATE ELSEWHERE WITH THE SAME BOOKING CONDITIONS, WE\'LL REFUND THE DIFFERENCE OR REVISE YOUR BOOKING TO THE LOWER RATE.</h6>
        </div>

        <div class="col-lg-12">
            <h5>SOCIAL MEDIA</h5>
            <div class="widget-social-icons ">
                <ul>';
                foreach($socialRec as $socialRow) {
                    $ressl.='<li><a href="'.$socialRow->linksrc.'" target="_blank" rel="noopener noreferrer"><i class="'.$socialRow->image.'"></i></a></li>';
                }
                $ressl.='</ul>
            </div>
	    </div>
    </div>-->


    <div class="widget-social-icons">

        <ul>';
            foreach($socialRec as $socialRow) {
                $ressl.='<li><a href="'.$socialRow->linksrc.'" target="_blank" rel="noopener noreferrer"><i class="'.$socialRow->image.'"></i></a></li>';
            }
            $ressl.='</ul>
    </div>';
}

$jVars['module:socilaLinkbtm'] = $ressl;

$detect = new Mobile_Detect;

$ret='';

// Any mobile device.
if ($detect->isMobile() && !$detect->isTablet()){
    $ret.='<div class="mobile-fb text-center">

     <iframe data-src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fprofile.php%3Fid%3D61555768349361&tabs=timeline&width=250&height=400&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=1651547328419060" frameborder="0" scrolling="yes" style="border: white; overflow: hidden; height: 350px; width: 250px; max-width:100%;background:#fafafa;color:000;" class="fb-iframe"  ></iframe>


    </div>';
}else{
    $ret.='<style type="text/css"> .theblogwidgets{background: url("images/fbwidget.webp") no-repeat scroll left center transparent !important; float: right;height: 350px;padding: 0 5px 0 34px;width: auto;z-index:  99999;position:fixed;right:-255px;top:40%;} .theblogwidgets div{ padding: 0; margin-right:-8px; border:4px solid  #3b5998; background:#fafafa;} .theblogwidgets span{bottom: 4px;font: 8px "lucida grande",tahoma,verdana,arial,sans-serif;position: absolute;right: 6px;text-align: right;z-index: 99999;} .theblogwidgets span a{color: gray;text-decoration:none;} .theblogwidgets span a:hover{text-decoration:underline;} } </style>
    <div class="theblogwidgets">
<div>

 <style>
        .fb-iframe {
            border: white;
            overflow: hidden;
            height: 350px;
            width: 250px;
            max-width: 100%;
            background: #fafafa;
            color: #000;
        }
    </style>
 <iframe data-src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fprofile.php%3Fid%3D61555768349361&tabs=timeline&width=250&height=400&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=1651547328419060" frameborder="0" scrolling="yes" class="fb-iframe" title="facebook"></iframe>

 </div>


</div>';
}
$jVars['module:fb- side'] = $ret;
?>