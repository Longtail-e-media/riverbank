<?php
$resinndetail=$imageList='';

if(defined('INNER_PAGE') and isset($_REQUEST['slug'])) {
	$slug = addslashes($_REQUEST['slug']);
	$recRow = Article::find_by_slug($slug);
	if(!empty($recRow)) {
		$imglink='';
		if($recRow->image != "a:0:{}") { 
			$imageList = unserialize($recRow->image);
			$file_path = SITE_ROOT . 'images/articles/' . $imageList[0];
        if (file_exists($file_path) and !empty($imageList[0])) {
            $imglink = IMAGE_PATH . 'articles/' . $imageList[0];
        }
        else{
            $imglink = BASE_URL . 'images/static/inner-banner.jpg';
        }
        
		
			$imgcount=count($imageList);	
		}
		else { $imglink = BASE_URL . 'images/static/inner-banner.jpg'; }
// pr($imageList);
		$rescontent = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($recRow->content));	
		$content = !empty($rescontent[1])?$rescontent[1] : $rescontent[0]; 

		$resinndetail.='<!-- Section Page Title -->
	   
	    <div class="banner-header section-padding valign bg-img innerpage2" data-background="'.$imglink.'">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 caption mt-200">
                        <h1>'.$recRow->title.'</h1>
                    </div>
                </div>
            </div>
        </div>

	    <!-- Section About Promo -->
	    <div class="section">
	       <!-- <div class="widget-about-promo2" data-background="'.$imglink.'">-->
	        <div class="widget-about--promo">
	        <div class="container">
	            <div class="wrapper-inner">
	                <div class="widget-inner">
	                    <div class="row">
	                        <div class="col-md-12">
	                            '.$content.'

	                        </div>';
	                       	//if($imageList!=''){
	                         //$resinndetail.='<div class="col-md-6 main-carousel ">';
	                       		
		                       // foreach($imageList as $image) {
		                        	//$resinndetail.='<!--<div class="carousel-cell">
		                        //	<img  src="'.BASE_URL.'images/articles/'.$image.'">
		                       // 	</div>-->
		                        
		                        //	';
		                        //}
		                       
		                  //      $resinndetail.='</div>';
	                       //}
	                    $resinndetail.='</div>
	                </div>
	            </div>
	            </div>
	        </div>
	    </div>';
	    if(!empty($recRow->sub_title)) {
			$resinndetail.='<div class="section">
				<div class="widget-history-timeline">
					<div class="wrapper-inner">
						'.$recRow->sub_title.'
						
					</div>
				</div>
			</div>';
		}
	}
    else {
		redirect_to(BASE_URL);
	}    
}

$jVars['module:inner_detail']= $resinndetail;


/*
* Home page 
*/
$resinnh='';

if(defined('HOME_PAGE')) {
	$recInn = Article::homepageArticle();
	if(!empty($recInn)) {
		foreach($recInn as $innRow) {
			$imglink='';
			if($innRow->image != "a:0:{}") { 
				$imageList = unserialize($innRow->image);
				$imgno = array_rand($imageList);
				$file_path = SITE_ROOT.'images/articles/'.$imageList[$imgno];
				if(file_exists($file_path)) {
					$imglink = IMAGE_PATH.'articles/'.$imageList[$imgno];
				}
				else {
					$imglink = IMAGE_PATH.'static/inner-img.jpg';
				}
			}
			else { $imglink = IMAGE_PATH.'static/inner-img.jpg'; }

			$content = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($innRow->content));   
    		$readmore='';
    		if(!empty($innRow->linksrc)) {
    			$linkTarget = ($innRow->linktype == 1)? ' target="_blank" ' : ''; 
    			$linksrc  = ($innRow->linktype == 1)? $innRow->linksrc : BASE_URL.$innRow->linksrc;
    			$readmore = '<a class="btn" href="'.$linksrc.'">READ MORE</a>';
    		}
    		else {
    			$readmore = (count($content) > 1) ? '<a class="btn" href="'.BASE_URL.$innRow->slug.'">READ MORE</a>' : '' ;
    		}

    		$resinnh.='<div class="section" style="background:#ffffff;">
    		    <div class="container">
    		        <div class="wrapper-inner">
		            
		                                '.$content[0].'
		                            
		            </div>
		        </div>
		    </div>';					
		}
	}
	
}

$jVars['module:home_article'] = $resinnh;


$restyp='';

$typRow = Article::get_by_type();
if(!empty($typRow)) {
	$content = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($typRow->content));   
	$readmore='';
	if(!empty($typRow->linksrc)) {
		$linkTarget = ($typRow->linktype == 1)? ' target="_blank" ' : ''; 
		$linksrc  = ($typRow->linktype == 1)? $typRow->linksrc : BASE_URL.$typRow->linksrc;
		$readmore = '<a class="text-link link-direct" href="'.$linksrc.'">see more</a>';
	}
	else {
		$readmore = (count($content) > 1) ? '<a href="'.BASE_URL.$typRow->slug.'">Read more...</a>' : '' ;
	}
	$restyp.='<h3 class="h3 header-sidebar">'.$typRow->title.'</h3>
	<div class="home-content">
		'.$content[0].' '.$readmore.'
	</div>';	

}

$jVars['module:article_by_type'] = $restyp;

?>