<?php

/**
 * Display Blogs in Homepage
 **/
$dataUrl = $breadcumb_blog = $breadcumb_blog2='';
$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/', $current_url);
$last = trim(end($data), '/');
$last = strtok($last, '?');
$dataUrl .= $last;

$breadcumb_blog .= '<ol class="breadcrumb text-center">
  <li><a href="'.BASE_URL.'">Home</a></li>
  <li class="active"><a href="'.BASE_URL.''.$dataUrl.'">Blog</a></li>
</ol>';








$home_blog = '';
if (defined("HOME_PAGE")) {
    $blogs = CombinedNews::find_all(3);

        if(!empty($blogs)){



        $home_blog .= ' <div class="blog-section">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                     <div class="widget-title mb-5">
                                    <h5 class="text-uppercase">Our Blog</h5>
                                    <h3>Stories from the River Bank</h3>
                                    </div>
                                    </div>';

    foreach ($blogs as $blog) {
        $img = BASE_URL . "template/web/images/image_2.jpg";
        if (!empty($blog->home_image)) {
            $file_path = SITE_ROOT . "images/combinednews/home/" . $blog->home_image;
            if (file_exists($file_path)) {
                $img = IMAGE_PATH . "combinednews/home/" . $blog->home_image;
            }
        }
        $home_blog .= '
                <div class="col-md-4">
                    <div class="panel panel-default blog-card">
                        <div class="image-blog">
                           <a href="' . BASE_URL . 'blog/' . $blog->slug . '"> <img src="' . $img . '" class="img-responsive" alt="' . $blog->title . '"></a>
                            <div class="author-date">
                                <small>Posted: ' . date('F d, Y', strtotime($blog->event_stdate)) . '</small>
                                <small><a href="' . BASE_URL . 'blog/' . $blog->slug . '">Author: '.$blog->author.'</a></small>
                            </div>

                        </div>
                        <div class="panel-body pt-2">
                            <div class="blog-text mt-4">
                               <a href="' . BASE_URL . 'blog/' . $blog->slug . '"> <h2 class="panel-title mb-4">' . $blog->title . '</h2></a>

                                <p class="excerpt">' . strip_tags($blog->content) . '
                                </p>
                            </div>
                        </div>
                        <div class="panel--blog-link">
                            <a href="' . BASE_URL . 'blog/' . $blog->slug . '" class="btn btn-primary btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
        ';

    }
              $home_blog .= '<div class=" col-sm-12 col-md-12 text-center mt-5 mb-5">
                    <a href="blog" class="btn-blog btn">View All Blog</a>
                </div>
            </div>

        </div>
      </div> ';
}
     }
$jVars["module:combinednews:home-blog"] = $home_blog;


/**
 * Blog listing page
 **/
$blog_list = $blog_list_breadcrumb = '';
if (defined("BLOG_PAGE")) {
    $img = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;

    $blog_list_breadcrumb .= '
        <div class="banner-header section-padding valign bg-img innerpage2" data-background="'.BASE_URL.'images/static/contact-banner.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 caption">
                            <h1>Blog</h1>
                        </div>
                    </div>
                </div>
            </div>
            '.$breadcumb_blog.'

    ';

    $page = (isset($_REQUEST["pageno"]) and !empty($_REQUEST["pageno"])) ? $_REQUEST["pageno"] : 1;
    $sql = "SELECT * FROM tbl_conbined_news WHERE status='1' ORDER BY event_stdate DESC";

    $limit = 3000;
    $total = $db->num_rows($db->query($sql));
    $startpoint = ($page * $limit) - $limit;
    $sql .= " LIMIT " . $startpoint . "," . $limit;

    $blogs = CombinedNews::find_by_sql($sql);
    if ($blogs) {
        foreach ($blogs as $blog) {
            $img = BASE_URL . "template/web/image/bg-image.png";
            if (!empty($blog->home_image)) {
                $file_path = SITE_ROOT . "images/combinednews/home/" . $blog->home_image;
                if (file_exists($file_path)) {
                    $img = IMAGE_PATH . "combinednews/home/" . $blog->home_image;
                }
            }
            $brief = substr(strip_tags($blog->content),'0','80').'...';
            $blog_list .= '
                                <div class="col-sm-5 col-lg-4">
                    <div class="panel panel-default blog-card">
                        <div class="image-blog">
                           <a href="' . BASE_URL . 'blog/' . $blog->slug . '"> <img src="' . $img . '" class="img-responsive" alt="' . $blog->title . '"></a>
                            <div class="author-date">
                                <small>Posted: ' . date('F d, Y', strtotime($blog->event_stdate)) . '</small>
                                <small><a href="">Author: '.$blog->author.'</a></small>
                            </div>

                        </div>
                        <div class="panel-body pt-2">
                            <div class="blog-text mt-4">
                               <a href="' . BASE_URL . 'blog/' . $blog->slug . '"> <h2 class="panel-title mb-4">' . $blog->title . '</h2></a>

                                <p class="excerpt">' . strip_tags($blog->content) . '
                                </p>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <a href="' . BASE_URL . 'blog/' . $blog->slug . '" class="btn btn-primary btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            ';
        }
    }
}

$jVars["module:combinednews:blog-list-breadcrumb"] = $blog_list_breadcrumb;
$jVars["module:combinednews:blog-list-list"] = $blog_list;


/**
 * Blog Detail page
 **/

$blog_detail = $blog_breadcrumb = $recent_blogs = $gallery_section = '';
if (defined("BLOG_PAGE")) {




    $slug = (isset($_REQUEST["slug"]) and !empty($_REQUEST["slug"])) ? $_REQUEST["slug"] : '';
    $blogRec = CombinedNews::find_by_slug($slug);

    if (!empty($blogRec)) {
        $banner_img = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
        if (!empty($blogRec->banner_image)) {
            $file_path = SITE_ROOT . "images/combinednews/banner/" . $blogRec->banner_image;
            if (file_exists($file_path)) {
                $banner_img = IMAGE_PATH . "combinednews/banner/" . $blogRec->banner_image;
            }
        }
        $img = BASE_URL . "template/web/image/bg-image.png";
        if (!empty($blogRec->banner_image)) {
            $file_path = SITE_ROOT . "images/combinednews/banner/" . $blogRec->banner_image;
            if (file_exists($file_path)) {
                $blog_detail_image = IMAGE_PATH . 'combinednews/banner/' . $blogRec->banner_image ;
            }
        }

               $breadcumb_blog2 .= '<ol class="breadcrumb text-center">
              <li><a href="'.BASE_URL.'">Home</a></li>
               <li><a href="'.BASE_URL.'blog">Blog</a></li>
              <li class="active"><a href="javascript:void(0);">'.$blogRec->title.'</a></li>
            </ol>';



                $blog_breadcrumb .= '

            <div class="banner-header section-padding valign bg-img innerpage2" data-background="'.$blog_detail_image.'">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 caption">
                            <h1>' . $blogRec->title . '</h1>
                        </div>
                    </div>
                </div>
            </div>
            '.$breadcumb_blog2.'

        ';



        $blog_detail .= '
        <div class="row">
            <!-- Blog Content -->
            <div class="col-md-8">
                <div class="blog-main">
                    <div class="person-info">
                        <div class="blog-meta">
                            <span><i class="fa fa-user"></i> ' . $blogRec->author . '</span>
                            <span><i class="fa fa-calendar"></i> ' . date('F d, Y', strtotime($blogRec->event_stdate)) . '</span>
                        </div>
                        <h1 class="blog-title">
                           ' . $blogRec->title . '
                        </h1>
                        <div class="blog-content">
                                 <p>' . $blogRec->content . '</p>
                        </div>
                    </div>
                    <!-- Social Share -->
                    <div class="social-share">
                        <h4>Share This Post</h4>
                        <ul>
                            <li>
                                <a href="http://www.facebook.com/share.php?caption=' . $blogRec->slug . '&description=' . $blogRec->brief . '&u=' . BASE_URL . 'blog/' . $blogRec->slug . '&picture=' . IMAGE_PATH . 'combinednews/' . $blogRec->home_image . '/">
                                    <i class="fa fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/share?url=' . BASE_URL . 'blog/' . $blogRec->slug . '/&text=' . $blogRec->title . '">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/shareArticle?url=' . BASE_URL . 'blog/' . $blogRec->slug . '">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="sidebar">
                    <h3 class="sidebar-title">
                        Recent Blog Posts
                    </h3> ';

        $recentBlogs = CombinedNews::getRelatednews_by($blogRec->id, 5);
        if (!empty($recentBlogs)) {
            foreach ($recentBlogs as $recentBlog) {
                $img = BASE_URL . "template/web/image/bg-image.png";
                if (!empty($recentBlog->home_image)) {
                    $file_path = SITE_ROOT . "images/combinednews/home/" . $recentBlog->home_image;
                    if (file_exists($file_path)) {
                        $img = IMAGE_PATH . "combinednews/home/" . $recentBlog->home_image;
                    }
                }
                $blog_detail .= '
                    <div class="recent-post">
                        <div class="recent-post-image">
                            <img src="' . $img . '" class="" alt="' . $recentBlog->title . '">
                        </div>
                        <div class="recent-post-content">
                            <h4 class="mb-0">
                                <a href="' . BASE_URL . 'blog/' . $recentBlog->slug . '">
                                  ' . $recentBlog->title . '
                                </a>
                            </h4>
                            <p class="card-text small text-muted mb-0">' . date('F, d Y', strtotime($recentBlog->event_stdate)) . '</p>
                             <small><a class="card-text text-muted" href="' . BASE_URL . 'blog/' . $recentBlog->slug . '" class="">' . $recentBlog->author . '</a></small>
                        </div>
                    </div>
                ';
            }
        }
         $blog_detail .= '
               </div>
            </div>
        </div>
         ';

    }
    else {
        //  redirect_to(BASE_URL . 'blog');
    }
}

$jVars["module:combinednews:blog-breadcrumb"] = $blog_breadcrumb;
$jVars["module:combinednews:blog-details"] = $blog_detail;
$jVars["module:combinednews:blog-recent-blogs"] = $recent_blogs;
?>