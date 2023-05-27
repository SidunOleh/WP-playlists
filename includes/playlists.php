<?php

defined( 'ABSPATH' ) or die;

/**
 * Plugin core
 */
class SOVA_Playlists
{
    public static function run()
    {
        self::load_dependencies();
        self::define_hooks();
        self::define_public_hooks();
    }

    private static function load_dependencies()
    {   
        require_once plugin_dir_path( __FILE__ ) . '/playlists-post-type.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/playlists-public.php';
    }

    private static function define_hooks()
    {
        add_action( 
            'init', 
            [ SOVA_Playlists_Post_Type::class, 'create' ] 
        );
        add_action( 
            'carbon_fields_register_fields', 
            [ SOVA_Playlists_Post_Type::class, 'metafields' ] 
        );
    }

    private static function define_public_hooks()
    {
        add_action( 
            'wp_enqueue_scripts', 
            [ SOVA_Playlists_Public::class, 'enqueue_styles' ] 
        );
        add_action( 
            'wp_enqueue_scripts', 
            [ SOVA_Playlists_Public::class, 'enqueue_scripts' ] 
        );
        add_action( 
            'init', 
            [ SOVA_Playlists_Public::class, 'playlist_shortcode' ] 
        );
        add_action( 
            'wp_ajax_update_like_count', 
            [ SOVA_Playlists_Public::class, 'update_like_count' ] 
        );
        add_action( 
            'wp_ajax_nopriv_update_like_count', 
            [ SOVA_Playlists_Public::class, 'update_like_count' ] 
        );
        add_action( 
            'wp_ajax_update_like_count', 
            [ SOVA_Playlists_Public::class, 'update_like_count' ] 
        );
        add_action( 
            'wp_ajax_nopriv_date_range_tracks', 
            [ SOVA_Playlists_Public::class, 'date_range_tracks' ] 
        );
        add_action( 
            'wp_ajax_date_range_tracks', 
            [ SOVA_Playlists_Public::class, 'date_range_tracks' ] 
        );
        add_action( 
            'init', 
            [ SOVA_Playlists_Public::class, 'thumbnail_size' ] 
        );
    }
}