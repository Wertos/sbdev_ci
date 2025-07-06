<?= doctype('html5') ?>
<html lang="ru">
    <head>
        <base href="<?php echo base_url(); ?>">
        <title><?php echo $this->template->title->append(' - ' . $this->config->item('site_name')); ?></title>
        <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<?php echo $this->template->meta; ?>
	<!-- <link rel="alternate" type="application/rss+xml" title="Новые торренты <?php echo $this->config->item('site_name'); ?>" href="<?php echo base_url(); ?>rss.xml" />  -->
        <?php 
			/*        	
        	$css = $this->template->stylesheet; 
        	$css_file = $this->config->config['public_folder'].'assets/style.min.css';
            preg_match_all("/<link.*href=\"([^\"]*)\">/siU", $css, $match); 
			$this->load->helper('file');
			if(!file_exists($css_file) OR (time() - get_file_info($css_file)['date'] >= 600)) {
			    unlink($css_file);
    	       	foreach($match[1] as $file) {
					$data = fn_minify_css(file_get_contents($file), $comment = 0, $quote = 0);
					write_file($css_file, $data, 'a+');
				}
			}
			unset($match, $file);
			$js = $this->template->javascript; 
        	$js_file = $this->config->config['public_folder'].'assets/script.min.js';
            preg_match_all("/<script.*src=\"([^\"]*)\"><\/script>/siU", $js, $match); 
			if(!file_exists($js_file) OR (time() - get_file_info($js_file)['date'] >= 60)) {
			    unlink($js_file);
    	       	foreach($match[1] as $file) {
					$data = fn_minify_js(file_get_contents($file), $comment = 0, $quote = 0);
					write_file($js_file, $data, 'a+');
        		}
			}
            unset($match, $file);
            */
        ?>
	<?php echo $this->template->stylesheet; ?>
        <?php echo $this->template->javascript; ?>
<?php if (isset($this->_ci_cached_vars['details'])): ?>
		<meta property="og:locale" content="ru_RU" />
		<meta property="og:site_name" content="<?php echo $this->config->item('site_name'); ?>" />
		<meta property="og:title" content="<?php echo $this->_ci_cached_vars['details']->name; ?>" />
		<?php preg_match('/<img[^>]*?src=\"(.*)\"/iU', $this->_ci_cached_vars['details']->poster, $result);	?>
		<meta property="og:image" content="<?php echo isset($result[1]) ? $result[1] : ''; ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content= "<?php echo base_url().$this->_ci_cached_vars['details']->id.'-'.$this->_ci_cached_vars['details']->url; ?>" />    
<?php endif; ?>
    </head>

    <body>
		<div class="go-up" title="Вверх" id='ToTop'>⇧</div>
		<div class="go-down" title="Вниз" id='OnBottom'>⇩</div>
        <?php if(!$this->agent->is_robot()): ?>
        <?php endif; ?>
        <?php echo $this->template->ajax; ?>
        <div class="header">
            <?php echo anchor(base_url(), img('public/assets/pic/logo.png'), array('class' => 'main_logo')); ?>
            <?= form_open('browse/search', array('class' => 'main_search', 'id' => 'main_search')) ?>
            <div class="input-group" role="group" style="padding: 5px 0px 5px 0px;">
                <input placeholder="Введите текст для поиска" id="search-home" type="search" name="q" value="" class="form-control input-sm main_search">
                <div class="input-group-btn dropdown">
	 				<button data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width:auto;" class="btn btn-danger btn-sm dropdown-toggle" type="button" id="cats-sel"><div title="Можно указать категорию<br />для поиска или не указывать,<br />тогда поиск будет осуществляться<br />по всем категориям.">Категория <span class="caret"></span></div></button>
					<input type="hidden" value="0" id="cat-id" name="cat-id">	
						<ul class="dropdown-menu" aria-labelledby="dropdownMenuCat">
						    <?php
						        $cat_list = '<li><a onclick="$(\'#cats-sel div\').html(\'Категория <span class=caret></span>\'); $(\'#cat-id\').val(0);" href="javascript:void(0);" id="reset-cat"><span class="glyphicon glyphicon-remove" style="color:red;"></span> Сбросить</a></li>';
						    	foreach($categories as $cat) {
						    		$cat_list .= '<li><a onclick="$(\'#cats-sel div\').html($(this).text()+\' <span class=caret></span>\'); $(\'#cat-id\').val($(this).attr(\'id\'));" href="javascript:void(0);" id="'.$cat->id.'">'.$cat->name.'</a></li>';
						    	}
						    	echo $cat_list;
						    ?>
						</ul>                    
                    <button type="submit" id="id_submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span> Поиск</button>
                </div>
            </div>
            <?= form_close() ?>
			<div style="width: 50%; display: block; line-height: 0; margin: 0 auto; /*padding-top: 10px;*/">
               	<?php echo anchor('browse', '<i class="glyphicon glyphicon-floppy-save" style="color:darkblue"></i> Все раздачи', 'class="btn btn-default btn-xs"'); ?>
								<?php echo anchor('pages/rules', '<i class="glyphicon glyphicon-hand-right" style="color:red"></i> Правила', 'class="btn btn-default btn-xs"'); ?>
								<?php echo anchor('pages/secure', '<i class="glyphicon glyphicon-info-sign"></i> Информация', 'class="btn btn-danger btn-xs"'); ?>
								<?php //echo anchor('pages/what', '<i class="glyphicon glyphicon-ok"></i> Как и зачем', 'class="btn btn-default btn-xs"'); ?>
								<?php echo mailto($this->config->item('admin_email'), '<i class="social-e-mail-envelope" style="color:green"></i> Обратная связь', 'class="btn btn-default btn-xs" target="_blank"'); ?>
            </div>
    </div>
        <div class="bredcrumbs_panel">
            <div class="container">
                <?= $this->breadcrumb->output(); ?>
            </div>
        </div>
		<div class="body_c">
            <div class="container">
    			<div class="row">
                    <!-- Body -->
                    <div class="col-xs-12 col-sm-9">
                        <?php echo $this->template->info; ?>
                        <?php echo $this->template->content; ?>
                    </div>
                    <!-- Body -->
					
										<!-- Sidebar -->
                    <div class="col-xs-12 col-sm-9 col-sm-3">
                        <?php if ($logged_in): ?>
                            <?php $this->template->partial->view("widgets/user"); ?>
                        <?php else: ?>
                            <?php $this->template->partial->view("widgets/homelogin"); ?>
                        <?php endif;?>
                        <?php echo $this->template->partial->view("widgets/categories"); ?>
                        <?php echo $this->template->widget("stats"); ?>
                        <?php 
				if($logged_in)
					echo $this->template->widget("online");
			?>
                      </div>
                    <!-- Sidebar -->
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="container">
                <!-- Footer -->
                <div class="row small" id="footer">
                    <div class="col-xs-12 col-sm-4" style="text-align: center;">
                        <?php echo $this->template->powered; ?>
                    </div>
                    <div class="col-sx-12 col-sm-4" style="text-align: center;">

                    </div>
                    <div class="col-xs-12 col-sm-4" style="text-align: center;">
                        <?php echo $this->template->loadtime; ?>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>