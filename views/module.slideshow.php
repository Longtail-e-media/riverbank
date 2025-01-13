<?php
/* First Slideshow */
$reslide='';

$Records = Slideshow::getSlideshow_by(1);

if($Records) {
    $reslide.='<div class="widget-carousel owl-carousel owl-theme">';
        foreach($Records as $RecRow) {
            $file_path = SITE_ROOT.'images/slideshow/'.$RecRow->image;
            if(file_exists($file_path) and !empty($RecRow->image)) {
                $reslide.='<div class="slider-item" data-background="'.IMAGE_PATH.'slideshow/'.$RecRow->image.'">';
                    if(!empty($RecRow->content)) {
                        $reslide.='<div class="wrapper">
                            <div class="item-inner">
                                '.$RecRow->content.'
                            </div>
                        </div>';
                    }
                $reslide.='</div>';
            }
        }
    $reslide.='</div>';
}

$jVars['module:slideshow']= $reslide;
?>