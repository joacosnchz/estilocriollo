{foreach $value as $color}
    <div class="color_picker_wrap">
        <div>{$color['label']}</div>
        <input class="color_picker" name="{$name}_{$color@index}" value="{$color['value']}" />
    </div>
{/foreach}