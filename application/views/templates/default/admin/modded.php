<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Торренты ожидающие проверку: <?= $num_items ?></h3>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <th style='width: 100%;'>Название</th>
        <th>Автор</th>
        <th>Добавлен</th>
        <th>Действие</th>

        </thead>

        <?php if ($items): ?>

            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= anchor('torrent/' . $item->id . '-' . $item->url, $item->name) ?></td>
                    <td><?= link_user($item->owner, $item->username) ?></td>
                    <td class='nobr'><?= timespan($item->added, time()) ?> назад</td>
                    <td align='center'>
                        <?= anchor('admin/torrents/modded/' . $item->id, '<span class="glyphicon glyphicon-ok"></span>', array('title' => 'Проверен', 'class' => 'btn btn-success btn-xs')) ?>
                        <?= anchor('admin/torrents/delete/' . $item->id, '<span class="glyphicon glyphicon-trash"></span>', array('title' => 'Удалить', 'class' => 'btn btn-danger btn-xs delete_user')) ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        <?php else: ?>

                <tr>
                    <td colspan="4" align="center">
                        
                        <b>Нет торрентов для проверки!</b>
                        
                    </td>
                    
                </tr>

        <?php endif; ?>

    </table>

</div>