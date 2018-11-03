<ul id="easyads_{$hook}" class="easyads clearfix">
{foreach $banners as $banner}
    {* test if exceptions *}
    {$exceptions=','|explode:$banner['exceptions']}
    {$display = true}
    {foreach $exceptions as $exception}
        {if $page_name == trim($exception) || ($page_name == 'authentication' && trim($exception) == 'auth')}
            {$display = false}{break}
        {/if}
    {/foreach}

    {if empty($exceptions) || $display}
        <li class="banner {if !empty($banner['class'])}{$banner['class']}{/if}" data-id="{$banner['id_item']}">
        {if !$banner['embed'] || empty($banner['embed_code'])} {* simple image ad *}
            {if !empty($banner['url'])}<a href="{$banner['url']}" {if $banner['target']}target="_blank"{/if} title="{$banner['title']}" >{/if}
                <img src="{$image_path}{$banner['image']}" alt="{$banner['title']}" />
            {if !empty($banner['url'])}</a>{/if}
        {else if !empty($banner['embed_code'])} {* embeded ad *}
            {if $banner['embed_popup']}
                <a href="#easyadsembed_{$banner['id_item']}" title="{$banner['title']}" class="embed_popup">
                    <img src="{$image_path}{$banner['image']}" alt="{$banner['title']}" />
                </a>
            {/if}
            <div id="easyadsembed_{$banner['id_item']}" class="embed {if $banner['embed_popup']}embed_popup{/if}">
                {$banner['embed_code']}
            </div>
        {/if}
        </li>
    {/if}
{/foreach}
</ul>