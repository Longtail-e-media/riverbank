<?php
$reslgall = $dataUrl = $breadcumb_gallery = '';
$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/', $current_url);
$last = trim(end($data), '/');
$last = strtok($last, '?');
$dataUrl .= $last;


$breadcumb_gallery .= '<ol class="breadcrumb text-center">
  <li><a href="' . BASE_URL . '">Home</a></li>
  <li class="active"><a href="' . BASE_URL . '' . $dataUrl . '">Gallery</a></li>
</ol>';


$gallRec = Gallery::getParentgallery(2);
if (!empty($gallRec)) {
    foreach ($gallRec as $gallRow) {
        $childRec = GalleryImage::getGalleryImages($gallRow->id);
        if (!empty($childRec)) {
            $reslgall .= '<div class="widget-carousel owl-carousel owl-theme">';
            foreach ($childRec as $childRow) {
                $file_path = SITE_ROOT . 'images/gallery/galleryimages/' . $childRow->image;
                if (file_exists($file_path) and !empty($childRow->image)) {
                    $reslgall .= '<div class="gallery-item">
                        <a href="' . IMAGE_PATH . 'gallery/galleryimages/' . $childRow->image . '"

                            data-src="' . resizeUrl('gallery/galleryimages/' . $childRow->image, 600) . '"

                            title="' . $childRow->title . '" class="popup-gallery owl-lazy">
                            <span class="item-text">' . $childRow->title . '</span>
                        </a>
                    </div>';
                }
            }
            $reslgall .= '</div>';
        }
    }
}

$jVars['module:galleryHome'] = $reslgall;


/*
* Gallery Page
*/
$gallnav = $gallimg = $resgall = '';
if (defined('GALLERY_PAGE')) {

    $gallRec = Gallery::getParentgallery(1);
    if (!empty($gallRec)) {
        foreach ($gallRec as $gallRow) {
            $childRec = GalleryImage::getGalleryImages($gallRow->id);
            if (!empty($childRec)) {
                $gallnav .= '<li><a href="javascript:void(0);" data-filter=".' . $gallRow->slug . '">' . $gallRow->title . '</a></li>';
                foreach ($childRec as $childRow) {
                    $file_path = SITE_ROOT . 'images/gallery/galleryimages/' . $childRow->image;
                    if (file_exists($file_path) and !empty($childRow->image)) {
                        $gallimg .= '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 isotope-item ' . $gallRow->slug . '">
	                        <div class="gallery-item">
	                            <a href="' . IMAGE_PATH . 'gallery/galleryimages/' . $childRow->image . '" data-background="' . IMAGE_PATH . 'gallery/galleryimages/' . $childRow->image . '" title="' . $childRow->title . '" class="popup-gallery" alt="' . $gallRow->title . '"></a>
	                        </div>
	                    </div>';
                    }
                }
            }
        }
    }

    // FAQ Schema
    $faqHtml = '';

    $gallerySchema = Schema::find_by_id(4);
    $faqs = isset($gallerySchema->faq_schema) ? trim((string)$gallerySchema->faq_schema) : '';

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

    $resgall .= '
        <div class="section">
            <div class="container">
                <div class="wrapper-inner">
                    <!-- Gallery Filter -->
                    <div class="widget-filter-top">
                        <ul>
                            <li class="active"><a href="javascript:void(0);" data-filter="*">ALL PHOTOS</a></li>
                            ' . $gallnav . '
                        </ul>
                    </div>

                    <div class="widget-gallery-grid">
                        <div class="row">
                            ' . $gallimg . '
                        </div>
                    </div>
                    
                    ' . $faqHtml . '
                </div>
            </div>
        </div>
    ';
}

$jVars['module:allgallery'] = $resgall;
$jVars['module:breadcumb_gallery'] = $breadcumb_gallery;
