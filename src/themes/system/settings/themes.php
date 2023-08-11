<div class="content_header">
  <div class="grid-12-12">
    <h1><i class="icon-cog"></i>{LANG="settings.theme_settings"}</h1>
  </div>
  <div class="grid-12-12">
    <h2>{LANG="settings.theme_settings_subtitle"}</h2>
  </div>
  {$module_info.content}
</div>

<div class="grid-12-12">
  <div class="formee-msg-warning"><h3>{LANG="settings.theme_warning"}</h3></div>
</div>

<div id="all_themes" class="grid-4-12">
    
    {loop="$themes_data"}
    <div class="theme{if="$value.theme_folder == $active_theme"} active{/if}" data-theme="{$value.theme_folder}">
        <h3 class="trs">{$value.theme_name}</h3>
        <img src="{$site_theme_url}{$value.theme_folder}/{$value.theme_cover}">
        <span class="trs arrow"><i class="icon-caret-right"></i></span>
        {if="$value.theme_folder == $active_theme"}<span class="trs active"><i class="icon-ok-sign"></i></span>{/if}
        {if="$value.theme_folder != $active_theme"}
        <button class="activate button lightText medium square green trs"><i class="icon-off"></i> {LANG="core.activate"}</button>
        {/if}
    </div>
    {/loop}

</div>

<div id="theme_page" class="grid-8-12 fr">
    
    <div id="theme_page_header" class="content">
        
        <h1>{$theme_name}</h1>
        {if="$theme_version"}<p>Theme Version: {$theme_version}</p>{/if}
        {if="$theme_author"}<p>Theme Author: {$theme_author}</p>{/if}
        {if="$theme_author"}<p class="desc">{$theme_description}</p>{/if}
        
        {if="$theme_supports"}<div>
        Supported Modules
            <ul>
                {loop="$theme_supports"}
                    <li>{$value}</li>
                {/loop}
            </ul>
        </div>{/if}

        {if="$theme_method_reset_defaults"}<a class="reset button lightText medium square red trs" href="#nogo" data-theme="{$active_theme}"><i class="icon-warning-sign"></i>Reset theme to Defaults</a>{/if}
        
    </div>
    
    {if="$has_settings"}
    <div id="theme_page_settings" class="content">
      {$settings_html}
    </div>
    {/if}

</div>



<script>
    
    jQuery(document).ready(function(){
      
      $('#all_themes .theme .activate').click(function(){
      
        $(this).parent().addClass('loading');
        $(this).append(' <i class="icon-spinner icon-spin"></i>');
        
        ucp.action.customRequest({
          action: 'activate_theme',
          module: 'settings',
          theme: $(this).parent().data('theme')
        });
      
      });
    
    });
    
</script>