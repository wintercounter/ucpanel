<div class="content_header">
  <div class="grid-12-12">
    <h1><i class="icon-pencil"></i>{LANG="content.content_title"}</h1>
  </div>
  <div class="grid-12-12">
    <h2>{LANG="content.content_subtitle"}</h2>
  </div>
  {$module_info.content}
</div>
<div class="grid-12-12">
  <form class="formee ajax" action="" method="POST">
                  
      <fieldset>
        
        <legend>{LANG="content.edit_contents"}</legend>
      
          <input type="hidden" name="action" value="update_content">
          <input type="hidden" name="module" value="content">
        
        {loop="content"}
          
          <div class="grid-12-12">
            {if="$value.type == 'text'"}
              <label>{$value.label}</label>
              <div><input type="text" name="{$value.key}" value="{$value.value}"></div>
            {/if}
            {if="$value.type == 'textarea'"}
              <label>{$value.label}</label>
              <div><textarea class="ckeditor" name="{$value.key}">{$value.value}</textarea></div>
            {/if}
          </div>
          
        {/loop}
        
        <div class="grid-12-12 tc">
            <button class="button large regular orange" type="submit"><i class="icon-save"></i>{LANG="core.save"}</button>
        </div>
          
      </fieldset>
          
  </form>
</div>

<script>
    jQuery(document).ready(function(){
        
        ucp.action.initEditor();
    
    });
</script>