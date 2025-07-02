<div class="panel sidebar">
    <div class="sidebar_title">
        Статистика трекера
    </div>
    <div class="panel-body">
        <ul class="list-unstyled" style="margin-bottom: 0px;">
            <?php if ($this->ion_auth->in_group(array('admin'))): ?>
            	<li>Всего торрентов: <span class="ratio_med quote_author"><?php echo $torrents ?></span></li>
            	<li>Объёмом: <span class="ratio_med quote_author"><?php echo $size ?></span></li>
            <?php endif; ?>
            <li>Сидов: <span class="seed_med quote_author"><?php echo $seeders ?></span></li>
            <li>Личеров: <span class="leech_med quote_author"><?php echo $leechers ?></span></li>
        </ul>
    </div>
</div>

