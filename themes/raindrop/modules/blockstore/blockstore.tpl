<!-- Block stores module -->
<div id="stores_block_left" class="block">
    <div class="frame_wrap frame_wrap_header clearfix">
        <div class="fwh-title"><a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" title="{l s='Our stores' mod='blockstore'}">{l s='Our stores' mod='blockstore'}</a></div>
    </div>
    <div class="block_content blockstore frame_wrap frame_wrap_content">
        <div class="store_image">
            <a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" title="{l s='Our stores' mod='blockstore'}">
                <img class="img-responsive" src="{$link->getMediaLink("`$module_dir``$store_img|escape:'htmlall':'UTF-8'`")}" alt="{l s='Our stores' mod='blockstore'}" />
            </a>
        </div>
        {if !empty($store_text)}
        <p class="store-description">
        	{$store_text}
        </p>
        {/if}
        <div>
            <a 
                class="btn button no-border-radius" 
                href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" 
                title="{l s='Our stores' mod='blockstore'}">
                <span>{l s='Discover our stores' mod='blockstore'}</i></span>
            </a>
        </div>
    </div>
</div>
<!-- /Block stores module -->
