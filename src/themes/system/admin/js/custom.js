(function($){

$.ajaxSetup ({
    // Disable caching of AJAX responses
    cache: false
});

$(document).ready(function(){

    /* Handle forms */
    
    $("body").delegate("form.ajax", "submit", function (event) {
        
        if(ucp.action.checkLetGo()){
            event.preventDefault();
            return false;
        }
        
        if($(this).find('button[type=submit]').hasClass('loading')){
            ucp.action.alert(ucp.lang.core.please_wait_for_request);
            event.preventDefault();
            return false;
        }
        
        ucp.action.addButtonLoading($(this));
        
        if($(this).find('.ckeditor').length > 0){
            for(var instanceName in CKEDITOR.instances)
                CKEDITOR.instances[instanceName].updateElement();
        }
        
        var that = $(this);

        $.ajax({ 
            type: "POST",
            data: $(this).serialize(),
            dataType : 'json',
            success: function(data){
                ucp.action.removeButtonLoading(that);
                ucp.action.run(data.action, data.content, data);
                //ucp.action[data.action](data.content);
            }
        });
        
        event.preventDefault();
        return false;
    
    });

    /* Panel on-off button */
    $('#panel_switch').click(function(){
        
        if(ucp.action.checkLetGo()){
            return false;
        }
    
      $(this).toggleClass('on').toggleClass('off');
      
      if($(this).hasClass('on')){
        $(this).text(ucp.lang.core.panel_off);
        ucp.action.toggleRightPanel('open');
        if($('#main_menu li.active').length != 0){
            ucp.action.showCenter();
        }
      }
      else{
        $(this).text(ucp.lang.core.panel_on);
        ucp.action.toggleRightPanel('close');
      }
      
      return false;
    
    });
    
    /* Init Button */
    $('#panel_switch').removeClass('none');
    
    /* Handle Menu */
    $('#main_menu a').click(function(){
        
        if(ucp.action.checkLetGo()){
            return false;
        }
      
      if($('#main_menu a.loading').length != 0){
        ucp.action.alert(ucp.lang.core.please_wait_for_request);
        return false;
      }
      
      if($(this).parent().hasClass('active')){
        return false;
      }
      
      var clicked = $(this);
    
      $('#main_menu li.active').each(function(){
        
        if(!clicked.parent().parent().hasClass('submenu')){
          
          $(this).removeClass('active');
          
        }
        
      });
      
      $('#main_menu li li.active').each(function(){
          
          $(this).removeClass('active');
        
      });
      
      $(this).parent().addClass('active');
      var parent = $(this).parent().parent().parent().parent();
      if(parent.attr('id') == 'main_menu'){
        parent.addClass('active');
      }
      
      var act = $(this).attr('href');
      if(act == '' || act == '#nogo') return false;
      act = act.replace("#",'');
      act = act.split(":");
      
      ucp.v.activePageLink = $(this);
      
      ucp.action.menuRequest(act, $(this));
      
      return false;
    
    });
    
    /* Quick preview */
    var over = false;
    $('#preview_switch').hover(function(){
        
        over = true;
        
        setTimeout(function(){
            if(over)
                $('#center_panel, #right_panel').addClass('preview');
        },300);
    
    },function(){
        
        over = false;
    
        $('#center_panel, #right_panel').removeClass('preview');
    
    });
    
    /* Logout */
    $('#logout').click(function() {
        if(ucp.action.checkLetGo()){
            return false;
        }
        ucp.action.logout();
        return false;
        
    });
    
    /* Handle pagination */
    $('body').delegate('.pagination a','click',function(){
    
        var page = $(this).attr('href').split('?');
        page = page[1].replace("page","").replace("=","");
        
        ucp.action.doPagination(page);
        
        return false;
    
    });
    
    /* Hanle filemanagers */
    $("body").delegate('.open_file_browser','click',function(){
        ucp.action.popupFileManager($(this).data('for'));
        return false;
    });
    
    /* Handle theme settings form submission */
    $("body").delegate('#theme_page_settings form', 'submit', function(e){
        
        if($(this).find('.icon-spinner').length > 0){
            ucp.action.alert(ucp.lang.core.please_wait_for_request);
            e.preventDefault();
            return false;
        }
        
        $(this).find('button[type=submit], input[type=submit]').each(function(){
        
            $(this).append('<i class="icon-spin icon-spinner"></i>');
        
        });
        
        ucp.action.customRequest($(this).serialize() + '&action=settings&module=theme_class');
        
        e.preventDefault();
        return false;
        
    });
    
    /* Handle theme reset */
    $("body").delegate('#theme_page_header .reset', 'click', function(e){
        
        if($(this).find('.icon-spinner').length > 0){
            ucp.action.alert(ucp.lang.core.please_wait_for_request);
            e.preventDefault();
            return false;
        }
        
        $(this).append('<i class="icon-spin icon-spinner"></i>');
        
        ucp.action.customRequest('action=reset_defaults&module=theme_class');
        
        e.preventDefault();
        return false;
        
    });

});


/* UCPanel actions */
ucp.action = {}

ucp.action.toggleRightPanel = function(state){
  
  $('#right_panel').toggleClass('open');
  $('#preview_switch').removeClass('off');
  
  if(state == 'close'){
    $('#preview_switch').addClass('off');
    ucp.action.closeCenterPanel();
  }
  
}

ucp.action.toggleCenterPanel = function(){
  $('#center_panel').toggleClass('open');
}

ucp.action.closeCenterPanel = function(){
  $('#center_panel').removeClass('open');
}

ucp.action.menuRequest = function(act, that, page){
  
  that.addClass('loading');
  that.append('<i class="icon-spinner icon-spin"></i>');
  
  ucp.action.hideCenter();
  
  var datas = {};
  
  if(typeof(act[1]) != 'undefined'){
    datas.action = act[1];
    datas.module = act[0];
  }
  else{
    datas.action = act;
  }
  
  if(typeof(page) != 'undefined'){
    datas.page = page;
  }
  
  $.ajax({
    type: "POST",
    dataType : 'json',
    data: datas,
    success: function(data){
      $('#main_menu a.loading').removeClass('loading').find('i.icon-spinner').remove();
      ucp.action.run(data.action, data.content);
    },
    error: function(data){
      $('#main_menu a.loading').parent().removeClass('active');
      $('#main_menu a.loading').removeClass('loading').find('i.icon-spinner').remove();
      var d = escapeHtml(data.responseText);
      ucp.action.alert( ucp.action.changeLang( ucp.lang.core.request_error, d) );
    }
  });

}

ucp.action.showContent = function(content){
    ucp.action.destroyEditor();
    $('#center_panel > div').empty().html(content);
    ucp.action.preparePagination();
    ucp.action.showCenter();
}

ucp.action.hideCenter = function(){
  $('#center_panel').removeClass('open');
}

ucp.action.showCenter = function(){
    setTimeout(function(){
        $('#center_panel').addClass('open');
    },200);
}

ucp.action.alert = function(msg){
    if($(".ui-dialog-content").length > 0)
        $(".ui-dialog-content").dialog("close");
    $('<div />').html(msg).dialog({title: ucp.lang.core.alert});
}

ucp.action.addButtonLoading = function(that){
  
    that.find('button[type=submit]').each(function(){
        $(this).addClass('loading');
        $(this).append('<i class="icon-spinner icon-spin"></i>');
    });
  
}

ucp.action.removeButtonLoading = function(that){
    
    if(typeof(that) != 'undefined'){
    
        that.find('button[type=submit], input[type=submit]').each(function(){
            $(this).removeClass('loading');
            $(this).find('i.icon-spinner').remove();
        });
        
    }
  
}

ucp.action.refreshIframe = function(){
    
    var iframe = document.getElementById('the_site');
    iframe.src = ucp.config.full_url;
    
}

ucp.action.refreshPage = function(){
    
    ucp.v.activePageLink.parent().removeClass('active');
    ucp.v.activePageLink.click();
    
}

ucp.action.preparePagination = function(){
    
    $('.pagination a.left').html('<i class="icon-chevron-left">');
    $('.pagination a.right').html('<i class="icon-chevron-right">');
    
}

ucp.action.doPagination = function(page){
    
    ucp.v.activePageLink.parent().removeClass('active');
    ucp.action.menuRequest( ucp.v.activePageLink.attr('href').replace('#','').split(':') , ucp.v.activePageLink , page);
    
}

ucp.action.customRequest = function(dat){

    $.ajax({ 
        type: "POST",
        data: dat,
        dataType : 'json',
        success: function(data){
            ucp.action.run(data.action, data.content, data);
            ucp.action.removeButtonLoading();
        },
        error: function(data){
            var d = escapeHtml(data.responseText);
            ucp.action.alert( ucp.action.changeLang( ucp.lang.core.request_error, d) );
            ucp.action.removeButtonLoading();
        }
    });
    
}

ucp.action.run = function(actions, content, data){
  
    ucp.lastResponse = data;
    
    actions = actions.split("|");
    
    for(var i in actions){
        var act = actions[i];
        ucp.action[act](content, data);
    }
    
}

var cke_i = 0;

ucp.action.initEditor = function(fullHTML){
    
    CKEDITOR.config.entities = true;
    
    if(fullHTML){
        CKEDITOR.config.fullPage = true;
    }else{
        CKEDITOR.config.fullPage = false;
    }
	
    $(".ckeditor").each(function(){
            
            var id;
            
            if(typeof($(this).attr('id')) == 'undefined'){
                $(this).attr('id','ckeditor' + cke_i);
                id = 'ckeditor' + cke_i;
            }
            else{
                id = $(this).attr('id');
            }
     
            CKEDITOR.replace( id );
            
            cke_i++;

    });
    
}

ucp.action.destroyEditor = function(){
    
    $('.ckeditor').each(function(){

            var n = $(this).attr('id');
            
            if(typeof(CKEDITOR.instances[n]) != 'undefined'){
                
                CKEDITOR.instances[n].destroy();
                
            }
    
    });    
    

}

ucp.action.changeLang = function(str, d){
  return str.replace("{VAR}", d);
}

ucp.action.popupFileManager = function(the_id){
    
    var h = parseInt($(window).height() / 100 * 80);
    var w = $(window).width() - 80;
    
    var elf = jQuery('<div id="elfinder_' + the_id + '" />').dialogelfinder({
            lang: ucp.lang.core.lng,
            url : ucp.config.system_theme_url + 'admin/js/elFinder/php/connector.php?URL=' + encodeURIComponent(ucp.config.full_url + 'storage/') + '&path=' + encodeURIComponent(ucp.config.full_path + 'storage/'),
            resizable: true,
            rememberLastDir: true,
            sync: 30,
            height: h,
            width: '80%',
            cssClass: 'filemanager',
            commandsOptions : {
                getfile : {
                    onlyURL  : true,
                    multiple : false,
                    folders  : false,
                    oncomplete : 'destroy'
                },
                quicklook : {
                    autoplay : false
                }
            },
            getFileCallback: function(url) {
                var path = url.replace(ucp.config.full_url,"").replace("//","/").leftTrim("/");
                $("#" + the_id).attr('value', path);
            }
    });
    
}

ucp.action.initFileManager = function(){
    
    var h = $(window).height() - $('.content_header').height() - 80;
    
    var elf = $('#filemanager').elfinder({
            lang: ucp.lang.core.lng,
            url : ucp.config.system_theme_url + 'admin/js/elFinder/php/connector.php?URL=' + encodeURIComponent(ucp.config.full_url + 'storage/') + '&path=' + encodeURIComponent(ucp.config.full_path + 'storage/'),
            resizable: true,
            rememberLastDir: true,
            sync: 30,
            height: h
    }).elfinder('instance');
    
}

ucp.action.logout = function(){
    var obj = { domain: ucp.config.cookie_domain, path: '/' }
    $.removeCookie('logged_in', obj);
    $.removeCookie('logged_hash', obj);
    $.removeCookie('logged_uname', obj);
    location.reload();
}

ucp.action.checkLetGo = function(){
    if(ucp.v.dontLetGo){
        alert(ucp.lang.core.process_in_progress);
        return true;
    }
    return false;
}

ucp.action.confirmExit = function(){
    if(ucp.v.dontLetGo){
        return ucp.lang.core.process_in_progress_sure;
    }
}

/* Confirm closing window when needed */
window.onbeforeunload = ucp.action.confirmExit;

/*
$(".filebrowser").click(function(){
		var the_id = $(this).attr("id").split("---")[1];
		init_filebrowser(the_id);
		$("#elfinder_" + the_id).dialog({width: '80%', height: parseInt($(window).height() / 100 * 80), resizable: true, show: "fade", hide: "fade", title: lang_add_files, closeText: lang_close});
	});
				
	function init_filebrowser(the_id){
		
	}*/

        var needToConfirm = true;


}(jQuery));


/* Some handy-dandy functions */

function escapeHtml(unsafe) {
  return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

String.prototype.leftTrim = function(charlist) {
  if (charlist === undefined)
    charlist = "\\s";
  return this.replace(new RegExp("^[" + charlist + "]+"), "");
};