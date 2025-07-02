<?= $errors; ?>

<?php echo form_open_multipart('torrent/add', '', array('max_file_size' => '1002400')) ?>

<div class="alert alert-info" role="alert">
  <p>При создании .torrent файла, необходимо указать один или несколько надёжных аннонсеров, например:<br />
	<span class="bold">
	http://87.248.186.252:8080/announce<br />
	udp://46.148.18.250:2710<br />
	udp://ipv6.leechers-paradise.org:6969<br />
	udp://torr.ws:2710/announce<br />
	http://torr.ws:2710/announce<br />
	</span>
  </p>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Добавить торрент</h3>
    </div>

    <table class="table table-bordered">
        <tr>
            <td class="rowhead">
                Торрент файл
            </td>
            <td>
               <label class="btn btn-default"> 
                <?php echo form_input($file); ?>
               </label>
            </td>
        </tr>
        <tr>
            <td class="rowhead">
                Название
            </td>
            <td>
                <?php echo form_input($name); ?>
				<span style="font-size: 11px;">Максимум 255 знаков - лишнее обрезается !</span><br />
				<span>Пример: <b>Название на русском / English name (год) HDRip</b></span>
            </td>
        </tr>
        <tr>
            <td class="rowhead">
                Постер
            </td>
            <td>
                <?php echo form_input($poster); ?>
				<span style="font-size:11px;">Ссылка на постер</span>
			</td>
        </tr>
        <tr>
            <td class="rowhead">
                Описание
            </td>
            <td>
                <?php echo bbeditor('descr', set_value('descr'), '', 15) ?>
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

<input type="submit" class="btn btn-default" name="submit" value="Загрузить" />

<?php echo form_close(); ?>