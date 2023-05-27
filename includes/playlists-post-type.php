<?php

defined( 'ABSPATH' ) or die;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Playlists post type
 */
class SOVA_Playlists_Post_Type
{
    public static function create()
    {
        ( new SOVA_WP_Post_Type )
            ->name( 'playlists')
            ->menu_icon( 'dashicons-format-audio' )
            ->supports( [ 'title', ] )
            ->create();
    }

    public static function metafields()
    {
        Container::make( 'post_meta', __( 'Playlist' ) )
            ->where( 'post_type', '=', 'playlists' )
            ->add_fields( [
                Field::make( 'checkbox',  'playlist_play_next', __( 'Play next track after end' ) ),
                Field::make( 'checkbox',  'playlist_calendar', __( 'Enable calendar' ) ),
                Field::make( 'complex',  'tracks', __( 'Tracks' ) )
                    ->add_fields( [
                        Field::make( 'file', 'file', __( 'File' ) )
                            ->set_value_type( 'audio' ),
                        Field::make( 'image', 'poster', __( 'Poster' ) ),
                        Field::make( 'text', 'title', __( 'Title' ) ),
                        Field::make( 'text', 'performer', __( 'Performer' ) ),
                        Field::make( 'date_time', 'published_at', __( 'Published at' ) ),
                        Field::make( 'text', 'like_count', __( 'Like count' ) )
                            ->set_default_value(0),
                    ] ),
            ] );
    }
}