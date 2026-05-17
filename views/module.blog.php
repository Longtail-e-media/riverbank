<?php
$bl =  '';

if (defined('BLOG_PAGE')) {
    $record = Blog::get_allblog();
    $linkTarget='';
    $pagelink='';
    if (!empty($record)) {
        
        
            $bl .= '
            <!--================ Breadcrumb ================-->
        <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="'. BASE_URL .'template/web/images/123456789.jpg">
            <div class="container wide">
                <h1 class="mad-page-title">Blogs</h1>
                <nav class="mad-breadcrumb-path">
                    <span><a href="' . BASE_URL . 'home" class="mad-link">Home</a></span> /
                    <span>News & Articles</span>
                </nav>
            </div>
            <h2>Blog list</h2>
        </div>

        
                ';
        
            foreach ($record as $homebl) {
            
           if(!empty($homebl->linksrc)){
            // $pagelink = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
            $linkTarget = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
                $linksrc = ($homebl->linktype == 1) ? $homebl->linksrc : BASE_URL.$homebl->linksrc;
           }
           else{
                $linksrc= BASE_URL. 'blog/'. $homebl->slug;
           }
           $bl .='
           <div class="item">
                    <div class="col-xl-12 col-lg-12 wow fadeInUp" data-wow-delay="100ms">
                        <div class="news-one__single">
                            <div class="news-one__img">
                                <img src="' . IMAGE_PATH . 'blog/' . $homebl->image . '" alt="' . $homebl->title . '">
                            </div>
                            <div class="news-one__content-box">
                                <div class="news-one__date">
                                    <p>' . date("d M Y", strtotime($homebl->blog_date)) . '</p>
                                </div>
                                <div class="news-one__content">
                                    <p class="news-one__author">by www.merolagani.com</p>
                                    <h3 class="news-one__title"><a href="'.$linksrc.'" '.$linkTarget.'>' . $homebl->title . '</a></h3>
                                </div>
                                <div class="news-one__bottom">
                                    <a href="'.$linksrc.'" target="_blank" class="news-one__more"> <i
                                            class="fa fa-arrow-right"></i> Read More</a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                  
           ';
        }
        $bl.='</div>
        </section>';
    } else {
        redirect_to(BASE_URL);
    }
}
$jVars['module:bloglist'] = $bl;
$linkTarget='';
$homebloglist = '';
$homeblogs ='';
if (defined('HOME_PAGE')) {
    $homeblog = Blog:: get_latestblog_by(3);
    // $homeblogs = Blog:: get_latestblog_by(3);
    if (!empty($homeblog)) {
        
        foreach ($homeblog as $homebl) {
            
           if(!empty($homebl->linksrc)){
            // $pagelink = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
            $linkTarget = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
                $linksrc = ($homebl->linktype == 1) ? $homebl->linksrc : BASE_URL.$homebl->linksrc;
           }
           else{
                $linksrc=  BASE_URL. 'blog/' .$homebl->slug;
           }
           $homebloglist .='
           <div class="mad-grid-item">
                            <!--================ Entity ================-->
                            <article class="mad-entity">
                                <div class="mad-entity-media mad-owl-center-img">
                                    <a href="'.$linksrc.'" '.$linkTarget.'>
                                        <img src="' . IMAGE_PATH . 'blog/' . $homebl->image . '" alt="' . $homebl->title . '" />
                                    </a>
                                </div>
                                <div class="mad-entity-content mad-owl-center-element">
                                    <div class="mad-entity-inner">
                                        <h4 class="mad-entity-title">' . $homebl->title . '</h4>
                                        <p>
                                        ' . $homebl->brief . '   
                                        </p>
                                        <div class="mad-entity-footer">
                                            <a href="'.$linksrc.'" '.$linkTarget.' class="btn btn-big">View More</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!--================ End of Entity ================-->
                        </div>
           
                  
           ';
        }
        $homeblogs='<div class="mad-title-wrap align-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="mad-pre-title">Make memories happen</div>
                <h2 class="mad-page-title">Club Himalaya Experience</h2>
            </div>
        </div>
    </div>
    <div class="mad-section no-pt mad-section-pb-mobile mad-section--stretched-content-no-px mad-colorizer--scheme-color-2">
    <div class="mad-entities mad-owl-center mad-pricing type-3 with-img-border mad-grid owl-carousel mad-owl-moving mad-grid--cols-2 nav-size-2 no-dots">
        <!-- owl item -->
                
                '.$homebloglist.'
                <!-- / owl item -->
                </div>
            </div>
        ';
    }
}

$jVars['module:homebloglist'] = $homeblogs;

$blog_detail = $recent_posts = '';
if (defined("BLOG_PAGE") ) {
    $slug = !empty($_REQUEST['slug']) ? $_REQUEST['slug'] : '';
    $Blogs = Blog::find_by_slug($slug);
    //pr($Blogs);
   

    if (!empty($slug)) {
        $blog_detail .= '
        <!--================ Breadcrumb ================-->
        <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="'. BASE_URL .'template/web/images/default.jpg">
            <div class="container wide">
                <h1 class="mad-page-title">Blogs</h1>
                <nav class="mad-breadcrumb-path">
                    <span><a href="' . BASE_URL . 'home" class="mad-link">Home</a></span> /
                    <span></span>
                </nav>
            </div>
        </div>
       
        
               ';
        
        $blog_detail .= '
        <div class="mad-content no-pd">
            <div class="container">
                <div class="row">
                <div class="mad-section col-md-8">
                    <div class="mad-entities mad-entities-reverse type-4">
                            <div class="news-details__img">
                                <img src="' . IMAGE_PATH . 'blog/' . $Blogs->image . '" alt="' . $Blogs->title . '">
                                <div class="news-details__date">
                                    <p>' . date('d M Y', strtotime($Blogs->blog_date)) . '</p>
                                </div>
                            </div>
                            <div class="news-details__content">
                                <p class="news-details__author">by ' . $Blogs->author . '</p>
                                ' . $Blogs->content . '

                                </div>
                            <br/>
                            <div class="news-details__pagenation-box">
	                        
                            </div>
                            
                        </div>
                    </div>
                    

   ';
                                

        $recents = Blog::get_latestblog_by(3);
        if (!empty($recents)) {
            $blog_detail .='<div class="mad-section col-md-4">
                        <div class="sidebar">
                            <div class="sidebar__single sidebar__post">
                                <h3 >Latest posts</h3>
                                <ul >';
            foreach ($recents as $recent) {
                if ($recent->title != $Blogs->title) {
                    $blog_detail .= '
                    
                                    
                                    <li>
                                        <div class="sidebar__post-image">
                                            <img src="' . IMAGE_PATH . 'blog/' . $recent->image . '" alt="' . $recent->title . '">
                                        </div>
                                        <div class="sidebar__post-content">
                                            <P><i
                                                class="fas fa-calendar"></i>' . date("d M Y", strtotime($homebl->blog_date)) . '<P>
                                            <h5>
                                                
                                                <a href="' . BASE_URL . 'blog/' . $recent->slug . '">' . $recent->title . '</a>
                                            </h5>
                                        </div>
                                    </li>
                                
                    
                 ';
                }
                
            }
            $blog_detail .= '
            
            </ul>
                            </div>
                        </div>
                    </div>
                    
            </div>
        </div>
        </div>
    </div>
    </div>';       
        }
    } else {
        $blog_detail .= '
        <!--================ Breadcrumb ================-->
        <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="'. BASE_URL .'template/web/images/default.jpg">
            <div class="container wide">
                <h1 class="mad-page-title">About Us</h1>
                <nav class="mad-breadcrumb-path">
                    <span><a href="' . BASE_URL . 'home" class="mad-link">Home</a></span> /
                    <span>Blogs</span>
                </nav>
            </div>
        </div>
        
        <div class="mad-title-wrap align-center">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="mad-pre-title">Make memories happen</div>
                            <h2 class="mad-page-title">Club Himalaya Experience</h2>
                        </div>
                    </div>
                </div>
                
                
                <div class="mad-section no-pt mad-section-pb-mobile mad-section--stretched-content-no-px mad__colorizer--scheme-color-2">
                <div class="mad-entities mad-owl-center mad-pricing type-3 with-img-border mad-grid owl__carousel mad-owl__moving mad-grid--cols-2 nav-size-2 no-dots d-flex flex-wrap">
                  
                ';
        $Blogs = Blog::get_allblog();
        //pr($Blogs);
         foreach ($Blogs as $homebl) {
            
           if(!empty($homebl->linksrc)){
            // $pagelink = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
            $linkTarget = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
                $linksrc = ($homebl->linktype == 1) ? $homebl->linksrc : BASE_URL.$homebl->linksrc;
           }
           else{
                $linksrc= BASE_URL. 'blog/'. $homebl->slug;
           }
           $blog_detail .='
           <div class="mad__grid-item">
                            <!--================ Entity ================-->
                            <article class="mad-entity col-12">
                                <div class="mad-entity-media mad-owl-center-img">
                                    <a href="'.$linksrc.'" '.$linkTarget.'>
                                        <img src="' . IMAGE_PATH . 'blog/' . $homebl->image . '" alt="' . $homebl->title . '" />
                                    </a>
                                </div>
                                <div class="mad-entity__content mad-owl-center-element">
                                    <div class="mad-entity-inner">
                                        <h4 class="mad__entity-title">' . $homebl->title . '</h4>
                                        <h4 class="mad__entity-title">' . date("d M Y", strtotime($homebl->blog_date)) . '</h4>
                                        <p>
                                            A Rare Blend Of Nature And Modern Amenities and has become synonymous with Nagarkot.
                                        </p>
                                        <div class="mad-entity-footer">
                                            <a href="'.$linksrc.'" '.$linkTarget.' class="btn btn-big">View More</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!--================ End of Entity ================-->
                </div>

           ';
    }
    $blog_detail .='
    </div>
    
                </div>
            ';
    
    }
}


$jVars['module:blog-detail'] = $blog_detail;
$jVars['module:blog-recent-posts'] = $recent_posts;


?>