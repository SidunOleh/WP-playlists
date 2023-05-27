<?php

defined( 'ABSPATH' ) or die;

/**
 * Plugin activator
 */
class SOVA_Playlists_Activator
{
    public static function activate()
    {
        self::required_plugins();
    }

    /**
     * Required plugins
     */
    private static function required_plugins()
    {
        $required = [
            'carbon-fields/carbon-fields-plugin.php',
            'post-types-and-taxs/post-types-and-taxs.php',
        ];
        $available = array_intersect( 
            get_option( 'active_plugins' ), 
            $required
        );
        if ( count(  $required ) != count( $available ) ) {
            $unavailable = array_diff(
                $required,
                get_option( 'active_plugins' ), 
            );

            die( 'Plugin requires ' . implode( ', ', $unavailable ) );
        }
    }
}