<?php
/**
 * Modify a hex color by the given number of steps (out of 255).
 *
 * Adapted from a solution by Torkil Johnsen.
 *
 * @param string $color
 * @param int $steps
 * @link http://stackoverflow.com/questions/3512311/how-to-generate-lighter-darker-color-with-php
 */
function thanksroy_brighten($color, $steps) {
    $steps = max(-255, min(255, $steps));
    $hex = str_replace('#', '', $color);
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));

    $r = max(0,min(255,$r + $steps));
    $g = max(0,min(255,$g + $steps));  
    $b = max(0,min(255,$b + $steps));

    $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
    $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
    $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

     return '#'.$r_hex.$g_hex.$b_hex;
}


/**
 * Return the HTML for summarizing a random featured exhibit
 *
 * @return string
 */
function thanksroy_exhibit_builder_display_random_featured_records($type = null, $count = 2, $hasImage = null)
{
    $records = get_records(strtoupper($type), array('featured' => 1,
                                     'sort_field' => 'random',
                                     'hasImage' => $hasImage), $count);

    $recordPaths = [
        'collection' => 'collections/',
        'exhibit' => 'exhibit-builder/exhibits/',
        'item' => 'items/'
    ];
    if ($records) {
        $html = '';
        foreach ($records as $record) {
            $html .= get_view()->partial($recordPaths[$type] . 'single.php', array($type => $record));
            release_object($record);
        }
    } else {
        $html .= '<p>' . __('You have no featured exhibits.') . '</p>';
    }
    $html = apply_filters('exhibit_builder_display_random_featured_exhibit', $html);
    return $html;
}