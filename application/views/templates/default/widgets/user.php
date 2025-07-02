    <div class="panel sidebar">
        <div class="sidebar_title">
            Привет, <?= $curuser->username ?>
        </div>
        <div class="panel-body">
            <div align="center" style="margin-bottom: 7px;"><?= avatar($curuser->userfile) ?></div>

            <ul class="nav user_menu nav-stacked">
                <li><?php echo anchor("user/profile", '<span class="glyphicon glyphicon-cog user_menu_icon"></span>Профиль') ?></li>
                <li><?php echo anchor("user/torrents", '<span class="glyphicon glyphicon-list-alt user_menu_icon"></span>Мои раздачи') ?></li>
				        <li><?php echo anchor("user/bookmarks", '<span class="glyphicon glyphicon-bookmark user_menu_icon"></span>Мои закладки') ?></li>
                <li><?php echo anchor("user/comments", '<span class="glyphicon glyphicon-bullhorn user_menu_icon"></span>Мои комментарии') ?></li>
                <li><?php echo anchor("torrent/add", '<span class="glyphicon glyphicon-upload user_menu_icon"></span>Добавить торрент') ?></li>
                <li><?php echo anchor("user/logout", '<span class="glyphicon glyphicon-off user_menu_icon"></span>Выйти') ?></li>
                <?php
                if ($admin_mod):

                    $un_modded = $this->global_model->un_modded();
                    //var_dump($un_modded); die();
                    $count_unmodded = ($un_modded > 0 ? ' <span class="label label-danger">' . $un_modded . '</span>' : '');
                    ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" style="color: green;" href="#"><span class="glyphicon glyphicon-user user_menu_icon"></span>Мод<?= $count_unmodded ?> <span class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><?= anchor("admin/torrents/modded", 'На проверку' . $count_unmodded) ?></li>
                            <li><?= anchor('admin/comments', 'Комментарии') ?></li>
                            <li><?= anchor('admin/reports', 'Жалобы') ?></li>
                        </ul>
                    </li>
                <?php endif; ?>


                <?php if ($this->ion_auth->in_group(array('admin'))): ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" style="color: red;" href="#"><span class="glyphicon glyphicon-user user_menu_icon"></span>Админ <span class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><?php echo anchor("admin/categories", 'Категории') ?></li>
                            <li><?php echo anchor("admin/users", 'Пользователи') ?></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

