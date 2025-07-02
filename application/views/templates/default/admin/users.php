<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Список пользователей: (<?= $num_results ?>)</h3>
    </div>

    <div class="panel-body">
        <?= form_open('admin/users') ?>

        <div class="input-group">
            <?php echo form_input($usersearch); ?>
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            </span>
        </div>

        <?= form_close() ?>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <th>ID</th>
        <th style='width: 100%;'>Пользователь</th>
        <th class='nobr'>Email</th>
        <th>Регистрация</th>
        <th>Группа</th>
        <th>Действие</th>

        </thead>

        <?php if ($results): ?>

            <?php foreach ($results as $result): ?>
                <tr>
                    <td><?= $result->id ?></td>
                    <td><?= link_user($result->id, $result->username) ?></td>
                    <td><?= $result->email ?></td>
                    <td class='nobr'><?= mysql_human($result->created_on) ?></td>
                    <td><?php echo (isset($this->ion_auth->get_users_groups($result->id)->row()->description)) ? $this->ion_auth->get_users_groups($result->id)->row()->description : ''; ?></td>
                    <td align='center'>
                        <?= anchor('admin/users/edit/' . $result->id, '<span class="glyphicon glyphicon-pencil"></span>', array('title' => 'Редактировать', 'class' => 'btn btn-primary btn-xs')) ?>
                        <?= anchor('admin/users/delete/' . $result->id, '<span class="glyphicon glyphicon-trash"></span>', array('title' => 'Удалить', 'class' => 'btn btn-danger btn-xs delete_user')) ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="6" align="center">
                    <b>Ничего не найдено</b>
                </td>
            </tr>
        <?php endif; ?>
    </table>

</div>

<?= $pagination ?>