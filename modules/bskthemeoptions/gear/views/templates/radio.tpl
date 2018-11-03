{foreach $value as $radio}
<div class='radio'>
    <input type='radio' value='{$radio['value']}' name='{$name}' id='{$name}_{$radio@index}' {if $radio['checked']}checked{/if} />
    <label for='{$name}_{$radio@index}'>{$radio['label']}</label>
</div>
{/foreach}