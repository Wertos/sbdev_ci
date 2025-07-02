<?php
die();
class Fixt extends CI_Controller
{
  function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->library(['parsing']);
		$this->load->helper('file');
  }
	
	public function readf()
	{
			die();
			$files = get_filenames($this->config->item('public_folder').'upload/torrents/');
  		foreach ($files as $file) {
  			$tid = (int) explode('.', $file)[0];
  			$torrent = new Torrenting($this->config->item('public_folder').'upload/torrents/'.$file);	
  			$info_hash = $torrent->hash_info();
  			if(is_array($torrent->announce())) {
					$ann = call_user_func_array('array_merge', $torrent->announce());
					foreach ($ann as $announce) {
						$insval[] = "($tid,'$info_hash','$announce',UNIX_TIMESTAMP() - 50000)";				
					}
					$this->db->query("INSERT INTO torrents_scrape (tid, info_hash, url, last_update) VALUES ".implode(',',$insval));
				} else {
					$insval[] = "($tid,'$info_hash','".$torrent->announce()."',UNIX_TIMESTAMP() - 50000)";				
					$this->db->query("INSERT INTO torrents_scrape (tid, info_hash, url, last_update) VALUES ".implode(',',$insval));
				}
				echo $tid.PHP_EOL;
				unset($tid, $ann, $insval, $torrent, $info_hash);
			}
  }
}
?>
