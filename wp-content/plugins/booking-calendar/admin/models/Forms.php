<?php
class wpdevart_bc_ModelForms {
	
  public function get_forms_rows() {
    global $wpdb;
    $limit = (isset($_POST['wpdevart_page']) && $_POST['wpdevart_page'])? (((int) $_POST['wpdevart_page'] - 1) * 20) : 0;
    $order_by = ((isset($_POST['order_by']) && $_POST['order_by'] != "") ? sanitize_sql_orderby($_POST['order_by']) :  'id');
	$order = ((isset($_POST['asc_desc']) && $_POST['asc_desc'] == 'asc') ? 'asc' : 'desc');
    $order_by = ' ORDER BY `' . $order_by . '` ' . $order;
    $where = ((isset($_POST['search_value']) && (sanitize_text_field($_POST['search_value']) != '')) ? 'WHERE title LIKE "%' . sanitize_text_field($_POST['search_value']) . '%"' : '');
	
    $query = "SELECT * FROM " . $wpdb->prefix . "wpdevart_forms " . $where . " ".$order_by." LIMIT " . $limit . ",20";
    $rows = $wpdb->get_results($query);
   
    return $rows;
  }	
  
  public function get_form_rows( $id ) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wpdevart_forms WHERE id="%d"', $id));
   
    return $row;
  } 

    /*############  Item navigation function ################*/
	
  public function items_nav() {
    global $wpdb;
    $where = ((isset($_POST['search_value']) && (sanitize_text_field($_POST['search_value']) != '')) ? 'WHERE title LIKE "%' . sanitize_text_field($_POST['search_value']) . '%"'  : '');
    $total = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."wpdevart_forms " .$where);
    $items_nav['total'] = $total;
    if (isset($_POST['wpdevart_page']) && $_POST['wpdevart_page']) {
      $limit = ((int)$_POST['wpdevart_page'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $items_nav['limit'] = (int)($limit / 20 + 1);
    return $items_nav;
  }
  
  public function check_exists( $form_id ) {
    global $wpdb;
	$exists = false;
    $rows = $wpdb->get_results('SELECT form_id FROM ' . $wpdb->prefix . 'wpdevart_calendars',ARRAY_A);
    foreach($rows as $row) {
		if(in_array($form_id,$row)){
			$exists = true;
			break;
		}
	}

    return $exists;
  }
  
}

?>