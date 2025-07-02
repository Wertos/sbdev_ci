<?php if ($rows): ?>
<?= heading('Похожие раздачи', 5) ?>
    <table class="table table-bordered torrentable table-striped table-hover" style="margin-bottom: 15px;">
        <thead>
            <tr>
                <th>Название</th>
                <th class="nobr" style="text-align: center;">Добавлен</th>
                <th style="text-align: center;">Размер</th>
                <th style="text-align: center;">Сид</th>
                <th style="text-align: center;">Лич</th>
            </tr>
        </thead>

        <?php foreach ($rows as $row): ?>
            <tr>
                <td style="width: 100%;">
                    <?= anchor('torrent/' . $row->id . ($row->url ? '-' . $row->url : ''), $row->name) ?>
                    <?= ($row->comments > 0 ? '<span class="pull-right" title="Комментарии">' . number_format($row->comments) . ' <span class="glyphicon glyphicon-comment"></span></span>' : ''); ?>
                </td>
                <td align="center" class="nobr"><?php echo mysql_human($row->added, 'd/m/Y') ?></td>
                <td align="center" class="nobr"><?php echo byte_format($row->size) ?></td>
                <td align="center"><span style="color: green;"><?php echo number_format($row->seeders); ?></span></td>
                <td align="center"><span style="color: red;"><?php echo number_format($row->leechers); ?></span></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>