<div class="calendar-section" id="calendar-<?php echo $playlist->ID ?>">

    <div class="calendar-btn">
        <img src="<?php echo plugin_dir_url(  dirname( __FILE__ ) ) ?>img/calendar.svg" alt="">
        <span><?php _e( 'Last 7 Days' ) ?></span>
        
        <div class="calendar-body">
            <div class="calendar-body__top">
                <div class="calendar-body__toptext">
                    <?php _e( 'Select Date Range' ) ?>
                </div>
                <div class="calendar-body__selected">
                    <?php _e( 'Last 7 Days' ) ?>
                </div>
            </div>
            <div class="calendar-body__content">
                <div class="calendar-body__ranges">
                    <div 
                        data-start="<?php echo time() - 7 * DAY_IN_SECONDS ?>" 
                        data-end="<?php echo time() ?>" 
                        class="calendar-body__range selected">
                        <?php _e( 'Last 7 days' ) ?>
                    </div>
                    <div 
                        data-start="<?php echo time() - MONTH_IN_SECONDS ?>" 
                        data-end="<?php echo time() ?>"  
                        class="calendar-body__range">
                        <?php _e( 'Last 30 days' ) ?>
                    </div>
                    <div 
                        data-start="<?php echo time() - 3 * MONTH_IN_SECONDS ?>" 
                        data-end="<?php echo time() ?>"  
                        class="calendar-body__range">
                        <?php _e( 'Last 3 months' ) ?>
                    </div>
                    <div 
                        data-start="<?php echo time() - YEAR_IN_SECONDS ?>" 
                        data-end="<?php echo time() ?>" 
                        class="calendar-body__range">
                        <?php _e( 'Last 12 months' ) ?>
                    </div>
                    <div 
                        data-start="<?php echo time() - 100 * YEAR_IN_SECONDS ?>" 
                        data-end="<?php echo time() ?>" 
                        class="calendar-body__range">
                        <?php _e( 'All time' ) ?>
                    </div>
                    <div 
                        data-start="" 
                        data-end="" 
                        class="calendar-body__range custom">
                        <?php _e( 'Custom' ) ?>
                    </div>
                </div>
                <div class="calendar-body__calendar calendar">
                    <div class="calendar__top">
                        <img class="calendar__prev" src="<?php echo plugin_dir_url(  dirname( __FILE__ ) ) ?>img/prev.svg" alt="">
                        <span class="calendar__month">
                            <?php echo date('M') ?>
                        </span>
                        <span class="calendar__year">
                            <?php echo date('Y') ?>
                        </span>
                        <img class="calendar__next" src="<?php echo plugin_dir_url(  dirname( __FILE__ ) ) ?>img/next.svg" alt="">
                    </div>
                    <div class="calendar__week">
                        <span><?php _e( 'Su' ) ?></span>
                        <span><?php _e( 'Mo' ) ?></span>
                        <span><?php _e( 'Tu' ) ?></span>
                        <span><?php _e( 'We' ) ?></span>
                        <span><?php _e( 'Th' ) ?></span>
                        <span><?php _e( 'Fr' ) ?></span>
                        <span><?php _e( 'Sa' ) ?></span>
                    </div>
                    <div class="calendar__days">
                    </div>
                </div>
            </div>
            <div class="calendar-body__bottom">
                <div class="calendar-body__btn calendar-body__btn-cancel">
                    <?php _e( 'Cancel' ) ?>
                </div>
                <div class="calendar-body__btn calendar-body__btn-apply">
                    <?php _e( 'Apply' ) ?>
                </div>
            </div>
        </div>
        
    </div>

</div>