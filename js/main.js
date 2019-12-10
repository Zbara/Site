/*
* @LitePanel
* @Developed by QuickDevel
*/

/* Ошибки, предупреждения... */
function showError(text) {
    var element = $('<div class="alert in fade alert-danger"><strong>Ошибка!</strong> ' + text + '</div>').prependTo('#for_alert');
    setTimeout(function() {
        element.fadeOut(500, function() {
            $(this).remove();
        });
    }, 10000);
}
function showWarning(text) {
    var element = $('<div class="alert in fade alert-warning"><strong>Проверка данных...</strong> ' + text + '</div>').prependTo('#for_alert');
    setTimeout(function() {
        element.fadeOut(500, function() {
            $(this).remove();
        });
    }, 10000);
}
function showSuccess(text) {
    var element = $('<div class="alert in fade alert-success"><strong>Выполнено!</strong> ' + text + '</div>').prependTo('#for_alert');
    setTimeout(function() {
        element.fadeOut(500, function() {
            $(this).remove();
        });
    }, 10000);
}

function redirect(url) {
    document.location.href=url;
}

function redirectPost(location, args) {
    var form = '';
    $.each( args, function( key, value ) {
        form += '<input type="hidden" name="'+key+'" value="'+value+'">';
    });
    $('<form action="'+location+'" method="POST">'+form+'</form>').appendTo('body').submit();
}

function reloadImage(img) {
    var src = $(img).attr('src');
    $(img).attr('src', src+'?'+Math.random());
};

function reload() {
    window.location.reload();
}


function setNavMode(mode) {
    switch(mode) {
        case "user":
        {
            $('#administratorNavModeBtn').removeClass("active");
            $('#userNavModeBtn').addClass("active");
            $('#administratorNavMode').hide();
            $('#userNavMode').fadeIn(500);
            break;
        }
        case "administrator":
        {
            $('#userNavModeBtn').removeClass("active");
            $('#administratorNavModeBtn').addClass("active");
            $('#userNavMode').hide();
            $('#administratorNavMode').fadeIn(500);
            break;
        }
    }
}
 
window.onload = function() {
    for(var i = 0, l = document.getElementsByTagName('input').length; i < l; i++) {
        if(document.getElementsByTagName('input').item(i).type == 'text') {
            document.getElementsByTagName('input').item(i).setAttribute('autocomplete', 'off');
        };
    };
};

var ls = {
    checkVersion: function() {
        return (window.localStorage !== undefined && window.JSON !== undefined);
    },
    set: function(k, v) {
        this.remove(k);
        try {
            return (ls.checkVersion()) ? localStorage.setItem(k, JSON.stringify(v)) : false;
        } catch (e) {
            return false;
        }
    },
    get: function(k) {
        if (!ls.checkVersion()) {
            return false;
        }
        try {
            return JSON.parse(localStorage.getItem(k));
        } catch (e) {
            return false;
        };
    },
    remove: function(k) {
        try { localStorage.removeItem(k); } catch(e) {};
    }
}






//CHECKBOX
var myhtml = {
    checkbox: function(id){
        name = '#'+id;
        $(name).addClass('html_checked');

        if(ge('checknox_'+id)){
            myhtml.checkbox_off(id);
        } else {
            $(name).append('<div id="checknox_'+id+'"><input type="hidden" id="'+id+'" /></div>');
            $(name).val('1');
        }
    },
    checkbox_off: function(id){
        name = '#'+id;
        $('#checknox_'+id).remove();
        $(name).removeClass('html_checked');
        $(name).val('');
    },
    checked: function(arr){
        $.each(arr, function(){
            myhtml.checkbox(this);
        });
    },
    title: function(id, text, prefix_id, pad_left){
        if(!pad_left)
            pad_left = 5;

        $("body").append('<div id="js_title_'+id+'" class="js_titleRemove"><div id="easyTooltip">'+text+'</div><div class="tooltip"></div></div>');
        xOffset = $('#'+prefix_id+id).offset().left-pad_left;
        yOffset = $('#'+prefix_id+id).offset().top-32;

        $('#js_title_'+id)
            .css("position","absolute")
            .css("top", yOffset+"px")
            .css("left", xOffset+"px")
            .css("display","none")
            .css("z-index","1000")
            .fadeIn('fast');

        $('#'+prefix_id+id).mouseout(function(){
            $('.js_titleRemove').remove();
        });
    },
    title_close: function(id){
        $('#js_title_'+id).remove();
    },
    updateAjaxNav: function(gc, pref, num, page){
        $.get('/updateAjaxNav', {gcount: gc, pref: pref, num: num, page:page}, function(data){
            $('#nav').html(data);
        });
    },
    scrollTop: function(){
        $('.scroll_fix_bg').hide();
        $(window).scrollTop(0);
    }
}









var Box = {
    cb: {},
    Open: function (p) {

        if ($('#box_' + p.id).length) {
            $('#box_' + p.id).length;
            return;
        }
        if (!p.top) p.top = 100;
        if (!p.width) p.width = 600;
        if (!p.cache) p.cache = 0;
        if (!p.cbdatas) p.cbdatas = '';
        if (p.cb) Box.cb[p.id] = p.cb;

        $('body').append('<div id="box_' + p.id + '" class="box_lh_pos" cb-datas="' + p.cbdatas + '" style="display: block"><div class="box_lh_bg" style="width:' + p.width + 'px;margin-top:' + p.top + 'px;">' +
            '<div class="box_lh_title"><span id="btitle" dir="auto">' + p.title + '</span><div onclick="Box.Clos(\'' + p.id + '\')" class="box_lh_close" id ="' + p.id + '" >Закрыть</div></div>' +
            '<div class="box_lh_conetnt">' + p.data + '</div></div></div></div></div></div>');

        $('#box_' + p.id).bind('keydown', function (event) {
            if (event.keyCode == 27) {
                Box.Clos(p.id, p.cache, 1);
            }
        }).bind('click', function (e) {
            if ($('#box_' + p.id + ':visible').length == 0) return;

            var x = e.clientX,
                wh = window.innerWidth;
            if (x >= wh - 15) return;
            if ($(e.target).parents().filter('.box_lh_bg').length == 0 && $(e.target).filter('.box_lh_top_but, .icon-cancel-7').length == 0) {
                Box.Clos(p.id, p.cache, 1);
            }
        }).scroll(function () {
            if ($(this).scrollTop() > 100) $('.box_lh_top_but').fadeIn('slow');
            else $('.box_lh_top_but').fadeOut('slow');
        });
        $('body').css('overflow-y', 'hidden');
        $(document).bind('keydown', function (event) {
            if (event.keyCode == 27) {
                cancelEvent(event);
                Box.Clos(p.id, p.cache, 1);
            }
        }); 
    },
    Clos: function (id, cache, force) {
        var cbdatas = $('#box_' + id).attr('cb-datas');
        if (cache) $('#box_' + id).hide();
        else $('#box_' + id).remove();
        if ($('.box_lh_pos').length == 0) $('body').css('overflow-y', 'auto');
        if (Box.cb[id]) {
            Box.cb[id](force);
            delete Box.cb[id];
        }
    }
}