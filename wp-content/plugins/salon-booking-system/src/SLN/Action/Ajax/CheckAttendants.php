<?php

class SLN_Action_Ajax_CheckAttendants extends SLN_Action_Ajax_Abstract{
	const STATUS_ERROR = -1;
    const STATUS_UNCHECKED = 0;
    const STATUS_CHECKED = 1;

    /** @var  SLN_Wrapper_Booking_Builder */
    protected $bb;
    /** @var  SLN_Helper_Availability */
    protected $ah;

    protected $date;
    protected $time;
    protected $errors = array();

    public function execute(){
        $this->bb = $this->plugin->getBookingBuilder();
        $this->ah = $this->plugin->getAvailabilityHelper();
        $this->bindDate($_POST);
        $ret = array();

        $selected_service_id = !empty($_POST['_sln_booking_service_select']) ? intval($_POST['_sln_booking_service_select']) : false;
        $selected_service =  $selected_service_id ? new SLN_Wrapper_Service($selected_service_id) : false;
        $attendants_repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
        $attendants = $attendants_repo->getIds() ?: array();
        $services = isset($_POST['_sln_booking']) && is_array($_POST['_sln_booking']) && isset($_POST['_sln_booking']['service']) && is_array($_POST['_sln_booking']['service']) ? array_map('intval',$_POST['_sln_booking']['service']) : intval($_POST['_sln_booking']['service']) ;

        $date = $this->getDateTime();
        $this->ah->setDate($date, $this->plugin->createBooking(intval($_POST['post_ID'])));

        $data = array();
        $bookingData = $_POST['_sln_booking'];
        foreach ($services as $sId) {
            $data[$sId] = array(
                'service'        => $sId,
                'attendant'      => sanitize_text_field(wp_unslash($bookingData['attendants'][$sId])),
                'price'          => sanitize_text_field(wp_unslash($bookingData['price'][$sId])),
                'duration'       => SLN_Func::convertToHoursMins(sanitize_text_field(wp_unslash($bookingData['duration'][$sId]))),
                'break_duration' => SLN_Func::convertToHoursMins(sanitize_text_field(wp_unslash($bookingData['break_duration'][$sId]))),
            );
        }

        foreach ($attendants as $k => $attendant_id) {

            $attendant = new SLN_Wrapper_Attendant($attendant_id);

            if($selected_service){
                
                $attendantErrors = $this->ah->validateAttendantService($attendant, $selected_service);
        
            }

            if (empty($attendantErrors)){
                if($selected_service){
                    $attendantErrors = $this->ah->validateAttendant($attendant, $date,
                        $selected_service->getTotalDuration()
                    );
                }else{
                    $attendantErrors = $this->ah->validateAttendant($attendant, $date);
                }
            }

            $errors = array();
            if ( ! empty($attendantErrors)) {
                $errors[] = $attendantErrors[0];
            }

            $ret[$attendant_id] = array(
                'status'   => empty($errors) ? self::STATUS_CHECKED : self::STATUS_ERROR,
                'errors'   => $errors
            );
        }

        $ret = array(
            'success'  => 1,
            'attendants' => $ret,
        );

        return $ret;
    }

	private function bindDate($data)
    {
        if ( ! isset($this->date)) {
            if (isset($data['sln'])) {
                $this->date = sanitize_text_field($data['sln']['date']);
                $this->time = sanitize_text_field($data['sln']['time']);
            }
            if (isset($data['_sln_booking_date'])) {
                $this->date = sanitize_text_field($data['_sln_booking_date']);
                $this->time = sanitize_text_field($data['_sln_booking_time']);
            }
        }
    }
    
    protected function getDateTime()
    {
        $date = $this->date;
        $time = $this->time;
        $ret  = new SLN_DateTime(
            SLN_Func::filter($date, 'date').' '.SLN_Func::filter($time, 'time'.':00')
        );

        return $ret;
    }
}