<?php
/**
 * IMPROVED Responsive Image Helper
 * With dynamic sizes attribute support
 */

/**
 * Generate responsive image with srcset
 *
 * @param string $imagePath Path relative to images folder
 * @param string $alt Alt text
 * @param array $widths Breakpoint widths
 * @param array $attributes Additional HTML attributes
 * @param string|null $sizes Custom sizes attribute (or use preset)
 * @return string Complete img tag
 */
function responsiveImage($imagePath, $alt = '', $widths = [480, 768, 1200, 1920], $attributes = [], $sizes = 'default')
{
    $baseUrl = BASE_URL . 'image_resize.php?path=' . urlencode($imagePath);

    // Build srcset
    $srcset = [];
    foreach ($widths as $width) {
        $srcset[] = $baseUrl . '&w=' . $width . ' ' . $width . 'w';
    }
    $srcsetStr = implode(', ', $srcset);

    // Default src (largest size)
    $src = $baseUrl . '&w=' . max($widths);

    // Get sizes attribute
    $sizesAttr = getSizesAttribute($sizes);

    // Default attributes
    $defaultAttrs = [
        'loading' => 'lazy',
        'class' => 'img-fluid'
    ];

    // Merge with custom attributes
    $attributes = array_merge($defaultAttrs, $attributes);

    // Build attributes string
    $attrsStr = '';
    foreach ($attributes as $key => $value) {
        $attrsStr .= ' ' . $key . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
    }

    // Generate img tag
    return sprintf(
        '<img src="%s" srcset="%s" sizes="%s" alt="%s"%s>',
        htmlspecialchars($src, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($srcsetStr, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($sizesAttr, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'),
        $attrsStr
    );
}

/**
 * Get sizes attribute based on preset or custom value
 *
 * @param string $preset Preset name or custom sizes string
 * @return string Sizes attribute value
 */
function getSizesAttribute($preset)
{
    // Predefined presets for common layouts
    $presets = [
        // Full width images (hero, banners, featured)
        'full' => '100vw',
        'fullwidth' => '100vw',
        'hero' => '100vw',
        'banner' => '100vw',

        // Default - full width on mobile, narrower on desktop
        'default' => '(max-width: 768px) 100vw, (max-width: 1200px) 80vw, 1200px',

        // Content width (blog posts, articles)
        'content' => '(max-width: 768px) 100vw, 750px',
        'article' => '(max-width: 768px) 100vw, 750px',

        // Half width (2-column layouts)
        'half' => '(max-width: 768px) 100vw, 50vw',
        'two-column' => '(max-width: 768px) 100vw, 50vw',

        // Third width (3-column layouts, sidebar)
        'third' => '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw',
        'sidebar' => '(max-width: 768px) 100vw, 33vw',

        // Quarter width (4-column grids)
        'quarter' => '(max-width: 480px) 100vw, (max-width: 768px) 50vw, (max-width: 1024px) 33vw, 25vw',

        // Thumbnails and small images
        'thumbnail' => '(max-width: 480px) 150px, 200px',
        'small' => '(max-width: 768px) 200px, 300px',

        // Large featured images
        'large' => '(max-width: 768px) 100vw, (max-width: 1400px) 90vw, 1400px',

        // Gallery images
        'gallery' => '(max-width: 480px) 100vw, (max-width: 768px) 50vw, (max-width: 1024px) 33vw, 25vw',
    ];

    // If preset exists, use it
    if (isset($presets[$preset])) {
        return $presets[$preset];
    }

    // If it contains parentheses or vw/px, treat as custom sizes string
    if (strpos($preset, '(') !== false || strpos($preset, 'vw') !== false || strpos($preset, 'px') !== false) {
        return $preset;
    }

    // Default fallback
    return $presets['default'];
}

/**
 * Get resized image URL
 */
function resizeUrl($imagePath, $width = null, $height = null, $quality = 85)
{
    $params = ['path' => $imagePath];
    if ($width) $params['w'] = $width;
    if ($height) $params['h'] = $height;
    if ($quality != 85) $params['q'] = $quality;

    return BASE_URL . 'image_resize.php?' . http_build_query($params);
}

/**
 * Generate picture element
 */
function pictureElement($imagePath, $alt = '', $widths = [480, 768, 1200, 1920], $sizes = 'default')
{
    $baseUrl = BASE_URL . 'image_resize.php?path=' . urlencode($imagePath);

    $jpgSrcset = [];
    foreach ($widths as $width) {
        $jpgSrcset[] = $baseUrl . '&w=' . $width . ' ' . $width . 'w';
    }

    $sizesAttr = getSizesAttribute($sizes);

    $html = '<picture>';
    $html .= '<source srcset="' . htmlspecialchars(implode(', ', $jpgSrcset), ENT_QUOTES, 'UTF-8') . '" ';
    $html .= 'sizes="' . htmlspecialchars($sizesAttr, ENT_QUOTES, 'UTF-8') . '">';
    $html .= '<img src="' . htmlspecialchars($baseUrl . '&w=' . max($widths), ENT_QUOTES, 'UTF-8') . '" ';
    $html .= 'alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '" loading="lazy" class="img-fluid">';
    $html .= '</picture>';

    return $html;
}