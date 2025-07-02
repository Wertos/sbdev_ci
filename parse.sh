#!/bin/sh
#exit
#test cron fle
if [ -f "/home/torr/www/cron_parse" ]
    then
        exit
fi

#create cron file
echo "parse" > /home/torr/www/cron_parse

/usr/bin/php -f /home/torr/www/public/index.php xxx/parse
/usr/bin/php -f /home/torr/www/public/index.php rutor/parse

rm -f /home/torr/www/cron_parse
exit