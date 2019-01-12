<?php
// Solo Framework table html editor
if ( ! defined( 'ABSPATH' ) ) exit;

class SLFTableGGAuth extends SLFTableComponent {

	public function __construct( $title, $name, $value, $data_source ) {
		parent::__construct( $title, $name, $value, null );
		$this->data_source = $data_source;
	}
	
    public function renderCell(){
		$html = __( 'Authorization is available only in the premium version', 'wbk' );
		$html .= '<br><a  rel="noopener"  href="https://1.envato.market/c/1297265/275988/4415?u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Fappointment-booking-for-wordpress-webba-booking%2F13843131" target="_blank">' . __( 'Upgrade now', 'wbk' ) . '</a>';

	    return $html;
    }
    public function renderControl(){      

		return '';
    }


}
