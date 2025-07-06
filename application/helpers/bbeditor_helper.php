<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function bbeditor($name, $content = "", $width = "100%", $rows = 10) {
	$CI =& get_instance();
    ?>
    <script src="<?= base_url('public/assets/js/bb_code.js' . $CI->config->item('cssjsver')) ?>"></script>

    <div id="bb_kod"><textarea style="width: <?= $width ?>;" class="form-control" name="<?php echo $name; ?>" id="area" rows="<?= $rows ?>"><?php echo $content; ?></textarea></div>
    <?php
}