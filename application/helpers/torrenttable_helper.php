<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function torrenttable($query, $sort = FALSE, $sort_by = 'id', $sort_order = 'desc', $segment = 'sort') {

    $fields = array(
        'name' => 'Название',
        'added' => 'Добавлен',
        'size' => 'Размер',
        'seeders' => 'Сид',
        'leechers' => 'Лич'
    );
    
       
    ?>


    <?php if ($query): ?>

        <table class="table table-bordered torrentable table-striped table-hover">

            <thead>
                <?php foreach ($fields as $field_name => $field_display): ?>

                <th 
                <?php
                echo ($field_name == 'name' ? '' : 'style="text-align: center;"');
                echo ($field_name == 'added' ? ' class="nobr"' : '');

                if ($sort == TRUE) {
                    if ($sort_by == $field_name)
                        echo " id=\"sort_$sort_order\"";
                } else {
                    echo "";
                }
                ?>>
                        <?php echo ($sort == TRUE ? anchor("browse/$segment/$field_name/" . (($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc'), $field_display) : $field_display); ?>
                </th>

            <?php endforeach; ?>
        </thead>


        <?php foreach ($query as $post): ?>
            <tr>
                <td style="width: 100%;">
                    <a rel="nofollow" title="Magnet Link" href='<?php echo $post->magnet; ?>'>
                        <span class="glyphicon glyphicon-magnet"></span>
                    </a>
                    <?= anchor('torrent/' . $post->id . ($post->url ? '-' . $post->url : ''), $post->name) ?>
                    <?= ($post->comments > 0 ? '<span class="pull-right" title="Комментарии">' . $post->comments . ' <span class="glyphicon glyphicon-comment"></span></span>' : ''); ?>
                </td>
                <td align="center" class="nobr"><?php echo mysql_human($post->added, 'd/m/Y') ?></td>
                <td align="center" class="nobr"><?php echo byte_format($post->size) ?></td>
                <td align="center"><span style="color: green;"><?php echo $post->seeders; ?></span></td>
                <td align="center"><span style="color: red;"><?php echo $post->leechers; ?></span></td>
            </tr>
        <?php endforeach; ?>
        </table>


    <?php else: ?>
        <h5 class="text-center text-danger">Ничего не найдено</h5>
    <?php endif; ?>

    <?php
}