<?php if ($comments) { ?>


    <?= heading('Всего комментариев: ' . $count, 5) ?>

    <ul id="comments" class="list-unstyled">

        <?php foreach ($comments as $row) {
            ?>
            <li id="c-<?= $row->id ?>">
                <div class="panel c_panel">
                    <div class="panel-body">
                        <span class="pull-right">
                            <button class="btn btn-default btn-xs" onclick="edit_comment_box('<?= $row->id ?>')">Править</button>
                            <button class="btn btn-default btn-xs" onclick="delete_comment('<?=$row->id ?>')">Удалить</button>
                        </span>
                        <?= heading(anchor('torrent/' . $row->fid . '-' . $row->url, $row->torrentname), 5) ?>
                        <div class="row">
                            <div class="col-xs-1 avatar">
                                <?php echo avatar($row->userfile) ?>
                            </div>
                            <div class="col-xs-11">
                                <p>
                                    <span class="glyphicon glyphicon-user"></span> <?php echo link_user($row->user, $row->username) ?> (<?php echo timespan($row->added, time()) ?> назад)
                                </p>
                                <?php echo _bbcode($row->text) ?>
                                <?php echo ($row->editedby ? "<p align='right' class='small'>Последний редактировал " . link_user($row->editedby, $row->editedbyname) . "</p>" : ""); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <?php
        }
        ?>
    </ul>   
    <?php
} else {
    echo '<div class="alert alert-info" align="center">Нет комментариев</div>';
}

echo $pagination;