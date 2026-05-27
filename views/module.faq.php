<?php

$faq_details = '';

$dataUrl = $breadcumbfaq = '';
$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/', $current_url);
$last = trim(end($data), '/');
$last = strtok($last, '?');
$dataUrl .= $last;


$breadcumbfaq .= '<ol class="breadcrumb text-center">
  <li><a href="' . BASE_URL . '">Home</a></li>
  <li class="active"><a href="' . BASE_URL . '' . $dataUrl . '">FAQ</a></li>
</ol>';

if (defined('FAQ_PAGE')) {

    $faqs = Faq::find_all();
    if (!empty($faqs)) {
        foreach ($faqs as $i => $faq) {
            $collapsed = ($i == 0) ? '' : '';
            $show = ($i == 0) ? 'in' : '';
            $faq_details .= '
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $faq->id . '">
                                ' . $faq->title . '
                            </a>
                        </h4>
                    </div>
                    <div id="collapse' . $faq->id . '" class="panel-collapse collapse ' . $show . '">
                        <div class="panel-body">
                            ' . $faq->content . '
                        </div>
                    </div>
                </div>
            ';
        }
    } else {
        $faq_details .= '<h3 class="text-center p-4">No FAQ Found</h3>';
    }
}

$jVars['module:faqbreadcumb'] = $breadcumbfaq;
$jVars['module:faq:details'] = $faq_details;


/**
 *      Homepage FAQ from schema
 */
$faq_details = '';

if (defined('HOME_PAGE')) {

    $homeSchema = Schema::find_by_id(2);
    $faqs = isset($homeSchema->faq_schema) ? trim((string)$homeSchema->faq_schema) : '';

    if (!empty($faqs)) {
        $faq_details .= '
            <div class="section-bg">
                <div class="container">
                    <div class="wrapper-inner">
                        <div class="widget-testimonials-carousel">
                            <!-- Testimonials Title -->
                            <div class="widget-title">
                                <h5>FAQs</h5>
                                <h3>Everything you need to know</h3>
                            </div>
                            <div class="wrapper-inner">
                                <div class="panel-group" id="accordion">
        ';

        $faqItems = json_decode($faqs, true);

        foreach ($faqItems as $i => $faqItem) {
            $q = isset($faqItem['q']) ? trim((string)$faqItem['q']) : '';
            $a = isset($faqItem['a']) ? trim((string)$faqItem['a']) : '';
            if ($q === '' || $a === '') continue;

            $collapsed = ($i == 0) ? '' : '';
            $show = ($i == 0) ? 'in' : '';

            $faq_details .= '
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $i . '">
                                ' . $q . '
                            </a>
                        </h4>
                    </div>
                    <div id="collapse' . $i . '" class="panel-collapse collapse ' . $show . '">
                        <div class="panel-body">
                            ' . $a . '
                        </div>
                    </div>
                </div>
            ';
        }

        $faq_details .= '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }
}

$jVars['module:faq:details-homepage'] = $faq_details;



/**
 *      Contact FAQ from schema
 */
$faq_contact = '';

if (defined('CONTACT_PAGE')) {

    $contactSchema = Schema::find_by_id(3);
    $faqs = isset($contactSchema->faq_schema) ? trim((string)$contactSchema->faq_schema) : '';

    if (!empty($faqs)) {
        $faq_details .= '
            <div class="section-bg">
                <div class="container">
                    <div class="wrapper-inner">
                        <div class="widget-testimonials-carousel">
                            <!-- Testimonials Title -->
                            <div class="widget-title">
                                <h5>FAQs</h5>
                                <h3>Everything you need to know</h3>
                            </div>
                            <div class="wrapper-inner">
                                <div class="panel-group" id="accordion">
        ';

        $faqItems = json_decode($faqs, true);

        foreach ($faqItems as $i => $faqItem) {
            $q = isset($faqItem['q']) ? trim((string)$faqItem['q']) : '';
            $a = isset($faqItem['a']) ? trim((string)$faqItem['a']) : '';
            if ($q === '' || $a === '') continue;

            $collapsed = ($i == 0) ? '' : 'collapsed';
            $show = ($i == 0) ? 'in' : '';

            $faq_details .= '
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle ' . $collapsed . '" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $i . '">
                                ' . $q . '
                            </a>
                        </h4>
                    </div>
                    <div id="collapse' . $i . '" class="panel-collapse collapse ' . $show . '">
                        <div class="panel-body">
                            ' . $a . '
                        </div>
                    </div>
                </div>
            ';
        }

        $faq_details .= '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }
}

$jVars['module:faq:contact'] = $faq_details;