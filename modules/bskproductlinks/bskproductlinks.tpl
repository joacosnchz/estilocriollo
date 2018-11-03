{if $prevLink != NULL OR $nextLink != NULL}
<div id="nextprev_links">

    <div class="btn btn-arrow {if $prevLink == NULL}disabled{/if}">
        <a {if $prevLink != NULL}href="{$prevLink}" title="{$prevName}"  data-toggle="tooltip" data-placement="left"{/if} id="prev_link"><i class="icon-chevron-left"></i></a>
    </div>


    <div class="btn btn-arrow {if $prevLink == NULL}disabled{/if}">
        <a {if $nextLink != NULL}href="{$nextLink}" title="{$nextName}"  data-toggle="tooltip" data-placement="right"{/if} id="next_link"><i class="icon-chevron-right"></i></a>
    </div>

</div>
{/if}