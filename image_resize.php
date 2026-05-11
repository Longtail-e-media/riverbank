<?php
/**
 * SECURE Image Resizer (No Composer Required)
 * Security-hardened version with all protections
 */

// Configuration
define('RESIZE_CACHE_DIR', __DIR__ . '/images/cache/');
define('RESIZE_SOURCE_DIR', __DIR__ . '/images/');
define('RESIZE_QUALITY', 85);
define('RESIZE_ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('RESIZE_MAX_WIDTH', 3000);
define('RESIZE_MAX_HEIGHT', 3000);
define('RESIZE_MAX_FILE_SIZE', 10485760); // 10MB
define('RESIZE_MEMORY_LIMIT', '256M');

// Set memory limit
ini_set('memory_limit', RESIZE_MEMORY_LIMIT);

// Create cache directory if it doesn't exist
if (!file_exists(RESIZE_CACHE_DIR)) {
    mkdir(RESIZE_CACHE_DIR, 0755, true);
}

// Get and validate parameters
$path = isset($_GET['path']) ? $_GET['path'] : '';
$width = isset($_GET['w']) ? (int)$_GET['w'] : null;
$height = isset($_GET['h']) ? (int)$_GET['h'] : null;
$quality = isset($_GET['q']) ? (int)$_GET['q'] : RESIZE_QUALITY;

// Security: Validate and sanitize path
$path = validatePath($path);
if ($path === false) {
    header('HTTP/1.0 400 Bad Request');
    exit('Invalid path');
}

// Security: Validate dimensions
if ($width !== null && ($width < 1 || $width > RESIZE_MAX_WIDTH)) {
    header('HTTP/1.0 400 Bad Request');
    exit('Invalid width');
}
if ($height !== null && ($height < 1 || $height > RESIZE_MAX_HEIGHT)) {
    header('HTTP/1.0 400 Bad Request');
    exit('Invalid height');
}

// Security: Validate quality
if ($quality < 1 || $quality > 100) {
    $quality = RESIZE_QUALITY;
}

// Get source file path
$sourcePath = RESIZE_SOURCE_DIR . $path;

// Security: Verify path is within allowed directory (prevent directory traversal)
$realSourcePath = realpath($sourcePath);
$realSourceDir = realpath(RESIZE_SOURCE_DIR);

if ($realSourcePath === false || strpos($realSourcePath, $realSourceDir) !== 0) {
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied');
}

// Check if source file exists
if (!file_exists($sourcePath) || !is_file($sourcePath)) {
    header('HTTP/1.0 404 Not Found');
    exit('Image not found');
}

// Security: Check file size
if (filesize($sourcePath) > RESIZE_MAX_FILE_SIZE) {
    header('HTTP/1.0 413 Payload Too Large');
    exit('Image too large');
}

// Security: Validate file extension
$ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
if (!in_array($ext, RESIZE_ALLOWED_EXTENSIONS)) {
    header('HTTP/1.0 403 Forbidden');
    exit('File type not allowed');
}

// Security: Validate actual file type (not just extension)
$imageInfo = @getimagesize($sourcePath);
if ($imageInfo === false) {
    header('HTTP/1.0 400 Bad Request');
    exit('Invalid image file');
}

list($origWidth, $origHeight, $imageType) = $imageInfo;

// Security: Verify MIME type matches extension
$allowedMimeTypes = [
    'jpg' => IMAGETYPE_JPEG,
    'jpeg' => IMAGETYPE_JPEG,
    'png' => IMAGETYPE_PNG,
    'gif' => IMAGETYPE_GIF,
    'webp' => IMAGETYPE_WEBP
];

if (!isset($allowedMimeTypes[$ext]) || $imageType !== $allowedMimeTypes[$ext]) {
    header('HTTP/1.0 403 Forbidden');
    exit('File extension does not match content');
}

// Generate safe cache path
$cacheKey = md5($path . $width . $height . $quality);
$cacheSubDir = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', dirname($path));
$cacheDir = RESIZE_CACHE_DIR . $cacheSubDir . '/';

// Security: Ensure cache directory is within RESIZE_CACHE_DIR
$realCacheDir = realpath(RESIZE_CACHE_DIR);
if (!file_exists($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}
$realCachePath = realpath($cacheDir);
if ($realCachePath === false || strpos($realCachePath, $realCacheDir) !== 0) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Cache error');
}

$cachePath = $cacheDir . pathinfo($path, PATHINFO_FILENAME) . '-' . $cacheKey . '.' . $ext;

// Serve cached version if exists and is newer than source
if (file_exists($cachePath) && filemtime($cachePath) >= filemtime($sourcePath)) {
    serveCachedImage($cachePath, $ext);
    exit;
}

// Calculate new dimensions
if ($width && $height) {
    $newWidth = $width;
    $newHeight = $height;
} elseif ($width) {
    $newWidth = $width;
    $newHeight = round(($origHeight / $origWidth) * $width);
} elseif ($height) {
    $newWidth = round(($origWidth / $origHeight) * $height);
    $newHeight = $height;
} else {
    $newWidth = $origWidth;
    $newHeight = $origHeight;
}

// Don't upscale images
if ($newWidth > $origWidth) {
    $newWidth = $origWidth;
    $newHeight = $origHeight;
}

// Security: Prevent creating extremely large images
if ($newWidth > RESIZE_MAX_WIDTH || $newHeight > RESIZE_MAX_HEIGHT) {
    header('HTTP/1.0 400 Bad Request');
    exit('Requested dimensions too large');
}

// Create image resource from source
$sourceImage = createImageFromFile($sourcePath, $imageType);
if (!$sourceImage) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Could not create image');
}

// Create new image
$newImage = @imagecreatetruecolor($newWidth, $newHeight);
if (!$newImage) {
    imagedestroy($sourceImage);
    header('HTTP/1.0 500 Internal Server Error');
    exit('Could not create resized image');
}

// Preserve transparency for PNG and GIF
if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
    imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
    imagealphablending($newImage, false);
    imagesavealpha($newImage, true);
}

// Resize image
imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

// Save to cache
$saved = saveImageToFile($newImage, $cachePath, $imageType, $quality);

// Clean up
imagedestroy($sourceImage);
imagedestroy($newImage);

if (!$saved) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Could not save image');
}

// Serve the image
serveCachedImage($cachePath, $ext);

/**
 * Validate and sanitize path
 */
function validatePath($path)
{
    // Remove any null bytes
    $path = str_replace("\0", '', $path);

    // Remove any directory traversal attempts
    $path = preg_replace('/\.\.+[\/\\\\]/', '', $path);
    $path = preg_replace('/[\/\\\\]\.\.+/', '', $path);

    // Remove absolute path indicators
    $path = ltrim($path, '/\\');

    // Only allow alphanumeric, dots, dashes, underscores, and forward slashes
    if (!preg_match('/^[a-zA-Z0-9._\-\/]+$/', $path)) {
        return false;
    }

    // Ensure path doesn't start with dot
    if (strpos($path, '.') === 0) {
        return false;
    }

    return $path;
}

/**
 * Create image resource from file
 */
function createImageFromFile($path, $type)
{
    switch ($type) {
        case IMAGETYPE_JPEG:
            return @imagecreatefromjpeg($path);
        case IMAGETYPE_PNG:
            return @imagecreatefrompng($path);
        case IMAGETYPE_GIF:
            return @imagecreatefromgif($path);
        case IMAGETYPE_WEBP:
            return @imagecreatefromwebp($path);
        default:
            return false;
    }
}

/**
 * Save image resource to file
 */
function saveImageToFile($image, $path, $type, $quality)
{
    switch ($type) {
        case IMAGETYPE_JPEG:
            return @imagejpeg($image, $path, $quality);
        case IMAGETYPE_PNG:
            $pngQuality = round((100 - $quality) / 11.111111);
            return @imagepng($image, $path, $pngQuality);
        case IMAGETYPE_GIF:
            return @imagegif($image, $path);
        case IMAGETYPE_WEBP:
            return @imagewebp($image, $path, $quality);
        default:
            return false;
    }
}

/**
 * Serve cached image with proper headers
 */
function serveCachedImage($path, $ext)
{
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp'
    ];

    $mimeType = isset($mimeTypes[$ext]) ? $mimeTypes[$ext] : 'application/octet-stream';

    // Security headers
    header('X-Content-Type-Options: nosniff');
    header('Content-Type: ' . $mimeType);
    header('Content-Length: ' . filesize($path));
    header('Cache-Control: public, max-age=31536000, immutable');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');

    // Output image
    readfile($path);
}