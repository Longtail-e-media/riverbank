<?php
$reslgall=$dataUrl =$breadcumb_gallery= '';
$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/', $current_url);
$last = trim(end($data), '/');
$last = strtok($last, '?');
$dataUrl .= $last;


$breadcumb_gallery .= '<ol class="breadcrumb text-center">
  <li><a href="'.BASE_URL.'">Home</a></li>
  <li class="active"><a href="'.BASE_URL.''.$dataUrl.'">Gallery</a></li>
</ol>';



$gallRec = Gallery::getParentgallery(2);
if(!empty($gallRec)) {
	foreach($gallRec as $gallRow) {
		$childRec = GalleryImage::getGalleryImages($gallRow->id);
		if(!empty($childRec)) {
		$reslgall.='<div class="widget-carousel owl-carousel owl-theme">';
			foreach($childRec as $childRow) {
				$file_path = SITE_ROOT.'images/gallery/galleryimages/'.$childRow->image;
				if(file_exists($file_path) and !empty($childRow->image)) {
					$reslgall.='<div class="gallery-item">
                        <a href="'.IMAGE_PATH.'gallery/galleryimages/'.$childRow->image.'"

                            data-src="'.resizeUrl('gallery/galleryimages/'.$childRow->image, 600).'"

                            title="'.$childRow->title.'" class="popup-gallery owl-lazy">
                            <span class="item-text">'.$childRow->title.'</span>
                        </a>
                    </div>';
				}
			}
		$reslgall.='</div>';
		}
	}
}

$jVars['module:galleryHome'] = $reslgall;


/*
* Gallery Page
*/
$gallnav=$gallimg=$resgall='';
if(defined('GALLERY_PAGE')) {
	$gallRec = Gallery::getParentgallery(1);
	if(!empty($gallRec)) {
		foreach($gallRec as $gallRow) {
			$childRec = GalleryImage::getGalleryImages($gallRow->id);
			if(!empty($childRec)) {
				$gallnav.='<li><a href="javascript:void(0);" data-filter=".'.$gallRow->slug.'">'.$gallRow->title.'</a></li>';
				foreach($childRec as $childRow) {
					$file_path = SITE_ROOT.'images/gallery/galleryimages/'.$childRow->image;
					if(file_exists($file_path) and !empty($childRow->image)) {
						$gallimg.='<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 isotope-item '.$gallRow->slug.'">
	                        <div class="gallery-item">
	                            <a href="'.IMAGE_PATH.'gallery/galleryimages/'.$childRow->image.'" data-background="'.IMAGE_PATH.'gallery/galleryimages/'.$childRow->image.'" title="'.$childRow->title.'" class="popup-gallery" alt="'.$gallRow->title.'"></a>
	                        </div>
	                    </div>';
					}
				}
			}
		}
	}

	$resgall.='<div class="section">
	                <div class="container">
                        <div class="wrapper-inner">
                            <!-- Gallery Filter -->
                            <div class="widget-filter-top">
                                <ul>
                                    <li class="active"><a href="javascript:void(0);" data-filter="*">ALL PHOTOS</a></li>
                                    '.$gallnav.'
                                </ul>
                            </div>

                            <div class="widget-gallery-grid">
                                <div class="row">
                	                '.$gallimg.'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
}

$jVars['module:allgallery'] = $resgall;
 $jVars['module:breadcumb_gallery'] = $breadcumb_gallery;
?>