{if isset($blockCategTree) && $blockCategTree.children|@count}
<div id="bskblockcategories" class="block {if $vertical_menu_fixed}fixed hidden-xs hidden-sm{/if}">
    <div class="frame_wrap_header frame_wrap clearfix">
        <div class="fwh-title">
            {l s='Categories' mod='bskblockcategories'}
        </div>
    </div>
    <div class="block_content frame_wrap frame_wrap_content">
        <ul class="tree">
        {foreach $blockCategTree.children as $child}
            {include file="$branche_tpl_path" node=$child}
        {/foreach}
        </ul>
    </div>
</div>
{/if}