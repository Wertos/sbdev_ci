<div class="row">

    <?php
    $no_torrents = TRUE;
	foreach ($categories as $cat) {


        ###enable cache        
        $list = CACHE()->get('sbdev_home_' . $cat->id);

        if (!$list) {
            $list = $this->browse->get_all_home($cat->id);

            // Save into the cache for 5 minutes
            CACHE()->save('sbdev_home_' . $cat->id, $list, CACHE_LIFE_TIME());
        }
        ###enable cache 
        
      
        ///skip if no torrent in this cat.
        if (!$list)
            continue;

        $no_torrents = FALSE;

        ?>

        <div class="col-xs-12 col-sm-4 col-lg-6">


            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h3 class="panel-title"><?= anchor($cat->url, $cat->name); ?><span class="pull-right"><?php if ($admin_mod): ?><span class="label label-primary"><?= $cat->count ?></span>  <?php endif; ?><a href="rss/<?= $cat->id ?>" target="_blank"><span style="color:#fe6601;" class="social-rss"></span></a></span></h3></div>
                <!-- List group -->
                <div class="list-group" id="catid_<?php echo $cat->id; ?>" data-start="0">
    <?php foreach ($list as $row): ?>
        <?php
        //$row->poster = "./img.php?src=".$row->poster;
        $metadata = '
                <ul class="list-inline home_metadata">
                                <li title="Размещён"><span class="glyphicon glyphicon-calendar"></span> ' . mysql_human($row->added, 'd/m/Y') . '</li>
                                <li title="Размер"><span class="glyphicon glyphicon-hdd"></span> ' . byte_format($row->size) . '</li>
                                <li title="Сидов" style="color: green;"><span class="glyphicon glyphicon-arrow-up"></span> ' . number_format($row->seeders) . '</li>
                                <li title="Личеров" style="color: red;"><span class="glyphicon glyphicon-arrow-down"></span> ' . number_format($row->leechers) . '</li>
                                <li title="Скачан" style="color: #255a6c;"><span class="glyphicon glyphicon-saved"></span> '.number_format($row->completed).'</li>    
                                <li title="Просмотров"><span class="glyphicon glyphicon-eye-open"></span> '.number_format($row->views).'</li>    
                    <li class="navbar-right">
                        <ul class="list-inline">
                            <li>' . ($row->comments > 0 ? number_format($row->comments) . ' <span class="glyphicon glyphicon-comment"></span>' : '') . '</li>
                        </ul>
                    </li>
                </ul>';
        ?>
                        <?= anchor('torrent/' . $row->id . ($row->url ? '-' . $row->url : ''), 
                       '<div class="poster-home" style="float:left;margin-right:3px;"><img data-html="true" title="<img width=\'100\' src=\''.$row->poster.'\' />" src="'.$row->poster.'" width="30" height="40" alt="" /></div><h5 class="list-group-item-heading" title="'.$row->name.'">' . character_limiter($row->name, 500) . '</h5>' . $metadata, array('class' => 'list-group-item', 'id' => 'torrent-'.$row->id)) ?>

                    <? endforeach; ?>
                </div>
            <div class="panel-footer">
		<?php echo get_pag($cat->id); ?>
            </div>
	  </div>
        </div>
        <?php } ?>
</div>

<?php
if ($no_torrents === TRUE) {
    echo heading('Внимание', '4');
    echo '<div class="alert alert-info" role="alert">На сайте нет торрентов! ' . ($logged_in ? anchor('torrent/add', 'Добавить?', array('class' => 'alert-link')) : '') . '</div>';
}