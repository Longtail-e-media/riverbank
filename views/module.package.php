<?php
/*
* Home accmodation list
*/
$reshmpkg = $breadcumb_package = '';





$booking_code = Config::getField('hotel_code', true);
if (defined('HOME_PAGE')) {
    $acid = Package::get_accommodationId();
    $pkgRec = Package::find_by_id($acid);
    if (!empty($pkgRec)) {
        $subRec = Subpackage::getPackage_limit($acid);
        if (!empty($subRec)) {
            $reshmpkg .= '<div class="widget-carousel owl-carousel owl-theme">';
            foreach ($subRec as $subRow) {
                $file_path = SITE_ROOT . 'images/subpackage/image/' . $subRow->image2;
                if (file_exists($file_path) and !empty($subRow->image2)) {
                    $reshmpkg .= '<div class="rooms-item">
                        <div class="item-inner">
                            <div class="item-photo">
                                <a href="' . BASE_URL . $subRow->slug . '" data-background="'  . resizeUrl('subpackage/image/'.$subRow->image2, 800) . '"></a>
                            </div>
                            <div class="item-desc">
                                <h2><a href="' . BASE_URL . $subRow->slug . '">' . $subRow->title . '</a></h2>
                                <p>' . strip_tags($subRow->detail) . '</p>

                            </div>
                        </div>
                    </div>';
                }
            }
            $reshmpkg .= '</div>';
        }
    }
}

$jVars['module:home-accommodation'] = $reshmpkg;


/*
* Home package list
*/
$reshplist = '';

if (defined('HOME_PAGE')) {
    $reshplist .= ' <!-- Section Features -->
        <div class="section">
            <div class="container">
                <div class="wrapper-inner">
                    <div class="widget-features-grid">
                        <!-- Features Title -->
                        <div class="widget-title">
                            <h5>Resort Highlights</h5>
                            <h3>Explore River Bank Jungle Resort </h3>
                        </div>
                        <!-- Features Title End -->

                        <!-- Features Content -->
                        <div class="widget-inner">
                            <div class="row">';

    $pkgRec = Package::getPackage();
// 	pr($pkgRec);
    //if (!empty($pkgRec) && isset($pkgRec->status) && $pkgRec->status == 1) {
        $reshplist .= '<ul class="property-container col-sm-12">';
        foreach ($pkgRec as $pkgRow) {
            $imgpath = '';
            if ($pkgRow->image != 'a:0:{}') {
                $innimg = unserialize($pkgRow->image);
                // if(!empty($innimg)){
                // $img_rand = array_rand($innimg);
                $imgpath .= IMAGE_PATH . 'package/' . $innimg[0];
// 			}
            } else {
                $imgpath .= IMAGE_PATH . 'images/package.jpg';
            }
            $reshplist .= '

			<div class="col-lg-4 col-sm-6">
                                    <div class="features-item" data-background="'. resizeUrl('package/'.$innimg[0], 800) .'">
                                        <a href="' . BASE_URL . $pkgRow->slug . '">
                                            <h3>' . $pkgRow->title . '</h3>
                                            <p>' . $pkgRow->sub_title . '</p>
                                        </a>
                                    </div>
                                </div>

							';
        }
        $reshplist .= '</div>
                        </div>
                        <!-- Features Content End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Features End -->';
    //}
    //else {
     //   redirect_to(BASE_URL.'404');
    //}
}

$jVars['module:home-packagelist'] = $reshplist;


/*
* Package Record
*/
$respkgDetail = '';

if (defined('PACKAGE_PAGE') and isset($_REQUEST['slug'])) {
    $slug = !empty($_REQUEST['slug']) ? addslashes($_REQUEST['slug']) : '';
    $pkgRec = Package::find_by_slug($slug);
    if (!empty($pkgRec) && isset($pkgRec->status) && $pkgRec->status == 1) {



        $imglink = '';
        $pkgRowImg = $pkgRec->banner_image;
        if ($pkgRowImg != "a:0:{}") {
            $pkgRowList = unserialize($pkgRowImg);
            $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
            // pr($file_path);
            if (file_exists($file_path) and !empty($pkgRowList[0])) {
                $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
            } else {
                $imglink = BASE_URL . 'images/static/subpackage-banner.jpg';
            }
        }

              $breadcumb_package.= '<ol class="breadcrumb text-center">
            <li><a href="'.BASE_URL.'">Home</a></li>
            <li><a href="'.BASE_URL.''.$pkgRec->slug.'">'.$pkgRec->title.'</a></li>
            <li class="active"><a href="'.BASE_URL.''.$pkgRec->slug.'">' . $pkgRec->title . '</a></li>
            </ol>';
        // Package detail
        $respkgDetail .= '<!-- Section Page Title -->
	    <!--<div class="section d-none">
	        <div class="widget-page-title">
	            <div class="container">

	                <div class="widget-breadcrumb">
	                    <ul>
	                        <li><a href="' . BASE_URL . 'home">HOME</a></li>
	                        <li>' . $pkgRec->title . '</li>
	                    </ul>
	                </div>

	            </div>
	        </div>
	    </div>-->

	    <div class="banner-header section-padding valign bg-img innerpage2" data-background="' . $imglink . '">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 caption">
                        <h1>' . $pkgRec->title . '</h1>
                    </div>
                </div>
            </div>
        </div>
        '. $breadcumb_package.'


	    <!-- Section Rooms -->
	    <div class="section">
	    <div class="container">
	        <div class="wrapper-inner">
	        	<!--<h1 class="text-center">' . $pkgRec->title . '</h1>-->
	        	<p class="text-center">' . $pkgRec->content . '</p>

	        	';
        $subRec = Subpackage::getPackage_limit($pkgRec->id);
        // 	pr($subRec);
        if (!empty($subRec)) {
            $respkgDetail .= '<div class="widget-rooms-list">';
            foreach ($subRec as $subRow) {
                $file_path = SITE_ROOT . 'images/subpackage/image/' . $subRow->image2;
                if (file_exists($file_path) and !empty($subRow->image2)) {
                    $respkgDetail .= '<div class="rooms-item">
		                		<div class="item-photo">
			                        <a href="' . BASE_URL . $subRow->slug . '" data-background="' . IMAGE_PATH . 'subpackage/image/' . $subRow->image2 . '"></a>';
    $respkgDetail .= '     </div>
			                    <div class="item-desc">
			                        <h2><a href="' . BASE_URL . $subRow->slug . '">' . $subRow->title . '</a></h2>
			                        <p>' . strip_tags($subRow->detail) . '</p>
			                        <div class="desc-features">';
                    if (!empty($subRow->feature)) {
                        $sfeatRec = unserialize($subRow->feature);
                        if (!empty($sfeatRec)) {
                            $j = 1;
                            foreach ($sfeatRec as $sfRow) {
                                if ($j <= 1) {
                                    $respkgDetail .= '<ul>';
                                    $i = 1;
                                    if (!empty($sfRow[1])) {
                                        foreach ($sfRow[1] as $val) {
                                            $sfetname = Features::find_by_id($val);
                                            if ($i <= 9) {
                                                $img_path = SITE_ROOT . 'images/features/' . $sfetname->image;

                                                $icon = (file_exists($img_path) and !empty($sfetname->image)) ? '<img width="28px" src="' . IMAGE_PATH . 'features/' . $sfetname->image . '" alt="' . $sfetname->title . '"/>' : '<i class="fa fa-check"></i>';

                                                $respkgDetail .= '<li>' . $icon . ' <p>' . $sfetname->title . '</p></li>';
                                            }
                                            $i++;
                                        }
                                    }
                                    $respkgDetail .= '</ul>';
                                }
                                $j++;
                            }
                        }
                    }
                    $respkgDetail .= '</div>';

                                                                                        if ($pkgRec->type == 1) {
                        $respkgDetail .= '<div class="item-price">
                             <div class="price-inner">
                              <a href="' . BASE_URL . $subRow->slug . '" class="btn" style="margin-right:20px;">ROOM DETAIL</a>
                              <a href="' . BASE_URL . '/result.php?hotel_code='.$booking_code.'" target="_blank" class="btn">BOOK NOW</a>
                              </div>
                          </div>';
                    }
                    if ($pkgRec->id == 4) {
                        $respkgDetail .= '<div class="item-price">
                             <div class="price-inner">
                              <a href="' . BASE_URL . $subRow->slug . '" class="btn" style="margin-right:20px;">VIEW DETAILS</a>
                              </div>
                          </div>';
                    }


                    $respkgDetail .= '	</div></div>';
                }
            }
            $respkgDetail .= '</div>';
        }
        $respkgDetail .= '</div>
	    </div>
	    </div>';
    }
    else{
        redirect_to(BASE_URL.'404');
    }
}

$jVars['module:pakcage-detail'] = $respkgDetail;


/*
* Sub package
*/
$resubpkgDetail = '';
$resubpkgDetailScript = $resubpkgDetailScriptOnload = $resubpkgDetailScriptVar = '';


if (defined('SUBPACKAGE_PAGE') and isset($_REQUEST['slug'])) {
    $slug = !empty($_REQUEST['slug']) ? addslashes($_REQUEST['slug']) : '';
    $subpkgRec = Subpackage::find_by_slug($slug);
        $pkgRec = Package::find_by_id($subpkgRec->type);

    if (!empty($subpkgRec) && $subpkgRec->status ==1) {
        $pkgRec = Package::find_by_id($subpkgRec->type);
        $imglink = BASE_URL . 'images/static/subpackage-banner.jpg';
        $pkgRowList = $subpkgRec->header_image;
        if (!empty($pkgRowList)) {
            // $pkgRowList = unserialize($pkgRowImg);
            $file_path = SITE_ROOT . 'images/subpackage/imgheader/' . $pkgRowList;
            if (file_exists($file_path) and !empty($pkgRowList)) {
                $imglink = IMAGE_PATH . 'subpackage/imgheader/' . $pkgRowList;
            } else {
                $imglink = BASE_URL . 'images/static/subpackage-banner.jpg';
            }
        }

                    $breadcumb_package.= '<ol class="breadcrumb text-center">
            <li><a href="'.BASE_URL.'">Home</a></li>
            <li><a href="'.BASE_URL.''.$pkgRec->slug.'">'.$pkgRec->title.'</a></li>
            <li class="active"><a href="'.BASE_URL.''.$subpkgRec->slug.'">' . $subpkgRec->title . '</a></li>
            </ol>';

        $resubpkgDetail .= '<!-- Section Page Title -->
	    <!--<div class="section d-none">
	        <div class="widget-page-title">
	            <div class="container">

	                <div class="widget-breadcrumb">
	                    <ul>
	                        <li><a href="' . BASE_URL . 'home">HOME</a></li>
	                        <li><a href="' . BASE_URL . $pkgRec->slug . '">' . $pkgRec->title . '</a></li>
	                        <li>' . $subpkgRec->title . '</li>
	                    </ul>
	                </div>

	            </div>
	        </div>
	    </div>-->
	     <div class="banner-header section-padding valign bg-img innerpage2" data-background="' . $imglink . '">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 caption">
                            <h1>' . $subpkgRec->title . '</h1>
                        </div>
                    </div>
                </div>
            </div>
                    '.$breadcumb_package.'

	    <!-- Section Rooms Detail -->
	    <div class="section">
	    <div class="container">
	        <div class="wrapper-inner">
	            <div class="widget-rooms-detail">
	                <div class="widget-inner">
	                    <div class="row">
	                    	';
        if ($pkgRec->type == 1) {
            $resubpkgDetail .= '

								<div class="col-md-12">';
        } else {

            $resubpkgDetail .= '

								<div class="col-md-12">';
        }

        $resubpkgDetail .= '	<div class="room-desc">
									' . $subpkgRec->content . '
								</div>';
        // pr($pkgRec);
        if ($pkgRec->id == 4) {
            $resubpkgDetail .= '
								<div class="text-center">
				<button type="button" class="btn btn-info btn-lg hall_inquiry_btn" data-toggle="modal" data-target="#myModal">Inquiry Now</button>
				<div class="hall_inquiry_form">
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">hall Inquiry</h4>
        </div>
        <div class="modal-body">
               <form id="hall_inquiry_form">
              <div class="mb-4">
                <input type="text" class="form-control" id="FullName" name="name" placeholder="Full Name">
                <input type="hidden" class="form-control"  name="hall" value="' . $subpkgRec->title . '">
              </div>
              <div class="mb-4">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
              </div>
              <div class="mb-4">
                <input type="number" class="form-control" id="Contact"  name="contact" placeholder="Mobile No.">
              </div>
              <div class="mb-4">
                <input type="text" class="form-control" id="event_name" name="event" placeholder="Event Name" value="' . $subpkgRec->title . '" disabled>
              </div>
              <div class="mb-4 d-flex justify-content-between gap-3">
                <div class="book_date flex-grow-1">
                <input type="text" class="form-control" id="checkin" name="date" placeholder="Date">
                </div>
                <div class="no_pax flex-grow-1">
                  <input type="number" class="form-control"  name="pax" id="number" placeholder="Pax">
                </div>
              </div>

              <div class="mb-4">
                <textarea class="form-control" id="message-text" name="message" placeholder="Message"></textarea>
              </div>
                <div class="mb-4">
                    <!--<div id="g-recaptcha-response" class="g-recaptcha" data-sitekey="6LeFNRAqAAAAAPHZU1kPnAcElVtYug0gkJcAuXSt"></div>-->
                    <div id="winget' . $subpkgRec->id . '"></div>
                </div>
              <div class="modal-footer">
                <button type="submit" id="submit" class="btn btn-danger">Submit</button>
                <div id="result_msg" class="alert alert-success media text-center" style="display: none"></div>
              </div>
            </form>
          </div>
          </div>
      </div>

    </div>
  </div>
</div>
';
            $resubpkgDetailScriptVar = '
                var widgetId' . $subpkgRec->id . ';
            ';
            $resubpkgDetailScriptOnload .= '
                widgetId' . $subpkgRec->id . ' = grecaptcha.render("winget' . $subpkgRec->id . '", {
                    "sitekey": "6LeFNRAqAAAAAPHZU1kPnAcElVtYug0gkJcAuXSt",
                    "callback": function(response) {
                      $("#hall_inquiry_form").data("recaptcha-response", response);
                    }
                  });
            ';
            $resubpkgDetailScript .= '

                    $(document).ready(function () {
                        $("#hall_inquiry_form").validate({
                            errorElement: "span",
                            errorClass: "validate-has-error",
                            rules: {
                                name: {required: true, minlength: 2},
                                email: {required: true, email: true},
                                contact: {required: true},
                                pax: {required: true},
                                message: {required: true}
                            },
                            messages: {
                                name: {required: "Please enter your Name."},
                                email: {required: "Please enter your Email."},
                                contact: {required: "Please enter your Mobile Number."},
                                date: {required: "Please select your Event Date."},
                                pax: {required: "Please enter your Pax."},
                                message: {required: "Please enter your Message."}
                            },
                            submitHandler: function (form) {
                                if (grecaptcha.getResponse(widgetId' . $subpkgRec->id . ') === "") {
                                    alert("Please complete the CAPTCHA");
                                    return false;
                                }
                                var Frmval = $("#hall_inquiry_form").serialize();
                                $("button#submit").attr("disabled", "true").text("Processing...");
                                $.ajax({
                                    type: "POST",
                                    dataType: "JSON",
                                    url: base_url+"/enquery_mail.php",
                                    data: "action=forHall&" + Frmval,
                                    success: function (data) {
                                        var msg = eval(data);
                                        $("button#submit").removeAttr("disabled").text("Submit");
                                        $("div#result_msg").html(msg.message).css("display", "block").fadeOut(8000);
                                        $("#hall_inquiry_form")[0].reset();
                                    }
                            });
                            return false;
                        }
                    })
                });

            ';

        }
        if (!empty($subpkgRec->feature)) {
            $ftRec = unserialize($subpkgRec->feature);
            if (!empty($ftRec)) {
                $resubpkgDetail .= '<!-- Room Features -->
	                            	<div class="room-features">';
                foreach ($ftRec as $k => $v) {
                    $resubpkgDetail .= '<h4>' . $v[0][0] . '</h4>';
                    if (!empty($v[1])) {
                        $resubpkgDetail .= '<div class="ammenities-box rooms-sub box' . $k . '">
													<div class="row">
													    ';
                        foreach ($v[1] as $kk => $vv) {
                            $sfetname = Features::find_by_id($vv);
                            $img_path = SITE_ROOT . 'images/features/' . $sfetname->image;
                            $icon = (file_exists($img_path) and !empty($sfetname->image)) ? '<img width="35px;"  src="' . IMAGE_PATH . 'features/' . $sfetname->image . '" alt="' . $sfetname->title . '"/>' : '<i class="fa fa-check"></i>';
                            $resubpkgDetail .= '<div class="col-md-4 col-sm-6 mb-20">
													    <div class="row"><div class="col-lg-2 col-md-1 col-sm-2">
															                        ' . $icon . '
															                 </div>
															                 <div class="col-lg-6 col-md-10 col-xs-10">
															                    <p>' . $sfetname->title . '</p></div></div></div>';
                        }
                        $resubpkgDetail .= '</div>
												</div><br />';
                    }
                }
                $resubpkgDetail .= '</div>';
            }
        }
        $resubpkgDetail .= '	<!-- <div class="room-rariff">
									<h2 class="h3 header-sidebar">Room Tariff</h2><hr>
									<table class="table">
										<tr>
											<th>Room Plan</th>
											<th>1 Pax./Night</th>
											<th>2 Pax./Night</th>
											<th>3 Pax./Night</th>
											<th>Extra Bed</th>
										</tr>
										<tr>
											<td>Without Breakfast</td>
											<td>' . $subpkgRec->currency . ' ' . set_na($subpkgRec->onep_price) . '</td>
											<td>' . $subpkgRec->currency . ' ' . set_na($subpkgRec->twop_price) . '</td>
											<td>' . $subpkgRec->currency . ' ' . set_na($subpkgRec->threep_price) . '</td>
											<td>' . $subpkgRec->currency . ' ' . set_na($subpkgRec->extra_bed) . '</td>
										</tr>
										<tr>
											<td>With Breakfast</td>
											<td>' . $subpkgRec->currency . ' ' . set_na($subpkgRec->oneb_price) . '</td>
											<td>' . $subpkgRec->currency . ' ' . set_na($subpkgRec->twob_price) . '</td>
											<td>' . $subpkgRec->currency . ' ' . set_na($subpkgRec->threeb_price) . '</td>
											<td>&nbsp;</td>
										</tr>
									</table>
								</div> -->
	                    	</div>';
        if ($pkgRec->type == 1) {
            $resubpkgDetail .= '	<!--	<div class="col-md-4">-->
	                    		<!-- Room Booking -->
	                          <!--  <div class="room-booking">
	                                <h3>Book a Room</h3>
	                                <div class="data-form">

	                                </div>
	                            </div></div>-->

	                            ';
        }
        $resubpkgDetail .= '   <div class="col-md-12">';

        if ($subpkgRec->image != 'a:0:{}') {
            $subimg = unserialize($subpkgRec->image);
            if (!empty($subimg)) {
                $main_img = $thum_img = '';
                $cn = 0;
                foreach ($subimg as $imgname) {
                    $file_path = SITE_ROOT . 'images/subpackage/' . $imgname;
                    if (file_exists($file_path) and !empty($imgname)) {
                        $main_img .= '<a href="' . IMAGE_PATH . 'subpackage/' . $imgname . '" data-background="' . IMAGE_PATH . 'subpackage/' . $imgname . '" title="' . $subpkgRec->title . '" class="popup-gallery"></a>';
                        $thum_img .= '<a href="javascript:void(0);" data-background="' . IMAGE_PATH . 'subpackage/' . $imgname . '" title="' . $subpkgRec->title . '"></a>';
                    }
                    $cn++;
                }

                $resubpkgDetail .= '<!-- Room Slider -->
			                            <div class="room-slider">
			                                <!-- <div class="room-price">' . $subpkgRec->currency . ' ' . $subpkgRec->onep_price . ' <small>PER NIGHT</small></div> -->
			                                <div class="owl-carousel owl-theme owl-type1">
			                                    ' . $main_img . '

			                                </div>
			                            </div>
			                            <!-- Room Thumbnails -->
			                            <!-- <div class="room-thumbnails">
			                                <div class="owl-carousel">
			                                    ' . $thum_img . '

			                                </div>
			                            </div> -->';
            }
        }
        $resubpkgDetail .= '
	</div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        </div>
	    </div>';
    }
    else {
        redirect_to(BASE_URL.'404');
    }
}

$jVars['module:sub-package-detail'] = $resubpkgDetail;
$jVars['module:sub-package-detail-script'] = $resubpkgDetailScript;
$jVars['module:sub-package-detail-script-onload'] = $resubpkgDetailScriptOnload;
$jVars['module:sub-package-detail-script-var'] = $resubpkgDetailScriptVar;


/*
* Facility
*/
$facility = '';

if (defined('FACILITIES_PAGE')) {

    //$slug = !empty($_REQUEST['slug'])? addslashes($_REQUEST['slug']) : '';
    $subpkgRec = services::find_all();


    if (!empty($subpkgRec)) {
        $fpkgRec = features::find_all_byparnt(0);

        $facility .= '<!-- Section Page Title -->
	    <!--<div class="section">
	        <div class="widget-page-title">
	            <div class="container">

	                <div class="widget-breadcrumb">
	                    <ul>
	                        <li><a href="' . BASE_URL . 'home">HOME</a></li>
	                        <li><a href="' . BASE_URL . $fpkgRec[0]->title . '">' . $fpkgRec[0]->title . '</a></li>

	                    </ul>
	                </div>

	            </div>
	        </div>
	    </div>-->
	    <!-- Section Rooms Detail -->
	    <div class="section">
	        <div class="container">
	            <div class="wrapper-inner">
	                <div class="widget-rooms-detail facilities--list">
	                    <div class="widget-inner">
	                        <div class="row">
	                    	    ';

        foreach ($subpkgRec as $k => $v) {
            $img_nm = unserialize($v->image);

            $facility .= '<div class="col-lg-3 col-sm-4 col-xs-6"><div class="etm">
	                    		 <div class="text-center"><img src="' . IMAGE_PATH . '/services/' . $img_nm[0] . '"/></div>
	                    		 <div class="text-center text--title">' . $v->title . '</div>
	                    		 </div></div>';
        }


        $facility .= '';


        $facility .= '

	                    </div>
	                </div>
	            </div>
	        </div>
	        </div>
	    </div>';
    }
}

$jVars['module:facility'] = $facility;