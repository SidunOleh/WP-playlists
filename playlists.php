<?php

/*
Plugin Name: Playlists
Description: Playlists for WordPress
Author: Sidun Oleh
*/

defined( 'ABSPATH' ) or die;

/*
Activate plugin
*/
require_once plugin_dir_path( __FILE__ ) . '/includes/playlists-activator.php';
register_activation_hook( 
    __FILE__, 
    [ SOVA_Playlists_Activator::class, 'activate' ] 
);

/*
Run plugin
*/
require_once plugin_dir_path( __FILE__ ) . '/includes/playlists.php';
SOVA_Playlists::run();