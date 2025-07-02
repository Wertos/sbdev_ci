<?php

//phpinfo();
//$a = (float)"12.3.";
//print_r($a);
//die();
$i = 0;
$mysqli = new mysqli("127.0.0.1", "torr", "ruerotic.ru", "torr");

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$result = $mysqli->query("SELECT id, info_hash FROM torrents ORDER BY id DESC");
while($row = $result->fetch_array(MYSQLI_NUM))
{
	$data = $mysqli->query("SELECT url FROM torrents_scrape WHERE tid = ".$row[0]);
	$tr = $data->fetch_all();
	if(count($tr) == 0) {
		  $infohash = bin2hex($row[1]);//(function_exists('hex2bin') ? hex2bin($row[0]) : pack('H*', $row[0]));
		  echo $row[0].PHP_EOL;
		  $mysqli->query("INSERT INTO torrents_scrape (tid, info_hash, url, last_update) VALUES
						(".$row[0].", '".$infohash."', 'udp://torr.ws:2710/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'http://torr.ws:2710/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'http://87.248.186.252:8080/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://[2001:67c:28f8:92::1111:1]:2710', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://tracker.leechers-paradise.org:6969/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://46.148.18.250:2710', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://public.popcorn-tracker.org:6969/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'http://tracker.torrentyorg.pl/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://182.176.139.129:6969/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://tracker.tiny-vps.com:6969/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://ipv6.leechers-paradise.org:6969', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://tracker.opentrackr.org:1337/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://bt.megapeer.org:6969', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://zephir.monocul.us:6969', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://tracker.vanitycore.co:6969', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR))
		 ");
		  $mysqli->query("UPDATE torrents SET updated = 0 WHERE id = ".$row[0]);
	}
}
/*
$query = "SELECT tid, info_hash, url FROM torrents_scrape WHERE url = '' ORDER BY tid ASC";
$result = $mysqli->query($query);
while($row = $result->fetch_array(MYSQLI_NUM))
{
	$data = $mysqli->query("SELECT url FROM torrents_scrape WHERE tid = ".$row[0]);
	$tr = $data->fetch_all();

	if(count($tr) === 0) {
		$infohash = $row[1];//bin2hex($row[1]);//(function_exists('hex2bin') ? hex2bin($row[0]) : pack('H*', $row[0]));
		echo $row[0].PHP_EOL;
		  $mysqli->query("INSERT INTO torrents_scrape (tid, info_hash, url, last_update) VALUES
						(".$row[0].", '".$infohash."', 'udp://torr.ws:2710/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'http://torr.ws:2710/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'http://87.248.186.252:8080/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://[2001:67c:28f8:92::1111:1]:2710', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://tracker.leechers-paradise.org:6969/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://46.148.18.250:2710', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://public.popcorn-tracker.org:6969/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'http://tracker.torrentyorg.pl/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://182.176.139.129:6969/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://tracker.tiny-vps.com:6969/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://ipv6.leechers-paradise.org:6969', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://tracker.opentrackr.org:1337/announce', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://bt.megapeer.org:6969', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://zephir.monocul.us:6969', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR)),
						(".$row[0].", '".$infohash."', 'udp://tracker.vanitycore.co:6969', UNIX_TIMESTAMP(NOW() - INTERVAL 12 HOUR))
		 ");
//		print_r($mysqli->error);
		echo "Updated - ".$row[0].PHP_EOL;
	}
}
*/
$result->free();
$mysqli->close();
