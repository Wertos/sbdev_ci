<?= heading('Добавить комментарий', 5) ?>

<?= form_open('', array('id' => 'add_comment_form'), array('id' => $id)) ?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= bbeditor("text", '', '', 5) ?>
    </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-primary btn-sm">Добавить</button>
        <button class="btn btn-default btn-sm" onclick="clear_textbox(); return false;">Очистить</button>
    </div>
</div>

<?= form_close() ?>