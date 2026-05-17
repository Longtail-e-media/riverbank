<?php
/*
* Testimonial Header Title
*/
$tstimg='';
$tstHtitle='';
$dataUrl = $breadcumb_review = '';
$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/', $current_url);
$last = trim(end($data), '/');
$last = strtok($last, '?');
$dataUrl .= $last;
$breadcumb_review .= '<ol class="breadcrumb text-center">
  <li><a href="'.BASE_URL.'">Home</a></li>
  <li class="active"><a href="'.BASE_URL.''.$dataUrl.'">Testimonial</a></li>
</ol>';

if(defined('REVIEWS_PAGE')) {
    $tstimg.=IMAGE_PATH.'reviews-img.jpg';

    $tstHtitle.='<h1>Review List</h1>
    <div class="text-center des-ico">
        <img src="'.IMAGE_PATH.'stroke.png" alt="img-border" class="img-respons">
    </div>';

    $tstRec = Testimonial::get_alltestimonial();
    if(!empty($tstRec)) {
        foreach($tstRec as $tstRow) {
            $slink = !empty($tstRow->linksrc)?$tstRow->linksrc:'javascript:void(0);';
            $target = !empty($tstRow->linksrc)?'target="_blank" rel="noopener noreferrer"':'';
            $tstHtitle.='<div class="owl-item col-sm-6">
                <div class="item skin flat ">
                    <div class="layer-media">
                        <img src="'.IMAGE_PATH.'testimonial/'.$tstRow->image.'" alt="'.$tstRow->name.'">
                    </div>
                    <div class="layer-content">
                        <div class="">
                            <p>'.strip_tags($tstRow->content).'</p>
                            <p class="name">&ndash; <strong>'.$tstRow->name.', '.$tstRow->country.'</strong> (Via : <a href="'.$slink.'" '.$target.'>'.$tstRow->via_type.'</a>)</p>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }
}

$jVars['module:reviews_img'] = $tstimg;
$jVars['module:testimonial-title'] = $tstHtitle;


/*
* Testimonial Rand
*/
$tstHead='';

$tstRand = Testimonial::get_by_rand();
if(!empty($tstRand)) {
	$tstHead.='<!-- Quote | START -->
	<div class="section quote fade">
		<div class="center">

	        <div class="col-1">
	        	<div class="thumb"><img src="'.IMAGE_PATH.'testimonial/'.$tstRand->image.'" alt="'.$tstRand->name.'"></div>
	            <h5><em>'.strip_tags($tstRand->content).'</em></h5>
	            <p><span><strong>'.$tstRand->name.', '.$tstRand->country.'</strong> (Via : '.$tstRand->via_type.')</span></p>
	        </div>

	    </div>
	</div>
	<!-- Quote | END -->';
}

$jVars['module:testimonial-rand'] = $tstHead;


/*
* Testimonial List
*/
$restst= $testilist='';
$tstRec = Testimonial::get_alltestimonial(9);
$tstRecall = Testimonial::get_alltestimonial();
if(!empty($tstRec)) {
	$restst.='<div class="widget-carousel owl-carousel owl-theme">';
        foreach($tstRec as $tstRow) {
            $slink = !empty($tstRow->linksrc)?$tstRow->linksrc:'javascript:void(0);';
            $target = !empty($tstRow->linksrc)?'target="_blank" rel="noopener noreferrer"':'';

            $restst.='<div class="testimonials-item">
                <div class="item-comment">
                    '.strip_tags($tstRow->content).'
                </div>
                <div class="item-customer">

                    <h5>'.$tstRow->name.', '.$tstRow->country.'</h5>
                    <h6>via <a class="text-link link-direct" href="'.$slink.'" '.$target.'>'.$tstRow->via_type.'</a></h6>
                </div>
            </div>';
        }
    $restst.='</div>';
}

if(!empty($tstRecall)) {
foreach($tstRecall as $tstRow) {
$testilist .= '
    <div class="col-md-6">
      <div class="testimonial--card panel panel-default mb-5">
        <div class="panel-body">
          <p>
            '.strip_tags($tstRow->content).'
          </p>
          <div class="testimonial--person">
           <ul class="pt-3">
                <li><b>'.$tstRow->name.'</b></li>
                <li class="mt-3">'.$tstRow->country.'</li>
                <li>via <a class="text-link link-direct" href="'.$slink.'" '.$target.'>'.$tstRow->via_type.'</a></li>
            </ul>

          </div>
        </div>
      </div>
    </div>

';
}
}



$jVars['module:testimonialList'] = $restst;
$jVars['module:testimonialList-all'] = $testilist;
$jVars['module:testimonialList-review'] = $breadcumb_review;
?>