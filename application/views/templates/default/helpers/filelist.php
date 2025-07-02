<table class='table table-bordered table-striped torrentable table-hover'>
    <thead>
        <tr>
            <th style='width: 100%;'>Файл</th>
            <th style="text-align: center;">Размер</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($files as $file) {
            echo "<tr>";
            echo "<td>$file->filename</td>";
            echo "<td align='center' class='nobr'>" . byte_format($file->size) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
