<h1>{LANG="skeleton2.settings_title"}</h1>

<form class="formee">
    
    <fieldset>
        
        <div class="grid-6-12">
            <label>{LANG="skeleton2.settings_scheme"}</label>
            <select name="color_scheme">
                <option value="light" {if="$skeleton2_color_scheme == 'light'"}SELECTED{/if}>{LANG="skeleton2.settings_scheme_light"}</option>
                <option value="dark" {if="$skeleton2_color_scheme == 'dark'"}SELECTED{/if}>{LANG="skeleton2.settings_scheme_dark"}</option>
            </select>
        </div>
        
        <div class="grid-6-12">
            <label>{LANG="skeleton2.settings_layout"}</label>
            <select name="layout">
                <option value="home" {if="$skeleton2_layout == 'home'"}SELECTED{/if}>Home</option>
                <option value="home2" {if="$skeleton2_layout == 'home2'"}SELECTED{/if}>Home2</option>
            </select>
        </div>
        
        <div class="grid-12-12">
            <label>{LANG="skeleton2.settings_logo"}</label>
            <input id="skeleton2_logo" type="text" name="logo" value="{$skeleton2_logo}">
            <a class="open_file_browser button small regular orange" data-for="skeleton2_logo"><i class="icon-plus-sign"></i></a>
        </div>
        
        <div class="grid-12-12">
            <h3 class="no_margin">{LANG="skeleton2.settings_block"}</h3>
        </div>
        
        <div class="grid-3-12">
            <label>{LANG="skeleton2.settings_block_logo"}</label>
            <input type="checkbox" name="blocks[blocks_logo]" value="{$skeleton2_blocks_logo}" class="iButton {labelOn: '{LANG="core.on"}', labelOff: '{LANG="core.off"}', easing: 'easeOutBounce', duration: 500}" {if="$skeleton2_blocks_logo == 'true'"}checked{/if}>
        </div>
        
        <div class="grid-3-12">
            <label>{LANG="skeleton2.settings_block_title"}</label>
            <input type="checkbox" name="blocks[blocks_title]" value="{$skeleton2_blocks_title}" class="iButton {labelOn: '{LANG="core.on"}', labelOff: '{LANG="core.off"}', easing: 'easeOutBounce', duration: 500}" {if="$skeleton2_blocks_title == 'true'"}checked{/if}>
        </div>
        
        <div class="grid-3-12">
            <label>{LANG="skeleton2.settings_block_subtitle"}</label>
            <input type="checkbox" name="blocks[blocks_subtitle]" value="{$skeleton2_blocks_subtitle}" class="iButton {labelOn: '{LANG="core.on"}', labelOff: '{LANG="core.off"}', easing: 'easeOutBounce', duration: 500}" {if="$skeleton2_blocks_subtitle == 'true'"}checked{/if}>
        </div>
        
        <div class="grid-3-12">
            <label>{LANG="skeleton2.settings_block_countdown"}</label>
            <input type="checkbox" name="blocks[blocks_countdown]" value="{$skeleton2_blocks_countdown}" class="iButton {labelOn: '{LANG="core.on"}', labelOff: '{LANG="core.off"}', easing: 'easeOutBounce', duration: 500}" {if="$skeleton2_blocks_countdown == 'true'"}checked{/if}>
        </div>
        
        <div class="grid-3-12">
            <label>{LANG="skeleton2.settings_block_progressbar"}</label>
            <input type="checkbox" name="blocks[blocks_progressbar]" value="{$skeleton2_blocks_progressbar}" class="iButton {labelOn: '{LANG="core.on"}', labelOff: '{LANG="core.off"}', easing: 'easeOutBounce', duration: 500}" {if="$skeleton2_blocks_progressbar == 'true'"}checked{/if}>
        </div>
        
        <div class="grid-3-12">
            <label>{LANG="skeleton2.settings_block_some_text"}</label>
            <input type="checkbox" name="blocks[blocks_some_text]" value="{$skeleton2_blocks_some_text}" class="iButton {labelOn: '{LANG="core.on"}', labelOff: '{LANG="core.off"}', easing: 'easeOutBounce', duration: 500}" {if="$skeleton2_blocks_some_text == 'true'"}checked{/if}>
        </div>
        
        <div class="grid-3-12">
            <label>{LANG="skeleton2.settings_block_subscribe_form"}</label>
            <input type="checkbox" name="blocks[blocks_subscribe_form]" value="{$skeleton2_blocks_subscribe_form}" class="iButton {labelOn: '{LANG="core.on"}', labelOff: '{LANG="core.off"}', easing: 'easeOutBounce', duration: 500}" {if="$skeleton2_blocks_subscribe_form == 'true'"}checked{/if}>
        </div>
        
        <div class="grid-3-12">
            <label>{LANG="skeleton2.settings_block_social_links"}</label>
            <input type="checkbox" name="blocks[blocks_social_links]" value="{$skeleton2_blocks_social_links}" class="iButton {labelOn: '{LANG="core.on"}', labelOff: '{LANG="core.off"}', easing: 'easeOutBounce', duration: 500}" {if="$skeleton2_blocks_social_links == 'true'"}checked{/if}>
        </div>
        
        <div class="grid-12-12">
            <button class="button large regular orange" type="submit"><i class="icon-save"></i> {LANG="core.save"}</button>
        </div>
        
    </fieldset>
    
</form>

<script>
    jQuery(document).ready(function(){
        $('.iButton').iButton();
    });
</script>