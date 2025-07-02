<?php
header('Content-Type: application/rss+xml; charset=utf-8');
echo '<?xml version = "1.0" encoding = "utf-8"?>'?>
<rss version = "2.0">
<channel>
<title><?= $this->config->item('site_name'); ?></title>
<link><?=base_url(); ?></link>
<description><?= $this->config->item('site_descr'); ?></description>
<language>ru</language>
<?php foreach($feeds as $item):?>
<item>
    <title><?=$item['name']?></title>
    <link><?=base_url().'torrent/'.$item['id']?>-<?= $item['url']; ?></link>
    <description><![CDATA[<img align='left' vspace='5' hspace='10' src="<?= $item['poster']; ?>" class='left' />]]></description>
    <guid><?=base_url().'torrent/'.$item['id']?>-<?= $item['url']; ?></guid>
    <pubDate><?=mysql_human($item['added'], 'D, d M Y H:i:s O')?></pubDate>
</item>
<?php endforeach;?>
</channel>
</rss>