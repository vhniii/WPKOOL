<?php
//WBK stat class

// check if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'wp_ajax_wbk_hidehello', 'wbk_hidehello' );
function wbk_hidehello(){
	add_option( 'wbk_show_hello', 'false');
	update_option( 'wbk_show_hello', 'false');
	wp_die();
	return;
}
class WBK_Admin_Notices {




	public static function labelUpdate(){
	 	return;
	}	 
	public static function colorUpdate(){		
	 	return;
	}	
	public static function appearanceUpdate(){
		if ( get_option( 'wbk_appearance_saved', '' ) != 'true' ) { 
			return '<div class="notice notice-warning is-dismissible"><p>WEBBA Booking: Please setup appearance settings. 				 
					</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	  	} 
	 	return;
	}	
	public static function emailLandingUpdate(){
		if ( get_option( 'wbk_email_landing', '' ) == '' ) { 
			return '<div class="notice notice-warning is-dismissible"><p>WEBBA Booking: Please setup the <strong>Link to the page with Webba Booking form</strong> setting in the Email Notifications tab. 				 
					</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	  	} 
	 	return;
	}	
	public static function hello(){
		$installed = get_option( 'wbk_install_cn', '1522521164' );
		$diff = time() - $installed;
		if( $diff > 3600 ){
			return;
		}
		if ( get_option( 'wbk_show_hello', '' ) == '' ) { 
			return '<div class="notice wbk_hello notice-info is-dismissible">
					<h2>Thank you for installing Webba Booking!</h2>
					<p>
					To start accepting reservations quickly with Webba Booking, simply follow a few steps:

					<ul>
					<li style="font-weight:bold;">1. Go to <a href="'.get_admin_url().'admin.php?page=wbk-services">Services</a> page and create your first booking service</li>				 
					<li  style="font-weight:bold;">2. Place the [webba_booking] shortcode in any page or post</li>
					<ul>
					</p>	
					<p>	
					And you are ready to test!<br>
					It\'s just a start. After, of course, you can always refine the <a href="'.get_admin_url().'admin.php?page=wbk-options">settings</a>  available according to your needs.
					</p>				 
					<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
					<br>
					<a onclick="(function(){ var data = { \'action\': \'wbk_hidehello\' };jQuery.post( ajaxurl, data ); jQuery(\'.wbk_hello\').fadeOut(\'fast\') })()" class="button ml5 wbk-shedule-tools-btn button-primary" >OK, do not show this again</a>
					<a rel="noreferrer noopener" href="https://webba-booking.com/ecosystem/knowledge-base/" target="_blank" class="button ml5 wbk-shedule-tools-btn button-primary" >Where is documentaion?</a>
					<a rel="noreferrer noopener" href="https://wordpress.org/support/plugin/webba-booking-lite" target="_blank" class="button ml5 wbk-shedule-tools-btn button-primary" >Ask a question</a>


					</div>';

			}		
	}	

}

?>