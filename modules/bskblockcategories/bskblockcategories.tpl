{if isset($blockCategTree) && $blockCategTree.children|@count}
<div id="bskblockcategories">
    <h3>{l s='Categories' mod='bskblockcategories'}</h3>
    <ul class="tree">
    {foreach $blockCategTree.children as $child}
        {include file="$branche_tpl_path" node=$child}
    {/foreach}
    </ul>
</div>
{/if}