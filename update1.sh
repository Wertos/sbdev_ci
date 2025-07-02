#!/bin/sh
#test cron fle
if [ -f "/home/torr/www/cron_update1" ]
    then
	exit
fi

#create cron file
echo "update" > /home/torr/www/cron_update1

data_file=$(echo "SELECT id FROM torrents WHERE updated = 1 AND last_update < NOW()-INTERVAL 3 HOUR ORDER BY id DESC" | mysql -utorr -pruerotic.ru -D torr);

#сколько параллельных задач будем запускать
max_parallel_workers=50
worker()
{
    nice -n 20 /usr/bin/php -f /home/torr/www/public/index.php torrent/update/${row}/0
    echo -en "${row} - Updated                                                             \r"
}

#счетчик запущенных параллельных процессов
nn=0

for row in $data_file; do
    if [[ $row -eq 'id' ]]
      then
        continue
    fi
 ( worker ${nn} ${row} ) &

 nn=$[nn+1]
 if [ ${nn} -eq ${max_parallel_workers} ]; then
  nn=0
  wait
 fi
done

wait

rm -f /home/torr/www/cron_update1
exit
