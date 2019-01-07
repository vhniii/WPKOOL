<?php
/**
 * @var SLN_Plugin $plugin
 * @var string     $formAction
 * @var string     $submitName
 * @var SLN_Shortcode_Salon_ThankyouStep $step
 */
$confirmation = $plugin->getSettings()->get('confirmation');
$pendingPayment = false;
$payLater = false;
$currentStep = $step->getShortcode()->getCurrentStep();
$ajaxData = "sln_step_page=$currentStep&submit_$currentStep=1";
$ajaxEnabled = $plugin->getSettings()->isAjaxEnabled();

$paymentMethod =  false;

if (!$confirmation && $plugin->getBookingBuilder()->getLastBooking()->getAmount() == 0) {
	$pendingPayment = $payLater = $paymentMethod = false;
}

$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);

?>
<div id="salon-step-thankyou" class="row sln-thankyou">
<?php include '_salon_thankyou_'.$size.'.php'; ?>
</div>
