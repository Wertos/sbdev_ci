var userinfo = function(id) {
    $('#myModal').modal();
    $("#modal-body").load('/user/' + id);
};

var updatestats = function(id) {
    $('#loading').show();
    $("#torrent_stats").load('/torrent/update/' + id, function() {
        $("#updatestats").remove();
        $('#loading').hide();
    });
};

var getfiles = function(id) {
    $("#file_table").load('/torrent/files/' + id, function() {
        $('#loading').hide();
    }).data('loaded','true');
};

var gettrackers = function(id) {
    $("#tracker_table").load('/torrent/trackers/' + id, function() {
        $('#loading').hide();
    }).data('loaded','true');
};

var clear_textbox = function() {
    $('textarea[name="text"]').val('').focus();
};

var edit_comment_box = function(id) {
    $('#myModal').modal();
    $.get("/comments/edit/" + id, function(html) {
        $("#modal-body").html(html)
    });
    return false;
};

var report_box = function(id, where) {
    $('#myModal').modal();
    $.get("/report/send/" + id + "/" + where, function(html) {
        $("#modal-body").html(html)
    });
    return false;
};

var delete_comment = function(id) {
    var parent = $("li[id='c-" + id + "']");
    if (confirm("Удалить этот комментарий?"))
    {
        $.get("/comments/delete/" + id, function() {
            parent.fadeOut('slow', function() {
                $(this).remove();
            });
        });
    }
    return false;
};

var quote_comment = function(id, user) {

    $.get("/comments/quote/" + id, function(html) {
        $("textarea#area").val('').focus().val("[quote=" + user + "]" + html + "[/quote]");
        $('html,body').animate({
            scrollTop: $("#comm").offset().top
        });

    });
    return false;
};


///send report
$(document).on('submit', '#add_report_form', function() {

    var id = $("#add_report_form :input[name='id']").val();
    var where = $("#add_report_form :input[name='location']").val();

    $.ajax({
        type: "POST",
        url: "/report/send/" + id + "/" + where,
        data: $('#add_report_form').serialize(),
        cache: false,
        success: function(html) {
            var n = $(html).length;
            if (n > 0) {
                $("div#report").html(html);
            } else {
                location.reload();
            }
        }
    });

    return false;
});
///send report

///edit comment
$(document).on('submit', '#edit_comment_form', function() {
    $(".comment_errors").remove();

    var cid = $("#edit_comment_form :input[name='id']").val();

    $.ajax({
        type: "POST",
        url: "/comments/edit/" + cid,
        data: $('#edit_comment_form').serialize(),
        cache: false,
        success: function(html) {
            var n = $(html).length;
            if (n > 0) {
                $("ul#comments").prepend(html);
                $('#myModal').modal('hide');
            } else {
                $('#myModal').modal('hide');
                location.reload();
            }
        }
    });

    return false;
});
///edit comment

$(function() {

    ///add comment
    $('#add_comment_form').submit(function() {
        $('#loading').show();
        $(".comment_errors").remove();
        $.ajax({
            type: "POST",
            url: "/comments/add",
            data: $('#add_comment_form').serialize(),
            cache: false,
            success: function(html) {
                $(html).prependTo('ul#comments').hide().slideDown('slow');
                $('textarea[name="text"]').val('').focus();
                $(".no_comments").fadeOut("slow");
                $('#loading').hide();
            }
        });
        return false;
    });
    ///add comment


    ////tooltip
    $("[title]").tooltip({html: true});

    ///spoiler
    $('.spoiler').click(function() {
        $(this).toggleClass('unfolded');
        $(this).next('#spoiler-body').slideToggle('slow');
    });


    ///show-hide (tracker list, file list)
    $('.show-hide').click(function() {
        var id = $(this).attr("id");
        $(this).toggleClass('unfolded');
        $(this).next('.show-hide-body').slideToggle('fast', function() {
            var checkid = $(this).attr("id");
            var check = $(this).is(":hidden");
//alert($('#file_table').data('loaded'));
            if (check == false && checkid == 'file_table')
            {
                if($(this).data("loaded") !== 'true') {
                	$('#loading').show();
                	getfiles(id)
                }
            } else if (check == false && checkid == 'tracker_table') {
            		if($(this).data("loaded") !== 'true') {
                	$('#loading').show();
                	gettrackers(id)
                }
            }

        });
    });
});

function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
    if (bytes == 0)
        return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return ((i == 0) ? (bytes / Math.pow(1024, i)) : (bytes / Math.pow(1024, i)).toFixed(1)) + ' ' + sizes[i];
}

$(document).ready(function() {
	$("a.postLink:not([href*='"+ window.location.hostname +"/'])", $('body'))
  	.bind("click", function(){ return !window.open(this.href);
  });
	$("#main_search").submit(function() {
  	var query = $("#search-home").val();
  	//alert(query);
  	if(query.length < 3) {
	 	$("#search-home").attr("placeholder", "Поисковая фраза - не менее 3х символов");
  		$("#search-home").val("Поисковая фраза - не менее 3х символов");
  		$("#id_submit").attr({disabled:true});
		setTimeout(function() {
  			$("#search-home").val(query);
			$("#id_submit").attr({disabled:false});
  		}, 1000);
  		return false;
  	}
  	$(this).submit();
	});
});

bookmark = function(id, name, value) {
	var aoData = {};
	aoData['id'] = id;
	aoData[name] = value;
	$.ajax({
			url: "torrent/bookmarks/",
			method: "POST",
			data: aoData
		}).done(function(data) {
			obj = JSON.parse(data);
			if(obj.action == "add") {
				msg = "добавлена";
				$("#bokmark").attr({class: obj.class, title: "Удалить из закладок", 'data-original-title': "Удалить из закладок"});
			} else {
				msg = "удалена";
				$("#bokmark").attr({class: obj.class, title: "Добавить в закладки", 'data-original-title': "Добавить в закладки"});
			}
			if(obj.error.code == 0) {
				alert("Закладка успешно "+msg);
			} else {
				alert("Ошибка: code="+obj.error.code+" message="+obj.error.message);
			}
	});
};

pager = function(catid,start,name,value,act) {
	if(act == 'next') {
	  start = start+5;
	} else if (act == 'back') {
	  start = start-5;
	}
	if(start < 0) {
	  start = 0;
	  $("#pag_prev_"+catid).attr('disabled', true);
	}

	width = $('#catid_'+catid).width();
	height= $('#catid_'+catid).height();
	$('#catid_'+catid).html('<div style="min-width:'+width+'px; height:'+height+'px;" class="cat_load"></div>');
	var aoData = {};
	aoData['catid'] = catid;
	aoData['start'] = start;
	aoData[name] = value;
	$.ajax({
			url: "ajax/ajaxpag",
			method: "POST",
			dataType: "json",
			data: aoData
		}).done(function(data) {
			if(!data) return false;
			$('#catid_'+catid).html(data['html']).data('start', start);
			$("[title]").tooltip({html: true});
			$("#pag_prev_"+catid).attr('disabled', false);
			if(data['start'] == 0) {
			  $("#pag_prev_"+catid).attr('disabled', true);
			}
	});
};

delAnn = function(id,tid,url,name,value) {
	var aoData = {};
	aoData['id']  = id;
	aoData['tid'] = tid;
	aoData['url'] = url;
	aoData[name]  = value;
	$.ajax({
			url: "ajax/delann",
			method: "POST",
			dataType: "json",
			data: aoData
		}).done(function(data) {
			if(!data) return false;
			id = data['id'];
			$("tr#"+id).hide();
		});
};




$(function(){
 if ($(window).scrollTop()>="250") $("#ToTop").fadeIn("slow")
 $(window).scroll(function(){
  if ($(window).scrollTop()<="250") $("#ToTop").fadeOut("slow")
   else $("#ToTop").fadeIn("slow")
 });

 if ($(window).scrollTop()<=$(document).height()-"999") $("#OnBottom").fadeIn("slow")
 $(window).scroll(function(){
  if ($(window).scrollTop()>=$(document).height()-"999") $("#OnBottom").fadeOut("slow")
   else $("#OnBottom").fadeIn("slow")
 });

 $("#ToTop").click(function(){$("html,body").animate({scrollTop:0},"slow")})
 $("#OnBottom").click(function(){$("html,body").animate({scrollTop:$(document).height()},"slow")})
});