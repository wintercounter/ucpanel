<div class="content_header">
  <div class="grid-12-12">
    <h1><i class="icon-time"></i>{LANG="countdown.countdown_title"}</h1>
  </div>
  <div class="grid-12-12">
    <h2>{LANG="countdown.countdown_subtitle"}</h2>
  </div>
  {$module_info.content}
</div>

<div class="grid-12-12">
    <form class="formee ajax" action="" method="POST">
    
        <input type="hidden" name="action" value="update_settings">
        <input type="hidden" name="module" value="countdown">
                  
        <fieldset>
          
          <legend>{LANG="countdown.legend_simple"}</legend>
          <div class="grid-12-12">
            {loop="simple_settings"}
                <div class="grid-3-12">
                      <label>{$value.label}<a class="info tooltip" data-tooltip="{LANG="countdown.more_info"}" href="http://keith-wood.name/countdownRef.html#{$key}" target="_blank"><i class="icon-info-sign"></i></a></label>
                      {if="$value.type == 'text' || $value.type == 'array'"}
                        <div><input type="text" name="{$key}" class="{$value.class}" value="{$value.value}"></div>
                      {elseif="$value.type == 'textarea'"}
                        <div><textarea name="{$key}">{$value.value}</textarea></div>
                      {elseif="$value.type == 'bool'"}
                      <div>
                          <select name="{$key}">
                              <option value="true"{if="$value.value == 'true'"} SELECTED{/if}>TRUE</option>
                              <option value="false"{if="$value.value == 'false'"} SELECTED{/if}>FALSE</option>
                          </select>
                      </div>
                    {/if}
                </div>
            {/loop}
            </div>
          
            <div class="grid-12-12 tc">
              <button class="button large regular orange" type="submit"><i class="icon-save"></i>{LANG="core.save"}</button>
            </div>
            
        </fieldset>
      
        <fieldset>
        
            <legend>{LANG="countdown.legend_advanced"}</legend>
            <div class="grid-12-12">
            {loop="advanced_settings"}

              <div class="grid-3-12">
                    <label>{$value.label}<a class="info tooltip" data-tooltip="{LANG="countdown.more_info"}" href="http://keith-wood.name/countdownRef.html#{$key}" target="_blank"><i class="icon-info-sign"></i></a></label>
                    {if="$value.type == 'text' || $value.type == 'array'"}
                      <div><input type="text" name="{$key}" class="{$value.class}" value="{$value.value}"></div>
                    {elseif="$value.type == 'textarea'"}
                      <div><textarea name="{$key}">{$value.value}</textarea></div>
                    {elseif="$value.type == 'bool'"}
                    <div>
                        <select name="{$key}">
                            <option value="true"{if="$value.value == 'true'"} SELECTED{/if}>TRUE</option>
                            <option value="false"{if="$value.value == 'false'"} SELECTED{/if}>FALSE</option>
                        </select>
                    </div>
                  {/if}
              </div>
              
            {/loop}
            </div>
            
            <div class="grid-12-12 tc">
                <button class="button large regular orange" type="submit"><i class="icon-save"></i>{LANG="core.save"}</button>
            </div>
        
        </fieldset>
          
    </form>
</div>

<script>
    jQuery(document).ready(function(){
    
        $( ".countdown_datepicker" ).datetimepicker({
            dateFormat: "mm/dd/yy",
            timeText: '{LANG="countdown.time"}',
            hourText: '{LANG="core.Hour"}',
            minuteText: '{LANG="core.Minute"}',
            currentText: '{LANG="countdown.now"}',
            closeText: '{LANG="countdown.done"}'
        });
    
    });
</script>