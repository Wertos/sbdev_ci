<div class="panel sidebar">
    <div class="sidebar_title">
        Кто на сайте
    </div>
    <div class="panel-body">
        <h5>Статистика</h5>
        <ul class="list-unstyled" style="margin-bottom: 0px;">
            <li>Всего посетителей: <?php echo $total ?></li>
            <li>Зарегистрированных: <?php echo $registered ?></li>
            <li>Гостей: <?php echo ($total - $registered) - $countbot ?></li>
            <li>Ботов: <?php echo ($countbot) ?></li>
        </ul>

        <?php if ($registered > 0): ?>
            <h5 style="margin-top: 10px; margin-bottom: 2px;">Пользователи</h5>
            <?php echo $userlist ?>
        <?php endif; ?>
        <?php if ($countbot > 0): ?>
            <h5 style="margin-top: 10px; margin-bottom: 2px;">Боты</h5>
            <?php echo $botlist ?>
        <?php endif; ?>

    </div>
</div>

