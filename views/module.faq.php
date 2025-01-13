<?php

$faq_details = '';

if (defined('FAQ_PAGE')) {

    $faqs = Faq::find_all();
    // pr($faqs);

    if (!empty($faqs)) {
        $faq_details .= '';

        foreach ($faqs as $i => $faq) {
            $collapsed = ($i == 0) ? '' : '';
            $show = ($i == 0) ? 'in' : '';
            $faq_details .= '
            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $faq->id . '">
                                            '. $faq->title .'
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse' . $faq->id . '" class="panel-collapse collapse '.$show.'">
                                    <div class="panel-body">
                                        '. $faq->content .'
                                    </div>
                                </div>
                            </div>

            
            ';
        }

        $faq_details .= '';
    } else {
        $faq_details .= '<h3 class="text-center p-4">No FAQ Found</h3>';
    }
}

$jVars['module:faq:details'] = $faq_details;


$faq_details = '';

if (defined('HOME_PAGE')) {

    $faqs = Faq::find_few(3);

    if (!empty($faqs)) {
        $faq_details .= '';
        
        foreach ($faqs as $i => $faq) {
            $collapsed = ($i == 0) ? 'mad-panels-active' : '';
            $show = ($i == 0) ? 'show' : '';
            $faq_details .= '
            <dt class="mad-panels-title '. $collapsed .'">
                <button id="' . $faq->id . '-button" type="button" aria-expanded="false" aria-controls="' . $faq->id . '" aria-disabled="false">
                '. $faq->title .'
                </button>
            </dt>
            <dd id="' . $faq->id . '" class="mad-panels-definition">
                <p> '. $faq->content .'</p>
            </dd>

                
                ';
        }

        $faq_details .= '';
    } else {
        $faq_details .= '<h3 class="text-center p-4">No FAQ Found</h3>';
    }
}

$jVars['module:faq:details-homepage'] = $faq_details;

$jVars['module:faqlink'] = BASE_URL . 'faq';