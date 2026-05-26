<?php
// SEO Meta Tags And Meta Description

function className_metatags()
{
    $current_url = pathinfo($_SERVER["PHP_SELF"]);
    $fileName = $current_url['filename'];

    if ($fileName == 'inner'):
        $className = 'Article';
        return $className;
        exit;
    endif;

    if ($fileName == 'career-form'):
        $className = 'vacency';
        return $className;
        exit;
    endif;

    if ($fileName == 'blog-detail'):
        $className = 'CombinedNews';
        return $className;
        exit;
    endif;

    if ($fileName == 'subpkgdetail'):
        $className = 'Subpackage';
        return $className;
        exit;
    endif;

    if ($fileName == 'conference_detail'):
        $className = 'Subpackage';
        return $className;
        exit;
    endif;

    if ($fileName == 'service_list'):
        $className = 'Services';
        return $className;
        exit;
    endif;

    if ($fileName == 'dining_list'):
        $className = 'Dining';
        return $className;
        exit;
    endif;

    if ($fileName == 'book'):
        $className = 'Offers';
        return $className;
        exit;
    endif;

    if ($fileName != 'index'):
        $className = ucfirst(strtolower($fileName));
        return $className;
        exit;
    endif;

    return '';
}

function MetaTagsFor_SEO()
{

    $c_url      = pathinfo($_SERVER["PHP_SELF"]);
    $chk        = $c_url['filename'];
    $config     = Config::find_by_id(1);
    $sitetitle  = (!empty($config->meta_title) and $chk == 'index') ? $config->meta_title : $config->sitetitle;
    $keywords   = $config->site_keywords;
    $description = $config->site_description;

    $addtitle   = '';
    $class      = className_metatags();

    // Transaction start
    if (isset($_REQUEST['slug']) and !empty($_REQUEST['slug'])) {
        if ($class == 'Global') {
            $nrec = Mlink::find_by_slug(addslashes($_REQUEST['slug']));
            if (!empty($nrec)) {
                $cls = new $nrec->mod_class;
                $rec = $cls->find_by_slug(addslashes($_REQUEST['slug']));
                if (!empty($rec)) {
                    $addtitle = !empty($rec->meta_title) ? $rec->meta_title : $rec->title;
                    if (!empty($rec->meta_keywords)) {
                        $keywords = $rec->meta_keywords;
                        $description = $rec->meta_description;
                    }
                }
            }
        } else {
            $cls = new $class;
            $rec = $cls->find_by_slug(addslashes($_REQUEST['slug']));
            if (!empty($rec)) {
                $addtitle = !empty($rec->meta_title) ? $rec->meta_title : $rec->title;
                if (!empty($rec->meta_keywords)) {
                    $keywords = $rec->meta_keywords;
                    $description = $rec->meta_description;
                }
            }
        }
    }

    if (isset($_REQUEST['id']) and !empty($_REQUEST['id'])) {
        $cls = new $class;
        $rec = $cls->find_by_id($_REQUEST['id']);
        if ($rec) {
            $addtitle = $rec->title;
        }
    }

    $altclass   = !empty($class) ? $class : '';
    $addtitle   = !empty($addtitle) ? $addtitle : $altclass;
    $addsep     = !empty($addtitle) ? ' - ' : '';

    $sluglink       = $rec->slug ?? '';
    $site_keyword   = (!empty($keywords)) ? $keywords : $config->site_keywords;
    $site_description = (!empty($description)) ? $description : $config->site_description;
    $site_title     = (!empty($rec->meta_title)) ? $rec->meta_title . ' | ' . $config->sitename : $config->meta_title;

    // === Dynamic JSON-LD builder for PHP 7.4 ===

    // Helper: PHP 7.4 safe str_contains
    function str_contains_74($haystack, $needle)
    {
        return $needle !== '' && strpos($haystack, $needle) !== false;
    }

    // Helper: Detect schema type for subpackages
    function detectSubpackageSchema($rec)
    {
        $title = strtolower($rec->title ?? '');
        $desc = strtolower($rec->content ?? $rec->description ?? '');

        if (str_contains_74($title, 'board meeting room')) return 'EventVenue';
        if (str_contains_74($title, 'room')) return 'Room';
        if (str_contains_74($title, 'restaurant') || str_contains_74($title, 'dining')) return 'Restaurant';
        if (
            str_contains_74($title, 'hall') ||
            str_contains_74($title, 'conference') ||
            str_contains_74($title, 'banquet')
        ) return 'EventVenue';

        return 'Service'; // fallback
    }

    // === Start Schema Graph ===
    $schemaGraph = [];

    // 1) WebSite
    $schemaGraph[] = [
        "@type" => "WebSite",
        "@id"   => rtrim(BASE_URL, '/') . "#website",
        "url"   => BASE_URL,
        "name"  => $config->sitename
    ];

    // 2) Hotel / Organization
    $sociallinks = SocialNetworking::getSocialNetwork();
    $sameAs = [];
    foreach ($sociallinks as $s) {
        if (!empty($s->linksrc)) $sameAs[] = $s->linksrc;
    }

    $hotelEntity = [
        "@type" => "Resort",
        "@id"   => rtrim(BASE_URL, '/') . "#resort",
        "name"  => $config->sitename,
        "url"   => BASE_URL,
        "logo"  => IMAGE_PATH . 'preference/' . $config->logo_upload
    ];

    if (!empty($sameAs)) {
        $hotelEntity['sameAs'] = $sameAs;
    }

    $schemaGraph[] = $hotelEntity;

    // 3) Determine real page class (handle Global via Mlink)
    $class = className_metatags();
    $slug = isset($_REQUEST['slug']) ? trim($_REQUEST['slug']) : '';
    $mainClass = $class;
    if (strtolower($class) === 'global' && !empty($slug)) {
        $mdata = Mlink::find_by_slug(addslashes($slug));
        if (!empty($mdata) && !empty($mdata->mod_class)) {
            $mainClass = $mdata->mod_class;
            if (empty($rec) && class_exists($mainClass)) {
                $tmp = new $mainClass;
                if (method_exists($tmp, 'find_by_slug')) {
                    $rec = $tmp->find_by_slug(addslashes($slug));
                }
            }
        }
    }

    // 4) Map class to schema type and page kind
    $mc         = strtolower($mainClass);
    $schemaType = 'WebPage';
    $pageKind   = 'general';

    // Map table
    $schemaMap = [
        'blog'          => ['type' => 'BlogPosting', 'kind' => 'detail'],
        'combinednews'  => ['type' => 'BlogPosting', 'kind' => 'detail'],
        'article'       => ['type' => 'Article', 'kind' => 'detail'],
        'news'          => ['type' => 'NewsArticle', 'kind' => 'detail'],

        'room'      => ['type' => 'Room', 'kind' => 'detail'],
        'rooms'     => ['type' => 'CollectionPage', 'kind' => 'listing'],

        'dining'    => ['type' => 'Restaurant', 'kind' => 'detail'],
        'hall'      => ['type' => 'EventVenue', 'kind' => 'detail'],
        'subpackage' => ['type' => 'AUTO', 'kind' => 'detail'],

        'services'  => ['type' => 'Service', 'kind' => 'detail'],
        'facilities' => ['type' => 'CollectionPage', 'kind' => 'listing'],
        'offers'    => ['type' => 'CollectionPage', 'kind' => 'listing'],
        'gallery'   => ['type' => 'CollectionPage', 'kind' => 'listing'],

        'faq'       => ['type' => 'FAQPage', 'kind' => 'detail'],
        'contact'   => ['type' => 'ContactPage', 'kind' => 'general'],
        'about'     => ['type' => 'AboutPage', 'kind' => 'general'],
        'virtual-tour' => ['type' => 'WebPage', 'kind' => 'general']
    ];

    if (isset($schemaMap[$mc])) {
        $schemaType = $schemaMap[$mc]['type'];
        $pageKind   = $schemaMap[$mc]['kind'];

        if ($schemaType === 'AUTO' && !empty($rec)) {
            $schemaType = detectSubpackageSchema($rec);
        }
    }

    // 5) Determine image URL
    $imageUrl = IMAGE_PATH . 'preference/' . ($config->fb_upload ?: $config->logo_upload);

    if (!empty($rec->fb_upload)) {

        //Article
        if (file_exists(SITE_ROOT . 'images/articles/social/' . $rec->fb_upload)) {
            $imageUrl = IMAGE_PATH . 'article/social/' . $rec->fb_upload;
        } //Subpackage
        elseif (file_exists(SITE_ROOT . 'images/subpackage/social/' . $rec->fb_upload)) {
            $imageUrl = IMAGE_PATH . 'subpackage/social/' . $rec->fb_upload;
        } //Offer
        elseif (file_exists(SITE_ROOT . 'images/offers/social/' . $rec->fb_upload)) {
            $imageUrl = IMAGE_PATH . 'offers/social/' . $rec->fb_upload;
        }
    } elseif (!empty($rec->banner_image)) {
        $imgs = @unserialize($rec->banner_image);
        if ($imgs && !empty($imgs[0]) && file_exists(SITE_ROOT . 'images/package/banner/' . $imgs[0])) {
            $imageUrl = IMAGE_PATH . 'package/banner/' . $imgs[0];
        }
    } elseif (!empty($rec->image)) {
        $imgs = @unserialize($rec->image);
        if ($imgs && !empty($imgs[0])) {
            if (file_exists(SITE_ROOT . 'images/articles/' . $imgs[0])) {
                $imageUrl = IMAGE_PATH . 'articles/' . $imgs[0];
            } elseif (file_exists(SITE_ROOT . 'images/subpackage/' . $imgs[0])) {
                $imageUrl = IMAGE_PATH . 'subpackage/' . $imgs[0];
            }
        }
    } elseif (!empty($rec->list_image) && file_exists(SITE_ROOT . 'images/offers/listimage/' . $rec->list_image)) {
        $imageUrl = IMAGE_PATH . 'offers/listimage/' . $rec->list_image;
    }

    // 6) Page-level schema
    if ($pageKind === 'detail' && !empty($rec)) {
        $pageObj = [
            "@type"     => $schemaType,
            "@id"       => rtrim(BASE_URL, '/') . "#primary-entity",
            "name"      => $rec->title ?? $addtitle ?? $config->sitename,
            "description" => $rec->meta_description ?? $description,
            "image"     => [$imageUrl],
            "url"       => rtrim(BASE_URL, '/') . $_SERVER['REQUEST_URI'],
            "containedInPlace" => [
                "@id"   => rtrim(BASE_URL, '/') . "#resort"
            ]
        ];

        if (in_array($schemaType, ['Article', 'BlogPosting', 'NewsArticle'])) {
            unset($pageObj['containedInPlace']);
            $pageObj['headline'] = $rec->title ?? null;
            if (!empty($rec->added_date)) $pageObj['datePublished'] = date('c', strtotime($rec->added_date));
            if (!empty($rec->updated_date)) $pageObj['dateModified'] = date('c', strtotime($rec->updated_date));
        }

        if (in_array($schemaType, ['Service'])) {
            unset($pageObj['containedInPlace']);
        }

        $schemaGraph[] = array_filter($pageObj, fn($v) => $v !== null && $v !== '');
    } elseif ($pageKind === 'listing') {
        $schemaGraph[] = [
            "@type"     => "CollectionPage",
            "@id"       => rtrim(BASE_URL, '/') . "#webpage",
            "name"      => $addtitle ?: $config->sitename,
            "description" => $description,
            "url"       => rtrim(BASE_URL, '/') . $_SERVER['REQUEST_URI']
        ];
    } else {
        $schemaGraph[] = [
            "@type"     => "WebPage",
            "@id"       => rtrim(BASE_URL, '/') . "#webpage",
            "name"      => $addtitle ?: $config->sitename,
            "description" => $description,
            "url"       => rtrim(BASE_URL, '/') . $_SERVER['REQUEST_URI']
        ];
    }

    // 7) Output final JSON-LD
    $schema = '<script type="application/ld+json">' . "\n" .
        json_encode([
            "@context" => "https://schema.org",
            "@graph" => $schemaGraph
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) .
        "\n</script>";
    // End Schema Graph

    // === schema_code injection ===
    // Static pages have no slug/record; their schema lives in Article rows with article_type=2.
    // Dynamic pages (Article, Package, Subpackage) carry schema_code on their own record.
    // In both cases we output the raw block only when the field is non-empty.
    $staticSchemaMap = [
        'index'     => 2, // /  or  /home
        'offers'    => 6, // /offer-list
        'gallery'   => 4, // /gallery-list
        'contact'   => 3, // /contact-us
        'blog'      => 7, // /blog
        'facilities' => 5, // /facilities-list
    ];

    $schemaCode = '';

    if (isset($staticSchemaMap[$chk])) {
        // Static route — pull the pre-authored schema from its dedicated Article row.
        $staticArticle = Schema::find_by_id((int)$staticSchemaMap[$chk]);
        if (!empty($staticArticle) && !empty(trim((string)($staticArticle->schema_code ?? '')))) {
            $schemaCode = trim((string)$staticArticle->schema_code);
        }
    } elseif (!empty($rec) && !empty(trim((string)($rec->schema_code ?? '')))) {
        // Dynamic route — Article / Package / Subpackage record already resolved above.
        $schemaCode = trim((string)$rec->schema_code);
    }

    if (!empty($schemaCode)) {
        // Security: NEVER echo schema_code as raw HTML.
        //
        // Even though this field is admin-only, a compromised or rogue account
        // could store arbitrary <script> tags. The safe pipeline is:
        //
        //   1. Strip any <script> wrapper the admin may have typed — we only
        //      want the JSON body, and we will supply our own controlled tag.
        //   2. Validate the result with json_decode(). Invalid JSON → silent skip
        //      (nothing broken, nothing injected).
        //   3. Re-encode through json_encode() with JSON_HEX_TAG, which converts
        //      '<' → < and '>' → > inside every JSON string value.
        //      This neutralises the "</script>" early-close injection vector.
        //   4. Emit inside <script type="application/ld+json"> — browsers never
        //      execute this MIME type as JavaScript regardless.

        // Step 1 — strip <script ...> ... </script> wrapper if present
        $jsonBody = $schemaCode;
        if (stripos($jsonBody, '<script') !== false) {
            $jsonBody = preg_replace('#<script[^>]*>#i', '', $jsonBody);
            $jsonBody = preg_replace('#</script>#i', '', $jsonBody);
            $jsonBody = trim($jsonBody);
        }

        // Step 2 — validate JSON
        $decoded = json_decode($jsonBody);
        if (json_last_error() === JSON_ERROR_NONE && $decoded !== null) {

            // Step 3 & 4 — re-encode safely and emit
            $schema = "\n" . '<script type="application/ld+json">' . "\n"
                . json_encode(
                    $decoded,
                    JSON_UNESCAPED_SLASHES
                    | JSON_PRETTY_PRINT
                    | JSON_UNESCAPED_UNICODE
                    | JSON_HEX_TAG        // <  →  < , >  →  >
                )
                . "\n</script>";
        }
        // Invalid JSON → no output, no error exposed to the browser
    }
    // === end schema_code injection ===

    // === FAQ schema injection ===
    // Reads faq_schema (structured Q&A stored as JSON array by the FAQ builder).
    // Reuses $staticArticle if the schema_code block already fetched it — no extra query.
    // Emits a FAQPage JSON-LD block only when there is at least one valid Q&A pair.
    $faqRaw = '';
    if (isset($staticSchemaMap[$chk])) {
        // Static route — $staticArticle may already be set above; fetch only if needed.
        if (!isset($staticArticle)) {
            $staticArticle = Article::find_by_id((int) $staticSchemaMap[$chk]);
        }
        $faqRaw = isset($staticArticle->faq_schema) ? trim((string) $staticArticle->faq_schema) : '';
    } elseif (!empty($rec)) {
        $faqRaw = isset($rec->faq_schema) ? trim((string) $rec->faq_schema) : '';
    }

    if ($faqRaw !== '') {
        $faqItems = json_decode($faqRaw, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($faqItems) && count($faqItems) > 0) {
            $mainEntity = [];
            foreach ($faqItems as $faqItem) {
                // Guard: skip malformed entries (missing/empty q or a)
                $q = isset($faqItem['q']) ? trim((string) $faqItem['q']) : '';
                $a = isset($faqItem['a']) ? trim((string) $faqItem['a']) : '';
                if ($q === '' || $a === '') continue;

                $mainEntity[] = [
                    '@type'          => 'Question',
                    'name'           => $q,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $a],
                ];
            }

            if (!empty($mainEntity)) {
                $schema .= "\n" . '<script type="application/ld+json">' . "\n"
                    . json_encode(
                        [
                            '@context'   => 'https://schema.org',
                            '@type'      => 'FAQPage',
                            'mainEntity' => $mainEntity,
                        ],
                        JSON_UNESCAPED_SLASHES
                        | JSON_PRETTY_PRINT
                        | JSON_UNESCAPED_UNICODE
                        | JSON_HEX_TAG
                    )
                    . "\n</script>";
            }
        }
    }
    // === end FAQ schema injection ===


    $seoSources = '<title>' . $addtitle . $addsep . $sitetitle . '</title>' . "\n";
    $seoSources .= '<meta charset="utf-8">' . "\n";
    $seoSources .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . "\n";
    $seoSources .= '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    $seoSources .= '<meta name="robots" content="index,follow">' . "\n";
    $seoSources .= '<meta name="Googlebot" content="index, follow"/>' . "\n";
    $seoSources .= '<meta name="distribution" content="Global">' . "\n";
    $seoSources .= '<meta name="revisit-after" content="2 Days" />' . "\n";
    $seoSources .= '<meta name="classification" content="Resort, Resorts in Nepal" />' . "\n";
    $seoSources .= '<meta name="category" content="Resort, Resorts in Nepal" />' . "\n";
    $seoSources .= '<meta name="language" content="en-us" />' . "\n";
    $seoSources .= '<meta name="keywords" content="' . $keywords . '">' . "\n";
    $seoSources .= '<meta name="description" content="' . $description . '">' . "\n";
    $seoSources .= '<meta name="author" content="Longtail-e-media">' . "\n\n";

    //Facebook and twitter sharing
    $seoSources .= '<meta property="og:title" content="' . $sitetitle . '">' . "\n";
    $seoSources .= '<meta property="og:description" content="' . $description . '">' . "\n";


    /**
     * Generate Open Graph and Twitter image meta tags
     *
     * @param string $imagePath Full server path to image file
     * @param string $defaultImage Default image URL to use as fallback
     * @param string &$seoSources Reference to string where meta tags are appended
     */
    function getImageMetaTag($imagePath, $defaultImage, &$seoSources)
    {
        $imageUrl = null;

        // Check if image path exists and convert to URL
        if (!empty($imagePath) && file_exists($imagePath)) {
            $imageUrl = str_replace(SITE_ROOT . 'images/', IMAGE_PATH, $imagePath);
        }

        // Use actual image or fallback to default
        $finalImage = $imageUrl ?? $defaultImage;

        // Generate meta tags with XSS protection
        $seoSources .= '<meta property="og:image" content="' . htmlspecialchars($finalImage, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $seoSources .= '<meta property="twitter:image" content="' . htmlspecialchars($finalImage, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }

    /**
     * Get image path for a given content type
     *
     * @param object $record The database record
     * @param string $folder The image folder name
     * @return string|null Full server path to image or null
     */
    function getContentImagePath($record, $folder)
    {
        if (empty($record) || empty($record->fb_upload)) {
            return null;
        }

        return SITE_ROOT . 'images/' . $folder . '/social/' . $record->fb_upload;
    }

    if (!empty($_REQUEST['slug'])) {
        $slug = $_REQUEST['slug'];
        $defaultImage = IMAGE_PATH . 'preference/' . $config->fb_upload;
        $imagePath = null;

        switch ($class) {
            case 'Global':
                $nrec = Mlink::find_by_slug($slug);

                if (!empty($nrec)) {
                    $cls = new $nrec->mod_class;
                    $classname = $nrec->mod_class;
                    $rec = $cls->find_by_slug($slug);

                    if (!empty($rec)) {
                        // Map class names to their image folders
                        $folderMap = [
                            'Article' => 'articles',
                            'Package' => 'package',
                            'Subpackage' => 'subpackage'
                        ];

                        if (isset($folderMap[$classname])) {
                            $imagePath = getContentImagePath($rec, $folderMap[$classname]);
                        }
                    }
                }
                break;

            case 'CombinedNews':
                $CombinedNews = CombinedNews::find_by_slug($slug);
                $imagePath = getContentImagePath($CombinedNews, 'combinednews');
                break;

            case 'Services':
                $service = Services::find_by_slug($slug);
                $imagePath = getContentImagePath($service, 'services');
                break;

            case 'Offers':
                $offer = Offers::find_by_slug($slug);
                $imagePath = getContentImagePath($offer, 'offers');
                break;
        }
        // Generate meta tags (works for all cases including default)
        getImageMetaTag($imagePath, $defaultImage, $seoSources);
    } else {
        // No slug provided - use default image
        getImageMetaTag(null, IMAGE_PATH . 'preference/' . $config->fb_upload, $seoSources);
    }

    $tot = strlen(SITE_FOLDER) + 1;
    $data = substr($_SERVER['REQUEST_URI'], $tot);
    $seoSources .= '<meta property="og:url" content="' . BASE_URL . $data . '">' . "\n";
    $seoSources .= '<meta property="og:type" content="website">' . "\n";
    $seoSources .= '<meta property="twitter:card" content="summary_large_image">' . "\n\n";
    $seoSources .= '<link rel="canonical" href="' . curPageURL() . '" />' . "\n";

    $seoSources .= '<base url="' . BASE_URL . '"/>' . "\n";
    $seoSources .= $config->google_anlytics . "\n";
    $seoSources .= $schema . "\n";
    $seoSources .= $config->headers . "\n";

    return $seoSources;
}

?>
