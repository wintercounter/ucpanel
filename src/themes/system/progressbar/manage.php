<div class="content_header">
  <div class="grid-12-12">
    <h1><i class="icon-group"></i>{LANG="progressbar.title"}</h1>
  </div>
  <div class="grid-12-12">
    <h2>{LANG="progressbar.subtitle"}</h2>
  </div>
  {$module_info.content}
</div>

<div class="grid-12-12">
    
    <form id="add_progress" class="formee ajax">
        <input type="hidden" name="action" value="view_add">
        <input type="hidden" name="module" value="progressbar">
        <fieldset>
            <legend>{LANG="progressbar.add_progress"}</legend>
            <div class="grid-5-12">
                <div><input type="text" name="label" value="" placeholder="{LANG="progressbar.progress_label"}"></div>
            </div>
            <div class="grid-5-12">
                <label>{LANG="progressbar.percentage"}<div id="current_add" style="float:right;">0%</div></label>
                <div id="add_percentage_silder"></div>
                <input id="add_percentage" type="hidden" name="percentage" value="0">
            </div>
            <div class="grid-2-12">
                <button class="button medium regular orange noTop no_margin" type="submit"><i class="icon-plus-sign"></i>{LANG="core.add"}</button>
            </div>
        </fieldset>
    </form>
    
    {if="$progresses"}
    <form id="progressbar_tableForm" class="formee ajax">
        <input type="hidden" name="action" value="save_table">
        <input type="hidden" name="module" value="progressbar"> 
        <table id="progressbar_table" class="table">
            <thead>
                <tr>
                    <th class="tiny tc">#</th>
                    <th class="medium">{LANG="progressbar.tb_label"}</th>
                    <th class="tiny tc">%</th>
                    <th>{LANG="progressbar.tb_actions"}</th>
                </tr>
            </thead>
            <tbody>
            {loop="$progresses"}
            <tr style="cursor: move" data-id="{$value.id}" data-perc="{$value.percentage}">
                <input type="hidden" name="id[]" value="{$value.id}">
                <td class="index tc">
                    <span>{$counter + 1}</span>
                    <input type="hidden" name="order[]" value="{$counter + 1}">
                </td>
                <td>
                    <span>{$value.label}</span>
                    <input type="text" class="none the_label" name="label[]" value="{$value.label}">
                </td>
                <td class="tc">
                    <span>{$value.percentage}</span>
                    <label class="none tc">{$value.percentage}%</label>
                    <div class="none the_slider"></div>
                    <input class="perc" type="hidden" name="percentage[]" value="{$value.percentage}">
                </td>
                <td>
                    <input type="hidden" name="disabled[]" value="{$value.disabled}">
                    <button class="{if="$value.disabled == 1"}none {/if}disable button medium regular orange noTop no_margin"><i class="icon-remove-sign"></i>{LANG="core.disable"}</button>
                    <button class="{if="$value.disabled == 0"}none {/if}enable button medium regular green noTop no_margin"><i class="icon-ok-sign"></i>{LANG="core.enable"}</button>
                    <button class="edit button medium regular blue noTop no_margin"><i class="icon-edit"></i>{LANG="core.edit"}</button>
                    <button class="delete button medium regular red noTop no_margin"><i class="icon-trash"></i>{LANG="core.delete"}</button>
                </td>
            </tr>
            {/loop}
            </tbody>
        </table>
    </form>
    
    <div class="formee-msg-info"><h3>{LANG="progressbar.reorder"}</h3></div>
    
    {else}
    <div class="formee-msg-warning"><h3>{LANG="progressbar.no_progress_yet"}</h3></div>
    {/if}

</div>



<script>
    
    jQuery(document).ready(function(){
        
        $('#add_percentage_silder').css({'width':'100%'}).slider({min:0,max:100,slide:function( event, ui ) {
            $('#add_percentage').val(ui.value);
            $('#current_add').text(ui.value + '%');
        }});
        
        $('#add_progress').submit(function(e){
        
            if($(this).find('input[name=label]').val() == ''){
                ucp.action.alert(ucp.lang.progressbar.no_empty);
                e.preventDefault();
                return false;
            }
            
            return true;
        
        });
        
           
        var fixHelperModified = function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width())
            });
            return $helper;
        },
        updateIndex = function(e, ui) {
            $('td.index', ui.item.parent()).each(function (i) {
                $(this).find('span').html(i + 1);
                $(this).find('input').val(i + 1);
            });
            ucp.action.customRequest($('#progressbar_tableForm').serialize());
        };
    
        $("#progressbar_table>tbody").sortable({
            helper: fixHelperModified,
            stop: updateIndex
        });
        
        $('#progressbar_table .disable, #progressbar_table .enable').click(function(){
            
            if($(this).find('.icon-spinner').length > 0){
                return false;
            }
        
            var tr = $(this).parent().parent();
            var id = tr.data('id');
            var action = ($(this).hasClass('enable')) ? 'view_enable' : 'view_disable';
            var state = parseInt($(this).parent().find('input').val());
            $(this).parent().find('input').val((state == 0) ? 1 : 0);
            
            $(this).append(' <i class="icon-spinner icon-spin"></i>');
            
            console.log({action: action, module: 'social_links', id: id});
            
            ucp.action.customRequest({action: action, module: 'progressbar', id: id});
            
            return false;
        
        });
        
        ucp.action.progressbar_disabled = function(id){
            var btn = $('tr[data-id=' + id + '] .disable');
            btn.find('.icon-spinner').remove();
            btn.addClass('none');
            btn.parent().find('.enable').removeClass('none');
        }
        
        ucp.action.progressbar_enabled = function(id){
            var btn = $('tr[data-id=' + id + '] .enable');
            btn.find('.icon-spinner').remove();
            btn.addClass('none');
            btn.parent().find('.disable').removeClass('none');
        }
        
        $('#progressbar_table .edit').click(function(e){
            
            if($(this).find('.icon-spinner').length > 0){
                return false;
            }
            
            var tr = $(this).parent().parent();
            var id = tr.data('id');
            
            if($(this).find('.icon-save').length > 0){
                
                if($(this).parent().parent().find('input.the_label').val() == ''){
                    ucp.action.alert(ucp.lang.progressbar.no_empty);
                    e.preventDefault();
                    return false;
                }
        
                var trClone = tr.clone();
                var action = 'view_edit';
                
                $(this).append(' <i class="icon-spinner icon-spin"></i>');
                
                ucp.action.customRequest($('<form />').append(trClone).serialize() + '&action=view_edit&module=progressbar');
                
            }
            else{
                
                $(this).html('<i class="icon-save"></i> ' + ucp.lang.core.save);
                
                tr.find('label.none,div.none,input.none').each(function(){
                    
                    $(this).parent().find('span').addClass('none');
                    $(this).removeClass('none');
                
                });
                
                tr.find('.the_slider:first').css({'width':'100%'}).slider({min:0,max:100,value: tr.data('perc'), slide:function( event, ui ) {
                    tr.find('.perc').val(ui.value);
                    tr.find('label').text(ui.value + '%');
                }}).parent().css({'width':'200px'});
                
            }
            
            return false;
        
        });
        
        $('#progressbar_table .delete').click(function(){
            
            if($(this).find('.icon-spinner').length > 0){
                return false;
            }
            
            $(this).append(' <i class="icon-spinner icon-spin"></i>');
            
            var tr = $(this).parent().parent();
            var id = tr.data('id');

            ucp.action.customRequest({action:'view_delete', module: 'progressbar', id : id});

            return false;
        
        });
    
    });
    
</script>