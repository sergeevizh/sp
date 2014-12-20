<?php
/*
Plugin Name: FancyBox for WordPress by CasePress
Description: Awesome http://fancybox.net/ (and http://fancyapps.com/fancybox/ ) for WordPress 
Version: 0.2
Author: CasePress
Author URI: http://casepress.org
License: MIT License
Text Domain: fancybox-cp
*/

//Определяем константу и помещаем в нее путь до папки с плагином. Чтобы затем использовать ее.
define ("CP_FANCYBOX_PLUGIN_DIR_URL", plugin_dir_url(__FILE__));

add_action( 'wp_enqueue_scripts', 'gallery_cp_fancybox_scripts');
function gallery_cp_fancybox_scripts() {
	
	wp_register_script( $handle = 'mousewheel', $src = CP_FANCYBOX_PLUGIN_DIR_URL.'includes/fancybox/jquery.mousewheel-3.1.3.pack.js', array('jquery'), false, true);
	
	wp_register_script( $handle = 'fancybox', $src = CP_FANCYBOX_PLUGIN_DIR_URL.'includes/fancybox/jquery.fancybox-2.1.5.js', array('jquery', 'mousewheel'), false, true);	
	
	wp_register_style( $handle = 'fancybox.css', $src = CP_FANCYBOX_PLUGIN_DIR_URL.'includes/fancybox/jquery.fancybox-2.1.5.css');	
	
	wp_enqueue_script( 'fancybox');
	wp_enqueue_style( 'fancybox.css' );	
}