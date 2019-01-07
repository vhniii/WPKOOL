<?php
class wpdevart_bc_ControllerThemes {
	private $model;	
	private $view;	
	  
	public function __construct() {
		require_once(WPDEVART_PLUGIN_DIR . "/admin/models/Themes.php");
		$this->model = new wpdevart_bc_ModelThemes();
		require_once(WPDEVART_PLUGIN_DIR . "/admin/views/Themes.php");
		$this->view = new wpdevart_bc_ViewThemes($this->model);
	}  	
		
	public function perform() {
		$task = wpdevart_bc_Library::get_value('task');
		$id = wpdevart_bc_Library::get_value('id', 0);
		if (method_exists($this, $task)) {
		  $this->$task($id);
		}
		else {
		  $this->display_themes();
		}
	}
	  
	  
	private function display_themes($error_msg="",$delete=true){
		$this->view->display_themes($error_msg,$delete);
	}  
	  
	private function add(){
		$this->view->edit_setting();
	}
	  
	private function edit( $id ){
		$this->view->edit_setting( $id );
	}
	  
	private function save( $id ){
		global $wpdb; 
		$textareas = array('custom_css','notify_admin_on_book_content','notify_admin_on_approved_content','notify_admin_paypal_content','notify_user_on_book_content','notify_user_on_approved_content','notify_user_canceled_content','notify_user_deleted_content','notify_user_paypal_content','notify_user_paypal_failed_content');
		
		$saved_parametrs = wpdevart_bc_Library::sanitizeAllPost($_POST,$textareas);
		
		$data_json = json_encode($saved_parametrs);
		$title = wpdevart_bc_Library::getData($_POST, 'title', 'text', '');
		if ($id != 0) {
		  $save = $wpdb->update($wpdb->prefix . 'wpdevart_themes', array(
			'title' => $title,
			'value' => $data_json,
		  ), array('id' => $id));
		}
		else {
		  $save = $wpdb->insert($wpdb->prefix . 'wpdevart_themes', array(
			'title' => $title,                       
			'value' => $data_json,         
		  ), array(
			'%s',
			'%s',
		  ));
		  $id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wpdevart_themes');
		}
		if(isset($_POST["button_action"]) && $_POST["button_action"] == "apply") {
			$this->edit($id);
		}
		else {
			$this->display_themes();
		}
		
	}
	  
	private function delete( $id ){
		global $wpdb; 
		$error_msg = "";
		$delete = true;
		$exists = $this->model->check_exists( $id );
		if($exists === false) {
			$del_query = $wpdb->query($wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'wpdevart_themes WHERE id="%d"',$id ));
			if($del_query) {
				$error_msg = "Item succesfully deleted.";
			}
		} else {
			$error_msg = "You can't delete theme which in use";
			$delete = false;
		}
		$this->display_themes($error_msg,$delete);
	}
	  
	private function delete_selected(){
		global $wpdb; 
		$error_msg = "";
		$delete = true;
		$check_for_action = (isset($_POST['check_for_action']) ? ( $_POST['check_for_action']) : '');
		foreach($check_for_action as $check){
			$exists = $this->model->check_exists( $check );
			if($exists === false) {
				$del_query = $wpdb->query($wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'wpdevart_themes WHERE id="%d"',$check ));
				if($del_query) {
					$error_msg = "Items succesfully deleted.";
				}
			} else {
				$error_msg = "You can't delete form which in use";
				$delete = false;
			}
		}
		$this->display_themes($error_msg,$delete);
	}
 
 
  
}

?>