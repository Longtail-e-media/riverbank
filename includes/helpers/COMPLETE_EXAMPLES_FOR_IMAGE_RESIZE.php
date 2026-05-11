<?php
/**
 * COMPLETE EXAMPLES WITH ALL PARAMETERS
 * Copy-paste ready examples for every use case
 */

// Include helper at top of your file
require_once 'includes/responsive_helper_simple.php';

// ═══════════════════════════════════════════════════════════
// BASIC USAGE
// ═══════════════════════════════════════════════════════════

// Example 1: Simple responsive image (default breakpoints: 480, 768, 1200, 1920)
echo responsiveImage('articles/photo.jpg', 'Article Photo');
// Output: <img src="/image_resize.php?path=articles/photo.jpg&w=1920"
//              srcset="/image_resize.php?path=articles/photo.jpg&w=480 480w, ..."
//              sizes="(max-width: 768px) 100vw, ..." alt="Article Photo" loading="lazy">

// Example 2: With custom breakpoints
echo responsiveImage('blog/hero.jpg', 'Hero Image', [320, 640, 1024, 1920]);

// Example 3: With custom attributes
echo responsiveImage('services/feature.jpg', 'Feature', [480, 768, 1200], [
    'class' => 'feature-img rounded',
    'id' => 'main-image',
    'fetchpriority' => 'high',
    'decoding' => 'async',
    'width' => '1200',
    'height' => '600'
]);

// ═══════════════════════════════════════════════════════════
// YOUR CURRENT CODE REPLACEMENT
// ═══════════════════════════════════════════════════════════

// BEFORE:
echo '<div class="about-img">
    <div class="img">
        <img src="'.IMAGE_PATH.'articles/'.$image[0].'" class="img-fluid" alt="article_image" width="1200" height="600" fetchpriority="high" decoding="async">
    </div>
</div>';

// AFTER:
echo '<div class="about-img">
    <div class="img">';
        echo responsiveImage('articles/'.$image[0], 'article_image', [480, 768, 1200, 1920], [
            'width' => '1200',
            'height' => '600',
            'fetchpriority' => 'high',
            'decoding' => 'async',
            'class' => 'img-fluid'
        ]);
echo '  </div>
</div>';

// ═══════════════════════════════════════════════════════════
// SINGLE RESIZE (resizeUrl function)
// ═══════════════════════════════════════════════════════════

// Example 4: Resize to specific width (maintains aspect ratio)
$url = resizeUrl('articles/photo.jpg', 800);
// Output: /image_resize.php?path=articles/photo.jpg&w=800

// Example 5: Resize to specific width and height
$url = resizeUrl('articles/photo.jpg', 300, 200);
// Output: /image_resize.php?path=articles/photo.jpg&w=300&h=200

// Example 6: Resize with custom quality
$url = resizeUrl('articles/photo.jpg', 1200, null, 95);
// Output: /image_resize.php?path=articles/photo.jpg&w=1200&q=95

// Example 7: Just height (maintains aspect ratio)
$url = resizeUrl('articles/photo.jpg', null, 400);
// Output: /image_resize.php?path=articles/photo.jpg&h=400

// ═══════════════════════════════════════════════════════════
// MANUAL SRCSET CREATION
// ═══════════════════════════════════════════════════════════

// Example 8: Build your own srcset manually
$imagePath = 'blog/featured.jpg';
echo '<img 
    src="'.resizeUrl($imagePath, 1200).'"
    srcset="
        '.resizeUrl($imagePath, 320).' 320w,
        '.resizeUrl($imagePath, 480).' 480w,
        '.resizeUrl($imagePath, 768).' 768w,
        '.resizeUrl($imagePath, 1024).' 1024w,
        '.resizeUrl($imagePath, 1200).' 1200w,
        '.resizeUrl($imagePath, 1920).' 1920w
    "
    sizes="(max-width: 768px) 100vw, (max-width: 1200px) 80vw, 1200px"
    alt="Featured Image"
    loading="lazy"
    class="img-fluid">';

// ═══════════════════════════════════════════════════════════
// PICTURE ELEMENT
// ═══════════════════════════════════════════════════════════

// Example 9: Picture element for art direction
echo '<picture>';
echo '  <source media="(max-width: 768px)" srcset="'.resizeUrl('mobile-crop.jpg', 480).'">';
echo '  <source media="(min-width: 769px)" srcset="'.resizeUrl('desktop-crop.jpg', 1200).'">';
echo '  <img src="'.resizeUrl('desktop-crop.jpg', 1200).'" alt="Responsive" loading="lazy">';
echo '</picture>';

// Example 10: Using pictureElement helper
echo pictureElement('articles/hero.jpg', 'Hero Image', [480, 768, 1200, 1920]);

// ═══════════════════════════════════════════════════════════
// DIFFERENT CONTEXTS
// ═══════════════════════════════════════════════════════════

// Example 11: Blog listing (smaller images)
foreach ($blogs as $blog) {
    echo '<div class="blog-card">';
    echo responsiveImage('blog/'.$blog->image, $blog->title, [320, 480, 640]);
    echo '</div>';
}

// Example 12: Thumbnail grid (very small)
echo responsiveImage('gallery/photo.jpg', 'Gallery', [150, 300], [
    'class' => 'thumbnail'
]);

// Example 13: Hero/Banner (large, high priority)
echo responsiveImage('banners/hero.jpg', 'Hero Banner', [768, 1200, 1920, 2560], [
    'fetchpriority' => 'high',
    'decoding' => 'sync',
    'class' => 'hero-banner'
]);

// Example 14: Product images (square crops)
echo '<img src="'.resizeUrl('products/item.jpg', 400, 400).'" alt="Product">';

// Example 15: Profile pictures (small, circular)
echo '<img src="'.resizeUrl('profiles/user.jpg', 150, 150).'" class="rounded-circle" alt="User">';

// ═══════════════════════════════════════════════════════════
// BACKGROUND IMAGES (CSS)
// ═══════════════════════════════════════════════════════════

// Example 16: Inline background image
echo '<div style="background-image: url('.resizeUrl('backgrounds/pattern.jpg', 1920).')" class="hero-section"></div>';

// Example 17: Multiple background sizes
echo '<style>
    .hero {
        background-image: url('.resizeUrl('backgrounds/hero.jpg', 1920).');
    }
    @media (max-width: 768px) {
        .hero {
            background-image: url('.resizeUrl('backgrounds/hero.jpg', 768).');
        }
    }
</style>';

// ═══════════════════════════════════════════════════════════
// SPECIFIC QUALITY SETTINGS
// ═══════════════════════════════════════════════════════════

// Example 18: High quality for photography
echo '<img src="'.resizeUrl('portfolio/photo.jpg', 1920, null, 95).'" alt="Portfolio">';

// Example 19: Lower quality for thumbnails (smaller file size)
echo '<img src="'.resizeUrl('thumbnails/preview.jpg', 200, null, 70).'" alt="Thumbnail">';

// Example 20: Medium quality for general use (balanced)
echo '<img src="'.resizeUrl('articles/image.jpg', 1200, null, 85).'" alt="Article">';

// ═══════════════════════════════════════════════════════════
// COMPLETE REAL-WORLD EXAMPLES
// ═══════════════════════════════════════════════════════════

// Example 21: Blog post with featured image
echo '<article class="blog-post">
    <header>';
        echo responsiveImage('blog/'.$post->featured_image, $post->title, [480, 768, 1200, 1920], [
            'class' => 'featured-image',
            'fetchpriority' => 'high',
            'width' => '1200',
            'height' => '600'
        ]);
echo '      <h1>'.$post->title.'</h1>
    </header>
    <div class="content">
        '.$post->content.'
    </div>
</article>';

// Example 22: Image gallery with lightbox
foreach ($galleryImages as $img) {
    echo '<a href="'.resizeUrl('gallery/'.$img->filename, 1920).'" data-lightbox="gallery">';
    echo responsiveImage('gallery/'.$img->filename, $img->caption, [320, 480, 640], [
        'class' => 'gallery-thumb'
    ]);
    echo '</a>';
}

// Example 23: Product page with multiple images
echo '<div class="product-images">
    <div class="main-image">';
        echo responsiveImage('products/'.$product->main_image, $product->name, [480, 768, 1200], [
            'class' => 'product-main',
            'fetchpriority' => 'high'
        ]);
echo '  </div>
    <div class="thumbnails">';
foreach ($product->images as $img) {
    echo '<img src="'.resizeUrl('products/'.$img, 150, 150).'" class="thumb" alt="Product view">';
}
echo '  </div>
</div>';

// Example 24: Team member cards
foreach ($teamMembers as $member) {
    echo '<div class="team-card">
        <img src="'.resizeUrl('team/'.$member->photo, 300, 300).'" class="rounded-circle" alt="'.$member->name.'">
        <h3>'.$member->name.'</h3>
        <p>'.$member->position.'</p>
    </div>';
}

// Example 25: Article with inline images
$content = $article->content;
// Replace [image:filename.jpg] with responsive image
$content = preg_replace_callback('/\[image:(.*?)\]/', function($matches) {
    return responsiveImage('articles/inline/'.$matches[1], '', [480, 768, 1024]);
}, $content);
echo $content;

// ═══════════════════════════════════════════════════════════
// LAZY LOADING VARIATIONS
// ═══════════════════════════════════════════════════════════

// Example 26: Eager loading (for above-the-fold images)
echo responsiveImage('hero.jpg', 'Hero', [768, 1200, 1920], [
    'loading' => 'eager',
    'fetchpriority' => 'high'
]);

// Example 27: Lazy loading (default, for below-the-fold)
echo responsiveImage('footer-image.jpg', 'Footer', [480, 768, 1200]);
// Note: loading="lazy" is added by default

// ═══════════════════════════════════════════════════════════
// CUSTOM SIZES ATTRIBUTE
// ═══════════════════════════════════════════════════════════

// Example 28: Full width on mobile, half width on desktop
function responsiveImageCustomSizes($imagePath, $alt, $widths, $sizes) {
    $baseUrl = '/image_resize.php?path=' . urlencode($imagePath);
    $srcset = [];
    foreach ($widths as $width) {
        $srcset[] = $baseUrl . '&w=' . $width . ' ' . $width . 'w';
    }
    return sprintf(
        '<img src="%s" srcset="%s" sizes="%s" alt="%s" loading="lazy">',
        $baseUrl . '&w=' . max($widths),
        implode(', ', $srcset),
        $sizes,
        htmlspecialchars($alt)
    );
}

echo responsiveImageCustomSizes('blog/post.jpg', 'Post', [480, 768, 1200], 
    '(max-width: 768px) 100vw, 50vw'
);

// Example 29: Sidebar image (33% width)
echo responsiveImageCustomSizes('sidebar/widget.jpg', 'Widget', [320, 480], 
    '(max-width: 768px) 100vw, 33vw'
);

// ═══════════════════════════════════════════════════════════
// PERFORMANCE OPTIMIZATIONS
// ═══════════════════════════════════════════════════════════

// Example 30: Preload critical images
echo '<link rel="preload" as="image" href="'.resizeUrl('hero.jpg', 1920).'" 
      imagesrcset="
          '.resizeUrl('hero.jpg', 480).' 480w,
          '.resizeUrl('hero.jpg', 768).' 768w,
          '.resizeUrl('hero.jpg', 1200).' 1200w,
          '.resizeUrl('hero.jpg', 1920).' 1920w
      "
      imagesizes="100vw">';

// Then use the image normally
echo responsiveImage('hero.jpg', 'Hero', [480, 768, 1200, 1920], [
    'fetchpriority' => 'high'
]);

// ═══════════════════════════════════════════════════════════
// ALL PARAMETERS REFERENCE
// ═══════════════════════════════════════════════════════════

/*
responsiveImage($imagePath, $alt, $widths, $attributes)

Parameters:
- $imagePath: string - Path relative to /images/ folder (e.g., 'articles/photo.jpg')
- $alt: string - Alt text for accessibility
- $widths: array - Breakpoint widths (default: [480, 768, 1200, 1920])
- $attributes: array - HTML attributes (key => value pairs)

Common attributes:
- 'class' => 'img-fluid custom-class'
- 'id' => 'main-image'
- 'width' => '1200'
- 'height' => '600'
- 'fetchpriority' => 'high' (for critical images)
- 'loading' => 'lazy' (default) or 'eager'
- 'decoding' => 'async' (default) or 'sync'
- 'style' => 'border-radius: 10px;'
- 'data-*' => any data attributes

resizeUrl($imagePath, $width, $height, $quality)

Parameters:
- $imagePath: string - Path relative to /images/ folder
- $width: int|null - Target width in pixels (null to maintain aspect ratio)
- $height: int|null - Target height in pixels (null to maintain aspect ratio)
- $quality: int - JPEG quality 1-100 (default: 85)

URL Parameters (when calling image_resize.php directly):
- path: Image path (required)
- w: Width in pixels
- h: Height in pixels
- q: Quality 1-100

Security limits (configurable in image_resize.php):
- RESIZE_MAX_WIDTH: 3000px
- RESIZE_MAX_HEIGHT: 3000px
- RESIZE_MAX_FILE_SIZE: 10MB
- Allowed extensions: jpg, jpeg, png, gif, webp
*/


// ═══════════════════════════════════════════════════════════
// UNDERSTANDING SIZES ATTRIBUTE
// ═══════════════════════════════════════════════════════════

/*
The browser uses the sizes attribute to decide which image to download:

1. Browser checks viewport width
2. Looks at sizes attribute to determine image display width
3. Picks the smallest srcset image that will look good

Example:
- Viewport: 375px (iPhone)
- sizes="100vw" → image displays at 375px → downloads 480w image
- sizes="50vw" → image displays at 187px → downloads 480w image (still smallest match)

- Viewport: 1920px (Desktop)
- sizes="100vw" → image displays at 1920px → downloads 1920w image
- sizes="50vw" → image displays at 960px → downloads 1200w image
- sizes="300px" → image displays at 300px → downloads 480w image
*/

// ═══════════════════════════════════════════════════════════
// PRESET SIZES (EASY TO USE)
// ═══════════════════════════════════════════════════════════

// Example 1: Full width hero image
// Mobile: 100vw, Desktop: 100vw
echo responsiveImage('hero.jpg', 'Hero', [480, 768, 1200, 1920], [], 'full');
// sizes="100vw"
// → Mobile (375px): downloads 480w
// → Tablet (768px): downloads 768w
// → Desktop (1920px): downloads 1920w

// Example 2: Default content image
// Mobile: 100vw, Desktop: contained in 1200px max width
echo responsiveImage('article.jpg', 'Article', [480, 768, 1200, 1920], [], 'default');
// sizes="(max-width: 768px) 100vw, (max-width: 1200px) 80vw, 1200px"
// → Mobile (375px): 100vw = 375px → downloads 480w
// → Tablet (800px): 80vw = 640px → downloads 768w
// → Desktop (1920px): 1200px → downloads 1200w

// Example 3: Blog post image (max 750px wide)
echo responsiveImage('blog/post.jpg', 'Post', [480, 768, 1200], [], 'content');
// sizes="(max-width: 768px) 100vw, 750px"
// → Mobile (375px): 100vw = 375px → downloads 480w
// → Desktop (1920px): 750px → downloads 768w (not 1200w!)

// Example 4: Two-column layout
echo responsiveImage('feature.jpg', 'Feature', [480, 768, 1200], [], 'half');
// sizes="(max-width: 768px) 100vw, 50vw"
// → Mobile (375px): 100vw = 375px → downloads 480w
// → Desktop (1920px): 50vw = 960px → downloads 1200w

// Example 5: Sidebar image (1/3 width)
echo responsiveImage('sidebar.jpg', 'Widget', [320, 480, 768], [], 'sidebar');
// sizes="(max-width: 768px) 100vw, 33vw"
// → Mobile (375px): 100vw = 375px → downloads 480w
// → Desktop (1920px): 33vw = 633px → downloads 768w

// Example 6: Thumbnail
echo responsiveImage('thumb.jpg', 'Thumb', [150, 300], [], 'thumbnail');
// sizes="(max-width: 480px) 150px, 200px"
// → Mobile (375px): 150px → downloads 150w
// → Desktop (1920px): 200px → downloads 300w

// Example 7: Gallery grid (4 columns on desktop)
echo responsiveImage('gallery.jpg', 'Gallery', [320, 480, 640, 960], [], 'gallery');
// sizes="(max-width: 480px) 100vw, (max-width: 768px) 50vw, (max-width: 1024px) 33vw, 25vw"
// → Mobile (375px): 100vw = 375px → downloads 480w
// → Tablet (768px): 50vw = 384px → downloads 480w
// → Desktop (1920px): 25vw = 480px → downloads 480w or 640w

// ═══════════════════════════════════════════════════════════
// CUSTOM SIZES (MAXIMUM CONTROL)
// ═══════════════════════════════════════════════════════════

// Example 8: Custom - full width on mobile, 60% on desktop
echo responsiveImage('custom.jpg', 'Custom', [480, 768, 1200, 1920], [],
    '(max-width: 768px) 100vw, 60vw'
);

// Example 9: Custom - specific pixel widths
echo responsiveImage('fixed.jpg', 'Fixed', [300, 600, 900], [],
    '(max-width: 768px) 300px, 600px'
);

// Example 10: Custom - complex breakpoints
echo responsiveImage('complex.jpg', 'Complex', [320, 480, 768, 1024, 1920], [],
    '(max-width: 480px) 100vw, (max-width: 768px) 50vw, (max-width: 1200px) 33vw, 25vw'
);

// ═══════════════════════════════════════════════════════════
// REAL-WORLD EXAMPLES WITH DIFFERENT LAYOUTS
// ═══════════════════════════════════════════════════════════

// Example 11: Hero banner (always full width)
echo '<section class="hero">';
echo responsiveImage('banners/hero.jpg', 'Welcome', [768, 1200, 1920, 2560], [
    'fetchpriority' => 'high',
    'class' => 'hero-image'
], 'full'); // sizes="100vw"
echo '</section>';

// Example 12: Blog post featured image (max-width container)
echo '<article class="blog-post" style="max-width: 800px;">';
echo responsiveImage('blog/featured.jpg', 'Featured', [480, 768, 1200], [
    'class' => 'featured-image'
], 'content'); // sizes="(max-width: 768px) 100vw, 750px"
echo '</article>';

// Example 13: Two-column feature section
echo '<div class="row">
    <div class="col-md-6">';
echo responsiveImage('features/feature1.jpg', 'Feature 1', [480, 768, 1200], [], 'half');
echo '  </div>
    <div class="col-md-6">';
echo responsiveImage('features/feature2.jpg', 'Feature 2', [480, 768, 1200], [], 'half');
echo '  </div>
</div>';

// Example 14: Three-column services grid
echo '<div class="services-grid">'; // 3 columns on desktop
for ($i = 1; $i <= 3; $i++) {
    echo '<div class="service-card">';
    echo responsiveImage('services/service' . $i . '.jpg', 'Service ' . $i, [320, 480, 768], [], 'third');
    echo '</div>';
}
echo '</div>';

// Example 15: Four-column gallery
echo '<div class="gallery">'; // 4 columns on desktop
foreach ($galleryImages as $img) {
    echo '<div class="gallery-item">';
    echo responsiveImage('gallery/' . $img, 'Gallery', [320, 480, 640], [], 'quarter');
    echo '</div>';
}
echo '</div>';

// Example 16: Sidebar widget (1/3 width)
echo '<aside class="sidebar">
    <div class="widget">';
echo responsiveImage('widgets/ad.jpg', 'Advertisement', [320, 480], [], 'sidebar');
echo '  </div>
</aside>';

// Example 17: Product thumbnails (fixed 200px)
echo '<div class="product-thumbnails">';
foreach ($product->images as $img) {
    echo responsiveImage('products/thumbs/' . $img, 'Product', [150, 300], [], 'thumbnail');
}
echo '</div>';

// ═══════════════════════════════════════════════════════════
// COMPARISON: WRONG vs RIGHT SIZES
// ═══════════════════════════════════════════════════════════

// Example 18: WRONG - Same sizes for different layouts
echo '<div class="row">
    <div class="col-md-8">';
// This is 2/3 width but we say 100vw - WASTES BANDWIDTH
echo responsiveImage('main.jpg', 'Main', [480, 768, 1200, 1920], [], 'full');
echo '  </div>
    <div class="col-md-4">';
// This is 1/3 width but we say 100vw - WASTES BANDWIDTH
echo responsiveImage('sidebar.jpg', 'Sidebar', [480, 768, 1200, 1920], [], 'full');
echo '  </div>
</div>';

// Example 19: RIGHT - Correct sizes for each column
echo '<div class="row">
    <div class="col-md-8">';
// 2/3 width = ~67vw
echo responsiveImage('main.jpg', 'Main', [480, 768, 1200], [], '(max-width: 768px) 100vw, 67vw');
echo '  </div>
    <div class="col-md-4">';
// 1/3 width = ~33vw
echo responsiveImage('sidebar.jpg', 'Sidebar', [320, 480, 768], [], 'sidebar');
echo '  </div>
</div>';

// ═══════════════════════════════════════════════════════════
// TESTING YOUR SIZES ATTRIBUTE
// ═══════════════════════════════════════════════════════════

/*
To test if your sizes are correct:

1. Open Chrome DevTools
2. Go to Network tab
3. Filter by "Img"
4. Resize browser window
5. Reload page
6. Check which image size was downloaded

Example test:
- Image displayed at 400px wide
- Available: 480w, 768w, 1200w
- Browser should download: 480w (smallest that covers 400px)

If it downloads 1200w → your sizes attribute is wrong (too large)
*/

// ═══════════════════════════════════════════════════════════
// ALL AVAILABLE PRESETS
// ═══════════════════════════════════════════════════════════

/*
'full' or 'fullwidth' or 'hero' or 'banner'
→ sizes="100vw"
→ Use for: Full-width hero images, banners, sliders

'default'
→ sizes="(max-width: 768px) 100vw, (max-width: 1200px) 80vw, 1200px"
→ Use for: General content images in a container

'content' or 'article'
→ sizes="(max-width: 768px) 100vw, 750px"
→ Use for: Blog posts, article images (max 750px wide)

'half' or 'two-column'
→ sizes="(max-width: 768px) 100vw, 50vw"
→ Use for: 2-column layouts

'third' or 'sidebar'
→ sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
→ Use for: 3-column layouts, sidebars

'quarter'
→ sizes="(max-width: 480px) 100vw, (max-width: 768px) 50vw, (max-width: 1024px) 33vw, 25vw"
→ Use for: 4-column grids

'gallery'
→ sizes="(max-width: 480px) 100vw, (max-width: 768px) 50vw, (max-width: 1024px) 33vw, 25vw"
→ Use for: Image galleries

'thumbnail'
→ sizes="(max-width: 480px) 150px, 200px"
→ Use for: Small thumbnails

'small'
→ sizes="(max-width: 768px) 200px, 300px"
→ Use for: Small fixed-width images

'large'
→ sizes="(max-width: 768px) 100vw, (max-width: 1400px) 90vw, 1400px"
→ Use for: Large featured images (wider than normal content)

Or pass custom string:
'(max-width: 768px) 100vw, 50vw'
*/

// ═══════════════════════════════════════════════════════════
// PERFORMANCE IMPACT EXAMPLES
// ═══════════════════════════════════════════════════════════

// Example 20: BAD - Loading huge image for small display
echo '<div class="thumbnail" style="width: 150px;">';
// This loads 1920w image even though displayed at 150px! WASTE!
echo responsiveImage('thumb.jpg', 'Thumb', [480, 768, 1200, 1920], [], 'full');
echo '</div>';

// Example 21: GOOD - Loading appropriate size
echo '<div class="thumbnail" style="width: 150px;">';
// This loads 150w or 300w image for 150px display - PERFECT!
echo responsiveImage('thumb.jpg', 'Thumb', [150, 300], [], 'thumbnail');
echo '</div>';

// Bandwidth saved: 1920w (500KB) vs 150w (15KB) = 97% reduction! 🚀