<input type="hidden" name="{$name}" value="{$value}" />
<div class="pattern {if $value == 0}active{/if}" data-pattern="0"><span class="glyphicon glyphicon-remove"></span></div>
{for $i=1 to 9}
    <div class="pattern {if $value == $i}active{/if}" data-pattern="{$i}" style="background-image: url(../img/patterns/{$i}.png)"></div>
{/for}