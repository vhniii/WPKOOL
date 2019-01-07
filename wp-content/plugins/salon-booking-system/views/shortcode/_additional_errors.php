<?php if ($additional_errors): ?>
<div class="row sln-box--main">
    <div class="col-md-12">
        <?php foreach ($additional_errors as $error): ?>
            <div class="sln-alert sln-alert--problem sln-additional-error"><?php echo $error ?></div>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>