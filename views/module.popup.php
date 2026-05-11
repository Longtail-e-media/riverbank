<?php

/*********** From new */

$restst = '';
$active = '';


$popRec = Popup::get_allpopup(1);

// pr($popRec);
if (!empty($popRec)) {
    //modal img
    $count = 1;
    $active .= '';
    $restst .= ' 
     <div class="col-sm-10 center-block center-text">
        <div class="modal fade" id="modal-popup-image">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header" style="border: 0; padding: 0;">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                    </div>
                    <div class="modal-body">
					<!--CAROUSEL CODE GOES HERE-->
                        <div id="myCarousel" class="carousel slide">
                            <div class="carousel-inner">
                            ';
    foreach ($popRec as $popr) {
        if (($popr->image) != "a:0:{}") {
            $q = implode(unserialize($popr->image));
            $file_path = SITE_ROOT . 'images/popup/' . $q;
            if (file_exists($file_path)) {
                $imglink = IMAGE_PATH . 'popup/' . $q;
            } else {
                $imglink = BASE_URL . 'template/cms/images/welcome.jpg';
            }
            if($popr->position==1){
                $oriclass='-vertical';
            }
            elseif($popr->position==2){
              $oriclass='-horizontal';  
            }else{
                $oriclass='-square';
            }
            $active = ($count == 1) ? 'active' : '';
            $linkhref = ($popr->linktype == 1) ? $popr->linksrc : BASE_URL . $popr->linksrc;
            $target = ($popr->linktype == 1) ? 'target="_blank" rel="noopener noreferrer"' : '';
            $restst .= '  
            <div class="item ' . $active . '">
                    <a href="'.$linkhref.'" ><img width="468" height="585" fetchpriority="high" src="' . $imglink . '" alt="' . $popr->title . '"></a>
                </div>
                ';
                
                // pr($imglink);

            $count++;
        }
    }
    $restst .= ' 
    </div>
    ';
    if(sizeof($popRec) > 1) {
        $restst .= '
       <a class="left carousel-control" href="#myCarousel" data-slide="prev" style="background: none;">
    <span class="glyphicon glyphicon-chevron-left"></span>
</a>
<a class="right carousel-control" href="#myCarousel" data-slide="next" style="background: none;">
    <span class="glyphicon glyphicon-chevron-right"></span>
</a>  
        ';
    }
    $restst .='
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

    //side img
    // $count = 1;
    // $active = '';
    // $restst .= ' 
    //         <div class="deals d-none">
    //         <a href="javascript:void(0);" class="close closepop">*</a>
    //             <div id="carouselExampleControlsss" class="carousel slide" data-ride="carousel">
    //               <div class="carousel-inner">';
    // foreach ($popRec as $popr) {
    //     if (($popr->image) != "a:0:{}") {
    //         $q = implode(unserialize($popr->image));
    //         $file_path = SITE_ROOT . 'images/popup/' . $q;
    //         if (file_exists($file_path)) {
    //             $imglink = IMAGE_PATH . 'popup/' . $q;
    //         } else {
    //             $imglink = BASE_URL . 'template/cms/images/welcome.jpg';
    //         }
    //         $active = ($count == 1) ? 'active' : '';
    //         $restst .= '  
    //             <div class="item ' . $active . '">
                    
    //                 <div class="cover_img">
    //                     <a href="' . BASE_URL . '' . $popr->linksrc . '">
    //                         <img src="' . $imglink . '" class="img-responsive">
    //                     </a>
    //                  </div>
    //             </div>
    //             ';
    //         $count++;
    //     }
    // }
    // $restst .= ' </div>
    //             <a class="left carousel-control" href="#carouselExampleControlsss" role="button" data-slide="prev"> 
    //                 <span class="glyphicon glyphicon-chevron-left"></span>
    //             </a> 
    //             <a class="right carousel-control" href="#carouselExampleControlsss" role="button" data-slide="next"> 
    //                 <span class="glyphicon glyphicon-chevron-right"></span>
    //             </a>
    //         </div>
    //     </div>';

    // //side img button
    // $restst .= '
    //     <!--<ul class="side-icon-block">
    //         <li class="">
    //             <a id="offon" href="javaScript:void(0);">
    //                 <img class="img-fluid" alt="Offers" title="Offers" width="50" src="' . IMAGE_PATH . 'offerside.png">
    //             </a> 
    //         </li> 
    //     </ul>-->
    // ';
}
/*
* Comment Header Title

*/
$tstRec = Popup::get_allpopup(0);

if (!empty($tstRec)) {
    $count = 1;
    $active = '';
    $restst .= '
    <div id="popup-popup-video" class="popup">
        <div class="popup-content">
            <div class="popup-swiper-container swiper-container">
                <div class="swiper-wrapper">


                            ';
    $auto = (count($tstRec) == 1) ? 'autoplay=1' : '';
    foreach ($tstRec as $tstRow) {
        //if(!empty($tstRow->source){
        $active = ($count == 1) ? 'active' : '';
        $parts = explode('.',$tstRow->source);
        if($parts[1] == 'facebook'){
            $restst .= ' 
                
                <div class="swiper-slide">

                    <iframe src="https://www.facebook.com/plugins/video.php?href='.urlencode($tstRow->source).'&width=365&show_text=false&appId=668102922175064&height=650" 
                        scrolling="no" frameborder="0" allowfullscreen="true"
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                        allowFullScreen="true"></iframe>
         </div>
                ';
        } else {
            $restst .= ' 
            <div class="swiper-slide">

                    <iframe width="100%" id="yt-video" height="600px" src="https://www.youtube.com/embed/' . get_youtube_code($tstRow->source) . '?' . $auto . '" frameborder="0" allow="accelerometer; autoplay ; encrypted-media; gyroscope; picture-in-picture" allowfullscreen ></iframe>  
          </div>
                ';
        }
        $count++;
    }
    $restst .='</div>
    ';
    if(sizeof($tstRec) > 1) {
        $restst .= '
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>

        ';
    }
    $restst .= ' 
    </div>
    </div>
        <button onclick="closeVideoPopup()" class="close-button">&times;</button>
    </div>


';
}

// $restst = '';
// $popRec = Popup::get_allpopup(1);
//     if(!empty($tstRec)){
//     //side img button
//     $restst .= '<div class="splashbg hidden">';

//     foreach ($tstRec as $popr) {

//     if (($popr->image) != "a:0:{}") {
//         $q = implode(unserialize($popr->image));
//         $file_path = SITE_ROOT . 'images/popup/' . $q;
//         if (file_exists($file_path)) {
//             $imglink = IMAGE_PATH . 'popup/' . $q;
//         } else {
//             $imglink = BASE_URL . 'template/cms/images/welcome.jpg';
//         }
//     }
// }
//     $restst .= '
        
//             <div class="cstm_modal">
//                 <a href="#" class="close closepop" style="color:#fff;">X</a>
//                 <img src="' . $imglink . '">
//             </div>
//         </div>
//     ';
// //     <!--<video width="100%" height="565" autoplay controls>
// //     <source src='' type="video/mp4" >
// //     your browser does not support the video tag.
// // </video>-->
// }


// // $jVars['module:popup'] = $restst;



// pr($restst);
// pr($restst,1);
$jVars['module:popup'] = $restst;
?>
