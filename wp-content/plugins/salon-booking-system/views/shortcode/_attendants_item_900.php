<div class="row sln-attendant">
    <div class="col-xs-12 col-sm-1 sln-radiobox sln-steps-check sln-attendant-check <?php $isChecked  ? 'is-checked' : '' ?>">
        <?php SLN_Form::fieldRadioboxForGroup($field, $field, $attendant->getId(), $isChecked, $settings) ?>
    </div>
    <div class="col-xs-8 col-sm-3 col-md-3 sln-steps-thumb sln-attendant-thumb">
        <label for="<?php echo $elemId ?>">
            <?php echo $thumb ?>
        </label>
    </div>
    <div class="col-xs-12 col-sm-8 col-md-8">
        <div class="row sln-steps-info sln-attendant-info">
            <div class="col-md-12">
                <label for="<?php echo $elemId ?>">
                    <h3 class="sln-steps-name sln-attendant-name"><?php echo $attendant->getName(); ?></h3>
                </label>
            </div>
        </div>
        <div class="row sln-steps-description sln-attendant-description">
            <div class="col-md-12">
                <label for="<?php echo $elemId ?>">
                    <p><?php echo $attendant->getContent() ?></p>
                </label>
            </div>
        </div>
    </div>
    <?php echo $tplErrors ?>
</div>
<div class="clearfix"></div>