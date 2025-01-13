<?php
$resoffr=$socialshare='';
$expired='';
$enquiry='';
$resrandoffr=$hmresoffr=$resinndetail=$offbredd='';
$offrRec = Offers::get_offer_by();

if(defined('OFFERS_PAGE') and isset($_REQUEST['slug'])) {
    
    $slug = addslashes($_REQUEST['slug']);
    $recRow = Offers::find_by_slug($slug);

    if(!empty($recRow)) {

        if(!empty($recRow->image)) {
              $imglink =IMAGE_PATH.'offers/'.$recRow->image;
        }
        else {
            $imglink = IMAGE_PATH.'static/inner-img.jpg';
        }
        $socialshare='<div class="share-social">
            <a class="facebook-share" target="blank" href="https://www.facebook.com/sharer/sharer.php?u='.BASE_URL.'offer/'.$recRow->slug.'&p='.$recRow->title.'&p[images][0]='.$imglink.'">
                <i class="fa fa-facebook" aria-hidden="true"></i><span>Share</span></a>
            <a class="twitter-share" target="blank" href="https://twitter.com/intent/tweet?text='.$recRow->title.' ?url='.BASE_URL.'offer/'.$recRow->slug.'" >
                <i class="fa fa-twitter" aria-hidden="true"></i><span>Share</span></a>
            <a class="gplus-share" target="blank" href="https://plus.google.com/share?url='.BASE_URL.'offer/'.$recRow->slug.'">
                <i class="fa fa-google-plus" aria-hidden="true"></i><span>Share</span></a>
        </div>';
        $rescontent = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($recRow->content));
        $content = !empty($rescontent[1])?$rescontent[1] : $rescontent[0];
    $currentdate = date("Y-m-d") ;
    // pr($recRow);
    // pr($currentdate);
    if($recRow->offer_date > $currentdate){
        $enquiry='<a href="'.BASE_URL.'book/'.$recRow->slug.'" class="btn btn-primary btn-book" style="color: #fff;background-color: #7b2b2e;border-color: #7b2b2e;">Enquiry</a>';
    }
    else{
        $enquiry='';
    }
          $resinndetail.=$socialshare.'
                        <div class="offer-detail3">
                            <h2>'.$recRow->title.'</h2>
                            '.$content.'
                            '.$enquiry.'
                        </div>';

                $offbredd.='<section class="breadcrumb-area overlay-dark-2 bg-2" style="background-image:url('.BASE_URL.'images/bbaner-2.jpg); background-repeat: no-repeat; background-size:cover; background-position-y: center; ">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-text article text-center">
                            <div class="breadcrumb-bar">
                                <ul class="breadcrumb">
                                    <li><a href="'.BASE_URL.'">Home</a></li>
                                    <li><a href="'.BASE_URL.'offer-list">Offer</a></li>
                                    <li>'.$recRow->title.'</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
    }
    else {
        redirect_to(BASE_URL);
    }
}
else{

      $offbredd.='<section class="breadcrumb-area overlay-dark-2 bg-2" style="background-image:url('.BASE_URL.'images/bbaner-2.jpg); background-repeat: no-repeat; background-size:cover; background-position-y: center;">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-text article text-center">
                            <div class="breadcrumb-bar">
                               <!-- <ul class="breadcrumb">
                                    <li><a href="'.BASE_URL.'">Home</a></li>
                                    <li>Offers</li>
                                </ul>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
         $offList= Offers::find_all();
         $resinndetail.='<div class="row">
                            <div class="col-sm-12">
                                <h1 class="text-center">Exclusive Offers</h1>
                                <br/>
                            </div>
                        ';
         foreach ($offList as $offer) {

             $currentdate = date("Y-m-d") ;
             if($offer->start_date <= $currentdate){
            //  pr($offer->offer_date);
            //  pr($currentdate);
             if($offer->offer_date < $currentdate ){
             $expired .='<div class="offer__expire position-absolute"><span>Expired</span></div>';
             $linkstart='';
             $linkend='';

             }
             else{
             $linkstart='href="'.BASE_URL.'offer/'.$offer->slug.'"';
                 $expired .='';
                 $hide = "d-block";
             $linkend='</a>';
             }
            //  pr($expired);
            $imglink = IMAGE_PATH . 'static/offer.jpg';
            
            if (!empty($offer->list_image)) {
                
                $file_path = SITE_ROOT . 'images/offers/listimage/' . $offer->list_image;
                // pr($file_path);
                if(file_exists($file_path)){
                    $imglink = IMAGE_PATH . 'offers/listimage/' . $offer->list_image;
                    
                }
                
            }
            $resinndetail.='<div class="col-md-4">
                                <div class="offer offer-item position-relative">
                                    <a '.$linkstart.'>
                                        <img src="'.$imglink.'" alt="'.$offer->image.'">
                                        <div class="details">
                                            <h3>'.$offer->title.'</h3>
                                        </div>
                                    </a>
                                </div>
                                '.$expired.'
                            </div>';
         $expired='';
         }
         }
         $resinndetail.='</div>';
}


// Rand offer
$randRec = Offers::get_offer_rand();
if(!empty($randRec)) {
    $file_path = SITE_ROOT.'images/offers/'.$randRec->image;
    if(file_exists($file_path) and !empty($randRec->image)) {
        $linkTarget = ($randRec->linktype == 1)? ' target="_blank" ' : '';
        $linksrc    = ($randRec->linktype != 1)? BASE_URL.$randRec->linksrc : $randRec->linksrc;
        $linkstart  = ($randRec->linksrc!='')? '<a href="'.$linksrc.'" '.$linkTarget.'>' : '<a href="javascript:void(0);">';
        $linkend    = ($randRec->linksrc!='')? '</a>' : '</a>' ;


        $resrandoffr.='<div class="section panel">
            <div class="item fade">
                <div class="back" data-image="'.IMAGE_PATH.'offers/'.$randRec->image.'"></div>
                <div class="panel-button">
                    <div class="button-container">
                        '.$linkstart.$randRec->title.$linkend.'
                        <span>Our Offer <i class="icon ion-ios-arrow-right"></i>
                    </div>
                </div>
            </div>

        </div>';
    }
}

if(defined('HOME_PAGE')) {

     if($offrRec) {
        $hmresoffr.='<div class="row" >';
                foreach($offrRec as $offrRow) {
                    $file_path = SITE_ROOT.'images/offers/'.$offrRow->image;

                    if(file_exists($file_path) and !empty($offrRow->image)) {
                        $linkTarget = ($offrRow->linktype == 1)? ' target="_blank" ' : '';
                        $linksrc    = ($offrRow->linktype != 1)? BASE_URL.$offrRow->linksrc : $offrRow->linksrc;
                        $linkstart  = ($offrRow->linksrc!='')? '<a class="button" href="'.$linksrc.'" '.$linkTarget.'>' : '<a class="button" href="javascript:void(0);">';
                        $linkend    = ($offrRow->linksrc!='')? '</a>' : '</a>' ;

                        $hmresoffr.='
                        <div class="col-sm-3">
                            <div class="figure"><a href="'.BASE_URL.'offer/'.$offrRow->slug.'"><img src="'.IMAGE_PATH.'offers/'.$offrRow->image.'" alt="'.$offrRow->image.'"></a></div>
                            <div class="details">
                                <h3><a href="'.BASE_URL.'offer/'.$offrRow->slug.'">'.strip_tags($offrRow->title).'</h3>
                                <p>'.strip_tags($offrRow->brief).'</p>

                            </div>
                            <div class="screen">
                                <div class="back" data-image="'.IMAGE_PATH.'offers/'.$offrRow->image.'"></div>
                            </div>
                        </div>';

                    }
                }

            $hmresoffr.='</div>';
    }
}


$jVars['module:homeoffers-list'] = $hmresoffr;
$jVars['module:offers-details'] = $resinndetail;
$jVars['module:offer_breadcrum'] = $offbredd;