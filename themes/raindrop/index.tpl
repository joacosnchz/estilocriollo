{if isset($HOOK_HOME_TAB_CONTENT) && $HOOK_HOME_TAB_CONTENT|trim}
    {if isset($HOOK_HOME_TAB) && $HOOK_HOME_TAB|trim}
        <ul id="home-page-tabs" class="nav nav-tabs frame_wrap frame_wrap_header fadeInScroll clearfix">
            {$HOOK_HOME_TAB}
            <div class="frame_secondary">{include file="./product-compare.tpl"}</div>
        </ul>
    {/if}
    <div class="tab-content frame_wrap frame_wrap_content fadeInScroll">{$HOOK_HOME_TAB_CONTENT}</div>
{/if}

{if isset($HOOK_HOME) && $HOOK_HOME|trim}
    <div id="displayHome" class="fadeInScroll clearfix">{$HOOK_HOME}</div>
{/if}

{if isset($HOOK_BLUE_BLOCK) && $HOOK_BLUE_BLOCK|trim}
<div class="blue_block fadeInScroll clearfix">
{$HOOK_BLUE_BLOCK}
</div>
{/if}