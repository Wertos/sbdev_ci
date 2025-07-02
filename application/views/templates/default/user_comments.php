
<?php
if ($comments):
    foreach ($comments as $row):
        ?>
        <div class="panel c_panel">
            <div class="panel-body">
                <span class="pull-right">
                    <button class="btn btn-default btn-xs" onclick="edit_comment_box('<?= $row->id ?>')">Править</button>
                </span>
                <?= heading(anchor('torrent/' . $row->fid . '-' . $row->url, $row->torrentname), 5) ?>
                <p class="small"><?php echo timespan($row->added, time()) ?> назад</p>
                <?php echo _bbcode($row->text) ?>
            </div>
        </div>
        <?php
    endforeach;
else:
    echo '<div class="alert alert-info" align="center">Нет комментариев</div>';
endif;
?>
<?= $pagination ?>