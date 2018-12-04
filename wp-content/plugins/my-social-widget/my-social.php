<?php
/*Plugin Name: Easy Social Media Widget
Version: 3.0.0
Description: Displays a list of social media website icons and a link to your profile.
              Seo friendly and Added content delivery network (CDN) support
Author: Kamal
Author URI: https://profiles.wordpress.org/kamalbir786

*/
defined( 'ABSPATH' ) || exit;
define( 'MYSCW_MEDIA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if ( is_admin() ) {
	require_once( MYSCW_MEDIA_PLUGIN_DIR . 'my-social.php' );
}

/**
 * Adds My Social Media Widget
 */
class myscw_media_widget extends WP_Widget {
 var $myscw_media_icons = array( 
			'facebook' 	=> 'Facebook' , 
			'twitter' 	=> 'Twitter' , 
            'youtube-play'	=>'YouTube' ,
			'instagram'	=> 'Instagram' , 
             'linkedin'	=> 'LinkedIn' , 
			'google-plus'	=> 'Google' , 
			'flickr'	=>  'Flickr' , 
			'vimeo' 	=> 'Vimeo' , 
			'wordpress'	=> 'Wordpress' , 
			'whatsapp'	=> 'Whatsapp' , 
			'google-plus'	=> 'Google' , 
			'behance'	=> 'Behance' ,
             'dribbble' 	=> 'Dribbble', 
			'github' 	=> 'Github', 
			'apple'	=>'Apple' , 
			'pinterest-p'	=> 'Pinterest' , 
			'flickr'	=> 'Flickr' , 
			'slack'	=>  'Slack', 
			'skype' 	=>  'Skype' , 
			'snapchat' 	=> 'Snapchat' , 
			'spotify'	=>'Spotify' , 
			'soundcloud'	=>  'Soundcloud' , 
			'tumblr'	=> 'Tumblr' , 
			'quora' 	=> 'Quora' ,
			'etsy ' 	=> 'Etsy ' , 
			'telegram' 	=> 'Telegram' , 
			'windows'	=> 'Windows' ,
			'codepen' 	=> 'Codepen' ,
			'digg' 	=> 'Digg' , 
			'dropbox' 	=> 'dropbox' , 
            			
		);
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'my_social_widget', // Base ID
            'Easy Social widget', // Name
            array( 'description' => __( 'A Social  Widget', 'Displays your social media links in the simplest way' ), ) // Args
        );
    }
 
    /**
     * Front-end display of widget.
     *
     */
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
	    $fontsize = apply_filters( 'widget_title', $instance['fontsize'] );
		$iconcolor = apply_filters( 'widget_title', $instance['iconcolor'] );


		foreach( $this->myscw_media_icons as $s=>$n ) {
			$s = $instance[$s] ;
		}
       
        echo $before_widget;
        if ( ! empty( $title ) ) {
           echo $before_title .  sanitize_text_field( $title ) . $after_title;
		}
			echo '<div class="mysocialwid" style="font-size:'.$fontsize.'px;">';
			
			foreach( $this->myscw_media_icons as $s=>$l ) {
				if ( ! empty( $instance[$s] ) and (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $instance[$s])) ) {

				echo '<div class="mysocial"><a href="'.  esc_url($instance[$s]) . '" target="_blank" style="color: '.$iconcolor.';"><i class="fa fa-'.$s.'"></i></a></div>';}
			}
					
			echo '</div>';
			
        
       
        echo $after_widget;
    }
 
    /**
     * widget form.
     */
    public function form( $instance ) {
		 if(isset( $instance[ 'title' ])){
		 $title = esc_attr($instance[ 'title' ]);
		 }
		 else{
			 $title = '';
		 }
		 
		 if(isset( $instance[ 'fontsize' ])){
		 $fontsize = esc_attr($instance[ 'fontsize' ]);
		 }
		 else{
			 $fontsize = '14px';
		 }
		 
		 if(isset( $instance[ 'iconcolor' ])){
		 $iconcolor = esc_attr($instance[ 'iconcolor' ]);
		 }
		 else{
			 $iconcolor = '#000';
		 }
         $myscw_def = array_fill_keys( array_merge( array_keys( $this->myscw_media_icons ), array( 'title' ) ), null );
		$instance = wp_parse_args( (array)$instance, $myscw_def ); 

        ?>
		
		
        <p>
        <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
      <?php foreach($this->myscw_media_icons as $s => $n){ 
	  ?>
		<p>
        <label for="<?php echo $this->get_field_name( $s ); ?>"><?php echo $n ; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( $s ); ?>" name="<?php echo $this->get_field_name( $s ); ?>" type="text" value="<?php echo esc_attr( $instance[$s]); ?>" />
        </p>
		<?php
	  }
	  ?>
	  
	  <label for="<?php echo $this->get_field_name( 'settings' ); ?>"><?php _e( 'SETTINGS:' ); ?></label>
	  <span style="background-color: #f1f1f1;display: block;padding: 1px 30px;margin: 10px;" >
	  <p>
	  
	  <label for="<?php echo $this->get_field_name( 'fontsize' ); ?>"><?php _e( 'Font-size:' ); ?></label>
        <input class="tiny-text" id="<?php echo $this->get_field_id( 'fontsize' ); ?>" name="<?php echo $this->get_field_name( 'fontsize'); ?>" type="number" step="1" min="1"  size="3" value="<?php echo esc_attr( $fontsize); ?>" />
      </p>
	   <p>
	  <label for="<?php echo $this->get_field_name( 'iconcolor' ); ?>"><?php _e( 'Icon color:' ); ?></label>
	  <input type="color" id="<?php echo $this->get_field_id( 'iconcolor' ); ?>" name="<?php echo $this->get_field_name( 'iconcolor'); ?>" value="<?php echo esc_attr( $iconcolor); ?>" class="my-color-field" />
      </p>
	  </span>
	  <?php
	  
    }
 
    /**
     * Sanitize widget form values as they are saved.

     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
		foreach($this->myscw_media_icons as $s => $n){
						$instance[$s] = !empty( $new_instance[$s] ) ? esc_url( $new_instance[$s] ) : null;
		}
		$instance['fontsize'] = ( !empty( $new_instance['fontsize'] ) ) ? strip_tags( $new_instance['fontsize'] ) : '';
		
		$instance['iconcolor'] = ( !empty( $new_instance['iconcolor'] ) ) ? strip_tags( $new_instance['iconcolor'] ) : '';


		
		
        return $instance;
    }
 
} // class myscw_media_widget
add_action( 'widgets_init', function() { register_widget( 'myscw_media_widget' ); } );

function myscw_social_media_widget(){
$response = wp_remote_get('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
$response_code = wp_remote_retrieve_response_code( $response );
if(empty($response_code)){
		wp_enqueue_style('font-awesome', plugin_dir_url( __FILE__ ).'css/fontawesome/css/font-awesome.min.css' );
}
else{
	
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0' );
}
		wp_enqueue_style('myscw_social_css', plugin_dir_url( __FILE__ ) .'css/my-social.css' );

}
add_action('wp_enqueue_scripts','myscw_social_media_widget');