<?php
/**
 * @var SLN_Plugin          $plugin
 * @var SLN_Wrapper_Booking $booking
 */
$message =
    $booking->getFirstname() . ' ' . $booking->getLastname()
    .' '. __('has booked at','salon-booking-system') .' ' .$plugin->getSettings()->getSalonName() .' ' .__('on','salon-booking-system')
    . ' ' . $plugin->format()->date($booking->getDate()) 
    . ' ' .__('at','salon-booking-system').' ' . $plugin->format()->time($booking->getTime())
    . ' - '.__('BOOKING ID','salon-booking-system').' '.$booking->getId(). ' ' . __('| Timing: ','salon-booking-system') .' ';
foreach($booking->getBookingServices()->getItems() as $bookingService){
	$message .=  $bookingService->getStartsAt()->format( 'H:i' )
	.' '.  ($bookingService->getAttendant() ? $bookingService->getAttendant(
                        )->getTitle() : $bookingService->getService()->getTitle()).' ';
}
$message .= __('Price','salon-booking-system') .': '. $booking->getAmount();
if(strlen($message)>160){
	$more_string = __('...more details in the email confirmation','salon-booking-system');
	$message = substr($message, 0, ( 159 - strlen($more_string))).$more_string;
}
echo $message;
