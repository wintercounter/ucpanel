<div class="content_header">
  <div class="grid-12-12">
    <h1><i class="icon-cog"></i>{LANG="settings.system_settings"}</h1>
  </div>
  <div class="grid-12-12">
    <h2>{LANG="settings.system_settings_sub"}</h2>
  </div>
  {$module_info.content}
</div>


<div id="all_themes" class="grid-12-12">
    
    <form id="system_settings_form" class="ajax formee">
        
        <input type="hidden" name="action" value="save_system">
        <input type="hidden" name="module" value="settings">
        
        <fieldset>
            
            <div class="grid-12-12">
                <label>{LANG="settings.system_username"}</label>
                <div><input type="text" name="username" value="{$username}"></div>
            </div>
            
            <div class="grid-12-12">
                <label>{LANG="settings.system_password"}</label>
                <div><input type="text" name="password" value="{$password}"></div>
                <p>{LANG="settings.system_password_hint"}</p>
            </div>
            
            <div class="grid-12-12">
                <label>{LANG="settings.system_email"}</label>
                <div><input type="text" name="email" value="{$email}"></div>
            </div>
            
            <div class="grid-12-12">
                <label>{LANG="settings.system_lang"}</label>
                <select name="language">
                    {$languages}
                </select>
            </div>
            
            <div class="grid-12-12 tc">
                <button class="button large regular orange noTop no_margin" type="submit"><i class="icon-save"></i>{LANG="core.save"}</button>
            </div>

            
        </fieldset>
        
    </form>

</div>



<script>
    
    jQuery(document).ready(function(){
      
        $('#system_settings_form').submit(function(){
            
            $(this).find('input').each(function(){
            
                if($(this).attr('name') != 'password' && $(this).attr('value') == ''){
                    
                    alert('Fields are can\'t be empty! (Except password...)');
                    
                    return false;
                    
                }
                
                return true;
            
            });
        
        });
    
    });
    
</script>