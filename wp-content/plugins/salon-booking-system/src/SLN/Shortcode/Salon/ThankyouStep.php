<?php

class SLN_Shortcode_Salon_ThankyouStep extends SLN_Shortcode_Salon_Step
{
    private $op;

    public function setOp($op)
    {
        $this->op = $op;
    }

    protected function dispatchForm()
    {
        $plugin = $this->getPlugin();
        $settings = $plugin->getSettings();
        $bb = $plugin->getBookingBuilder();
        if (isset($_GET['sln_booking_id']) && intval($_GET['sln_booking_id'])) {
            $bb->clear(intval($_GET['sln_booking_id']));
        }
        $booking = $bb->getLastBooking();
        $mode = sanitize_text_field(wp_unslash($_GET['mode']));
        $mode = isset($mode) ? $mode : null;
        if ($mode == 'confirm') {
            $this->goToThankyou();
        } elseif ($mode == 'later') {
            if(!$settings->get('confirmation')){
                    $booking->setStatus(SLN_Enum_BookingStatus::CONFIRMED);
            }
            $this->goToThankyou();
        }

        return false;
    }

    public function goToThankyou()
    {
        $id = $this->getPlugin()->getSettings()->getThankyouPageId();
        if ($id) {
            $this->redirect(get_permalink($id));
        }else{
            $this->redirect(home_url());
        }
    }

    public function getViewData()
    {
        $ret = parent::getViewData();
        $formAction = $ret['formAction'];

        return array_merge(
            $ret,
            array(
                'formAction' => $formAction,
                'booking' => $this->getPlugin()->getBookingBuilder()->getLastBooking(),
                'confirmUrl' => add_query_arg(
                    array('mode' => 'confirm', 'submit_'.$this->getStep() => 1),
                    $formAction
                ),
                'laterUrl' => add_query_arg(
                    array('mode' => 'later', 'submit_'.$this->getStep() => 1),
                    $formAction
                )
            )
        );
    }

    public function redirect($url)
    {
        if ($this->isAjax()) {
            throw new SLN_Action_Ajax_RedirectException($url);
        } else {
            wp_redirect($url);die();
        }
    }

    public function isAjax()
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }
}
