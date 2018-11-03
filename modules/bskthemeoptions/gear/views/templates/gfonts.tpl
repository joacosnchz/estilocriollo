<div class="gfonts_select">
    <div class="input-group">
        <span class="input-group-btn">
            <a class="addFont btn btn-primary" disabled href="#">ADD</a>
        </span>
        <select class="gfonts form-control">
            <option value="0">--- Select a Font ---</option>
        </select>
    </div><!-- /input-group -->
</div>
<div class="gfonts_active clearfix">
    <input type="hidden" name="{$name}" value="{$value}" />
    {foreach $active_fonts as $font}
        <span class="label label-success" data-value="{$font}">
            {$font|replace:':normal,italic,bold,bolditalic':''|replace:'+':' '}
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </span>
    {/foreach}
</div>