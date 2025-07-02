<?php if ($message) { ?>
    <?php echo '<div class="alert alert-danger">' . $message . '</div>'; ?>
<?php } ?>


<?php echo form_open('admin/users/edit/' . $user->id); ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Редактировать профиль (<?= $user->username ?>)</h3>
    </div>
    <div class="panel-body">


        <div class="form-group">    
            <label for="username">Имя пользователя</label>
            <?php echo form_input($username); ?>
        </div>

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

        <hr />

        <label class="checkbox-inline">
            <?= $can_comment ?> Может комментировать?
        </label>
        <label class="checkbox-inline">
            <?= $can_upload ?> Может заливать?
        </label>
                <label class="checkbox-inline">
            <?= $del_avatar ?> Удалить аватар
        </label>

        <hr />

        <?= heading('Группа', 5) ?>
        <?php foreach ($groups as $group): ?>
            <div class="radio-inline">
                <label>
                    <?php
                    $gID = $group['id'];
                    $checked = null;
                    $item = null;
                    foreach ($currentGroups as $grp) {
                        if ($gID == $grp->id) {
                            $checked = ' checked="checked"';
                            break;
                        }
                    }
                    ?>
                    <input type="radio" name="groups[]" value="<?php echo $group['id']; ?>"<?php echo $checked; ?>>
                    <?php echo $group['description']; ?>
                </label>
            </div>
        <?php endforeach ?>


        <hr />

        <input type="submit" name="submit" value="Сохранить" class="btn btn-default btn-xs" />
        <?= anchor('admin/users/delete/' . $user->id, '<span class="glyphicon glyphicon-trash"></span> Удалить', array('class' => 'btn btn-danger btn-xs')) ?>

    </div>
</div>



<?php echo form_close(); ?>