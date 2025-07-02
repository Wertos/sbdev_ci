<ul class="nav nav-tabs user_tabs" role="tablist">
    <li<?= ($this->uri->segment(3) === 'torrents' ? ' class="active"' : '') ?>><?= anchor('admin/reports', 'Торренты') ?></li>
    <li<?= ($this->uri->segment(3) === 'comments' ? ' class="active"' : '') ?>><?= anchor('admin/reports/comments', 'Комментарии') ?></li>
</ul>

<?php if ($this->uri->segment(3) === 'torrents'): ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Всего жалоб на торренты: <?= $count ?></h3>
        </div>

        <table class="table table-bordered">
            <thead>
            <th style='width: 100%;'>Торрент</th>
            <th>Прислал</th>
            <th>Проверил</th>
            <th>Действие</th>

            </thead>

            <?php if ($reports): ?>

                <?php foreach ($reports as $row): ?>
                    <tr<?= ($row->modded_by == 0 ? ' class="warning"' : '') ?>>
                        <td>
                            <p><?= anchor('torrent/' . $row->fid . '-' . $row->url, $row->torrentname) ?></p>
                            <p><b>Жалоба:</b> <?= $row->comment ?></p>
                        </td>
                        <td>
                            <p><?= ($row->sender != 0 ? link_user($row->sender, $row->sendername) : $row->ip) ?></p>
                            <span class="nobr"><?= mysql_human($row->added) ?></span>   
                        </td>
                        <td class='nobr'><?= ($row->modded_by != 0 ? link_user($row->modded_by, $row->mod_by) : '<i>Не проверено</i>') ?></td>
                        <td align='center'>
                            <?= anchor('admin/reports/mark/' . $row->id, '<span class="glyphicon glyphicon-ok"></span>', array('title' => 'Проверено', 'class' => 'btn btn-success btn-xs')) ?>
                            <?= ($this->ion_auth->is_admin() ? anchor('admin/reports/delete/' . $row->id, '<span class="glyphicon glyphicon-trash"></span>', array('title' => 'Удалить', 'class' => 'btn btn-danger btn-xs delete_user')) : '') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5" align="center">

                        <b>Нет жалоб на торренты!</b>

                    </td>

                </tr>

            <?php endif; ?>

        </table>

    </div>


<?php elseif ($this->uri->segment(3) === 'comments'): ?>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Всего жалоб на комментарии: <?= $count ?></h3>
        </div>

        <table class="table table-bordered">
            <thead>
            <th style='width: 100%;'>Комментарий</th>
            <th>Прислал</th>
            <th>Проверил</th>
            <th>Действие</th>

            </thead>

            <?php if ($reports): ?>

                <?php foreach ($reports as $row): ?>
                    <tr<?= ($row->modded_by == 0 ? ' class="warning"' : '') ?>>
                        <td>
                            <p><?= _bbcode($row->comment_text) ?></p>

                            <p><b>Жалоба:</b> <?= $row->comment ?></p>
                        </td>
                        <td>
                            <p><?= ($row->sender != 0 ? link_user($row->sender, $row->sendername) : $row->ip) ?></p>
                            <span class="nobr"><?= mysql_human($row->added) ?></span>   
                        </td>
                        <td class='nobr'><?= ($row->modded_by != 0 ? link_user($row->modded_by, $row->mod_by) : '<i>Не проверено</i>') ?></td>
                        <td align='center'>
                            <?= anchor('admin/reports/mark/' . $row->id . '/1', '<span class="glyphicon glyphicon-ok"></span>', array('title' => 'Проверено', 'class' => 'btn btn-success btn-xs')) ?>
                            <?= ($this->ion_auth->is_admin() ? anchor('admin/reports/delete/' . $row->id . '/1', '<span class="glyphicon glyphicon-trash"></span>', array('title' => 'Удалить', 'class' => 'btn btn-danger btn-xs delete_user')) : '') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5" align="center">

                        <b>Нет жалоб на комментарии!</b>

                    </td>

                </tr>

            <?php endif; ?>

        </table>

    </div>



<?php endif; ?>


<?= $pagination; ?>