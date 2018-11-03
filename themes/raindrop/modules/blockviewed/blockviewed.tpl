<!-- Block Viewed products -->
<div id="viewed-products_block_left" class="block">
    <div class="frame_wrap frame_wrap_header clearfix">
        <div class="fwh-title">{l s='Viewed products' mod='blockviewed'}</div>
    </div>
    <div class="block_content products-block frame_wrap frame_wrap_content">
        <ul>
            {foreach from=$productsViewedObj item=viewedProduct name=myLoop}
                <li class="clearfix{if $smarty.foreach.myLoop.last} last_item{elseif $smarty.foreach.myLoop.first} first_item{else} item{/if}">
                    <a
                        class="products-block-image" 
                        href="{$viewedProduct->product_link|escape:'html':'UTF-8'}" 
                        title="{l s='More about %s' mod='blockviewed' sprintf=[$viewedProduct->name|escape:'html':'UTF-8']}" >
                        <img 
                            class="img-responsive"
                            src="{if isset($viewedProduct->id_image) && $viewedProduct->id_image}{$link->getImageLink($viewedProduct->link_rewrite, $viewedProduct->cover, 'medium')}{else}{$img_prod_dir}{$lang_iso}-default-sidebar_default.jpg{/if}" 
                            alt="{$viewedProduct->legend|escape:'html':'UTF-8'}" />
                    </a>
                    <div class="product-content">
                        <h5>
                            <a class="product-name" 
                               href="{$viewedProduct->product_link|escape:'html':'UTF-8'}" 
                               title="{l s='More about %s' mod='blockviewed' sprintf=[$viewedProduct->name|escape:'html':'UTF-8']}">
                                {$viewedProduct->name|truncate:25:'...'|escape:'html':'UTF-8'}
                            </a>
                        </h5>
                        <p class="product-description">{$viewedProduct->description_short|strip_tags:'UTF-8'|truncate:40}</p>
                    </div>
                </li>
            {/foreach}
        </ul>
    </div>
</div>
