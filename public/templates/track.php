<?php defined( 'ABSPATH' ) or die ?>

<div class="track" data-track-id="<?php echo $track_id ?>">
    <audio src="<?php echo wp_get_attachment_url( $track[ 'file' ] ) ?>" class="track__audio" preload="metadata">
    </audio>

    <div class="track__img">
        <img src="<?php echo wp_get_attachment_image_url( $track[ 'poster' ], 'track-poster' ) ?>" alt="">
    </div>
    
    <div class="track__right">
        <div class="track__inf">
            <div class="track__title">
                <?php echo $track[ 'title' ] ?>
            </div>
            <div class="track__performer">
                <?php echo $track[ 'performer' ] ?>
            </div>
        </div>
		
		<?php if ( $track[ 'published_at' ] ) : ?>
			<div class="track__date">
				<?php echo date( 'd M Y g:i a', strtotime( $track[ 'published_at' ] ) ) ?>
			</div>
		<?php endif ?>
        
        <div class="track__like">
            <img class="like-img" src="<?php echo plugin_dir_url(  dirname( __FILE__ ) ) ?>img/like.svg" alt="">
            <img class="liked-img" src="<?php echo plugin_dir_url(  dirname( __FILE__ ) ) ?>img/liked.svg" alt=""> 
            <span class="count"><?php echo $track[ 'like_count' ] ?></span>
        </div>
        
        <div class="track__time">
            <?php
            $time = wp_get_attachment_metadata( $track[ 'file' ] )[ 'length_formatted' ];       
            echo $time;
            ?>
        </div>
    </div>
    
    <div class="play">

        <div class="play__left">
            <div class="play__title">
                <?php echo $track[ 'title' ] ?>
            </div>
    
            <div class="play__progressbar">
                <div class="progress">
                </div>
                <div class="rewind-progress">
                </div>
            </div>
            
            <div class="play__times">
                <div class="play__currenttime">
                    0:00
                </div>
                <div class="play__totaltime">
                    <?php echo $time ?>
                </div>
            </div>
        </div>

        <div class="play__volume">
            <img class="on" src="<?php echo plugin_dir_url(  dirname( __FILE__ ) ) ?>img/volume.svg" alt="">
            <img class="off" src="<?php echo plugin_dir_url(  dirname( __FILE__ ) ) ?>img/volume-off.svg" alt="">
            <input type="range" min="0" max="10" value="10">
        </div> 
    </div>

</div>