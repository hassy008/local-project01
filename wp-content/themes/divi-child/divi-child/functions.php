<?php


//Add class
// include "class/class-OpsShortCode.php";
include "class/CT_TAX_META.php";

// add_action('after_setup_theme', array('OpsShortCode', 'init'));
add_action('after_setup_theme', array('CT_TAX_META', 'init'));








add_action('wp_enqueue_scripts', 'my_enqueue_assets');

function my_enqueue_assets()
{

    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}