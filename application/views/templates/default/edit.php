<?= $errors; ?>

<?php echo form_open('torrent/edit/' . $details->id); ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Редактировать торрент</h3>
    </div>

    <table class="table table-bordered">
        <tr>
            <td class="rowhead">
                Название
            </td>
            <td>
                <?php echo form_input($name); ?>
            </td>
        </tr>
        <tr>
            <td class="rowhead">
                Постер
            </td>
            <td>
                <?php echo form_input($poster); ?>
            </td>
        </tr>
        <tr>
            <td class="rowhead">
                Описание
            </td>
            <td>
                <?php echo bbeditor('descr', html_escape($details->descr, TRUE), '', 15) ?>
            </td>
        </tr>
        <tr>
            <td class="rowhead">
                Категория
            </td>
            <td>
                <?= $cat ?>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <div class="checkbox">
                    <label>
                        <?= $can_comment ?>
                        Разрешить комментарии к данному торренту?
                    </label>
                </div>
            </td>
        </tr>


    </table>

</div>

<?php echo form_submit('', 'Сохранить', 'class="btn btn-primary"'); ?>
<?php echo anchor('torrent/' . $details->id, 'Отмена', 'class="btn btn-danger"'); ?>
<?php echo form_close(); ?>