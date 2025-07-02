<?php if ($moderate): ?>
<div class="alert alert-danger" style="padding: 5px !important;">
	<b>Панель модерирования</b>
	<div class="details_mod_tools">
		<form>
		<?= $delete ?>
		<a href="javascript:void(0);" title="Заблокировать торрент" onclick="storeMod('block', 1); return false;"><i class="glyphicon glyphicon-ban-circle"></i></a>
		<a href="javascript:void(0);" title="Заблокировать для стран" class="dropdown-toggle" id="drmenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-globe"></i></a>
		<ul style="max-width: 400px;" class="dropdown-menu" aria-labelledby="drmenu1">
			<li style="padding: 10px;color: #000;">Введите 2х значный код страны в формате <b><a target="_blank" style="color: #2a6496;" href="https://ru.wikipedia.org/wiki/ISO_3166-1">ISO 3166-1</a></b>. Если стран несколько, введите через запятую.</li>
			<li role="separator" class="divider"></li>
			<li style="padding: 10px"><input title="Только английские заглавные буквы" pattern="[A-Z]{2,3}" style="text-transform: uppercase;" id="c_code" name="c_code" type="text" class="form-control" placeholder="Например - RU,EN,UK"></li>
			<li style="padding: 10px"><input onclick="storeMod('country', $('#c_code').val()); return false;" type="submit" class="btn btn-danger btn-xs" value="Применить"></li>
		</ul>
		</form>
	</div>
</div>
<?php endif; ?>            
<div class="panel panel-default <?php echo ($details->report == '1') ? 'report' : ''; ?>">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?php if ($edit): ?>
                    <?= $edit ?>
            <?php endif; ?>            
            <?php
	            if($logged_in) {
					$id    = $details->id;
					$name  = $this->security->get_csrf_token_name();
					$value = $this->input->cookie($this->config->item("csrf_cookie_name"));
					echo anchor('torrent/bookmarks/' . $details->id, "<span class='glyphicon glyphicon-bookmark'></span>", array('class' => $book_class, 'title' => $book_title, 'rel' => 'nofollow', 'id' => 'bokmark', 'onclick' => 'bookmark('.$id.', \''.$name.'\', \''.$value.'\'); return false;'));
              	}
            ?>
            <span class="t_title"><?= $details->name ?></span>
        </h3>
        <ul class="list-inline home_metadata">
            <li><span class="glyphicon glyphicon-calendar"></span> <?= $details->added ?></li>
            <li><span class="glyphicon glyphicon-hdd"></span> <?= $details->size ?></li>
            <li>
                <ul class="list-inline home_metadata" id="torrent_stats">
                    <li style="color: green;"><span class="glyphicon glyphicon-arrow-up"></span> <?= number_format($details->seeders) ?></li>
                    <li style="color: red;"><span class="glyphicon glyphicon-arrow-down"></span> <?= number_format($details->leechers) ?></li>
                    <li><span class="glyphicon glyphicon-glyphicon glyphicon-saved"></span> <?= number_format($details->completed) ?></li>
                    <?php if($logged_in): ?>
					  <li class="clickable" title="Обновить статистику"><span onclick="updatestats('<?= $details->id ?>')" id="updatestats"><span class="glyphicon glyphicon-refresh"></span> Обновить</span></li>
					<?php endif; ?>
                </ul>
            </li>
            <li class="navbar-right">
                <ul class="list-inline download_btn">
                    <?= ($details->file == 'yes' ? '<li>' . anchor('torrent/download/' . $details->id, "<span class='glyphicon glyphicon-download-alt down_icon'></span> Скачать .torrent", array('rel' => 'nofollow', 'class' => 'btn btn-success btn-xs')) . '</li>' : "") ?>
                    <?= ($details->magnet ? "<li><a class='btn btn-primary btn-xs' rel='nofollow' href='" . $details->magnet . "'><span class='glyphicon glyphicon-magnet down_icon'></span> Примагнититса</a></li>" : "") ?>
                    <li><button class="btn btn-danger btn-xs" onclick="report_box(<?= $details->id ?>, 'torrents')"><span class='glyphicon glyphicon-share down_icon'></span> Жалоба</button></li>
                </ul> 
            </li>
        </ul>
    </div>
    <div class="panel-body">

        <div class="poster">
            <?= $details->poster ?>
        </div>
        <?php
         	//$details->descr = preg_replace('~<a(.*?)href="(.*?)imdb\.com(.*?)>(.*?)<\/a>~is', '', $details->descr);
//         	$details->descr = preg_replace('/<a.*href=".*(FREEISLAND|HQCLUB|HQ-ViDEO|HELLYWOOD|ExKinoRay|NewStudio|LostFilm|RiperAM|Generalfilm|Files-x|NovaLan|Scarabey|New-Team|HD-NET|MediaClub|Baibako|CINEMANIA|Rulya74|WazZzuP|Ash61|egoleshik|Т-Хzona|BAGIRA|F-Torrents|Pshichko66|Занавес|msltel|Leo.pard|Zрения|BenderBEST|PskovLine|HDReactor|Temperest|Element-Team|Club|potroks|fox-torrents|HYPERHD|GORESEWAGE|Team|FireBit-Films|NNNB|Youtracker|marcury|Neofilm|Filmrus|Deadmauvlad|Torrent-Xzona|Brazzass|Кинорадиомагия|Assassin|GOLDBOY|ClubTorrent|AndreSweet|TORRENT-45|0ptimus|Torrange|NeoJet|Leonardo|Anything|group|Gersuzu|Xixidok|PEERATES|ivandubskoj|Roger|Fredd|Kruger|Киномагия|MixTorrent|RusTorents|Тorrent-Хzona|Best|KINOREAKTOR|ImperiaFilm|Jolly|Sheikn|Mobile-Men|KinoRay|HitWay|mcdangerous|Тorren|Stranik|Romych|Lebanon|Big111|Dizell|СИНЕМА-ГРУПП|PlanetaUA|Superdetki|potrokis|olegek70|bAGrat|Alekxandr48|Dzedyn|Fartuna|Best|DenisNN|Киномагии|UAGet|Victorious|KinoFiles|HQRips|F-Torrent|Star|Beeboop|Azazel|Leon-masl|Vikosol|RG Orient Extreme|TorrBy|x2008|Deadmauvlad|semiramida1970|Zelesk|CineLab|Сотник|ALGORITM|E76|datynet|leon030982|GORESEWAGE|Hot-Film|КинозалSAT|ENGINEER|CinemaClub|Zlofenix|pro100shara|FreeRutor|FreeHD|гаврила|vadi|SuperMin|GREEN TEA|Kerob|Generalfilm|DHT-Music|Витек|Twi7ter|KinoGadget|BitTracker|KURD28|Gears|KINONAVSE100|Just).*>(.*?)<\/a>/i', '', $details->descr);
//         	$details->descr = preg_replace('~\[(.*)\]\[\/(.*)\]~', '' ,$details->descr);
//        	$details->descr = preg_replace('~(<strong>)?(Автор релиза:|Релиз:|Релиз от:|Автор рипа:)(<\/strong>)?.*(<img src=\".*\">)?+~i', '', $details->descr);
        ?>

        <?= $details->descr; ?>

    </div>

    <table class="table table-striped table-bordered">

        <tr>
            <td class="rowhead">Автор</td>
            <td><span class="glyphicon glyphicon-user"></span> <?= link_user($details->owner, $details->username); ?></td>
        </tr>
        <tr>
            <td class="rowhead">Категория</td>
            <td><?= $category ?></td>
        </tr>
        <tr>
            <td class="rowhead">InfoHash</td>
            <td><?= strtoupper(bin2hex($details->info_hash)) ?></td>
        </tr>
        <tr>
            <td class="rowhead">Файлы (<?= $details->numfiles ?>)</td>
            <td><div id="<?= $details->id ?>" class="show-hide folded">Показать файлы</div><div id="file_table" class="show-hide-body"></div></td>
        </tr>   
        <tr>
            <td class="rowhead">Трекера</td>
            <td><div id="<?= $details->id ?>" class="show-hide folded">Показать трекера</div><div id="tracker_table" class="show-hide-body"></div></td>
        </tr>     

    </table>
</div>

<?= $related ?>


<?php if ($details->can_comment == 'yes'): ?>

    <div id="comm"> <!--To scroll pagination url -->
        <?= $commentform ?>
        <ul id="comments" class="list-unstyled"> <!-- do not change, used by ajax -->
            <?= $comments ?>
        </ul>
    </div>
    <?= $pagination ?>

<?php endif; ?>