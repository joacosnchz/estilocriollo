{$localFonts = ['psans' => 'Perspective Sans']}
{$defaultFonts = ['Arial', 'Arial Black', 'Arial Narrow', 'Calibri', 'Candara', 'Century Gotic', 'Franklin Gothic Medium', 'Futura', 'Geneva', 'Helvetica', 'Impact', 'Lucida Grande', 'Optima', 'Tahoma', 'Trebuchet MS', 'Verdana']}

<select name="{$name}" id="{$name}" class="form-control font_list" data-value="{$value}">
    <optgroup label="Google Fonts" class="google_fonts"></optgroup>
    <optgroup label="Local Fonts">
        {foreach $localFonts as $font_value => $font_name}
            <option value="{$font_value}" {if $value == $font_value}selected{/if}>{$font_name}</option>
        {/foreach}
        {foreach $defaultFonts as $font_name}
            <option value="{$font_name}" {if $value == $font_name}selected{/if}>{$font_name}</option>
        {/foreach}
    </optgroup>
</select>