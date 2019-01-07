<div class="row sln-service sln-service--<?php echo $service->getId(); ?>">
    <div class="col-xs-2 col-sm-1 sln-checkbox sln-steps-check sln-service-check <?php echo  $bb->hasService($service) ? 'is-checked' : '' ?>">
        <?php SLN_Form::fieldCheckbox('sln[services]['.$service->getId().']', $bb->hasService($service), $settings) ?>
        <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>"></label>
        <!-- .sln-service-check // END -->
    </div>
    <div class="col-xs-10 col-sm-11 col-md-10">
        <div class="row sln-steps-info sln-service-info">
            <div class="col-sm-9 col-md-9">
                <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>">
                    <h3 class="sln-steps-name sln-service-name"><?php echo $service->getName(); ?></h3>
                </label>
                <!-- .sln-service-info // END -->
            </div>

<?php if($showPrices): ?>
            <h3 class="col-sm-3 col-md-3  sln-steps-price  sln-service-price">
                <?php echo $plugin->format()->moneyFormatted($service->getPrice())?>
                <!-- .sln-service-price // END -->
            </h3>
<?php endif ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row sln-steps-description sln-service-description">
            <div class="col-md-12"><hr></div>
            <div class="col-sm-1 col-md-1 hidden-xs hidden-sm">&nbsp;</div>
            <div class="col-md-9">
                <label for="<?php echo SLN_Form::makeID('sln[services][' . $service->getId() . ']') ?>">
                    <p><?php echo $service->getContent() ?></p>
                    <?php if ($service->getDuration()->format('H:i') != '00:00'): ?>
                        <span class="sln-steps-duration sln-service-duration"><small><?php echo __('Duration', 'salon-booking-system')?>:</small> <?php echo $service->getDuration()->format(
                                'H:i'
                            ) ?></span>
                    <?php endif ?>
                </label>
                <!-- .sln-service-info // END -->
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-11">
                    <span class="errors-area" data-class="sln-alert sln-alert-medium sln-alert--problem">
                    <?php if ($serviceErrors) foreach ($serviceErrors as $error): ?>
                        <div class="sln-alert sln-alert-medium sln-alert--problem"><?php echo $error ?></div>
                    <?php endforeach ?>
                        <div class="sln-alert sln-alert-medium sln-alert--problem" style="display: none" id="availabilityerror"><?php _e('Not enough time for this service','salon-booking-system') ?></div>
                    </span>
            </div>
        </div>
    </div>
</div>
