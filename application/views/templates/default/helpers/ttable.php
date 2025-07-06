<style type="text/css">
div.dataTables_processing {background: transparent !important; top: 100px !important;}
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('#jtable').dataTable({
            order: [[0, "desc"]],
            bServerSide: true,
            bProcessing: true,
            bFilter: true,
            bInfo: true,
            iDisplayLength: 50,
            sServerMethod: "POST",
            sAjaxSource: '<?= base_url($this->uri->uri_string()); ?>',
            "language": {
				"sProcessing"		:	'<img src="/public/assets/pic/ajax-loader.gif" />',
                "lengthMenu"		:	"Торрентов на страницу: _MENU_",
                "zeroRecords"		:	"Ничего не найдено",
                "search"			:	"Поиск",
				"sLengthMenu"		:	"Показать _MENU_ записей",
				"sInfo"				:	"Торренты с _START_ до _END_ из _TOTAL_ торрентов",
				"sInfoEmpty"		:	"Торренты с 0 до 0 из 0 торрентов",
				"sInfoFiltered"		:	"(отфильтровано из _MAX_ торрентов)",
				"sInfoPostFix"		:	"",
				"sSearch"			:	"Поиск:",
				"sUrl"				:	"",
				"oPaginate": {
					"sFirst"	: "Первая",
					"sPrevious"	: "Предыдущая",
					"sNext"		: "Следующая",
					"sLast"		: "Последняя"
				},
				"oAria": {
					"sSortAscending"	: ": активировать для сортировки столбца по возрастанию",
					"sSortDescending"	: ": активировать для сортировки столбцов по убыванию"
				}
			},
            "columns": [
                {"data": "id"},
                {"data": "name", "asSorting": ["desc", "asc"]},
                {"data": "added", "asSorting": ["desc", "asc"]},
                {"data": "size", "asSorting": ["desc", "asc"]},
                {"data": "seeders", "asSorting": ["desc", "asc"]},
                {"data": "leechers", "asSorting": ["desc", "asc"]},
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [2], "searchable": false,
                    "mRender": function(data, type, full) {
                        var date = new Date(data * 1000);
                        var day = date.getDate();
                        var year = date.getFullYear();
                        var month = date.getMonth();
                        var data = day + '/' + month + '/' + year;
                        return data;
                    }
                },
                {
                    "targets": [3],
                    "searchable": false,
                    "mRender": function(data, type, full) {
                        var data = '<nobr>' + bytesToSize(data) + '</nobr>';
                        return data;
                    }
                },
                {
                    "targets": [4],
                    "searchable": false,
                    "mRender": function(data, type, full) {
                        var data = '<span style="color: green;">' + data + '</span>';
                        return data;
                    }
                },
                {
                    "targets": [5],
                    "searchable": false,
                    "mRender": function(data, type, full) {
                        var data = '<span style="color: red;">' + data + '</span>';
                        return data;
                    }
                }
            ],
            "fnInitComplete": function() {
                //oTable.fnAdjustColumnSizing();
            },
            'fnServerData': function(sSource, aoData, fnCallback) {
                var csrf = {"name": "<?php echo $this->security->get_csrf_token_name(); ?>", "value": '<?php echo $this->input->cookie($this->config->item("csrf_cookie_name")); ?>'};
                aoData.push(csrf);
                $.ajax
                        ({
                            dataType: 'json',
                            method : 'POST',
                            url: sSource,
                            data: aoData,
                            success: fnCallback
						});
            }
        });
    });
</script>
<table id="jtable" class="table table-bordered table-hover table-striped torrentable">
    <thead>
        <tr>
            <th>ID</th>
            <th style="width: 100%;">Название</th>
            <th style="text-align: center;">Добавлен</th>
            <th style="text-align: center;">Размер</th>
            <th style="text-align: center;">Сид</th>
            <th style="text-align: center;">Лич</th>   
        </tr>
    </thead>
</table>
