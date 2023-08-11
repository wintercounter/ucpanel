<div class="content_header">
  <div class="grid-12-12">
    <h1><i class="icon-pencil"></i>{LANG="newsletter.create"}</h1>
  </div>
  <div class="grid-12-12">
    <h2>{LANG="newsletter.create_subtitle"}</h2>
  </div>
  {$module_info.content}
</div>
<div class="grid-12-12">
  <a id="nws_create_message" class="button lightText medium square green trs" href="#nogo"><i class="icon-plus"></i>{LANG="newsletter.btn_create_new"}</a>
</div>
<div class="grid-12-12">
  
  {if="$messages"}
  <table class="table nws_create">
    <tr>
      <th class="tiny">{LANG="newsletter.id"}</th>
      <th>{LANG="newsletter.subject"}</th>
      <th class="tiny3 tc">{LANG="newsletter.sent_num"}</th>
      <th class="tiny3">{LANG="newsletter.send_date"}</th>
      <th class="big">{LANG="newsletter.actions"}</th>
    </tr>
    
    {loop="messages"}
      <tr>
        <td class="id">{$value.id}</td>
        <td>{$value.subject}</td>
        <td class="tc">{$value.sent_num}</td>
        <td>{if="$value.done == 'pending'"}{LANG="newsletter.paused"}{else}{$value.date}{/if}</td>
        <td data-id="{$value.id}" data-subject="{$value.subject}">
            <a class="send button small square green off trs noTop no_marginBottom" href="#nogo" data-all="{$all_num}"><i class="icon-play"></i>{if="$value.done == 'true'"}{LANG="newsletter.btn_resend"}{elseif="$value.done == 'pending'"}{LANG="newsletter.btn_countinue"}{else}{LANG="newsletter.btn_send"}{/if}</a>
            <a class="send_test button small square orange off trs noTop no_marginBottom" href="#nogo"><i class="icon-play-circle"></i>{LANG="newsletter.btn_send_test"}</a>
            <a class="edit button small square blue off trs noTop no_marginBottom" href="#nogo"><i class="icon-edit"></i>{LANG="newsletter.btn_edit"}</a>
            <a class="delete button small square red off trs noTop no_marginBottom" href="#nogo"><i class="icon-trash"></i>{LANG="newsletter.btn_delete"}</a>
            <textarea class="none">{$value.text}</textarea>
        </td>
      </tr>
    {/loop}
        
  </table>

  {else}
  <div class="formee-msg-warning"><h3>{LANG="newsletter.no_messages"}</h3></div>
  {/if}
  
</div>

<form id="nws_create_message_form" class="ajax formee none" action="" method="POST">
    
    <input type="hidden" name="action" value="process_create">
    <input type="hidden" name="module" value="newsletter">
    <input type="hidden" name="message_id" value="">
    
    <div class="grid-12-12">
      <label>{LANG="newsletter.subject"}</label>
      <div><input type="text" name="subject"></input></div>
    </div>
    
    <div class="grid-12-12">
      <label>{LANG="newsletter.message_text"}</label>
      <div><textarea class="ckeditor" name="message"></textarea></div>
    </div>
    
    <div class="grid-12-12">
      <p>{LANG="newsletter.message_variables"}</p>
    </div>
  
</form>

<form id="nws_create_delete" class="ajax formee none" action="" method="POST">
  <input type="hidden" name="action" value="process_delete">
  <input type="hidden" name="module" value="newsletter">
  <input type="hidden" name="id" value="">
  <h1>{LANG="newsletter.confirm_delete_message"}<br><br><span></span></h1>
</form>

<div id="nws_create_send_all" class="ajax formee none">
  <h1>{LANG="newsletter.warn_send"}</h1><br><br>
  <div class="tc">
    <button id="nws_create_start_sending" class="button large square green trs"><i class="icon-play"></i>{LANG="newsletter.btn_start_sending"}</button>
  </div>
</div>

<script>
    jQuery(document).ready(function(){
        
        function init(id,title, w, h){
            
            if(typeof(w) == 'undefined'){
                w = 500;
            }
            
            if(typeof(h) == 'undefined'){
                h = 300;
            }
            
            $( "#" + id ).dialog({
              autoOpen: false,
              height: h,
              width: w,
              modal: true,
              title: title,
              open: function() {
                $(".ui-dialog-titlebar-close").hide(0);
              },
              buttons: {
                '{LANG="core.save"}': function() {
                    
                    if($(this).parent().find('.ui-dialog-processing').length > 0){
                      return;
                    }
                    
                    $(this).submit();
                    $(this).parent().find('.ui-dialog-buttonset').hide();
                    $(this).parent().find('.ui-dialog-buttonpane').append('<div class="ui-dialog-processing">' + ucp.lang.core.processing + '</div>');
                
                    $(this).find('input[name=subject]').val('');
                    $(this).find('input[name=message_id]').val('');
                    $(this).find('textarea').val('');
                    
                },
                
                '{LANG="core.cancel"}': function() {
                    
                    ucp.action.destroyEditor();
                    
                    $( this ).dialog( "close" );
                    
                    $(this).find('input[name=subject]').val('');
                    $(this).find('input[name=message_id]').val('');
                    $(this).find('textarea').val('');
                }
              }
            });
        }
        
        ucp.action.newsletterDestroyDelete = function(){
          $( "#nws_create_delete" ).dialog( "destroy" );
          init('nws_create_delete', ucp.lang.newsletter.delete_message_title);
        }
        
        ucp.action.newsletterDestroyCreate = function(){
            ucp.action.destroyEditor();
            $( "#nws_create_message_form" ).dialog( "destroy" );
            init('nws_create_message_form', ucp.lang.newsletter.create_message_title, '70%', 680);
        }
        
        $('.nws_create .delete').click(function(){
            
            if(ucp.action.checkLetGo()) return false;
            
            $('#nws_create_delete input[name=id]').val($(this).parent().data('id'));
            $('#nws_create_delete span').text($(this).parent().data('subject'));
            $( "#nws_create_delete" ).dialog( "open" );
          
            return false;
        
        });
        
        $('.nws_create .edit').click(function(){
            
            if(ucp.action.checkLetGo()) return false;
            
            $('#nws_create_message_form input[name=subject]').val($(this).parent().data('subject'));
            $('#nws_create_message_form input[name=message_id]').val($(this).parent().data('id'));
            $('#nws_create_message_form textarea[name=message]').val($(this).parent().find('textarea').val());
            $('#nws_create_message_form').dialog( "open" );
            setTimeout(function(){
                ucp.action.initEditor(true);
            },100);
            
            return false;
            
        });
        
        $('.nws_create .send').click(function(){
            
            if(ucp.action.checkLetGo()) return false;
            $('#nws_create_start_sending').data('id', $(this).parent().data('id'));
            $('#nws_create_send_all h1 span').text($(this).data('all')).css({'fontSize': '12px'});
            $(".ui-dialog-content").dialog("close");
            $('#nws_create_send_all').dialog( {
                title: ucp.lang.newsletter.send_all_title,
                width: 400,
                beforeClose: function(){
                    if(ucp.action.checkLetGo()){
                        return false;
                    }
                    return true;
                }
            });
            
            return false;
        
        });
        
        init('nws_create_message_form', ucp.lang.newsletter.create_message_title, '70%', 680);
        init('nws_create_delete', ucp.lang.newsletter.delete_message_title);
        
        $('#nws_create_message').click(function(){
            
            if(ucp.action.checkLetGo()) return false;
            
            $(this).find('input[name=subject]').val('');
            $(this).find('input[name=message_id]').val('');
            $(this).find('textarea').val('');
            $('#nws_create_message_form').dialog('open');
            setTimeout(function(){
                ucp.action.initEditor(true);
            },100);
            
            return false;
        
        });
        
        $('.nws_create .send_test').click(function(){
            
            if(ucp.action.checkLetGo()) return false;
            
            if($('.nws_create .send_test i.icon-spinner').length > 0){
                return false;
            }
            
            $(this).append('<i class="icon-spin icon-spinner"></i>');
            
            ucp.action.customRequest({action:'send_test',module:'newsletter',id:$(this).parent().data('id')});
            
            return false;
        
        });
        
        $('#nws_create_start_sending').click(function(){
            ucp.v.dontLetGo = true;
            var id = $(this).data('id');
            $(this).parent().text(ucp.lang.core.processing);
            ucp.action.customRequest({action:'send_full', module:'newsletter',message_id:id,successful:0, unsuccessful:0});
            return false;
        });
        
        ucp.action.newsletterProcessSend = function(content,data){
            
            var html;
            
            if(data.last == 'false'){
                html = '<span class="green" style="display: inline-block; padding: 10px; font-size: 28px; margin-right: 20px;color: #fff;">' + ucp.lang.newsletter.sent_label + ': ' + data.successful + '</span><span class="red" style="color: #fff; display: inline-block; padding: 10px; font-size: 28px; margin-left: 20px;">' + ucp.lang.newsletter.failed_label + ': ' + data.unsuccessful + '</span>';
            }
            else{
                html = '<h1 style="font-size: 22px;">' + ucp.lang.newsletter.success_label + '</h1>';
            }
            
            $('#nws_create_send_all > div').html(html);
            
            if(data.last == 'false'){
                setTimeout(function(){
                    ucp.action.customRequest({action:'send_full', module:'newsletter', message_id:data.message_id, successful: data.successful, unsuccessful: data.unsuccessful});
                },3000);
            }
            else{
                ucp.v.dontLetGo = false;
            }
            
        }
        
        ucp.action.newsletterRemoveTestLoad = function(){
            $('.nws_create .send_test i.icon-spinner').remove();
        }
    
    });
</script>