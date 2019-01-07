<div class="sln-admin-sidebar mobile">
	<div class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--save sln-update-settings">
		<input type="submit" name="submit" id="submit" class="" value="Update Settings">
	</div>
	<div class="sln-toolbox">
		<button class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--tools sln-toolbox-trigger visible-md-inline-block visible-lg-inline-block">Tools </button>
		<a href="edit.php?post_type=sln_booking" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--booking">Manage bookings </a>
		<a href="admin.php?page=salon" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--calendar">Check calendar </a>
		<a href="edit.php?post_type=sln_attendant" class="sln-btn sln-btn--main sln-btn--big sln-btn--icon sln-icon--assistants">Active assistants </a>
	</div>
	<button class="sln-btn sln-btn--main sln-btn--small--round sln-btn--icon sln-icon--tools sln-toolbox-trigger-mob
	hidden-md hidden-lg">Tools </button>
	<div class="clearfix visible-xs-block"></div>
	<button class="sln-btn hidden-md hidden-lg sln-admin-banner--trigger"><?php echo __('Get Premium', 'salon-booking-system') ?></button>
	<div class="clearfix"></div>
	<div class="sln-admin-banner">
		<div class="sln-admin-banner-content">
			<div class="sln-admin-banner--closewrapper hidden-md hidden-lg">
				<button class="sln-btn sln-admin-banner--close">
					<span class="sr-only"><?php echo __('Close', 'salon-booking-system') ?></span>
				</button>
			</div>
			<h3><?php echo __('Accept online payments with PayPal, Stripes and other payment gateways.', 'salon-booking-system') ?></h3>
			<h2><?php echo __('Purchase the', 'salon-booking-system') ?> <strong><?php echo __('Premium Version', 'salon-booking-system') ?></strong> <?php echo __('for 69€/year.', 'salon-booking-system') ?></h2>
			<a href="https://www.salonbookingsystem.com/salon-booking-plugin-pricing/" class="sln-btn sln-btn--banner sln-btn--big" target="_blank"><?php echo __('Get Premium', 'salon-booking-system') ?></a>
			<div class="sln-banner-bottom">
				<div class="sln-banner-logowrapper">
					<a href="https://www.salonbookingsystem.com" target="_blank">
						<img class="sticky" src="<?php echo SLN_PLUGIN_URL . '/img/logo_bianco.png'; ?>" alt="Logo">
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>