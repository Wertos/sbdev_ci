<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Редактировать (<?= $cat->name ?>)</h3>
    </div>
    <div class='panel-body'>
        <?= $errors; ?>
        <?= form_open('admin/categories/edit/' . $cat->id); ?> 
        <div class='row'>
            <div class='col-sm-4'>
                <div class="form-group">
                    <label for="name">Название</label>
                    <?php echo form_input($name); ?>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class="form-group">
                    <label for="sort">В списке</label>
                    <?php echo form_input($sort); ?>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class="form-group">
                    <label for="url">Ссылка (латинские буквы)</label>
                    <?php echo form_input($url); ?>
                </div>
            </div>
        </div>

        <input type="submit" class="btn btn-default btn-xs" value="Редактировать" />

        <?= form_close(); ?>
    </div>   
</div>