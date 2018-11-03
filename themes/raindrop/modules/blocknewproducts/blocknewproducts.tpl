<!-- MODULE Block new products -->
<div id="new-products_block_right" class="block products_block">
    <div class="frame_wrap frame_wrap_header clearfix">
        <div class="fwh-title"><a href="{$link->getPageLink('new-products')|escape:'html'}" title="{l s='New products' mod='blocknewproducts'}">{l s='New products' mod='blocknewproducts'}</a></div>
    </div>
    <div class="block_content products-block frame_wrap frame_wrap_content">
        {if $new_products !== false}
            <ul class="products">
                {foreach from=$new_products item=newproduct name=myLoop}
                    <li class="clearfix">
                        <a class="products-block-image" href="{$newproduct.link|escape:'html'}" title="{$newproduct.legend|escape:html:'UTF-8'}"><img class="replace-2x img-responsive" src="{$link->getImageLink($newproduct.link_rewrite, $newproduct.id_image, 'medium')|escape:'html'}" alt="{$newproduct.name|escape:html:'UTF-8'}" /></a>
                        <div class="product-content">
                            <h5>
                                <a class="product-name" href="{$newproduct.link|escape:'html'}" title="{$newproduct.name|escape:html:'UTF-8'}">{$newproduct.name|strip_tags|escape:html:'UTF-8'}</a>
                            </h5>
                            <p class="product-description">{$newproduct.description_short|strip_tags:'UTF-8'|truncate:75:'...'}</p>
                            {if (!$PS_CATALOG_MODE AND ((isset($newproduct.show_price) && $newproduct.show_price) || (isset($newproduct.available_for_order) && $newproduct.available_for_order)))}
                                {if isset($newproduct.show_price) && $newproduct.show_price && !isset($restricted_country_mode)}
                                    <div class="price-box">
                                        <span class="price">
                                    {if !$priceDisplay}{convertPrice price=$newproduct.price}{else}{convertPrice price=$newproduct.price_tax_exc}{/if}
                                </span>
                            </div>
                        {/if}
                    {/if}
                </div>
            </li>
        {/foreach}
    </ul>
    <div>
        <a href="{$link->getPageLink('new-products')|escape:'html'}" title="{l s='All new products' mod='blocknewproducts'}" class="btn button no-border-radius"><span>{l s='All new products' mod='blocknewproducts'}</span></a>
    </div>
{else}
    <p>&raquo; {l s='Do not allow new products at this time.' mod='blocknewproducts'}</p>
{/if}
</div>
</div>
<!-- /MODULE Block new products -->