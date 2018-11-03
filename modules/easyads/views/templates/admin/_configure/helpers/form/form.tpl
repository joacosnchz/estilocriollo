{extends file="helpers/form/form.tpl"}
{block name="input"}
    {if $input.type == 'file_lang'}
        <div class="row">
            {foreach from=$languages item=language}
                {if $languages|count > 1}
                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                    {/if}
                    <div class="col-lg-9">
                        {*$fields_value|d*}
                        {if isset($fields_value['image']) && $fields_value['image'][$language.id_lang] != ''}
                            <img src="{$image_baseurl}{$language.iso_code}/{$fields_value['image'][$language.id_lang]}" class="img-thumbnail" /><br><br>
                        {/if}
                        <input id="{$input.name}_{$language.id_lang}" type="file" name="{$input.name}_{$language.id_lang}" class="hide" />
                        <div class="dummyfile input-group">
                            <span class="input-group-addon"><i class="icon-file"></i></span>
                            <input id="{$input.name}_{$language.id_lang}-name" type="text" class="disabled" name="filename" readonly />
                            <span class="input-group-btn">
                                <button id="{$input.name}_{$language.id_lang}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                                    <i class="icon-folder-open"></i> {l s='Choose a file' mod='homeslider'}
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
    {else if $input.type == 'exceptions'}
        <div class="row">
            <div class="well col-lg-12">
                <p>{if !empty($input.msg)}{$input.msg}{/if}</p>
                <input type="text" class="active_exceptions" id="{$input.name}" name="{$input.name}" value="{if !empty($fields_value['exceptions'])}{$fields_value['exceptions']}{/if}" />
                {if !empty($input.exceptions)}
                    <select class="exceptions" multiple="multiple">
                        {foreach $input.exceptions as $exception}
                            <option value="{$exception}">{$exception}</option>
                        {/foreach}
                    </select>
                {/if}
            </div>
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}