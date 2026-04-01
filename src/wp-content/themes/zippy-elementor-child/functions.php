<?php

/*
 * Define Variables
 */
if (!defined('THEME_DIR'))
    define('THEME_DIR', get_template_directory());

if (!defined('THEME_DIR_CHILD'))
    define('THEME_DIR_CHILD', THEME_DIR . '-child');

if (!defined('THEME_URL'))
    define('THEME_URL', get_template_directory_uri());


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


/**
 *
 * Include Autoload file
 */

require_once THEME_DIR_CHILD . '/autoload.php';

function enqueue_sprintf_for_elementor()
{
    // chỉ load trên Elementor editor
    if (did_action('elementor/loaded')) {
        wp_enqueue_script(
            'sprintf-js',
            'https://cdnjs.cloudflare.com/ajax/libs/sprintf/1.1.2/sprintf.min.js',
            [],
            '1.1.2',
            true
        );
    }
}
add_action('elementor/editor/after_enqueue_scripts', 'enqueue_sprintf_for_elementor');