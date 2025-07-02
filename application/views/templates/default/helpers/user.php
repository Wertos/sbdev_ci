<div class="panel panel-default" style="margin-bottom: 0px;">
    <div class="panel-heading">
        <h3 class="panel-title">Пользователь: <?= $user->username ?></h3>
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-sm-3" align="center">

                <?= $user->userfile ?>

                <?= ($logged_in && $this->ion_auth->is_admin() && $user->id != $curuser->id ? anchor('admin/users/edit/' . $user->id, 'Редактировать') : '') ?>

            </div>

            <div class="col-sm-9">

                <ul class="list-unstyled user_list">

                    <li><span class="row_title">Группа</span><span class="row_data"><span class="glyphicon glyphicon-flag"></span> <?= $group->description ?></span></li>

                    <li><span class="row_title">Регистрация</span><span class="row_data"><span class="glyphicon glyphicon-calendar"></span> <?= $user->created_on ?></span></li>

                    <li><span class="row_title nobr">Дата посещения</span><span class="row_data"><span class="glyphicon glyphicon-calendar"></span> <?= $user->last_login ?> назад</span></li>

                    <li><span class="row_title">Страна</span><span class="row_data"><span class="glyphicon glyphicon-globe"></span> <?= $user->country ?></span></li>

                    <li><span class="row_title">Загрузил</span><span class="row_data"><span class="glyphicon glyphicon-upload"></span> <?= $user->torrents ?> <?= anchor('browse/user/' . $user->id, 'торрентов') ?></span></li>

                    <li><span class="row_title">Написал</span><span class="row_data"><span class="glyphicon glyphicon-comment"></span> <?= $user->comments ?> <?= ($admin_mod ? anchor('admin/comments/user/' . $user->id, 'комментариев') : 'комментариев') ?></span></li> 
                </ul>

            </div>

        </div>
    </div>
</div>