<?php

require 'vendor/autoload.php';

use Intervention\Image\Image;
use RicardoFiorani\Legofy\Pallete\Palettes;

function main($image_path, $output_path = '', $size = null, $palette_mode = null, $dither = false)
{
    $image_path = realpath($image_path);

    if (false == file_exists($image_path)) {
        printf('Image file "{0}" was not found.', $image_path);
        exit(1);
    }

    if (endswith($image_path, '.gif')) {
        print('GIF not supported at the moment');
        exit(1);
    }

    $brick_path = __DIR__ . DIRECTORY_SEPARATOR . '1x1.png';

    if (false == file_exists($brick_path)) {
        printf('Brick asset "%s" was not found.', $brick_path);
        exit(1);
    }

    $base_image = \Intervention\Image\ImageManagerStatic::make($image_path);
    $brick_image = \Intervention\Image\ImageManagerStatic::make($brick_path);

    if ($palette_mode) {
        printf('LEGO Palette %s selected...', $palette_mode);
    } else {
        $palette_mode = 'all';
    }


    if (is_null($output_path)) {
        $output_path = get_new_filename($image_path, '.png');
    }

    legofy_image($base_image, $brick_image, $output_path, $size, $palette_mode, $dither);

    $base_image->destroy();
    $brick_image->destroy();

    print("Finished!");
}

/**
 * Returns the save destination file path
 */
function get_new_filename($file_path, $ext_override = '')
{
    $path = pathinfo($file_path);

    if ($ext_override) {
        $extention = $ext_override;
    }

    $new_filename = sprintf(
        '%s%s%s%s.%s',
        $path['dirname'],
        DIRECTORY_SEPARATOR,
        $path['filename'],
        '_lego',
        $path['extension']
    );

    return $new_filename;
}

function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

/**
 * Legofy an image
 */
function legofy_image(Image $base_image, Image $brick_image, $output_path, $size, $palette_mode, $dither)
{
    $new_size = get_new_size($base_image, $brick_image, $size);
    $base_image->resize($new_size[0], $new_size[1]);

    if ($palette_mode) {
        $palette = get_lego_palette($palette_mode);
        $base_image = apply_thumbnail_effects($base_image, $palette, $dither);
    }

    make_lego_image($base_image, $brick_image)->save($output_path);
}

/**
 * Returns a new size the first image should be so that the second one fits neatly in the longest axis
 */
function get_new_size(Image $base_image, $brick_image, $size = null)
{
    $new_size = [$base_image->getWidth(), $base_image->getHeight()];

    if ($size) {
        $scale_x = $scale_y = $size;
    } else {
        $scale_x = $base_image->getWidth();
        $scale_y = $base_image->getHeight();
    }

    if ($new_size[0] > $scale_x or $new_size[1] > $scale_y) {
        if ($new_size[0] < $new_size[1]) {
            $scale = $new_size[1] / $scale_y;
        } else {
            $scale = $new_size[0] / $scale_x;
        }

        $new_size = [
            (int)(round($new_size[0] / $scale)) ?? 1,
            (int)(round($new_size[1] / $scale)) ?? 1,
        ];
    }

    return $new_size;
}

/**
 * Create a lego version of an image from an image
 */
function make_lego_image($thumbnail_image, $brick_image)
{
    list($base_width, $base_height) = $thumbnail_image->size;
    list ($brick_width, $brick_height) = $brick_image->size;

    $rgb_image = $thumbnail_image . convert('RGB');

    $lego_image = Image::new("RGB", [base_width * brick_width, base_height * brick_height], "white");

    foreach (range(0, $base_width) as $brick_x) {
        foreach (range(0, $base_height) as $brick_y) {
            $color = $rgb_image->getpixel([brick_x, brick_y]);
            $lego_image->paste(
                apply_color_overlay($brick_image, $color),
                [brick_x * brick_width, brick_y * brick_height]
            );
        }
    }

    return $lego_image;
}

/**
 * Small function to apply an effect over an entire image
 */
function apply_color_overlay($image, $color)
{
    list($overlay_red, $overlay_green, $overlay_blue) = $color;
    $channels = $image->split();

    $r = $channels[0]->point(overlay_effect($color, $overlay_red));
    $g = $channels[1]->point(overlay_effect($color, $overlay_green));
    $b = $channels[2]->point(overlay_effect($color, $overlay_blue));

    $channels[0]->paste($r);
    $channels[1]->paste($g);
    $channels[2]->paste($b);

    return Image::merge($image->mode, $channels);
}

/**
 * Actual overlay effect function
 */
function overlay_effect($color, $overlay)
{
    if ($color < 33) {
        return $overlay - 100;
    } elseif ($color > 233) {
        return $overlay + 100;
    } else {
        return $overlay - 133 + $color;
    }
}

/**
 * Gets the palette for the specified lego palette mode
 */
function get_lego_palette($palette_mode)
{
    $legos = Palettes::legos();
    $palette = $legos[$palette_mode];

    return Palettes::extend_palette($palette);
}

/**
 * Apply effects on the reduced image before Legofying
 */
function apply_thumbnail_effects(Image $image, $palette, $dither)
{
    $palette_image = \Intervention\Image\ImageManagerStatic::canvas(1, 1);
    $palette_image->limitColors(256);

//    $image->col

    return $image->im->convert("P",
        $dither ? Image::FLOYDSTEINBERG : Image::NONE,
        $palette_image->im);

}
