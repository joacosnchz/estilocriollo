{extends file="helpers/form/form.tpl"}
{block name="field"}
    {if $input.type == 'file_lang'}
        <div class="row">
            {foreach from=$languages item=language}
                {if $languages|count > 1}
                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                    {/if}
                    <div class="col-lg-6">
                        {if isset($fields[0]['form']['images'])}
                            <img src="{$image_baseurl}{$fields[0]['form']['images'][$language.id_lang]}" class="img-thumbnail" />
                        {/if}
                        <input id="{$input.name}_{$language.id_lang}" type="file" name="{$input.name}_{$language.id_lang}" class="hide" />
                        <div class="dummyfile input-group">
                            <span class="input-group-addon"><i class="icon-file"></i></span>
                            <input id="{$input.name}_{$language.id_lang}-name" type="text" class="disabled" name="filename" readonly />
                            <span class="input-group-btn">
                                <button id="{$input.name}_{$language.id_lang}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                                    <i class="icon-folder-open"></i> {l s='Choose a file' mod='bskcameraslider'}
                                </button>
                            </span>
                        </div>
                    </div>
                    {if $languages|count > 1}
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=lang}
                                    <li><a href="javascript:hideOtherLanguage({$lang.id_lang});" tabindex="-1">{$lang.name}</a></li>
                                    {/foreach}
                            </ul>
                        </div>
                    {/if}
                    {if $languages|count > 1}
                    </div>
                {/if}
                <script>
                    $(document).ready(function() {
                        $('#{$input.name}_{$language.id_lang}-selectbutton').click(function(e) {
                            $('#{$input.name}_{$language.id_lang}').trigger('click');
                        });
                        $('#{$input.name}_{$language.id_lang}').change(function(e) {
                            var val = $(this).val();
                            var file = val.split(/[\\/]/);
                            $('#{$input.name}_{$language.id_lang}-name').val(file[file.length - 1]);
                        });
                    });
                </script>
            {/foreach}
        </div>
    {/if}
    {$smarty.block.parent}
{/block}

{block name="footer"}
    {if isset($fieldset['form']['submit']) || isset($fieldset['form']['buttons'])}
        <div class="panel-footer">
            {if isset($fieldset['form']['submit']) && !empty($fieldset['form']['submit'])}
                <button
                    type="submit"
                    value="1"
                    id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}"
                    name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}"
                    class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-default pull-right{/if}"
                    >
                    <i class="{if isset($fieldset['form']['submit']['icon'])}{$fieldset['form']['submit']['icon']}{else}process-icon-save{/if}"></i> {$fieldset['form']['submit']['title']}
                </button>
            {/if}
            {if isset($show_cancel_button) && $show_cancel_button}
                <a href="{$back_url}" class="btn btn-default" onclick="window.history.back()">
                    <i class="process-icon-cancel"></i> {l s='Cancel'}
                </a>
            {/if}
            {if isset($fieldset['form']['reset'])}
                <button
                    type="reset"
                    id="{if isset($fieldset['form']['reset']['id'])}{$fieldset['form']['reset']['id']}{else}{$table}_form_reset_btn{/if}"
                    class="{if isset($fieldset['form']['reset']['class'])}{$fieldset['form']['reset']['class']}{else}btn btn-default{/if}"
                    >
                    {if isset($fieldset['form']['reset']['icon'])}<i class="{$fieldset['form']['reset']['icon']}"></i> {/if} {$fieldset['form']['reset']['title']}
                </button>
            {/if}
            
            {if isset($fieldset['form']['resetSettings'])}
                <button 
                    type="submit" 
                    id="{if isset($fieldset['form']['resetSettings']['id'])}{$fieldset['form']['resetSettings']['id']}{else}{$table}_form_reset_btn{/if}"
                    class="{if isset($fieldset['form']['resetSettings']['class'])}{$fieldset['form']['resetSettings']['class']}{else}btn btn-default{/if}"
                    name="resetSettings" >
                    {if isset($fieldset['form']['resetSettings']['icon'])}<i class="{$fieldset['form']['resetSettings']['icon']}"></i> {/if} {$fieldset['form']['resetSettings']['title']}
                </button>
            {/if}
            
            {if isset($fieldset['form']['buttons'])}
                {foreach from=$fieldset['form']['buttons'] item=btn key=k}
                    {if isset($btn.href) && trim($btn.href) != ''}
                        <a href="{$btn.href}" {if isset($btn['id'])}id="{$btn['id']}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']}{/if}" {if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']}" ></i> {/if}{$btn.title}</a>
                    {else}
                        <button type="{if isset($btn['type'])}{$btn['type']}{else}button{/if}" {if isset($btn['id'])}id="{$btn['id']}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']}{/if}" name="{if isset($btn['name'])}{$btn['name']}{else}submitOptions{$table}{/if}"{if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']}" ></i> {/if}{$btn.title}</button>
                    {/if}
                {/foreach}
            {/if}
        </div>
    {/if}
{/block}