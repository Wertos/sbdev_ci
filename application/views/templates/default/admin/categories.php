<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Список категорий</h3>
    </div>

    <div class='panel-body'>
        <?= $errors; ?>
        <?= form_open('admin/categories'); ?> 
        <div class='row'>
            <div class='col-sm-1'>
                <div class="form-group">
					<div class="dropdown">
						<label for="icon">Иконка</label>
						<input type="hidden" id="icon" name="icon" value="">
						<button class="btn btn-default dropdown-toggle" type="button" id="IconMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<span class="caret"></span>
						</button>
						<ul style="width: 365px;" class="dropdown-menu" aria-labelledby="IconMenu">
							<?php echo $select; ?>
						</ul>
					<script type="text/javascript">
					icon_sel = function(icon) {
						$("input#icon").val(icon);
						$("#IconMenu").html('<span class="glyphicon glyphicon-'+icon+'"></span>  <span class="caret"></span>')
					}
					</script>
					</div>
                </div>
            </div>
            <div class='col-sm-6'>
                <div class="form-group">
                    <label for="name">Название</label>
                    <?php echo form_input($name); ?>
                </div>
            </div>
            <div class='col-sm-2'>
                <div class="form-group">
                    <label for="sort">В списке</label>
                    <?php echo form_input($sort); ?>
                </div>
            </div>
            <div class='col-sm-3'>
                <div class="form-group">
                    <label for="url">Ссылка (латинские буквы)</label>
                    <?php echo form_input($url); ?>
                </div>
            </div>
        </div>

        <input type="submit" class="btn btn-default btn-xs" value="Добавить" />

        <?= form_close(); ?>
    </div>  

    <table class="table table-bordered table-striped">
        <thead>
        <th>ID</th>
        <th style='width: 100%;'>Название</th>
        <th class='nobr'>В списке</th>
        <th>Ссылка</th>
        <th>Действие</th>

        </thead>

        <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= $cat->id ?></td>
                <td><?= $cat->name ?></td>
                <td><?= $cat->sort ?></td>
                <td class='nobr'><?= $cat->url ?></td>
                <td align='center'>
                    <?= anchor('admin/categories/edit/' . $cat->id, '<span class="glyphicon glyphicon-pencil"></span>', array('title' => 'Редактировать', 'class' => 'btn btn-primary btn-xs')) ?>
                    <?= anchor('admin/categories/delete/' . $cat->id, '<span class="glyphicon glyphicon-trash"></span>', array('title' => 'Удалить', 'class' => 'btn btn-danger btn-xs delete_user')) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>

