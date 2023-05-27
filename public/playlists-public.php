<?php

defined( 'ABSPATH' ) or die;

/**
 * Plugin public side
 */
class SOVA_Playlists_Public
{
    public static function enqueue_styles()
    {
        wp_enqueue_style( 
            'playlists', 
            plugin_dir_url( __FILE__ ) . '/css/playlists.css' 
        );
        wp_enqueue_style( 
            'calendar', 
            plugin_dir_url( __FILE__ ) . '/css/calendar.css' 
        );
    }

    public static function enqueue_scripts()
    {
        wp_enqueue_script( 
            'playlists', 
            plugin_dir_url( __FILE__ ) . '/js/playlists.js', 
            [ 'jquery', 'calendar', ], 
            false, 
            true 
        );
        wp_enqueue_script( 
            'calendar', 
            plugin_dir_url( __FILE__ ) . '/js/calendar.js', 
            [ 'jquery', ], 
            false, 
            true 
        );
    }

    /**
     * add shortcode
     */
    public static function playlist_shortcode()
    {
        add_shortcode( 'playlist', [ self::class, 'playlist_html' ] );
    }

    /**
     * shortcode output
     */
    public static function playlist_html( $attr, $content, $tag )
    {
        if ( 
            ! $playlist = self::get_playlist( $attr[ 'id' ] ) or
            ! $tracks = carbon_get_post_meta( $playlist->ID, 'tracks' )
        ) {
            return;
        }

        $play_next = carbon_get_post_meta( $playlist->ID, 'playlist_play_next' );
        $enable_calendar = carbon_get_post_meta( $playlist->ID, 'playlist_calendar' );
		
		if ( $enable_calendar ) {
            $tracks = self::getLastWeekTracks( $tracks );
        }

        require plugin_dir_path( __FILE__ ) . '/templates/playlist.php';
    }

    private static function get_playlist( $id )
    {
        $posts = get_posts( [
            'post_type' => 'playlists',
            'post__in' => [ $id ], 
        ] );
        if ( count( $posts ) == 0 ) {
            return null;
        }

        return $posts[0];
    }

    private static function getLastWeekTracks( array $tracks )
    {
        return array_filter( 
            $tracks, 
            function ( $track ) {
                if ( ! $track[ 'published_at' ] ) return false;

                $published_ago = time() - strtotime( $track[ 'published_at' ] );
                
                return $published_ago <= WEEK_IN_SECONDS;
            } 
        );
    }

    /**
     * update like count
     */
    public static function update_like_count()
    {
        $old_count = carbon_get_post_meta( $_GET[ 'playlist_id' ], 'tracks' )
            [ $_GET[ 'track_id' ] ]
            [ 'like_count' ];
        carbon_set_post_meta( 
            $_GET[ 'playlist_id' ], 
            'tracks[' . $_GET[ 'track_id' ] . ']/like_count', 
            ( int ) ++$old_count 
        );
    }

    public static function date_range_tracks()
    {
        $playlistId = $_GET[ 'playlist_id' ];
        $startDate = $_GET[ 'start' ];
        $endDate = $_GET[ 'end' ];

        $tracks = array_filter(
            carbon_get_post_meta( $playlistId, 'tracks' ), 
            function ( $track ) use( $startDate, $endDate ) {
                return 
                    strtotime( explode( ' ', $track[ 'published_at' ] )[0] ) >= $startDate and
                    strtotime( explode( ' ', $track[ 'published_at' ] )[0] ) <= $endDate;
            }
        );

        ob_start();
        require plugin_dir_path( __FILE__ ) . '/templates/track-list.php';
        wp_send_json( [
            'data' => ob_get_clean(),
        ] );
    }

    /**
     * add thumbnail size for track poster
     */
    public static function thumbnail_size()
    {
        add_image_size( 'track-poster', 50, 50, true );
    }
}