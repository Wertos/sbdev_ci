<div id="report">
    <?= heading('Написать жалобу', 4) ?>
    <?= form_open('', array('id' => 'add_report_form'), array('id' => $id, 'location' => $location)) ?>
    <?php echo (validation_errors() ? '<div class="alert alert-danger">' . validation_errors() . '</div>' : ''); ?>
    <?php echo form_textarea(array('name' => 'report', 'class' => 'form-control', 'rows' => '6', 'value' => $value)); ?>
    <button type="submit" style="margin-top:10px;" class="btn btn-primary btn-xs">Отправить</button>
    <button data-bs-dismiss="modal" style="margin-top:10px;" class="btn btn-danger btn-xs">Отменить</button>
</div>