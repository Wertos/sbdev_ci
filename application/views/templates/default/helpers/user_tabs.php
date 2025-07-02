<ul class="nav nav-tabs user_tabs" role="tablist">
    <li<?= ($this->uri->segment(2) === 'profile' ? ' class="active"' : '') ?>><?= anchor('user/profile', 'Профиль') ?></li>
    <li<?= ($this->uri->segment(2) === 'torrents' ? ' class="active"' : '') ?>><?= anchor('user/torrents', 'Торренты: ' . $this->user_model->count_user_torrents($curuser->id)) ?></li>
    <li<?= ($this->uri->segment(2) === 'bookmarks' ? ' class="active"' : '') ?>><?= anchor('user/bookmarks', 'Закладки: ' . $this->user_model->count_user_bookmarks($curuser->id)) ?></li>
    <li<?= ($this->uri->segment(2) === 'comments' ? ' class="active"' : '') ?>><?= anchor('user/comments', 'Комментарии: ' . $this->user_model->count_user_comments($curuser->id)) ?></li>
</ul>