{if $blockCategTree && $blockCategTree.children|@count}
    <!-- Block categories module -->
    <div id="categories_block_left" class="block">
        <div class="frame_wrap_header frame_wrap clearfix">
            <div class="fwh-title">
                {if isset($currentCategory)}
                    {$currentCategory->name|escape}
                {else}
                    {l s='Categories' mod='blockcategories'}
                {/if}
            </div>
        </div>
        <div class="block_content frame_wrap frame_wrap_content">
            <ul class="tree {if $isDhtml}dhtml{/if}">
                {foreach from=$blockCategTree.children item=child name=blockCategTree}
                    {if $smarty.foreach.blockCategTree.last}
                        {include file="$branche_tpl_path" node=$child last='true'}
                    {else}
                        {include file="$branche_tpl_path" node=$child}
                    {/if}
                {/foreach}
            </ul>
        </div>
    </div>
    <!-- /Block categories module -->
{/if}
