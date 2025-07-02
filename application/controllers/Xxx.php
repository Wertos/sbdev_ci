<?php

class Xxx extends CI_Controller
{
  function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->library(array('HtmlDom', 'parsing', 'image_lib'));
        $this->load->model('details_model');

  }
	
	public function parse($site = "xxx-tracker")
	{
		$page = 1;
		while ($page > -1) {
			$options = array(
  			'http'=>array(
			  'method'=>"GET",
  	  	'header'=> array("Accept-language: ru",
          	    "Cookie: 3iRzgF0BNefe1Q2AAw0000cy",
        	      "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36 OPR/34.0.2036.42",
  			        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*\/*;q=0.8",
  			        ),
  			)
			);
			$context = stream_context_create($options);
			$html = file_get_html('http://xxx-tracker.com/b.php?page='.$page, false, $context);
			$link = $html->find("a[href^=/torrent]");
			foreach($link as $href)
			{
			  unset($q,	$totallen,
			  			$ffa, $ffe, $fn, $ll, $ff,
			  			$id_parse, $t_id, $dict,
			  			$info, $type, $nl, $array,
			  			$id, $torrent_file, $magnet,
			  			$new_id, $file, $filelist,
			  			$poster, $torrent,
			  			$parsed_urls, $title, $bb_descr);
					
			preg_match("~\/torrent\/([0-9]{1,10})\/+~s", $href->href, $t_id);

		  	$body = file_get_contents("http://xxx-tracker.com".$href->href, false, $context);
	  		preg_match("~<h1[^>]*?>(.*?)<\/h1>~siu", $body, $title);

	  		$bb_descr = $this->rutor($body);
		  	preg_match("~\[img(=left|=right)?\]([^\]]+)\[\/img\]~siu", $bb_descr, $poster);

		  	$bb_descr = str_replace($poster[0], "", $bb_descr); 
		  	if(!$title[1]) continue;

        $new_id = $this->details_model->new_torrent_id();
        
        $config['upload_path'] = './public/upload/torrents/';
        $config['allowed_types'] = 'torrent';
        $config['max_size'] = '1048';
        $config['file_name'] = $new_id . '.torrent';
        $torrent_file = $this->config->item('public_folder').'upload/torrents/' . $new_id . '.torrent';

		  	file_put_contents($torrent_file, file_get_contents("http://xxx-tracker.com/download/".$t_id[1]));
		  	chmod($torrent_file, 0777);
		  	if(!file_exists($torrent_file))
		  	{
		  		continue;
		  	}
		  	$dict = bdecode(file_get_contents($torrent_file));

        if (!isset($dict)) {
        	continue;
        }
        $info = $dict['info'];
        list($dname, $pieces) = array($info['name'], $info['pieces']);
            if (isset($info['length']))
                $totallen = $info['length'];
            if (strlen($pieces) % 20 != 0) {
                continue;
            }

        $filelist = array();
        if (isset($totallen)) {
        	$filelist[] = array($dname, $totallen);
        	$type = "single";
        } else {
          $flist = $info['files'];
                if (!isset($flist)) {
                   continue;
                }
                if (!count($flist)) {
                	 continue;
                }
          $totallen = 0;
          foreach ($flist as $fn) {
            list($ll, $ff) = array($fn['length'], $fn['path']);
            $totallen += $ll;
            $ffa = array();
            foreach ($ff as $ffe) {
              $ffa[] = $ffe;
            }
            if (!count($ffa)) continue;
            $ffe = implode("/", $ffa);
            $filelist[] = array($ffe, $ll);
          }
          $type = "multi";
        }
        $dict = BDecode(BEncode($dict));
        $infohash = sha1(BEncode($dict['info']));
        $q = $this->db->select("id, name, url")
                    ->from("torrents")
                    ->where("info_hash", (function_exists('hex2bin') ? hex2bin($infohash) : pack('H*', $infohash)))
                    ->get()
                    ->row();
        if ($q) {
        	if (file_exists($torrent_file)) unlink($torrent_file);
        	continue;
        }

            if (!empty($dict['announce-list'])) {
                $parsed_urls = array();
                array_push($dict['announce-list'], ["http://torr.ws:2710/announce"], ["udp://torr.ws:2710/announce"]);
                foreach ($dict['announce-list'] as $al_url) {
                    $al_url[0] = trim($al_url[0]);
                    if ($al_url[0] == 'http://retracker.local/announce')
                        continue;
                    if (!preg_match('#^(udp|http)://#si', $al_url[0]))
                        continue;
                    if (in_array($al_url[0], $parsed_urls))
                        continue;
                    $url_array = parse_url($al_url[0]);
                    if (substr($url_array['host'], -6) == '.local')
                        continue;
                    $parsed_urls[] = $al_url[0];

                    $array = array(
                        'tid' => $new_id,
                        'info_hash' => $infohash,
                        'url' => $al_url[0]
                    );

                    $this->details_model->add_trackers($array);
                }
            }
        
        foreach ($filelist as $file) {
            $array = array(
                'torrent' => $new_id,
                'filename' => $file[0],
                'size' => $file[1]
            );
            $this->details_model->add_files($array);
        }
        $torrent = new Torrenting($torrent_file);
        $magnet = $torrent->magnet(); //generate magnet link
        $torrent->comment($this->config->item('site_name')); //set comment (sitename)
        $torrent->announce($this->config->item('base_url').'annonce'); // add a tracker
        $torrent->save($torrent_file);

        ### db insert torrent data array
        $array = array(
                'name' => $title[1],
                'descr' => $bb_descr,
                'poster' => $poster[2],
                'category' => 17,
                'owner' => 3,
                'file' => 'yes',
                'url' => title_url($title[1]),
                'added' => time(),
                'info_hash' => (function_exists('hex2bin') ? hex2bin($infohash) : pack('H*', $infohash)),
                'size' => $totallen,
                'numfiles' => count($filelist),
                'type' => $type,
                'magnet' => $magnet,
                'can_comment' => 'yes',
                'modded' => 'yes'
        );
            //var_dump($array);
            //inserting data and getting insert_id()
	        $id = $this->details_model->add_info($array);
		$this->load->helper('update');
#		updatestats($id);
				
		$this->db->delete('torrents_scrape', array('tid' => $id, 'state' => 'error'));
        
        $this->db->insert("parser", array("t_id"=>(int)$t_id[1], "site"=>"xxx-tracker"));
		  	
		  	echo $title[1]."  OK".PHP_EOL;

		  	//die();
			}
			$page--;
			echo("--------------------------".PHP_EOL);
		}
	}


	private function rutor($text)
	{
		preg_match_all ("#<table id=\"details\">(.*?)<tr><td></td><td>([\s\S]*?)</td></tr>#si", $text, $source, PREG_SET_ORDER);
		$text = $source[0][0];

		$text = preg_replace('/<br.*?>/', "", $text);
		$text = preg_replace('/<a href="\/tag\/.*?" target="_blank">([\s\S]*?)<\/a>/', '$1', $text);
		$text = preg_replace('/<div class="hidewrap"><div class="hidehead" onclick="hideshow.*?">([\s\S]*?)<\/div><div class="hidebody"><\/div><textarea class="hidearea">([\s\S]*?)<\/textarea><\/div>/', "[spoiler=\"\\1\"]\\2[/spoiler]", $text);

		$text = str_replace('<center>', '[align=center]', $text);
		$text = str_replace('</center>', '[/align]', $text);
		$text = str_replace('<hr />', '', $text);

		$text = str_replace('&#039;', "'", $text);
		$text = str_replace('&nbsp;', ' ', $text);
		$text = str_replace('&gt;', '>', $text);
		$text = str_replace('&lt;', '<', $text);

		for ($i=0; $i<=20; $i++)
		{
			$text = preg_replace('/<a href="([^<]*?)">([^<]*?)<(?=\/)\/a>/siu', '[url=$1]$2[/url]', $text);
			$text = preg_replace('/<img border=0 src="([^<]*?)" style="float:(.*?);">/siu', '[img=$2]$1[/img]', $text);
			$text = preg_replace('/<img border=0 src="([^<]*?)">/siu', '[img]$1[/img]', $text);
			$text = preg_replace('/<b>([^<]*?)<(?=\/)\/b>/', '[b]$1[/b]', $text);
			$text = preg_replace('/<u>([^<]*?)<(?=\/)\/u>/', '[u]$1[/u]', $text);
			$text = preg_replace('/<i>([^<]*?)<(?=\/)\/i>/', '[i]$1[/i]', $text);
			$text = preg_replace('/<s>([^<]*?)<(?=\/)\/s>/', '[s]$1[/s]', $text);
			$text = preg_replace('/<font size="([^<]*?)">([^<]*?)<(?=\/)\/font>/', "[size=\\1]\\2[/size]", $text);
			$text = preg_replace('/<span style="color:([^<]*?);">([^<]*?)<(?=\/)\/span>/', '[color=$1]$2[/color]', $text);
			$text = preg_replace('/<span style="font-family:([^<]*?);">([^<]*?)<(?=\/)\/span>/', '[font="$1"]$2[/font]', $text);
		}
		$text = strip_tags(html_entity_decode($text));

		return $text;
	}
 //	$subject = preg_replace("/(FREEISLAND|HQCLUB|HQ-ViDEO|HELLYWOOD|ExKinoRay|NewStudio|LostFilm|RiperAM|Generalfilm|Files-x|NovaLan|Scarabey|New-Team|HD-NET|MediaClub|Baibako|CINEMANIA|Rulya74|RG WazZzuP|Ash61|egoleshik|Т-Хzona|TORRENT - BAGIRA|F-Torrents|2LT_FS|Bagira|Pshichko66|Занавес|msltel|Leo.pard|Точка Zрения|BenderBEST|PskovLine|HDReactor|Temperest|Element-Team|BT-Club|Filmoff CLUB|HD Club|HDCLUB|potroks|fox-torrents|HYPERHD|GORESEWAGE|NoLimits-Team|New Team|FireBit-Films|NNNB|New-team|Youtracker|marcury|Neofilm|Filmrus|Deadmauvlad|Torrent-Xzona|Brazzass|Кинорадиомагия|Assassin&#039;s Creed|GOLDBOY|ClubTorrent|AndreSweet|TORRENT-45|0ptimus|Torrange|Sanjar &amp; NeoJet|Leonardo|BTT-TEAM и Anything-group|BTT-TEAM|Anything-group|Gersuzu|Xixidok|PEERATES|ivandubskoj|R. G. Jolly Roger|Fredd Kruger|Киномагия|RG MixTorrent|RusTorents|Тorrent-Хzona|R.G. Mega Best|Gold Cartoon KINOREAKTOR (Sheikn)|ImperiaFilm|RG Jolly Roger|Sheikn|R.G. Mobile-Men|KinoRay &amp; Sheikn|HitWay|mcdangerous|Тorren|Stranik 2.0|Romych|R.G. AVI|Lebanon|Big111|Dizell|СИНЕМА-ГРУПП|PlanetaUA|RG Superdetki|potrokis|olegek70|bAGrat|Alekxandr48|Mao Dzedyn|Fartuna|R.G.Mega Best|DenisNN|Киномагии|UAGet|Victorious|Gold Cartoon KINOREAKTOR|KINOREAKTOR|KinoFiles|HQRips|F-Torrent|A.Star|Beeboop|Azazel|Leon-masl|Vikosol|RG Orient Extreme|R.G.TorrBy|ale x2008|Deadmauvlad|semiramida1970|Zelesk|CineLab SoundMix|Сотник|ALGORITM|E76|datynet|Дяди Лёши| leon030982|GORESEWAGE|Hot-Film|КинозалSAT|ENGINEER|CinemaClub|Zlofenix|pro100shara|FreeRutor|FreeHD|гаврила|vadi|SuperMin|GREEN TEA|Kerob|AGR - Generalfilm|R.G. DHT-Music|Витек 78|Twi7ter|KinoGadget|BitTracker|KURD28|Gears Media|KINONAVSE100|Just TeMa)/si","ТВОЙ_ТРЕКЕР.РУ",$subject);
}
?>
