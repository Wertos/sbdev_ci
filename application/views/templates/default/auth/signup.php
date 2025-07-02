<script src='//www.google.com/recaptcha/api.js'></script>
<?php if ($message) { ?>
    <div id="infoMessage" class="alert alert-danger"><?php echo $message; ?></div>
<?php } ?>



<div class="col-sm-6 col-md-offset-3">


    <?php echo form_open("auth/signup"); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Регистрация</h3>
        </div>
        <div class="panel-body">

            <div class="form-group">    
                <label for="username">Имя пользователя</label>
                <?php echo form_input($username); ?>
                <span style="font-size:9px">Только русские и английские символы, знак тире и подчёркивания !</span>
            </div>

            <div class="form-group">    
                <label for="email">Email</label>
                <?php echo form_input($email); ?>
            </div>

            <div class="form-group">    
                <label for="country">Страна</label>
                <?php echo $country; ?>
            </div>

            <div class="form-group">    
                <label for="password">Пароль</label>
                <?php echo form_input($password); ?>
                <span style="font-size:9px">От 6 до 10 любых символов, кроме пробела !</span>
            </div>


            <div class="form-group">    
                <label for="password_again">Подтвердить пароль</label>
                <?php echo form_input($password_confirm); ?>
            </div>

            <div class="form-group">    
                <?php echo $captcha; ?>
            </div>
            
            <input type="submit" name="submit" value="Регистрация!" class="btn btn-primary btn-sm" />

        </div>
    </div>



    <?php echo form_close(); ?>


</div>