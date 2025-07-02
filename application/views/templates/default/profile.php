
<?php if ($message) { ?>
    <?php echo '<div class="alert alert-danger">' . $message . '</div>'; ?>
<?php } ?>

<?php echo form_open_multipart('user/profile', '', array('max_file_size' => '1002400')); ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Личный кабинет</h3>
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-sm-2" align="center">
		<a onclick="avatar_delete(<?php echo $id; ?>);" href="javascript:void(0);" id="avatar_del" style="position:absolute; top: 1px; right: 25px;"><span class="glyphicon glyphicon-remove" style="color:red;"></span></a>
                <?= $avatar ?>
            </div>
            <div class="col-sm-10">

                <div class="form-group">    
                    <label for="first">Аватар</label>
                    <?php echo form_input($userfile); ?>
                    <p class="help-block">Изоброжение (gif, jpg, png) Макс. размер <?= $this->config->item('avatar_resize_w') ?> x <?= $this->config->item('avatar_resize_h') ?> px (<?= $this->config->item('avatar_size') ?> Кб)</p>
                </div>

            </div>
        </div>

        <hr />

        <div class="form-group">    
            <label for="country">Страна</label>
            <?php echo $country; ?>
        </div>

        <div class="form-group">    
            <label for="email">Email</label>
            <?php echo form_input($email); ?>
        </div>

        <hr />

        <div class="row">
            <div class="col-sm-6">

                <div class="form-group">    
                    <label for="password">Пароль</label>
                    <?php echo form_input($password); ?>
                </div>

            </div>

            <div class="col-sm-6">

                <div class="form-group">    
                    <label for="pass_again">Подтвердить пароль</label>
                    <?php echo form_input($password_confirm); ?>
                </div>

            </div>

        </div>

    </div>
</div>

<input type="submit" name="submit" value="Сохранить" class="btn btn-default" />



<?php echo form_close(); ?>


