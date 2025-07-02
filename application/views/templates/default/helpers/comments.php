<?php
if ($comments) {

    foreach ($comments as $row) {
        ?>
        <li id="c-<?= $row->id ?>">
            <div class="panel c_panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-1 avatar">
                            <?php echo avatar($row->userfile) ?>
                        </div>
                        <div class="col-xs-11">
                            <p>
                                <span class="glyphicon glyphicon-user"></span> <?php echo link_user($row->user, $row->username) ?> (<?php echo timespan($row->added, time()) ?> назад)
                                <?php if ($logged_in) { ?>
                                <!-- action buttons -->
                                    <span class="pull-right" id="c_action">
                                        <?php $owner = ($curuser->id == $row->user ? TRUE : FALSE); ?>
                                        <?php echo ($owner == TRUE || $admin_mod ? '<button class="btn btn-default btn-xs" onclick="edit_comment_box(' . $row->id . ')">Править</button>' : ''); ?>
                                        <?php echo ($admin_mod ? '<button class="btn btn-default btn-xs" onclick="delete_comment(' . $row->id . ')">Удалить</button>' : '') ?>
                                        <?php echo ($curuser->id != $row->user ? "<button class=\"btn btn-default btn-xs\" onclick=\"report_box('" . $row->id . "', 'comments')\">Репорт</button>" : ""); ?>
                                        <button class="btn btn-default btn-xs" onclick="quote_comment('<?= $row->id ?>', '<?= $row->username ?>')">Цитата</button>
                                    </span>
                                <?php } ?>
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
} else {
    echo '<b><p class="no_comments text-center">Нет комментариев</p></b>';
}