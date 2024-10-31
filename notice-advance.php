<?php
/*
 * Plugin Name: Notice Advance NTH
 * Plugin URI: none
 * Description: This plugin allows you show notice bar on top or bottom your page. You can edit height, background, font color and embed links.
 * Version: 1.1 
 * Author: nguyenhuy.nth@gmail.com
 * Author URI: http://storewed.com
 * License: GPLv2 or later.
 */
define('NTH_SETTING_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NTH_SETTING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NTH_SETTING_INCLUDES_DIR', plugin_dir_path(__FILE__));
// init front page
if(!is_admin()){  
    add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );
    require NTH_SETTING_INCLUDES_DIR. 'includes/nth_frontend.php';
    new NTHFrontend();
// Init Admin page
}else{
    add_action( 'admin_enqueue_scripts', 'nth_add_color_picker' ); 
    add_action( 'admin_enqueue_scripts', 'register_plugin_admin' );
    require NTH_SETTING_INCLUDES_DIR. 'includes/nth_backend.php';
    new NTHBackend();
    
}


function nth_add_color_picker( $hook ) { 
    // Add style and js for datapicker
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
            // Add the color picker css file       
            wp_enqueue_style( 'wp-color-picker' );            
            // Include our custom jQuery file with WordPress Color Picker dependency
            wp_enqueue_script( 'custom-script-handle', plugins_url( 'js/jquery.custom.js',__FILE__ ), array( 'wp-color-picker' ), false, true );
            
             
}

function register_plugin_admin()
{
    wp_register_style( 'switch-button', plugins_url( 'css/admin.css',__FILE__  ) );
    wp_enqueue_style( 'switch-button' );
}

function register_plugin_styles() {
    	wp_register_style( 'alert-advance-nth', plugins_url( 'css/frontpage.css',__FILE__  ) );
    	wp_enqueue_style( 'alert-advance-nth' );
        
}


