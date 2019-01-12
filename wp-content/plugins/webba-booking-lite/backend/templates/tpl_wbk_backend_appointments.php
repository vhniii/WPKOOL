<!-- Webba Booking backend options page template --> 
<?php
    // check if accessed directly
    if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="wrap">
 	<h2 class="wbk_panel_title"><?php  echo __( 'Appointments', 'wbk' ); ?>
    <a style="text-decoration:none;" href="http://webba-booking.com/documentation/working-with-appointments" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
    </h2>
    <div class="notice notice-warning is-dismissible">
    <p>Please, note that Email notifications (except administrator's message options), PayPal, Stripe, Google Calendar, iCalendar, CSV export, WooCommerce integration and Coupons elements are for demo purpose only. To unlock notifications, payment and csv-export features, please, upgrade to Premium version. <a  rel="noopener"  href="https://1.envato.market/c/1297265/275988/4415?u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Fappointment-booking-for-wordpress-webba-booking%2F13843131" target="_blank">Upgrade now</a>. </p>
    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>

    </div>

        <?php        
            if( isset( $_GET['cancelled'] ) ){
                date_default_timezone_set( get_option( 'wbk_timezone', 'UTC' ) );
                $table = new WBK_Cancelled_Appointments_Table();
                $html = $table->render();
                echo $html;
                date_default_timezone_set( 'UTC' );                               
            }  else {
                date_default_timezone_set( get_option( 'wbk_timezone', 'UTC' ) );
                $table = new WBK_Appointments_Table();
                $html = $table->render();
                echo $html;
                date_default_timezone_set( 'UTC' );                
            }  
        ?>                                              
</div>
