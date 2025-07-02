<div class="panel sidebar">
    <div class="sidebar_title">
        Категории
    </div>
    <div class="list-group">
        <?php
        foreach ($categories as $cat):
            echo anchor($cat->url, $cat->name, array('class' => 'list-group-item bold' . ($this->uri->segment(1) === $cat->url ? ' active' : ''))) . "\n";
        endforeach;
        ?>
    </div>
</div>