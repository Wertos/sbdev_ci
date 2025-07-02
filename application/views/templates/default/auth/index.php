<?php if ($message) { ?>
    <div id="infoMessage" class="alert alert-danger"><?php echo $message; ?></div>
<?php } ?>



<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Список пользователей</h3>
    </div>

    <table class="table table-hover">
        <tr>
<!--            <th><?php echo lang('index_fname_th'); ?></th>-->
            <th><?php echo lang('index_heading'); ?></th>
            <th><?php echo lang('index_email_th'); ?></th>
            
            <th>Регистрация</th>
            
            <th><?php echo lang('index_groups_th'); ?></th>
            <th><?php echo lang('index_status_th'); ?></th>
            <th><?php echo lang('index_action_th'); ?></th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <!--<td><?php echo $user->first_name; ?></td>-->
                <td><?php echo $user->username; ?></td>
                <td><?php echo $user->email; ?></td>
                <td><?php echo unix_to_human($user->created_on); ?></td>
                <td>
                    <?php foreach ($user->groups as $group): ?>
                        <?php echo anchor("auth/edit_group/" . $group->id, $group->name)." | "; ?>
                    <?php endforeach ?>
                </td>
                <td><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, lang('index_active_link')) : anchor("auth/activate/" . $user->id, lang('index_inactive_link')); ?></td>
                <td><?php echo anchor("admin/users/edit/" . $user->id, 'Edit'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>

<p><?php echo anchor('auth/create_user', 'Создать пользователя') ?> | <?php echo anchor('auth/create_group', 'Создать группу') ?></p>