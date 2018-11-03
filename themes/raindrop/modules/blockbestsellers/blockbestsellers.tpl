{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- MODULE Block best sellers -->
<div id="best-sellers_block_right" class="block products_block">
    <div class="frame_wrap frame_wrap_header clearfix">
        <div class="fwh-title"><a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='View a top sellers products' mod='blockbestsellers'}">{l s='Top sellers' mod='blockbestsellers'}</a></div>
    </div>
    <div class="block_content frame_wrap frame_wrap_content clearfix">
        {if $best_sellers && $best_sellers|@count > 0}
            <ul class="block_content products-block">
                {foreach $best_sellers as $product}
                    <li class="clearfix">
                        <a href="{$product.link|escape:'html'}" title="{$product.legend|escape:'html':'UTF-8'}" class="products-block-image content_img clearfix">
                            <img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'medium')|escape:'html'}" alt="{$product.legend|escape:'html':'UTF-8'}" />
                        </a>
                        <div class="product-content">
                            <h5>
                                <a class="product-name" href="{$product.link|escape:'html'}" title="{$product.legend|escape:'html':'UTF-8'}">
                                    {$product.name|strip_tags:'UTF-8'|escape:'html':'UTF-8'}
                                </a>
                            </h5>
                            <p class="product-description">{$product.description_short|strip_tags:'UTF-8'|truncate:75:'...'}</p>
                            {if !$PS_CATALOG_MODE}
                                <div class="price-box">
                                    <span class="price">{$product.price}</span>
                                </div>
                            {/if}
                        </div>
                    </li>
                    {if $product@iteration == 2}
                        {break}
                    {/if}

                {/foreach}
            </ul>
            <div class="lnk">
                <a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='All best sellers' mod='blockbestsellers'}"  class="btn button no-border-radius"><span>{l s='All best sellers' mod='blockbestsellers'}</span></a>
            </div>
        {else}
            <p>{l s='No best sellers at this time' mod='blockbestsellers'}</p>
        {/if}
    </div>
</div>
<!-- /MODULE Block best sellers -->