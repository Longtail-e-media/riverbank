<?php
$resbpkg = '';
$socialshare = '';
$maxdate = '';

if (defined('BOOK_PAGE')) {
	$slug = !empty($_REQUEST['slug']) ? addslashes($_REQUEST['slug']) : '';
	$sRec = Offers::find_by_slug($slug);


	if (!empty($sRec)) {
	    $maxdate=$sRec->offer_date;

		$imglink = IMAGE_PATH . 'static/about-img.jpg';
		if (!empty($sRec->image)) {
			$file_path = SITE_ROOT . 'images/offers/' . $sRec->image;
			if(file_exists($file_path)){
				$imglink = IMAGE_PATH . 'offers/' . $sRec->image;
			}
		}

		$socialshare = '
			<div class="share-social">
            	<a class="facebook-share" target="blank" href="https://www.facebook.com/dialog/share?app_id=919245263524173&display=popup&href=' . BASE_URL . 'offer/' . $sRec->slug . '&name=' . $sRec->title . '&caption=' . $sRec->title . $sRec->slug . '&description=' . $sRec->brief . '">
                	<i class="fa fa-facebook" aria-hidden="true"></i><span>Share</span>
				</a>
            <a class="twitter-share" target="blank" href="https://twitter.com/share?url=' . BASE_URL . 'offer/' . $sRec->slug . '&text=' . $sRec->title . '" >
                <i class="fa fa-twitter" aria-hidden="true"></i><span>Share</span></a>
            <!--<a class="gplus-share" target="blank" href="https://plus.google.com/share?url=' . BASE_URL . 'offer/' . $sRec->slug . '">
                <i class="fa fa-google-plus" aria-hidden="true"></i><span>Share</span></a>-->
        </div>
		';

		$rescontent = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($sRec->content));
		$content = !empty($rescontent[1]) ? $rescontent[1] : $rescontent[0];

		$resbpkg .= '
		<div class="banner-header gallery_other_half section-padding valign bg-img innerpage2" data-background="' . $imglink . '" style="background-image: url(&quot;' . $imglink . '&quot;);">
			<div class="container">
				<div class="row">
					<div class="col-md-12 caption">
						<h1>Exclusive Offers</h1>
					</div>
				</div>
			</div>
		</div>

	 <section class="breadcrumb-area overlay-dark-2 bg-2" style="background-image:url(' . IMAGE_PATH . 'images/static/about-img.jpg); background-repeat: no-repeat; ">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-text article text-center">
                            <div class="breadcrumb-bar">
                                <ul class="breadcrumb">
                                    <li><a href="' . BASE_URL . '">Home</a></li>
                                    <li><a href="' . BASE_URL . 'offer-list">Offer</a></li>
                                    <li>' . $sRec->title . '</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="wrapper-inner">
	 	    <div class="container">
        ' . $socialshare . '
                        <div class="offer-detail3">
                            <h2>' . $sRec->title . '</h2>
                            
                        </div>


			    <div class="row">
			        <div class="col-md-12">
			         <!--   <h4 class="text=center">' . $sRec->title . '</h4>-->
			        </div>
			    </div>

			    <div class="alert alert-success" id="msg" style="display:none;"></div>
					<form id="frm-booking">
					    <div class="row">';
					    if($sRec->currency==0){
					    $currency='NPR';
					    }else{
					    $currency='USD';
					        
					    }
					    $resbpkg .= '<input type="hidden" name="currency" value="'.$currency.'"/> ';
		if ($sRec->type == 3) {

			$resbpkg .= '
					         <div class="col-lg-6 col-md-12 col-xs-12">
					         ';
		} else {
			$resbpkg .= '<div class="col-lg-7 col-md-6 col-xs-12">';
		}
		$resbpkg .= '
							    <input type="hidden" name="offer_type" value="' . $sRec->type . '">';
		if ($sRec->type == 1) {
			$resbpkg .= '
								<table class="table table-bordered">
									<tr>
										<th>Package</th>
										<th>Price('.$currency.')</th>
										<th>No. of People</th>
										<th>Total Amount('.$currency.')</th>
									</tr>

									<tr class="parent">
										<td>
											<a class="text-info" href="' . BASE_URL . 'offer/' . $sRec->slug . '" target="_blank">' . $sRec->title . '</a>
											<input type="hidden" name="package_title[]" value="' . $sRec->title . '">
										</td>
										<td>
											' . $sRec->rate . '
											<input type="hidden" name="package_price[]" value="' . $sRec->rate . '">
											<input type="hidden" name="package_discount[]" value="' . $sRec->discount . '">
										</td>
										<td class="form-group">
											<!--<input type="text" name="no_pax[]" class="form-control"/>-->
											<select name="no_pax[]" class="form-control">
                                              <option value="">Select</option>
                                              ';
			for ($i = 1; $i <= $sRec->adults; $i++) {
				$resbpkg .= '<option value="' . $i . '">' . $i . '</option>';
			}
			$resbpkg .= '
                                            </select>
										</td>
										<td class="text-center totalamt">0</td>
									</tr>';
			if (!empty($sRec->discount) and $sRec->discount > 0) {
				$resbpkg .= '
                                    <tr>
										<td colspan="3">Discount (' . $sRec->discount . '%)<br>
										<small>* Discount not applicable for only 1 person</small></td>
										<td class="text-center discountamt">0</td>
									</tr>
                    ';
			}
			$resbpkg .= '
                                    <tr>
										<td colspan="3">Grand Total</td>
										<td class="text-center grand-total">0</td>
									</tr>
								</table>
								';
		}

		if ($sRec->type == 0) {
			$resbpkg .= '
								<table class="table">
									<tr>
										<th class="text-center">Choose</th>
										<th class="text-center">Price('.$currency.')</th>
										<th class="text-center">Number Of People</th>
									</tr>
									';
			$sql = "SELECT * FROM tbl_offer_child WHERE offer_id=$sRec->id";
			$query = $db->query($sql);
			$num = $db->num_rows($query);

			if ($num > 0) {
				while ($row = $db->fetch_array($query)) {
					if (!empty($row['offer_pax'])) {
						$resbpkg .= '
                                    <tr class="parent">
										<td class="col-sm-3 text-center">
											<input type="radio" value="' . $row['offer_pax'] . ';;' . $row['offer_usd'] . '" name="radio_type" id="radio_type" style="height:1em;">
										</td>
										<td class="col-sm-3 text-center">
											' . $row['offer_usd'] . '
											<input type="hidden" name="package_title[]" value="' . $sRec->title . '">
											<input type="hidden" name="package_price[]" value="' . $row['offer_usd'] . '">
										</td>
										<td class="col-sm-3 text-center">
											<input type="text" name="no_pax[]" class="" value="' . $row['offer_pax'] . '" disabled/>

										</td>
									</tr>
                    ';
					}
				}
			}
			$resbpkg .= '
								</table>
								';
		}
		if ($sRec->type == 2) {
			$resbpkg .= '
								<table class="table">
									<tr>
										<th class="text-center">Choose</th>
										<th class="text-center">Items</th>
										<th class="text-center">Price of Item('.$currency.')</th>
										<th class="text-center">No. of items</th>
										<th class="text-center">Total('.$currency.')</th>
									</tr>
									';
			$sql = "SELECT * FROM tbl_offer_child WHERE offer_id=$sRec->id";
			$query = $db->query($sql);
			$num = $db->num_rows($query);

			if ($num > 0) {
				while ($row = $db->fetch_array($query)) {

					if (!empty($row['multi_offer_title'])) {

						$resbpkg .= '
                                    <tr class="parent">
										<td class="col-sm-3 text-center">
											<input type="checkbox" name="multi_item[]" value="' . $row['multi_offer_title'] . '|' . $row['multi_offer_npr'] . '"

										</td>
										<td class="col-sm-3 text-center">
											' . $row['multi_offer_title'] . '
										</td>
										<td class="col-sm-3 text-center">
											' . $row['multi_offer_npr'] . '
											<input type="hidden" name="package_title[]" value="' . $sRec->title . '">
											<input type="hidden" name="package_price[]" value="' . $row['multi_offer_npr'] . '">
											<input type="hidden" name="package__item[]" value="' . $row['multi_offer_title'] . '">
										</td>
										<td class="col-sm-3 text-center">
											<input type="number" name="no_pax[]" class="" min="1" value="" disabled/>
										</td>
										<td class="col-sm-3 text-center">
											<div class="row_total"></div>
											<input type="hidden" name="row_hidden[]" class="row_hidden" value=""/>
											</td>

											</tr>
											';
					}
				}
			}
			$resbpkg .= '
									<tr>
									<td></td>
									<td></td>
									<td></td>
									<td>Grand Total('.$currency.')</td>
									<td class="gtotal">0</td>
									<input type="hidden" class="gtotal" name="multitotal" value=""/>
									</tr>
								</table>
								';

		}
		if ($sRec->type == 3) {
			$imglink = IMAGE_PATH . 'static/about-img.jpg';

            if (!empty($sRec->list_image)) {

                $file_path = SITE_ROOT . 'images/offers/listimage/' . $sRec->list_image;
                // pr($file_path);
                if(file_exists($file_path)){
                    $imglink = IMAGE_PATH . 'offers/listimage/' . $sRec->list_image;

                }

            }

			$resbpkg .= '
			<input type="hidden" name="package_title" value="'.$sRec->title.'">
			<div class="col-lg-12 col-md-7 col-xs-12" style="padding-inline:0;">
							<div class="col-md-12" style="padding-inline:0;">
                                <div class="offer offer-item position-relative">
                                    <a href="">
                                        <img src="'.$imglink.'" alt="' . $sRec->title . '">
                                        
                <!--     <div class="details">
                                            <h3>' . $sRec->title . '</h3>
                                        </div> -->
                                    </a>
                                </div>
                            </div>
							</div>

							';
							// pr($sRec->list_image);
		}

		$resbpkg .= '
							</div>

							<div class="col-lg-5 col-md-5 col-xs-12">
								<div class="row" id="offer__page">

    						        <div class="clearfix"></div>
    								<div class="form-group col-sm-6 astrik">
    						            <input name="person_first" type="text" placeholder="First Name *" class="form-control"/>
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_last" type="text" placeholder="Last Name " class="form-control"/>
    						        </div>
    						        <div class="form-group col-sm-6 astrik">
    						            <input id="person_checkin" name="person_checkin" type="text" placeholder="Check In Date *" class="form-control"/>
    						        </div>
    						        <div class="form-group col-sm-6 astrik">
    						            <input name="person_contact" type="text" placeholder="Contact No.*" class="form-control"/>
    						        </div>
    						        <div class="form-group col-sm-6 astrik">
    						            <input name="person_email" type="text" placeholder="Email Address *" class="form-control"/>
    						        </div>
									<div class="form-group col-sm-6 astrik">
    						            <select name="person_country" class="form-control *">
    						            	<option value="">Choose</option>';
		$contRec = Countries::find_all();
		foreach ($contRec as $contRow) {
			$resbpkg .= '<option value="' . $contRow->country_name . '">' . $contRow->country_name . '</option>';
		}
		$resbpkg .= '</select>
    						        </div>
    						        <div class="form-group col-sm-6 astrik">
    						            <input name="person_address" type="text" placeholder="Address *" class="form-control"/>
    						        </div>
    				                </div>


    						        <div class="form-group col-sm-12" style="padding-inline:0;">
    						            <textarea name="person_message" placeholder="Message *" class="form-control"></textarea>
    						        </div>
    				                <div class="form-group col-sm-6 astrik" style="padding-inline:0;">
    				        		<!--<img src="' . BASE_URL . 'captcha/imagebuilder.php?rand=310333" border="1"  onclick="updateCaptcha(this);">	-->
    				        		<div id="g-recaptcha-response" class="g-recaptcha" data-sitekey="6LeFNRAqAAAAAPHZU1kPnAcElVtYug0gkJcAuXSt"></div>

    				                </div>
    				                <div class="form-group col-sm-12" style="padding-left:0;">
    						            <button class="btn btn-primary pay-btn" id="pay-btn" type="submit">Submit</button>
    						        </div>
                                </div>
							</div>
						</div>
					
					</form>
	   				</div>
	   				<div class="text4">
	   				    <div class="container">
	   				        ' . $content . '
	   				    </div>
	   				</div>
				</div>

		</section>';
	} 
	else {
	    redirect_to(BASE_URL.'404');
	}
}

$jVars['module:bookpkg_detail'] = $resbpkg;
$jVars['module:max_date'] = $maxdate;
