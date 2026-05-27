<?php
$resoffr = $socialshare = '';
$expired = '';
$enquiry = '';
$resrandoffr = $hmresoffr = $resinndetail = $offbredd = '';
$offrRec = Offers::get_offer_by();
$dataUrl = $breadcumb_offer = '';

$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/', $current_url);
$last = trim(end($data), '/');
$last = strtok($last, '?');
$dataUrl .= $last;

$breadcumb_offer .= '<ol class="breadcrumb text-center">
  <li><a href="' . BASE_URL . '">Home</a></li>
  <li class="active"><a href="' . BASE_URL . '' . $dataUrl . '">Offer</a></li>
</ol>';


if (defined('OFFERS_PAGE') and isset($_REQUEST['slug'])) {

    $slug = addslashes($_REQUEST['slug']);
    $recRow = Offers::find_by_slug($slug);

    if (!empty($recRow)) {

        if (!empty($recRow->image)) {
            $imglink = IMAGE_PATH . 'offers/' . $recRow->image;
        } else {
            $imglink = IMAGE_PATH . 'static/about-img.jpg';
        }
        $socialshare = '<div class="share-social">
            <a class="facebook-share" target="blank" rel="noreferrer" href="https://www.facebook.com/sharer/sharer.php?u=' . BASE_URL . 'offer/' . $recRow->slug . '&p=' . $recRow->title . '&p[images][0]=' . $imglink . '">
                <i class="fa fa-facebook" aria-hidden="true"></i><span>Share</span></a>
            <a class="twitter-share" target="blank" rel="noreferrer" href="https://twitter.com/intent/tweet?text=' . $recRow->title . ' ?url=' . BASE_URL . 'offer/' . $recRow->slug . '" >
                <i class="fa fa-twitter" aria-hidden="true"></i><span>Share</span></a>
            <a class="gplus-share" target="blank" rel="noreferrer" href="https://plus.google.com/share?url=' . BASE_URL . 'offer/' . $recRow->slug . '">
                <i class="fa fa-google-plus" aria-hidden="true"></i><span>Share</span></a>
        </div>';
        $rescontent = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($recRow->content));
        $content = !empty($rescontent[1]) ? $rescontent[1] : $rescontent[0];
        $currentdate = date("Y-m-d");
        // pr($recRow);
        // pr($currentdate);
        if ($recRow->offer_date > $currentdate) {
            $enquiry = '<a href="' . BASE_URL . 'book/' . $recRow->slug . '" class="btn btn-primary btn-book" style="color: #fff;background-color: #7b2b2e;border-color: #7b2b2e;">Enquiry</a>';
        } else {
            $enquiry = '';
        }
        $resinndetail .= $socialshare . '
                        <div class="offer-detail3">
                            <h2>' . $recRow->title . '</h2>
                            ' . $enquiry . '
                            ' . $content . '
                        </div>';

        $offbredd .= '<section class="breadcrumb-area overlay-dark-2 bg-2" style="background-image:url(' . BASE_URL . 'images/bbaner-2.jpg); background-repeat: no-repeat; background-size:cover; background-position-y: center; ">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-text article text-center">
                            <div class="breadcrumb-bar">
                                <ul class="breadcrumb">
                                    <li><a href="' . BASE_URL . '">Home</a></li>
                                    <li><a href="' . BASE_URL . 'offer-list">Exclusive Offers</a></li>
                                    <li>' . $recRow->title . '</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
    } else {
        redirect_to(BASE_URL);
    }
}
else {

    $offbredd .= '
        <section class="breadcrumb-area overlay-dark-2 bg-2" style="background-image:url(' . BASE_URL . 'template/web/assets/images/about-img.jpg); background-repeat: no-repeat; background-size:cover; background-position-y: center;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-text article text-center">
                            <div class="breadcrumb-bar">
                               <!-- <ul class="breadcrumb">
                                    <li><a href="' . BASE_URL . '">Home</a></li>
                                    <li>Exclusive Offers</li>
                                </ul>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';

    $offList = Offers::get_offer_date();
    $resinndetail .= '<div class="row">
                            <!--<div class="col-sm-12">
                                <h1 class="text-center">Exclusive Offers</h1>
                                <br/>
                            </div> -->
                        ';
    if (!empty($offList)) {
        foreach ($offList as $offer) {

            $currentdate = date("Y-m-d");

            $linkstart = 'href="' . BASE_URL . 'offer/' . $offer->slug . '"';
            $expired .= '';
            $hide = "d-block";
            $linkend = '</a>';

            //  pr($expired);
            $imglink = IMAGE_PATH . 'static/offer.jpg';

            if (!empty($offer->list_image)) {

                $file_path = SITE_ROOT . 'images/offers/listimage/' . $offer->list_image;
                // pr($file_path);
                if (file_exists($file_path)) {
                    $imglink = IMAGE_PATH . 'offers/listimage/' . $offer->list_image;

                }

            }
            $resinndetail .= '<div class="col-sm-6 col-lg-4">
                                <div class="offer offer-item position-relative">
                                    <a ' . $linkstart . '>
                                        <img src="' . resizeUrl('offera/listimage/' . $imglink, 800) . '" alt="' . $offer->image . '">
                                        <div class="details">
                                            <h3>' . $offer->title . '</h3>
                                        </div>
                                    </a>
                                </div>
                                ' . $expired . '
                            </div>';
            $expired = '';

        }
    } else {
        $resinndetail = '<h1 class="row text-center">No Offer Available</h1>';
    }
    $resinndetail .= '</div>';

    // FAQ Schema
    $faqHtml = '';
    $offerSchema = Schema::find_by_id(6);
    $faqs = isset($offerSchema->faq_schema) ? trim((string)$offerSchema->faq_schema) : '';

    if (!empty($faqs)) {
        $faqHtml .= '
        <style>
            .panel-heading .accordion-toggle:after {
                /* symbol for "opening" panels */
                font-family: "Glyphicons Halflings";  /* essential for enabling glyphicon */
                content: "\e114";    /* adjust as needed, taken from bootstrap.css */
                float: right;        /* adjust as needed */
                color: grey;         /* adjust as needed */
            }
            .panel-heading .accordion-toggle.collapsed:after {
                /* symbol for "collapsed" panels */
                content: "\e080";    /* adjust as needed, taken from bootstrap.css */
            }
        </style>
        <div class="section-bg mt-5">
            <div class="container">
                <div class="wrapper-inner">
                    <div class="widget-title">
                        <h5>FAQs</h5>
                        <h3>Everything you need to know</h3>
                    </div>
                    <div class="panel-group" id="accordion">
    ';

        $faqItems = json_decode($faqs, true);

        foreach ($faqItems as $i => $faqItem) {
            $q = isset($faqItem['q']) ? trim((string)$faqItem['q']) : '';
            $a = isset($faqItem['a']) ? trim((string)$faqItem['a']) : '';
            if ($q === '' || $a === '') continue;

            $collapsed = ($i == 0) ? '' : 'collapsed';
            $show = ($i == 0) ? 'in' : '';

            $faqHtml .= '
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle ' . $collapsed . '" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $i . '">
                                ' . $q . '
                            </a>
                        </h4>
                    </div>
                    <div id="collapse' . $i . '" class="panel-collapse collapse ' . $show . '">
                        <div class="panel-body">
                            ' . $a . '
                        </div>
                    </div>
                </div>
            ';
        }

        $faqHtml .= '
                    </div>
                </div>
            </div>
        </div>
        ';
    }
    $resinndetail .= $faqHtml;
}

//new home offer listing
$homelisting = '';

if (defined('HOME_PAGE')) {


    $homeoffLists = Offers::get_offer_date(3);
    if (!empty($homeoffLists)) {
        $homelisting .= '<section class="wrapper-inner offer__list">
	    <div class="container">
         <div class="widget-title">
                            <!--<h5>Resort Offers</h5>-->
                            <h3>Exclusive Offers</h3>
                        </div>
        <div class="row">
                          <!--<div class="col-sm-12">
                              <h1 class="text-center">Exclusive Offers</h1>
                              <br/>
                          </div> -->
                      ';
        foreach ($homeoffLists as $homeoffList) {

            $currentdate = date("Y-m-d");

            $linkstart = 'href="' . BASE_URL . 'offer/' . $homeoffList->slug . '"';
            $expired .= '';
            $hide = "d-block";
            $linkend = '</a>';

            //  pr($expired);
            $imglink = IMAGE_PATH . 'static/offer.jpg';

            if (!empty($homeoffList->list_image)) {

                $file_path = SITE_ROOT . 'images/offers/listimage/' . $homeoffList->list_image;
                // pr($file_path);
                if (file_exists($file_path)) {
                    $imglink = IMAGE_PATH . 'offers/listimage/' . $homeoffList->list_image;

                }

            }
            $homelisting .= '<div class="col-sm-4">
                              <div class="offer offer-item position-relative">
                                  <a ' . $linkstart . '>
                                      <img src="' . $imglink . '" alt="' . $homeoffList->title . '">
                                      <div class="details">
                                          <h3>' . $homeoffList->title . '</h3>
                                          <p>' . substr(strip_tags(strip_tags($homeoffList->content)), 0, 120) . '...</p>
                                      </div>
                                  </a>
                              </div>
                              ' . $expired . '
                          </div>';
            $expired = '';

        }
        $homelisting .= '
       </div>
       <div class="row text-center">
       <a class="btn" href="' . BASE_URL . 'offer-list" bis_skin_checked="1">View all</a>
       </div>
       </div>
       </section>
       ';
    }
}

$jVars['module:offers-home'] = $homelisting;
// Rand offer
$randRec = Offers::get_offer_rand();
if (!empty($randRec)) {
    $file_path = SITE_ROOT . 'images/offers/' . $randRec->image;
    if (file_exists($file_path) and !empty($randRec->image)) {
        $linkTarget = ($randRec->linktype == 1) ? ' target="_blank" rel="noopener noreferrer"' : '';
        $linksrc = ($randRec->linktype != 1) ? BASE_URL . $randRec->linksrc : $randRec->linksrc;
        $linkstart = ($randRec->linksrc != '') ? '<a href="' . $linksrc . '" ' . $linkTarget . '>' : '<a href="javascript:void(0);">';
        $linkend = ($randRec->linksrc != '') ? '</a>' : '</a>';


        $resrandoffr .= '<div class="section panel">
            <div class="item fade">
                <div class="back" data-image="' . IMAGE_PATH . 'offers/' . $randRec->image . '"></div>
                <div class="panel-button">
                    <div class="button-container">
                        ' . $linkstart . $randRec->title . $linkend . '
                        <span>Our Offer <i class="icon ion-ios-arrow-right"></i>
                    </div>
                </div>
            </div>

        </div>';
    }
}

if (defined('HOME_PAGE')) {

    if ($offrRec) {
        $hmresoffr .= '<div class="row" >';
        foreach ($offrRec as $offrRow) {
            $file_path = SITE_ROOT . 'images/offers/' . $offrRow->image;

            if (file_exists($file_path) and !empty($offrRow->image)) {
                $linkTarget = ($offrRow->linktype == 1) ? ' target="_blank" rel="noopener noreferrer"' : '';
                $linksrc = ($offrRow->linktype != 1) ? BASE_URL . $offrRow->linksrc : $offrRow->linksrc;
                $linkstart = ($offrRow->linksrc != '') ? '<a class="button" href="' . $linksrc . '" ' . $linkTarget . '>' : '<a class="button" href="javascript:void(0);">';
                $linkend = ($offrRow->linksrc != '') ? '</a>' : '</a>';

                $hmresoffr .= '
                       <div class="col-sm-3">
                            <div class="figure"><a href="' . BASE_URL . 'offer/' . $offrRow->slug . '"><img src="' . IMAGE_PATH . 'offers/' . $offrRow->image . '" alt="' . $offrRow->image . '"></a></div>
                            <div class="details">
                                <h3><a href="' . BASE_URL . 'offer/' . $offrRow->slug . '">' . strip_tags($offrRow->title) . '</h3>
                                <p>' . strip_tags($offrRow->brief) . '</p>

                            </div>
                            <div class="screen">
                                <div class="back" data-image="' . IMAGE_PATH . 'offers/' . $offrRow->image . '"></div>
                            </div>
                        </div>
                            ';

            }
        }

        $hmresoffr .= '</div>';
    }
}


$jVars['module:homeoffers-list'] = $hmresoffr;
$jVars['module:offers-details'] = $resinndetail;
$jVars['module:offer_breadcrum'] = $offbredd;


$homepopup = '';
if (defined('HOME_PAGE')) {
    $homepopupdatas = offers::get_offer_by_popup();
    // pr($homepopupdatas);
    if (!empty($homepopupdatas)) {
        //modal img
        $count = 1;
        $active = '';
        $homepopup = '
     <div class="col-sm-10 center-block center-text">
        <div class="modal fade" id="modal-popup-image-1">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header" style="border: 0; padding: 0;">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                    </div>
                    <div class="modal-body">
					<!--CAROUSEL CODE GOES HERE-->
                        <div id="myCarousel1" class="carousel slide">
                            <div class="carousel-inner">
                            ';
        foreach ($homepopupdatas as $popr) {
            if (!empty($popr->list_image)) {
                $q = $popr->list_image;
                $file_path = SITE_ROOT . 'images/offers/listimage/' . $q;
                if (file_exists($file_path)) {
                    $imglink = IMAGE_PATH . 'offers/listimage/' . $q;
                } else {
                    $imglink = BASE_URL . 'template/cms/images/welcome.jpg';
                }
                $active = ($count == 1) ? 'active' : '';
                $linkhref = ($popr->linktype == 1) ? $popr->linksrc : BASE_URL . $popr->linksrc;
                $target = ($popr->linktype == 1) ? 'target="_blank" rel="noopener noreferrer"' : '';
                $homepopup .= '
                <div class="item ' . $active . '">
                    <a href="' . BASE_URL . 'offer/' . $popr->slug . '" ><img src="' . $imglink . '" alt="' . $popr->title . '"></a>
                </div>
                ';
                // pr($imglink);

                $count++;
            }
        }
        $homepopup .= ' <!--end carousel-inner-->
                        </div>
    ';
        if (sizeof($homepopupdatas) > 1) {
            $homepopup .= '
        <a class="left carousel-control" href="#myCarousel1" data-slide="prev" style="background: none;">
    <span class="glyphicon glyphicon-chevron-left"></span>
</a>
<a class="right carousel-control" href="#myCarousel1" data-slide="next" style="background: none;">
    <span class="glyphicon glyphicon-chevron-right"></span>
</a>
        ';
        }
        $homepopup .= '

                        </div>
                        <!--end carousel-->
                    </div>
                    <!--end modal-body-->
                </div>
                <!--end modal-content-->
            </div>
            <!--end modal-dialoge-->
        </div>
        <!--end myModal-->
    </div>
    <!--end col-->
';
    }
}

$jVars['module:offer_homepopup'] = $homepopup;
$jVars['module:breadcumb_offer'] = $breadcumb_offer;