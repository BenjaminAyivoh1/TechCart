<?php
/**
 * Shared helpers for TechCart.
 */

/**
 * Escape a value for safe output in HTML.
 */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * The price a customer actually pays for a product.
 *
 * shop.php and product.php display discount_price whenever it is set,
 * so the cart, checkout and order code must charge the same amount.
 */
function effective_price(array $product): float
{
    $discount = isset($product['discount_price']) ? (float)$product['discount_price'] : 0.0;

    return $discount > 0 ? $discount : (float)$product['price'];
}

/**
 * True when the product is currently sold below its list price.
 */
function is_discounted(array $product): bool
{
    return effective_price($product) < (float)$product['price'];
}

/**
 * Check an uploaded product image without trusting anything the browser sent.
 *
 * The file name and the browser's claimed type are ignored. The real type is
 * read from the file's own bytes, and only JPG, PNG and WebP are accepted.
 *
 * Returns [extension, null] when valid, or [null, "message"] when not.
 */
function validate_product_image(array $file, int $maxBytes = 2097152): array
{
    $code = $file['error'] ?? UPLOAD_ERR_NO_FILE;

    if ($code === UPLOAD_ERR_NO_FILE) {
        return [null, "Please choose an image."];
    }

    if ($code === UPLOAD_ERR_INI_SIZE || $code === UPLOAD_ERR_FORM_SIZE) {
        return [null, "That image is too large (2 MB maximum)."];
    }

    if ($code !== UPLOAD_ERR_OK) {
        return [null, "The upload failed. Please try again."];
    }

    if (($file['size'] ?? 0) > $maxBytes) {
        return [null, "That image is too large (2 MB maximum)."];
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        return [null, "Only JPG, PNG or WebP images are allowed."];
    }

    // It must also decode as a real image
    if (@getimagesize($file['tmp_name']) === false) {
        return [null, "That file is not a valid image."];
    }

    return [$allowed[$mime], null];
}

/**
 * Validate an uploaded product image and save it under a random name.
 *
 * The extension always comes from the detected type, never from the
 * uploaded file name, so a script can't be saved as something executable.
 *
 * Returns [filename, null] on success, or [null, "message"] on failure.
 */
function store_product_image(array $file, string $directory): array
{
    [$extension, $error] = validate_product_image($file);

    if ($error !== null) {
        return [null, $error];
    }

    $name = bin2hex(random_bytes(8)) . '.' . $extension;

    if (!move_uploaded_file($file['tmp_name'], rtrim($directory, '/\\') . '/' . $name)) {
        return [null, "Could not save the image."];
    }

    return [$name, null];
}

/**
 * Remove a stored product image. Safe to call with an empty name.
 */
function delete_product_image(?string $filename, string $directory): void
{
    if ($filename === null || $filename === '') {
        return;
    }

    $path = rtrim($directory, '/\\') . '/' . basename($filename);

    if (is_file($path)) {
        unlink($path);
    }
}
