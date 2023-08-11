<div class="content_header">
  <div class="grid-12-12">
    <h1><i class="icon-pencil"></i>{LANG="newsletter.manage"}</h1>
  </div>
  <div class="grid-12-12">
    <h2>{LANG="newsletter.manage_subtitle"}</h2>
  </div>
  {$module_info.content}
</div>
<div class="grid-12-12">
  <a id="nws_manage_add_subscriber" class="button lightText medium square green trs" href="#nogo"><i class="icon-plus"></i>{LANG="newsletter.btn_add_new"}</a>
  <a id="nws_manage_search_init" class="button lightText medium square blue trs" href="#nogo"><i class="icon-search"></i>{LANG="newsletter.btn_search"}</a>
  {$pagination}
</div>
<div class="grid-12-12">
  
  {if="$subscribers"}
  <table class="table nws_manage">
    <tr>
      <th class="tiny">{LANG="newsletter.id"}</th>
      <th>{LANG="newsletter.email"}</th>
      <th class="medium">{LANG="newsletter.added_on"}</th>
      <th class="medium">{LANG="newsletter.actions"}</th>
    </tr>
    
    {loop="subscribers"}
      <tr>
        <td class="id">{$value.id}</td>
        <td>{$value.email}</td>
        <td>{$value.date}</td>
        <td data-id="{$value.id}" data-mail="{$value.email}">
          <a class="edit button small square blue off trs noTop no_marginBottom" href="#nogo"><i class="icon-edit"></i>{LANG="newsletter.btn_edit"}</a>
          <a class="delete button small square red off trs noTop no_marginBottom" href="#nogo"><i class="icon-trash"></i>{LANG="newsletter.btn_delete"}</a>
        </td>
      </tr>
    {/loop}
        
  </table>
  {$pagination}
  {else}
  <div class="formee-msg-warning"><h3>{LANG="newsletter.no_subscribers"}</h3></div>
  {/if}
  
</div>

<form id="nws_manage_add_single" class="ajax formee none" action="" method="POST">
  <input type="hidden" name="action" value="add_emails">
  <input type="hidden" name="module" value="newsletter">
  <div class="grid-12-12">
    <label>{LANG="newsletter.email"}</label>
    <div><textarea name="email"></textarea></div>
    <div><p><br>{LANG="newsletter.bulk_desc"}</p></div>
  </div>
</form>

<form id="nws_manage_edit" class="ajax formee none" action="" method="POST">
  <input type="hidden" name="action" value="edit_email">
  <input type="hidden" name="module" value="newsletter">
  <input type="hidden" name="email_id" value="">
  <div class="grid-12-12">
    <label>{LANG="newsletter.email"}</label>
    <div><input type="text" name="email"></div>
  </div>
</form>

<form id="nws_manage_delete" class="ajax formee none" action="" method="POST">
  <input type="hidden" name="action" value="delete_email">
  <input type="hidden" name="module" value="newsletter">
  <input type="hidden" name="email_id" value="">
  <h1>{LANG="newsletter.confirm_delete"}<br><br><span></span></h1>
</form>

<form id="nws_manage_search" class="ajax formee none" action="" method="POST">
  <input type="hidden" name="action" value="search_email">
  <input type="hidden" name="module" value="newsletter">
  <div class="grid-12-12">
    <label>{LANG="newsletter.email"}</label>
    <div><input type="text" name="email"></div>
  </div>
</form>

<script>
    jQuery(document).ready(function(){
        
        function init(id,title){
          $( "#" + id ).dialog({
            autoOpen: false,
            height: 350,
            width: 620,
            modal: true,
            title: title,
            buttons: {
              '{LANG="core.submit"}': function(b) {
  
                if($(this).parent().find('.ui-dialog-processing').length > 0){
                  return;
                }
                $(this).submit();
                $(this).parent().find('.ui-dialog-buttonset').hide();
                $(this).parent().find('.ui-dialog-buttonpane').append('<div class="ui-dialog-processing">' + ucp.lang.core.processing + '</div>');
                
              },
              '{LANG="core.cancel"}': function() {
                $( this ).dialog( "close" );
              }
            }
          });
        }
        
        ucp.action.newsletterDone = function(content, data){
          
          var html = '<h1>' + ucp.lang.newsletter.add_message + '</h1><p class="nws_results" style="line-height: 26px; font-size: 14px; font-weight: bold; padding-top: 10px;">';
          html += '<span class="green">' + ucp.lang.newsletter.was_valid + ' ' + data.success + '</span><br>';
          html += '<span class="red">' + ucp.lang.newsletter.was_failed + ' ' + data.failed + '</span><br>';
          html += '<span class="blue">' + ucp.lang.newsletter.was_duplicated + ' ' + data.duplicated + '</span></p>';
          
          $( "#nws_manage_add_single" ).dialog( "destroy" );
          
          ucp.action.alert(html);

          init('nws_manage_add_single', ucp.lang.newsletter.add_new_title);
          
        }
        
        ucp.action.newsletterDestroyDelete = function(){
          $( "#nws_manage_delete" ).dialog( "destroy" );
          init('nws_manage_delete', ucp.lang.newsletter.delete_title);
        }
        
        ucp.action.newsletterDestroyEdit = function(){
          $( "#nws_manage_edit" ).dialog( "destroy" );
          init('nws_manage_edit', ucp.lang.newsletter.edit_title);
        }
        
        ucp.action.newsletterDestroySearch = function(){
          $( "#nws_manage_search" ).dialog( "destroy" );
          init('nws_manage_search', ucp.lang.newsletter.search_title);
        }
      
        $( "#nws_manage_add_subscriber" ).click(function() {
          $( "#nws_manage_add_single" ).dialog( "open" );
        });
        
        $('.nws_manage .delete').click(function(){
          $('#nws_manage_delete input[name=email_id]').val($(this).parent().data('id'));
          $('#nws_manage_delete span').text($(this).parent().data('mail'));
          $( "#nws_manage_delete" ).dialog( "open" );
        });
        
        $('.nws_manage .edit').click(function(){
          $('#nws_manage_edit input[name=email]').val($(this).parent().data('mail'));
          $('#nws_manage_edit input[name=email_id]').val($(this).parent().data('id'));
          $( "#nws_manage_edit" ).dialog( "open" );
        });
        
        $('#nws_manage_search_init').click(function(){
          $( "#nws_manage_search" ).dialog( "open" );
        });
        
        init('nws_manage_add_single', ucp.lang.newsletter.add_new_title);
        init('nws_manage_edit', ucp.lang.newsletter.edit_title);
        init('nws_manage_search', ucp.lang.newsletter.search_title);
        init('nws_manage_delete', ucp.lang.newsletter.delete_title);
    
    });
</script>