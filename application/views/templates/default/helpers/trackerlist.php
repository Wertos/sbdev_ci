<?php
//var_dump($trackers);
		$CI =& get_instance();
		$name  = $CI->security->get_csrf_token_name();
		$value = $CI->input->cookie($CI->config->item("csrf_cookie_name"));

        echo "<table class='table table-bordered table-striped torrentable table-hover'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th></th>";
        echo "<th style='width: 100%;'>Трекер</th>";
        echo "<th style=\"text-align: center;\"><span title=\"Раздают\" class=\"glyphicon glyphicon-arrow-up\"></span></th>";
        echo "<th style=\"text-align: center;\"><span title=\"Качают\" class=\"glyphicon glyphicon-arrow-down\"></span></th>";
        echo "<th style=\"text-align: center;\"><span title=\"Скачан\" class=\"glyphicon glyphicon-download-alt\"></span></th>";

        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($trackers as $tracker) {
            echo "<tr id=\"".$tracker->id."\">";
            if ($tracker->state == 'ok') {
                echo "<td><span style=\"color: green;\" class=\"glyphicon glyphicon-ok\" title=\"OK\"></span></td>";
            } else {
                echo "<td><span onclick=\"delAnn(".$tracker->id.", ".$tracker->tid.",'".$tracker->url."', '".$name."', '".$value."'); return false;\" style=\"color: red;\" class=\"glyphicon glyphicon-remove\" title=\"" . $tracker->error . "\"></span></td>";
            }


            echo "<td>" . $tracker->url . "</td>";
            echo "<td align='center' style='color: green;'>" . $tracker->seeders . "</td>";
            echo "<td align='center' style='color: red;'>" . $tracker->leechers . "</td>";
            echo "<td align='center'>" . $tracker->completed . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
?>
