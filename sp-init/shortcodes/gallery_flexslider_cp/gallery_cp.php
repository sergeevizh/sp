<?php
/*
Plugin Name: FlexSlider for WordPress by CasePress
Description: Awesome slider http://flexslider.woothemes.com/ for WordPress
Version: 0.1
Author: CasePress
Author URI: http://casepress.org
License: MIT License
Text Domain: flexslider-cp
Domain Path: languages
*/



//Определяем константу и помещаем в нее путь до папки с плагином. Чтобы затем использовать ее.
define ("CP_FLEXSLIDER_PLUGIN_DIR_URL", plugin_dir_url(__FILE__));

add_action( 'wp_enqueue_scripts', 'gallery_cp_shortcode_scripts');
function gallery_cp_shortcode_scripts() {
	
	wp_register_script( $handle = 'flexslider', $src = CP_FLEXSLIDER_PLUGIN_DIR_URL.'includes/flexslider/jquery.flexslider.js', array('jquery'));
	wp_register_style( $handle = 'flexslider.css', $src = CP_FLEXSLIDER_PLUGIN_DIR_URL.'includes/flexslider/flexslider.css');
	
	wp_enqueue_script( 'flexslider');
	wp_enqueue_style( 'flexslider.css' );
}