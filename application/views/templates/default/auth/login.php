
<?php if ($message) { ?>
    <div id="infoMessage" class="alert alert-danger"><?php echo $message; ?></div>
<?php } ?>



<div class="col-sm-6 col-md-offset-3">


    <?php echo form_open("auth/login"); ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">    
                <label for="username">Пользователь</label>
                <?php echo form_input($identity); ?>
            </div>

            <div class="form-group">    
                <label for="password">Пароль <?php echo anchor("auth/forgot_password", "(забыл пароль)", "class='bold'") ?></label>
                <?php echo form_input($password); ?>
            </div>

            <div class="form-group">
                <input type="submit" name="submit" value="Вход!" class="btn btn-primary btn-sm" />
                <label for="remember">Запомнить меня</label>
                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
            </div>
        </div>
        <div class="panel-footer">Нет аккаунта?<br /><?php echo anchor("auth/signup", "Регистрировать новый аккаунт сейчас") ?></div>
    </div>
    <?php echo form_close(); ?>


</div>