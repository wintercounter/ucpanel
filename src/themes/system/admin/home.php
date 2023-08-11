{include="header"}
    <body>
        
        <iframe id="the_site" width="100%" src="{$full_url}" frameborder="0"></iframe>
        
        <section id="center_panel" class="center_panel">
            
            <div>
            
            </div>
            
        </section>
        
        <section id="right_panel" class="side_panel right trs">
            
            <div id="ucp_info">
                {LANG="core.version"}: {$system_version}<br>
                <a id="logout" class="button small square orange off trs" href="#nogo">{LANG="core.logout"}</a>
            </div>
            
            <ul id="main_menu">
                {loop="menu"}
                <li><a id="menu_item_{$key}" href="{$value.target}"><i class="icon-{$value.icon}"></i>{$value.title}{if="$value.count"}{$value.count}{/if}</a>
                {if="$value.submenu"}
                    <ul class="submenu">
                        {loop="$value.submenu"}
                            <li><a class="trs" id="submenu_item_{$key}" href="{$value.target}">{$value.title}</a>
                        {/loop}
                    </ul>
                {/if}
                </li>
                {/loop}
            </ul>
            
        </section>
        
        <section id="button_wrapper">
            <button id="preview_switch" class="button lightText large square blue off trs">{LANG="core.panel_preview"}</button><br>
            <button id="panel_switch" class="none button lightText large square green off trs" >{LANG="core.panel_on"}</button>
        </section>
        
{include="footer"}