

    var sShort = '<li><a href="#" class="smile-link" title="angry"><img src="/public/assets/pic/smilies/angry.gif" /></a></li><li><a href="#" class="smile-link" title="bug"><img src="/public/assets/pic/smilies/bigsurprise.gif" /></a></li><li><a href="#" class="smile-link" title="cheese"><img src="/public/assets/pic/smilies/cheese.gif" /></a></li><li><a href="#" class="smile-link" title="red"><img src="/public/assets/pic/smilies/embarrassed.gif" /></a></li><li><a href="#" class="smile-link" title="roll"><img src="/public/assets/pic/smilies/rolleyes.gif" /></a></li><li><a href="#" class="smile-link" title="coolsmile"><img src="/public/assets/pic/smilies/shade_smile.gif" /></a></li><li><a href="#" class="smile-link" title="ahhh"><img src="/public/assets/pic/smilies/shock.gif" /></a></li><li><a href="#" class="smile-link" title="snake"><img src="/public/assets/pic/smilies/snake.gif" /></a></li><li><a href="#" class="smile-link" title="wink"><img src="/public/assets/pic/smilies/wink.gif" /></a></li><li><a href="#" class="smile-link" title="tongue"><img src="/public/assets/pic/smilies/tongue_wink.gif" /></a></li><li><a href="#" class="smile-link" title="vampire"><img src="/public/assets/pic/smilies/vampire.gif" /></a></li><li><a href="#" class="smile-link" title="zip"><img src="/public/assets/pic/smilies/zip.gif" /></a></li><li><a href="#" class="smile-link" title="sick"><img src="/public/assets/pic/smilies/sick.gif" /></a></li><li><a href="#" class="smile-link" title="question"><img src="/public/assets/pic/smilies/question.gif" /></a></li><li><a href="#" class="smile-link" title="kiss"><img src="/public/assets/pic/smilies/kiss.gif" /></a></li><li><a href="#" class="smile-link" title="exclaim"><img src="/public/assets/pic/smilies/exclaim.gif" /></a></li><li><a href="#" class="smile-link" title="blank"><img src="/public/assets/pic/smilies/blank.gif" /></a></li><li><a href="#" class="smile-link" title="grrr"><img src="/public/assets/pic/smilies/grrr.gif" /></a></li><li><a href="#" class="smile-link" title="lol"><img src="/public/assets/pic/smilies/lol.gif" /></a></li><li><a href="#" class="smile-link" title="mad"><img src="/public/assets/pic/smilies/mad.gif" /></a></li><li><a href="#" class="smile-link" title="confused"><img src="/public/assets/pic/smilies/confused.gif" /></a></li>';


    $.fn.bbcodes = function(tags) {
        var self = $(this);
        var textarea = $("#area");

        var editor = '<ul class="tags" style="padding-left: 0px;">';
        if (tags.bold)
            editor += '<li><a title="Жирный" class="bbtags bold" href="#"></a></li>';
        if (tags.italic)
            editor += '<li><a title="Курсив" class="bbtags italic" href="#"></a></li>';
        if (tags.underline)
            editor += '<li><a title="Подчеркнутый" class="bbtags underline" href="#"></a></li>';
        if (tags.strike)
            editor += '<li><a title="Зачеркнутый" class="bbtags strike" href="#"></a></li>';
        if (tags.link)
            editor += '<li><a title="Ссылка" class="bbtags link" href="#"></a></li>';
        if (tags.image)
            editor += '<li><a title="Изображение" class="bbtags image" href="#"></a></li>';
        if (tags.quote)
            editor += '<li><a title="Цитата" class="bbtags quote" href="#"></a></li>';
        if (tags.youtube)
            editor += '<li><a title="Видео YouTube" class="bbtags youtube" href="#"></a></li>';
        if (tags.smiles)
            editor += '<li><a title="Смайлики" class="bbtags smiles" href="#"></a></li>';

        editor += '</ul>';


        this.prepend(editor);

        this.find('.bbtags').bind('click', function(e) {
            e.preventDefault();

            var c = $(this).attr('class').substr(7);

            if (c == 'bold')
                insertB('[b]', '[/b]', textarea);
            else if (c == 'italic')
                insertB('[i]', '[/i]', textarea);
            else if (c == 'underline')
                insertB('[u]', '[/u]', textarea);
            else if (c == 'strike')
                insertB('[s]', '[/s]', textarea);
            else if (c == 'link')
                insertB('[url]', '[/url]', textarea);
            else if (c == 'image')
                insertB('[img]', '[/img]', textarea);
            else if (c == 'quote')
                insertB('[quote]', '[/quote]', textarea);
            else if (c == 'youtube') {
                var yt = self.find('.youtube-bubble');
                var sm = self.find('.smiles-bubble');
                if ($(yt).is(':hidden')) {
                    $(yt).show();
                    $(sm).hide();
                } else {
                    $(yt).hide();
                }
            }
            else if (c == 'smiles') {
                e.stopPropagation();
                var yt = self.find('.youtube-bubble');
                var sm = self.find('.smiles-bubble');
                if ($(sm).is(':hidden')) {
                    $(sm).show();
                    $(yt).hide();
                    $('.pixfuture').css('visibility', 'hidden');
                } else {
                    $(sm).hide();
                    $('.pixfuture').css('visibility', 'visible');
                }
            }
        });

        if (tags.youtube)
            this.find('.bbtags.youtube').parent().append('<div class="youtube-bubble"><div class="youtube-wrap"><p>Ссылка на видео YouTube:</p><input name="youtube"/><button>ОК</button></div></div>');

        if (tags.smiles) {
            this.find('.bbtags.smiles').parent().append('<div class="smiles-bubble"><div class="smiles-wrap"><ul class="smiles-list">' + sShort + '</ul></div></div>');


            this.find('.smile-link').click(function(e) {
                e.preventDefault();
                insertB('', ':' + $(this).attr('title') + ':', textarea);
                self.find('.smiles-bubble').hide();
                $('.pixfuture').css('visibility', 'visible');
            });




            this.find('.smiles-bubble').bind('click', function(e) {
                e.stopPropagation();
            });

            $(document).click(function(event) {
                if (!$(event.target).is(self.find('.smiles-bubble'))) {
                    self.find('.smiles-bubble').hide();
                    $('.pixfuture').css('visibility', 'visible');
                }
            });
        }
    }


    function insertB(opentag, closetag, textarea) {
        textarea = $(textarea).get(0);
        textarea.focus();
        var scrollPosition = textarea.scrollTop;

        if (document.selection) {
            selection = document.selection;
            if ($.browser.msie) {
                var range = selection.createRange();
                var stored_range = range.duplicate();
                stored_range.moveToElementText(textarea);
                stored_range.setEndPoint('EndToEnd', range);
                var s = stored_range.text.length - range.text.length;

                var caretPosition = s - (textarea.value.substr(0, s).length - textarea.value.substr(0, s).replace(/\r/g, '').length);
                var selection = range.text;
            } else {
                var caretPosition = textarea.selectionStart;
                var selection = selection.createRange().text;
            }
        } else {
            var caretPosition = textarea.selectionStart;
            var selection = textarea.value.substring(caretPosition, textarea.selectionEnd);
        }

        if (closetag == '')
            selection = '';

        if (document.selection) {
            var newSelection = document.selection.createRange();
            newSelection.text = opentag + selection + closetag;
        } else {
            textarea.value = textarea.value.substring(0, caretPosition) + opentag + textarea.value.substring(caretPosition, textarea.selectionEnd) + closetag + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
        }


        if (textarea.createTextRange) {
            if ($.browser.opera && $.browser.version >= 9.5 && len == 0) {
                return false;
            }

            range = textarea.createTextRange();
            range.collapse(true);
            range.moveStart('character', caretPosition);
            range.moveEnd('character', selection.length + opentag.length + closetag.length);
            range.select();
        } else if (textarea.setSelectionRange) {
            textarea.setSelectionRange(caretPosition, caretPosition + selection.length + opentag.length + closetag.length);
        }

        textarea.scrollTop = scrollPosition;
        textarea.focus();
    }

    $(document).ready(function() {
        $("#bb_kod").bbcodes({
            bold: true,
            italic: true,
            underline: true,
            strike: true,
            link: true,
            image: true,
            quote: true,
            youtube: false,
            smiles: true
        });

    });
