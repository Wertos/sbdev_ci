<?php
	$identity = ['type'=>'text','name'=>'identity','id'=>'identity','value'=>'','class'=>'form-control'];                
	$password = ['type'=>'password','name'=>'password','id'=>'password','value'=>'','class'=>'form-control'];                
?>
<div class="panel sidebar">
    <div class="sidebar_title">
        Войти на сайт
    </div>
    <div class="panel-body">
    <?php echo form_open("auth/login"); ?>
            <div class="form-group">    
                <label for="identity">Пользователь</label>
                <?php echo form_input($identity); ?>
            </div>
            <div class="form-group">    
                <label for="password">Пароль <?php echo anchor("auth/forgot_password", "(забыл пароль)", "class='bold'") ?></label>
                <?php echo form_input($password); ?>
            </div>
            <div class="form-group btn-group btn-group-sm">
                <?php echo form_submit('submit', 'Вход', 'class="btn btn-primary"'); ?>
                <?php echo anchor('auth/signup', 'Регистрация', 'class="btn btn-default"'); ?>
            </div>
            <div class="form-group">
  	  				<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
	            <label for="remember">Запомнить меня</label>
  	  			</div>
    <?php echo form_close(); ?>
    </div>
</div>