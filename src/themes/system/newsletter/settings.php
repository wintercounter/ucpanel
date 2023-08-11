<div class="content_header">
  <div class="grid-12-12">
    <h1><i class="icon-cog"></i>{LANG="newsletter.settings_title"}</h1>
  </div>
  <div class="grid-12-12">
    <h2>{LANG="newsletter.settings_subtitle"}</h2>
  </div>
  {$module_info.content}
</div>

<form class="grid-12-12 ajax formee">
  
    <input type="hidden" name="action" value="process_settings">
    <input type="hidden" name="module" value="newsletter">
        
    <fieldset>
        
        <legend>{LANG="newsletter.sender_details"}</legend>
        
        <div class="grid-6-12">
            <label>{LANG="newsletter.settings_from"}</label>
            <input type="text" name="from" value="{$newsletter_from}">
        </div>
        
        <div class="grid-6-12">
            <label>{LANG="newsletter.settings_from_name"}</label>
            <input type="text" name="from_name" value="{$newsletter_from_name}">
        </div>
        
        <div class="grid-12-12 tc">
            <button class="button large regular orange" type="submit"><i class="icon-save"></i>{LANG="core.save"}</button>
        </div>
        
    </fieldset>
        
    <fieldset>
        
        <legend>{LANG="newsletter.reply_details"}</legend>
        
        <div class="grid-6-12">
            <label>{LANG="newsletter.settings_reply_to_mail"}</label>
            <input type="text" name="reply_to_mail" value="{$newsletter_reply_to_mail}">
        </div>
        
        <div class="grid-6-12">
            <label>{LANG="newsletter.settings_reply_to_name"}</label>
            <input type="text" name="reply_to_name" value="{$newsletter_reply_to_name}">
        </div>
        
        <div class="grid-12-12 tc">
            <button class="button large regular orange" type="submit"><i class="icon-save"></i>{LANG="core.save"}</button>
        </div>
        
    </fieldset>
    
    <fieldset>
        
        <legend>{LANG="newsletter.server_details"}</legend>
        
        <div class="grid-12-12">
            <label>{LANG="newsletter.settings_choose_method"}</label>
            <select id="type_select" name="type">
                <option value="php"{if="$newsletter_type == 'php'"}SELECTED{/if}>PHP Mail (default)</option>
                <option value="smtp"{if="$newsletter_type == 'smtp'"}SELECTED{/if}>SMTP</option>
                <option value="sendmail"{if="$newsletter_type == 'sendmail'"}SELECTED{/if}>Sendmail</option>
                <option value="qmail"{if="$newsletter_type == 'qmail'"}SELECTED{/if}>QMail</option>
            </select>
        </div>
        
        <div id="smtp_settings" class="none">
            
            <div class="grid-6-12">
                <label>{LANG="newsletter.settings_smtp_host"}</label>
                <input type="text" name="smtp_host" value="{$newsletter_smtp_host}">
            </div>
            
            <div class="grid-6-12">
                <label>{LANG="newsletter.settings_smtp_port"}</label>
                <input type="text" name="smtp_port" value="{$newsletter_smtp_port}">
            </div>
            
            <div class="grid-6-12">
                <label>{LANG="newsletter.settings_smtp_username"}</label>
                <input type="text" name="smtp_username" value="{$newsletter_smtp_username}">
            </div>
            
            <div class="grid-6-12">
                <label>{LANG="newsletter.settings_smtp_password"}</label>
                <input type="text" name="smtp_password" value="{$newsletter_smtp_password}">
            </div>
            
        </div>
        
        <div class="grid-12-12 tc">
            <button class="button large regular orange" type="submit"><i class="icon-save"></i>{LANG="core.save"}</button>
        </div>
        
    </fieldset>
  
</form>


<script>
    
    jQuery(document).ready(function(){
        
        if('{$newsletter_type}' == 'smtp'){
            $('#smtp_settings').show();
        }
        
        $('#type_select').change(function(){
           
            if($(this).val() == 'smtp'){
                $('#smtp_settings').removeClass('none').show();
            }
            else{
                $('#smtp_settings').addClass('none').hide();
            }
            
        });
    
    });
    
</script>