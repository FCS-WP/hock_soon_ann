<?php
add_action('wp_enqueue_scripts', 'shin_scripts');

function shin_scripts()
{
    $version = time();

    wp_enqueue_style('main-style-css', THEME_URL . '-child' . '/assets/dist/css/main.min.css', array(), $version, 'all');

    // Slick carousel (CSS + theme)
    wp_enqueue_style(
        'slick-css',
        'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.css',
        array(),
        '1.8.1'
    );
    wp_enqueue_style(
        'slick-theme-css',
        'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.min.css',
        array('slick-css'),
        '1.8.1'
    );

    // Slick JS (depends on jQuery)
    // wp_enqueue_script(
    //     'slick-js',
    //     'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
    //     array('jquery'),
    //     '1.8.1',
    //     true
    // );

    wp_enqueue_script('slick-js', THEME_URL . '-child' . '/assets/lib/slick/slick.min.js', array('jquery'), $version, false);
    wp_enqueue_script('main-scripts-js', THEME_URL . '-child' . '/assets/dist/js/main.min.js', array('jquery', 'slick-js'), $version, true);
}
