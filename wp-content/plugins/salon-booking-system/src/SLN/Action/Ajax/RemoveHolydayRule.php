<?php 

class SLN_Action_Ajax_RemoveHolydayRule extends SLN_Action_Ajax_Abstract
{
	private $errors = array();

	public function execute()
	{
		if (!is_user_logged_in()) {
			return array( 'redirect' => wp_login_url());
		}

		

		if(current_user_can('manage_options')) {
			$data = array();
			$data['from_date']	= sanitize_text_field(wp_unslash($_POST['rule']['from_date']));
			$data['to_date']	= sanitize_text_field(wp_unslash($_POST['rule']['to_date']));
			$data['from_time']	= sanitize_text_field(wp_unslash($_POST['rule']['from_time']));
			$data['to_time']	= sanitize_text_field(wp_unslash($_POST['rule']['to_time']));
			$data['daily']		= true;
			$plugin = SLN_Plugin::getInstance();
			$settings = $plugin->getSettings();
			$holidays_rules = $settings->get('holidays_daily');
			$search_rule=array();

			foreach ($holidays_rules as $rule) {
				if(!(
					$data['from_date']	=== $rule['from_date'] &&
					$data['to_date']	=== $rule['to_date'] &&
					$data['from_time']	=== $rule['from_time'] &&
					$data['to_time']	=== $rule['to_time'] &&
					$rule['daily']		=== true 
				)) $search_rule[] = $rule;
			}								
			
			//$holidays_rules = SLN_Helper_HolidayItems::processSubmission($holidays_rules);
			$settings->set('holidays_daily',$search_rule);
			$settings->save();
		} else {
			$this->addError(__("You don't have permissions", 'salon-booking-system'));
		}

		if ($errors = $this->getErrors()) {
			$ret = compact('errors');
		} else {
			$ret = array('success' => 1,'rules'=>$search_rule);
		}

		return $ret;
	}

	protected function addError($err)
	{
		$this->errors[] = $err;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function removeCorruptedHoliday(){
		$plugin = SLN_Plugin::getInstance();
		$settings = $plugin->getSettings();
		$holidays_rules = $settings->get('holidays_daily');
		foreach ($holidays_rules as $index => $rule) {
			if(!empty($data['from_date']) && !empty($data['to_date']) && !empty($data['from_time']) && !empty($data['to_time']) ){}else{
				unset($holidays_rules[$index]);
			}
		}
		$settings->set('holidays_daily',$holidays_rules);
		$settings->save();
	}
}
