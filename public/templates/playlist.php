<?php defined( 'ABSPATH' ) or die ?>

<div class="playlist" id="playlist-<?php echo $playlist->ID ?>" data-playlist-id="<?php echo $playlist->ID ?>">

    <div class="playlist__top">
        
        <div class="playlist__title">
            <?php echo $playlist->post_title ?>
        </div>
        
        <?php if ( $enable_calendar ) : ?>
            <?php require plugin_dir_path( __FILE__ ) . '/calendar.php' ?>
        <?php endif ?>
    
    </div>

    <div class="playlist__tracks">
        <?php require plugin_dir_path( __FILE__ ) . '/track-list.php' ?>
    </div>

</div>

<script>
    const ajaxUrl = '<?php echo admin_url( 'admin-ajax.php' ) ?>'
    
    window.onload = e => {
        if ($('#playlist-<?php echo $playlist->ID ?>').length) sovaPlaylist({
            playlistId: <?php echo $playlist->ID ?>,
            playNext: <?php echo $play_next ? 'true' : 'false' ?>,
            calendar: <?php echo $enable_calendar ? 'true' : 'false' ?>,
        })
    }
</script>