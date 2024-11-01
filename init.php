<?php
/*
Plugin Name: WP basic Info
Description: This plugin is to show basic information of Wordpress setup and associated plugins on Admin side
Author: Geethu Vijayan
Version: 1.2
*/
function wp_info_dashboard() {
    add_menu_page(
        __( 'WP Info', 'textdomain' ),
        'WP Info',
        'manage_options',
        'wpInfo',
        'wpInfo',
        plugins_url( 'wp-info/images/icon.png' ),
        6
    );
}
add_action( 'admin_menu', 'wp_info_dashboard' );
function wpInfo(){
	require_once('wp-basic-info.php');
}
