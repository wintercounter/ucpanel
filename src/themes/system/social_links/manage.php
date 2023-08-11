<div class="content_header">
  <div class="grid-12-12">
    <h1><i class="icon-group"></i>{LANG="social_links.title"}</h1>
  </div>
  <div class="grid-12-12">
    <h2>{LANG="social_links.subtitle"}</h2>
  </div>
  {$module_info.content}
</div>

<div class="grid-12-12">
    
    <form class="formee ajax">
        <input type="hidden" name="action" value="view_add_link">
        <input type="hidden" name="module" value="social_links">
        <fieldset>
            <legend>{LANG="social_links.add_link"}</legend>
            <div class="grid-3-12">
                <select id="select_social_site" name="key">
                    <option value="FALSE" SELECTED disabled="disabled">{LANG="social_links.choose_site"}</option>
                    {loop="sites"}
                    {if="$value.supported"}
                    <option value="{$key}">{$value.name}</option>
                    {/if}
                    {/loop}
                    <optgroup label="{LANG="social_links.unsupported"}">
                    {loop="sites"}
                    {if="!$value.supported"}
                    <option value="{$key}">{$value.name}</option>
                    {/if}
                    {/loop}
                    </optgroup>
                </select>
            </div>
            <div class="grid-3-12">
                <div><input id="social_link_title" type="text" name="title" value="" placeholder="{LANG="social_links.link_title"}"></div>
            </div>
            <div class="grid-4-12">
                <div><input type="text" name="value" value="" placeholder="{LANG="social_links.link_url"}"></div>
            </div>
            <div class="grid-2-12">
                <button class="button medium regular orange noTop no_margin" type="submit"><i class="icon-plus-sign"></i>{LANG="core.add"}</button>
            </div>
        </fieldset>
    </form>
    
    {if="$links"}
    <form id="social_links_tableForm" class="formee ajax">
        <input type="hidden" name="action" value="save_table">
        <input type="hidden" name="module" value="social_links"> 
        <table id="social_links_table" class="table">
            <thead>
                <tr>
                    <th class="tiny tc">#</th>
                    <th>{LANG="social_links.tb_site"}</th>
                    <th>{LANG="social_links.tb_title"}</th>
                    <th class="medium">{LANG="social_links.tb_link"}</th>
                    <th>{LANG="social_links.tb_actions"}</th>
                </tr>
            </thead>
            <tbody>
            {loop="links"}
            <tr style="cursor: move" data-id="{$value.id}">
                <td class="index tc">
                    <span>{$counter + 1}</span>
                    <input type="hidden" name="order[]" value="{$counter + 1}">
                </td>
                <td>
                    {$value.site_title}
                    <input type="hidden" name="id[]" value="{$value.id}">
                    <input type="hidden" name="key[]" value="{$value.key}">
                </td>
                <td>
                    <span>{$value.title}</span>
                    <input type="text" class="none" name="title[]" value="{$value.title}">
                </td>
                <td>
                    <span>{$value.value}</span>
                    <input type="text" class="none" name="value[]" value="{$value.value}">
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
    
    <div class="formee-msg-info"><h3>{LANG="social_links.reorder"}</h3></div>
    
    {else}
    <div class="formee-msg-warning"><h3>{LANG="social_links.no_links_yet"}</h3></div>
    {/if}

</div>



<script>
    
    jQuery(document).ready(function(){
        
        $('#select_social_site').change(function(){
            
            $('#social_link_title').val($(this).find('option:selected').text());
            
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
            ucp.action.customRequest($('#social_links_tableForm').serialize());
        };
    
        $("#social_links_table>tbody").sortable({
            helper: fixHelperModified,
            stop: updateIndex
        });
        
        $('#social_links_table .disable, #social_links_table .enable').click(function(){
            
            if($(this).find('.icon-spinner').length > 0){
                return false;
            }
        
            var tr = $(this).parent().parent();
            var id = tr.data('id');
            var action = ($(this).hasClass('enable')) ? 'view_enable' : 'view_disable';
            var state = parseInt($(this).parent().find('input').val());
            $(this).parent().find('input').val((state == 0) ? 1 : 0);
            
            $(this).append(' <i class="icon-spinner icon-spin"></i>');
            
            ucp.action.customRequest({action: action, module: 'social_links', id: id});
            
            return false;
        
        });
        
        ucp.action.social_links_disabled = function(id){
            var btn = $('tr[data-id=' + id + '] .disable');
            btn.find('.icon-spinner').remove();
            btn.addClass('none');
            btn.parent().find('.enable').removeClass('none');
        }
        
        ucp.action.social_links_enabled = function(id){
            var btn = $('tr[data-id=' + id + '] .enable');
            btn.find('.icon-spinner').remove();
            btn.addClass('none');
            btn.parent().find('.disable').removeClass('none');
        }
        
        $('#social_links_table .edit').click(function(){
            
            if($(this).find('.icon-spinner').length > 0){
                return false;
            }
            
            var tr = $(this).parent().parent();
            var id = tr.data('id');
            
            if($(this).find('.icon-save').length > 0){
        
                var trClone = tr.clone();
                var action = 'view_edit';
                
                $(this).append(' <i class="icon-spinner icon-spin"></i>');
                
                ucp.action.customRequest($('<form />').append(trClone).serialize() + '&action=view_edit&module=social_links');
                
            }
            else{
                
                $(this).html('<i class="icon-save"></i> ' + ucp.lang.core.save);
                
                tr.find('input[type=text]').each(function(){
                    
                    $(this).parent().find('span').addClass('none');
                    $(this).removeClass('none');
                
                });
                
            }
            
            return false;
        
        });
        
        $('#social_links_table .delete').click(function(){
            
            if($(this).find('.icon-spinner').length > 0){
                return false;
            }
            
            $(this).append(' <i class="icon-spinner icon-spin"></i>');
            
            var tr = $(this).parent().parent();
            var id = tr.data('id');

            ucp.action.customRequest({action:'view_delete', module: 'social_links', id : id});

            return false;
        
        });
    
    });
    
</script>