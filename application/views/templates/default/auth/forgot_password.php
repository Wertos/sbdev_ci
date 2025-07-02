<?php if ($message) { ?>
    <div id="infoMessage" class="alert alert-danger"><?php echo $message; ?></div>
<?php } ?>



<div class="col-sm-6 col-md-offset-3">


    <?php echo form_open("auth/forgot_password"); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Восстановить пароль</h3>
        </div>
        <div class="panel-body">

            <div class="form-group">    
                <label for="email"><?php echo sprintf(lang('forgot_password_email_label'), $identity_label); ?></label>
                <?php echo form_input($email); ?>
            </div>

            <input type="submit" name="submit" value="Восстановить" class="btn btn-primary btn-sm" />

        </div>
    </div>



    <?php echo form_close(); ?>


</div>