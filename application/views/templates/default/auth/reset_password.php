


<?php if ($message) { ?>
    <div id="infoMessage" class="alert alert-danger"><?php echo $message; ?></div>
<?php } ?>



<div class="col-sm-6 col-md-offset-3">


    <?php echo form_open('auth/reset_password/' . $code); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Восстановить пароль</h3>
        </div>
        <div class="panel-body">

            <div class="form-group">
                <label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length); ?></label>
                <?php echo form_input($new_password); ?>
            </div>

            <div class="form-group">
                <label for="new_password"><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm'); ?></label>
                <?php echo form_input($new_password_confirm); ?>
            </div>

            <?php echo form_input($user_id); ?>
            <?php echo form_hidden($csrf); ?>

            <input type="submit" name="submit" value="Восстановить" class="btn btn-default" />

        </div>
    </div>



    <?php echo form_close(); ?>


</div>


