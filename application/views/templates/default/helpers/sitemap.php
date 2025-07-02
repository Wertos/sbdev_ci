<?php header('Content-type: text/xml'); ?>
<?= '<?xml version="1.0" encoding="UTF-8" ?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= base_url();?></loc> 
        <priority>1.0</priority>
    </url>
    <?php foreach($data as $row) { ?>
    <url>
        <loc><?= base_url('torrent/' . $row->id . '-' . $row->url) ?></loc>
        <priority>0.5</priority>
    </url>
    <?php } ?>
</urlset>