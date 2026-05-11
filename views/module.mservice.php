<?php


/**
 *      Home page
 */
$servicecont = '';

if (defined('HOME_PAGE') ) {
    // $slug = addslashes($_REQUEST['slug']);
    // $recRow = mservices::find_by_slug($slug);
    $recInn = mservices::homepageArticle();
    
    $imagem='';
    if (!empty($homearticle)) {
        
}
        $servicecont .='<div class="section">
            <div class="container">
                <div class="wrapper-inner">
                    <div class="widget-features-grid widget-features-grid1">
                        <!-- Features Title -->
                        <div class="widget-title">
                            <h5>OUR SPECIALS</h5>
                            <h3>Experience at River Bank Jungle Resort</h3>
                        </div>
                        <!-- Features Title End -->
                    
                        <!-- Features Content -->
                        <div class="widget-inner">
                            <div class="row">';
        

                                    
        foreach ($recInn as $innRow) {
          if ($innRow->image != "a:0:{}") {
            $imageList = unserialize($innRow->image);
            $imgno = array_rand($imageList);
            $file_path = SITE_ROOT . 'images/mservices/' . $imageList[$imgno];
            if (file_exists($file_path)) {
                $imglink = IMAGE_PATH . 'mservices/' . $imageList[$imgno];
            } else {
                $imglink = BASE_URL . 'template/web/img/mosaic_2.jpg';
            }
        } else {
            $imglink = BASE_URL . 'template/cms/img/mosaic_2.jpg';
        }
            $linkTarget = ($innRow->linktype == 1) ? ' target="_blank" ' : '';
        $linksrc = ($innRow->linktype == 1) ? $innRow->linksrc : BASE_URL . $innRow->linksrc;
        if(!empty($innRow->linksrc)){
          $linkmain='<a href="'.$linksrc.'" '.$linkTarget.'>
                                            <h3>'. $innRow->title .'</h3>
                                            <p>'. $innRow->sub_title .'</p>
                                        </a>
                                       ';
        }else{
          $linkmain='<a href="#" target="blank">
                                            <h3>'. $innRow->title .'</h3>
                                            <p>'. $innRow->sub_title .'</p>
                                        </a>';
        }
        $servicecont .='

        <div class="col-lg-4 col-sm-6 blockCoApi">
                                    <div class="features-item" data-background="' . $imglink . '">
                                    '.$linkmain.'
                                    </div>
                                </div>
                                       ';
                                    //    pr($recInn);
                                    } 
                                    $servicecont .='</div>
                            </span>
                            
                            <div class="row">
					            <div class="col-lg-12">
						            <div class="text-center">
							            <button id="loadCourierApis" class="btn-new btn btn-md btn-alt">
                                            Load More
                                        </button>
						            </div>
					            </div> 
				            </div>
                        </div>
                        <!-- Features Content End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Features End -->';
                                    
                                        
    
    }
    


$jVars['module:home-mainservice'] = $servicecont;

/**
 *      Inner page detail
 */






?>