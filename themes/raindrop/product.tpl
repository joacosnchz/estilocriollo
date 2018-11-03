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
{include file="$tpl_dir./errors.tpl"}
{if $errors|@count == 0}
    {if !isset($priceDisplayPrecision)}
        {assign var='priceDisplayPrecision' value=2}
    {/if}
    {if !$priceDisplay || $priceDisplay == 2}
        {assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, $priceDisplayPrecision)}
        {assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
    {elseif $priceDisplay == 1}
        {assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, $priceDisplayPrecision)}
        {assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(true, $smarty.const.NULL)}
    {/if}
    <div class="primary_block row 
         {if $product_image_format == 'portrait'}pb-image-portrait{elseif $product_image_format == 'landscape'}pb-image-landscape{/if} {* PRODUCT IMAGE FORMAT *}
         {if isset($thumbs_position)}pb-thumbs-{$thumbs_position}{/if}" {* PRODUCT IMAGE THUMBNAILS POSITION *}
         itemscope itemtype="http://schema.org/Product">
        {if isset($adminActionDisplay) && $adminActionDisplay}
            <div id="admin-action">
                <p>{l s='This product is not visible to your customers.'}
                    <input type="hidden" id="admin-action-product-id" value="{$product->id}" />
                    <input type="submit" value="{l s='Publish'}" name="publish_button" class="exclusive" />
                    <input type="submit" value="{l s='Back'}" name="lnk_view" class="exclusive" />
                </p>
                <p id="admin-action-result"></p>
            </div>
        {/if}
        {if isset($confirmation) && $confirmation}
            <p class="confirmation">
                {$confirmation}
            </p>
        {/if}
        
        {* AVAILABILITY MESSAGE *}
        {if !$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT}
            <div class="product_warnings col-xs-12">
                <div id="availabilityWarning" class="alert alert-danger" style="display: none;">
                    {if $product->quantity <= 0}
                        {if $allow_oosp}
                            {$product->available_later}
                        {else}
                            {l s='This product is no longer in stock'}
                        {/if}
                    {/if}
                </div>
                <div class="alert alert-warning" id="last_quantities"{if ($product->quantity > $last_qties || $product->quantity <= 0) || $allow_oosp || !$product->available_for_order} style="display: none"{/if} >
                    {l s='Warning: Last items in stock!'}
                </div>
            </div>
        {/if}
        {* & AVAILABILITY MESSAGE *}
        
        <!-- left infos-->  
        <div class="pb-left-column 
            {if !$content_only}
                {if $product_image_format == 'portrait'}col-lg-5{elseif $product_image_format == 'landscape'}col-lg-6{/if} 
                col-lg-offset-0 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12
            {else}
                col-xs-5
            {/if}
        ">
            <div class="frame_wrap clearfix pb-product-img">
                {* THUMBNAILS LEFT *}
                {if isset($thumbs_position) && $thumbs_position == 'left' && isset($images) && count($images) > 0}
                    <!-- thumbnails -->
                    <div id="views_block" class="clearfix {if count($images) < 2}hidden{/if}">
                        <div id="thumbs_list">
                            <ul id="thumbs_list_frame">
                                {foreach from=$images item=image name=thumbnails}
                                    {assign var=imageIds value="`$product->id`-`$image.id_image`"}
                                    {if !empty($image.legend)}
                                        {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
                                    {else}
                                        {assign var=imageTitle value=$product->name|escape:'html':'UTF-8'}
                                    {/if}
                                    <li id="thumbnail_{$image.id_image}"{if $smarty.foreach.thumbnails.last} class="last"{/if}>
                                        <a{if $jqZoomEnabled && $have_image && !$content_only} href="javascript:void(0);" rel="{literal}{{/literal}gallery: 'gal1', smallimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'large_default')|escape:'html':'UTF-8'}',largeimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}'{literal}}{/literal}"{else} href="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}"	data-fancybox-group="other-views" class="fancybox{if $image.id_image == $cover.id_image} shown{/if}"{/if} title="{$imageTitle}">
                                            <img class="img-responsive" id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'cart_default')|escape:'html':'UTF-8'}" alt="{$imageTitle}" title="{$imageTitle}" height="{$cartSize.height}" width="{$cartSize.width}" itemprop="image" />
                                        </a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div> <!-- end thumbs_list -->
                        {if count($images) > 3}
                        <div id="view_scroll_left" class="btn btn-arrow" title="{l s='Other views'}"><i class="icon-chevron-up"></i></div>
                        <div id="view_scroll_right" class="btn btn-arrow" title="{l s='Other views'}"><i class="icon-chevron-down"></i></div>
                        {/if}
                    </div> <!-- end views-block -->
                    <!-- end thumbnails -->
                {/if}
                {* & THUMBNAILS LEFT *}
                
                <!-- product img-->        
                <div id="image-block" class="clearfix">
                    {if $product->specificPrice && $product->specificPrice.reduction_type == 'percentage'}
                        <span id="reduction_percent_display" class="box-label box-label-left">
                            {if $product->specificPrice && $product->specificPrice.reduction_type == 'percentage'}-{$product->specificPrice.reduction*100}%{/if}
                        </span>
                    {/if}
                    {if $product->new}
                        <span class="sale-label sale-box">{l s='New'}</span>
                    {/if}
                    {if $product->on_sale}
                        <span class="sale-label sale-box">{l s='Sale!'}</span>
                    {/if}
                    {if $product->online_only}
                        <span class="online_only sale-box">{l s='Online only'}</span>
                    {/if}
                    
                    {if $have_image}
                        <div id="view_full_size">
                            {if $jqZoomEnabled && $have_image && !$content_only}
                                <a class="jqzoom" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" rel="gal1" href="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'thickbox_default')|escape:'html':'UTF-8'}" itemprop="url">
                                    <img itemprop="image" src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html':'UTF-8'}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}"/>
                                </a>
                            {else}
                                <img id="bigpic" itemprop="image" src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html':'UTF-8'}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" width="{$largeSize.width}" height="{$largeSize.height}"/>
                                {if !$content_only}
                                    <span class="span_link no-print">{l s='View larger'}</span>
                                {/if}
                            {/if}
                            {if !$content_only}
                                <span class="span_link">
                                    {l s='View larger'}
                                </span>
                            {/if}
                        </div>
                    {else}
                        <div id="view_full_size">
                            <img itemprop="image" src="{$img_prod_dir}{$lang_iso}-default-large_default.jpg" id="bigpic" alt="" title="{$product->name|escape:'html':'UTF-8'}" />
                            {if !$content_only}
                                <span class="span_link">
                                    {l s='View larger'}
                                </span>
                            {/if}
                        </div>
                    {/if}
                </div> <!-- end image-block -->
                {* THUMBNAILS BOTTOM OR RIGHT *}
                {if isset($thumbs_position) && ($thumbs_position == 'bottom' || $thumbs_position == 'right') && isset($images) && count($images) > 0}
                    <!-- thumbnails -->
                    <div id="views_block" class="clearfix {if count($images) < 2}hidden{/if}">
                        {if $thumbs_position == 'bottom' && count($images) > 3}
                        <div id="view_scroll_left" class="btn btn-arrow" title="{l s='Other views'}"><i class="icon-chevron-left"></i></div>
                        {/if}
                        <div id="thumbs_list">
                            <ul id="thumbs_list_frame">
                                {foreach from=$images item=image name=thumbnails}
                                    {assign var=imageIds value="`$product->id`-`$image.id_image`"}
                                    {if !empty($image.legend)}
                                        {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
                                    {else}
                                        {assign var=imageTitle value=$product->name|escape:'html':'UTF-8'}
                                    {/if}
                                    <li id="thumbnail_{$image.id_image}"{if $smarty.foreach.thumbnails.last} class="last"{/if}>
                                        <a 
                                            {if $jqZoomEnabled && $have_image && !$content_only}
                                                href="javascript:void(0);"
                                                rel="{literal}{{/literal}gallery: 'gal1', smallimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'large_default')|escape:'html':'UTF-8'}',largeimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}'{literal}}{/literal}"
                                            {else}
                                                href="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}"
                                                data-fancybox-group="other-views"
                                                class="fancybox{if $image.id_image == $cover.id_image} shown{/if}"
                                            {/if}
                                            title="{$imageTitle}">
                                            <img class="img-responsive" id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'cart_default')|escape:'html':'UTF-8'}" alt="{$imageTitle}" title="{$imageTitle}" height="{$cartSize.height}" width="{$cartSize.width}" itemprop="image" />
                                        </a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div> <!-- end thumbs_list -->
                        {if count($images) > 3}
                            {if $thumbs_position == 'bottom'}
                                <div id="view_scroll_right" class="btn btn-arrow" title="{l s='Other views'}"><i class="icon-chevron-right"></i></div>
                            {elseif $thumbs_position == 'right'}
                                <div id="view_scroll_left" class="btn btn-arrow" title="{l s='Other views'}"><i class="icon-chevron-up"></i></div>
                                <div id="view_scroll_right" class="btn btn-arrow" title="{l s='Other views'}"><i class="icon-chevron-down"></i></div>
                            {/if}
                        {/if}
                    </div> <!-- end views-block -->
                    <!-- end thumbnails -->
                {/if}
                {* & THUMBNAILS BOTTOM OR RIGHT *}
                {if isset($images) && count($images) > 1}
                <div id="wrapResetImages" style="display: none;">
                    <a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" class="btn btn-default button no-border-radius" name="resetImages">
                        <i class="icon-repeat"></i>
                        {l s='Display all pictures'}
                    </a>
                </div>
                {/if}
            </div>
            {if $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
        </div> <!-- end pb-left-column -->
        <!-- end left infos--> 
<!-- pb-right-column-->
<div class="pb-right-column 
    {if !$content_only}
        {if $product_image_format == 'portrait'}col-lg-7{elseif $product_image_format == 'landscape'}col-lg-6{/if} col-xs-12
    {else}
        col-xs-7
    {/if}
">
    <div class="product_header">
        <div class="table-block">
            <h1 class="table-cell" itemprop="name">{$product->name|escape:'html':'UTF-8'}</h1>
            {* AVERAGE RATING STARS*}
            {if isset($nbComments) && isset($averageTotal) && $nbComments != 0 && $averageTotal > 0}
                <div class="table-cell">
                    {assign var="roundAvgRating" value=($averageTotal*2)|floor /2}
                    <div class="star_content clearfix">
                        {for $i=0 to 4}
                            {if $roundAvgRating >= 1}
                                <div class="star star_on"></div>
                            {elseif $roundAvgRating == 0.5}
                                <div class="star star_half"></div>
                            {else}
                                <div class="star"></div>
                            {/if}
                            {assign var=roundAvgRating value=$roundAvgRating-1}
                        {/for}
                        <meta itemprop="worstRating" content = "0" />
                        <meta itemprop="ratingValue" content = "2" />
                        <meta itemprop="bestRating" content = "5" />
                        <span class="hidden" itemprop="ratingValue">{$averageTotal}</span> 
                    </div>
                </div>
            {/if}
            {* & AVERAGE RATING STARS*}
        </div>
        <div class="table-block">
            <div class="table-cell" id="product_reference"{if empty($product->reference) || !$product->reference} style="display: none;"{/if}>
                <label>{l s='Model'} </label>
                <span class="editable" itemprop="sku">{if !isset($groups)}{$product->reference|escape:'html':'UTF-8'}{/if}</span>
            </div>
            {if $product->condition}
            <div class="table-cell" id="product_condition">
                <label>{l s='Condition'} </label>
                {if $product->condition == 'new'}
                    <link itemprop="itemCondition" href="http://schema.org/NewCondition"/>
                    <span class="editable">{l s='New'}</span>
                {elseif $product->condition == 'used'}
                    <link itemprop="itemCondition" href="http://schema.org/UsedCondition"/>
                    <span class="editable">{l s='Used'}</span>
                {elseif $product->condition == 'refurbished'}
                    <link itemprop="itemCondition" href="http://schema.org/RefurbishedCondition"/>
                    <span class="editable">{l s='Refurbished'}</span>
                {/if}
            </div>
            {/if}
            <div class="table-cell" id="availability_date"{if ($product->quantity > 0) || !$product->available_for_order || $PS_CATALOG_MODE || !isset($product->available_date) || $product->available_date < $smarty.now|date_format:'%Y-%m-%d'} style="display: none;"{/if}>
                <label id="availability_date_label">{l s='Availability date:'}</label>
                <span id="availability_date_value">{dateFormat date=$product->available_date full=false}</span>
            </div>
            {if ($display_qties == 1 && !$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && $product->available_for_order)}
                <!-- number of item in stock -->
                <div class="table-cell" id="pQuantityAvailable"{if $product->quantity <= 0} style="display: none;"{/if}>
                    <span id="quantityAvailable">{$product->quantity|intval}</span>
                    <span {if $product->quantity > 1} style="display: none;"{/if} id="quantityAvailableTxt">{l s='Item'}</span>
                    <span {if $product->quantity == 1} style="display: none;"{/if} id="quantityAvailableTxtMultiple">{l s='Items'}</span>
                </div>
            {/if}
            {if $PS_STOCK_MANAGEMENT}
                <!-- availability -->
                <div class="table-cell" id="availability_statut"{if ($product->quantity <= 0 && !$product->available_later && $allow_oosp) || ($product->quantity > 0 && !$product->available_now) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
                    <span id="availability_value" class="label label-success">{$product->available_now}</span>				
                </div>
                {hook h="displayProductDeliveryTime" product=$product}
            {/if}
        </div>
    </div>
    
    <div id="productTabs">
        <ul class="nav nav-tabs frame_wrap frame_wrap_header clearfix">
            <li class="active"><a href="#pdtBox1" data-toggle="tab">{l s='Info'}</a></li>
            {if $product->description}<li><a href="#pdtBox2" data-toggle="tab">{l s='Description'}</a></li>{/if}
            {if !$content_only}
                {if isset($features) && $features}<li><a href="#pdtBox3" data-toggle="tab">{l s='Data sheet'}</a></li>{/if}
                {if $product->customizable}<li><a href="#pdtBox4" data-toggle="tab">{l s='Customization'}</a></li>{/if}
                {if isset($attachments) && $attachments}<li><a href="#pdtBox5" data-toggle="tab">{l s='Download'}</a></li>{/if}
                {if isset($HOOK_PRODUCT_TAB) && $HOOK_PRODUCT_TAB|trim}{$HOOK_PRODUCT_TAB}{/if}
            {/if}
        </ul>
        <div class="tab-content frame_wrap frame_wrap_content">
            {* INFO TAB *}
            <div id="pdtBox1" class="tab-pane active">
                {* SHORT DESCRIPTION *}
                {if $product->description_short || $packItems|@count > 0}
                    <div id="short_description_block">
                        {if $product->description_short}
                            <div id="short_description_content" itemprop="description">{$product->description_short}</div>
                        {/if}
                        <!--{if $packItems|@count > 0}
                                <div class="short_description_pack">
                                <h3>{l s='Pack content'}</h3>
                        {foreach from=$packItems item=packItem}
                        
                        <div class="pack_content">
                            {$packItem.pack_quantity} x <a href="{$link->getProductLink($packItem.id_product, $packItem.link_rewrite, $packItem.category)|escape:'html':'UTF-8'}">{$packItem.name|escape:'html':'UTF-8'}</a>
                            <p>{$packItem.description_short}</p>
                        </div>
                            {/foreach}
                    </div>
                        {/if}-->
                    </div> <!-- end short_description_block -->
                {/if}
                {* & SHORT DESCRIPTION *}
                <!-- Out of stock hook -->
                <div id="oosHook"{if $product->quantity > 0} style="display: none;"{/if}>
                    {$HOOK_PRODUCT_OOS}
                </div>
                {* QUANTITY DISCOUNT INFO *}
                {if !$content_only && isset($quantity_discounts) && count($quantity_discounts) > 0}
                    <!-- quantity discount -->
                    <div id="quantityDiscount">
                        <h4>{l s='Volume discounts'}</h4>
                        <table class="std table-product-discounts table table-hover">
                            <thead>
                                <tr>
                                    <th>{l s='Quantity'}</th>
                                    <th>{if $display_discount_price}{l s='Price'}{else}{l s='Discount'}{/if}</th>
                                    <th>{l s='You Save'}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
                                   <tr id="quantityDiscount_{$quantity_discount.id_product_attribute}" class="quantityDiscount_{$quantity_discount.id_product_attribute}" data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value|floatval}" data-discount-quantity="{$quantity_discount.quantity|intval}">
                                        <td>
                                                {$quantity_discount.quantity|intval}
                                        </td>
                                        <td>
                                            {if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
                                                {if $display_discount_price}
                                                    {convertPrice price=$productPrice-$quantity_discount.real_value|floatval}
                                                {else}
                                                    {convertPrice price=$quantity_discount.real_value|floatval}
                                                {/if}
                                            {else}
                                                {if $display_discount_price}
                                                    {convertPrice price = $productPrice-($productPrice*$quantity_discount.reduction)|floatval}
                                                {else}
                                                    {$quantity_discount.real_value|floatval}%
                                                {/if}
                                            {/if}
                                        </td>
                                        <td>
                                            <span>{l s='Up to'}</span>
                                            {if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
                                                {$discountPrice=$productPrice-$quantity_discount.real_value|floatval}
                                            {else}
                                                {$discountPrice=$productPrice-($productPrice*$quantity_discount.reduction)|floatval}
                                            {/if}
                                            {$discountPrice=$discountPrice*$quantity_discount.quantity}
                                            {$qtyProductPrice = $productPrice*$quantity_discount.quantity}
                                            {convertPrice price=$qtyProductPrice-$discountPrice}
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                {/if}
                {* & QUANTITY DISCOUNT INFO *}
                {* PRODUCT BUY BLOCK *}
                {if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
                    <!-- add to cart form-->
                    <form id="buy_block" class="form-horizontal {if $PS_CATALOG_MODE && !isset($groups) && $product->quantity > 0} hidden"{/if} action="{$link->getPageLink('cart')|escape:'html':'UTF-8'}" method="post">
                        <!-- hidden datas -->
                        <p class="hidden">
                            <input type="hidden" name="token" value="{$static_token}" />
                            <input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id" />
                            <input type="hidden" name="add" value="1" />
                            <input type="hidden" name="id_product_attribute" id="idCombination" value="" />
                        </p>
                        <div class="box-info-product">
                            <div class="product_attributes clearfix">
                        <!-- minimal quantity wanted -->
                        <div id="minimal_quantity_wanted_p" class="alert alert-warning" {if $product->minimal_quantity <= 1 || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
                            {l s='This product is not sold individually. You must select at least'} <b id="minimal_quantity_label">{$product->minimal_quantity}</b> {l s='quantity for this product.'}
                        </div>
                        <!-- quantity wanted -->
                        {if !$PS_CATALOG_MODE}
                            <div id="quantity_wanted_p" class="form-group" {if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
                                <label class="col-md-4 control-label">{l s='Quantity:'}</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a href="#" data-field-qty="qty" class="btn btn-default button-minus product_quantity_down">
                                                <span><i class="icon-minus"></i></span>
                                            </a>
                                        </span>
                                        <input type="text" name="qty" id="quantity_wanted" class="form-control" value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}{if $product->minimal_quantity > 1}{$product->minimal_quantity}{else}1{/if}{/if}" />
                                        <span class="input-group-btn">
                                            <a href="#" data-field-qty="qty" class="btn btn-default button-plus product_quantity_up">
                                                <span><i class="icon-plus"></i></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        {/if}
                        {if isset($groups)}
                            <!-- attributes -->
                            <div id="attributes">
                                {foreach from=$groups key=id_attribute_group item=group}
                                    {if $group.attributes|@count}
                                        <fieldset class="attribute_fieldset form-group">
                                            <label class="attribute_label col-md-4 control-label" {if $group.group_type != 'color' && $group.group_type != 'radio'}for="group_{$id_attribute_group|intval}"{/if}>{$group.name|escape:'html':'UTF-8'} :&nbsp;</label>
                                            {assign var="groupName" value="group_$id_attribute_group"}
                                            <div class="attribute_list col-md-8">
                                                {if ($group.group_type == 'select')}
                                                    <select name="{$groupName}" id="group_{$id_attribute_group|intval}" class="form-control attribute_select no-print">
                                                        {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                                            <option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if} title="{$group_attribute|escape:'html':'UTF-8'}">{$group_attribute|escape:'html':'UTF-8'}</option>
                                                        {/foreach}
                                                    </select>
                                                {elseif ($group.group_type == 'color')}
                                                    <ul id="color_to_pick_list" class="clearfix">
                                                        {assign var="default_colorpicker" value=""}
                                                        {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                                            {assign var='img_color_exists' value=file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
                                                            <li{if $group.default == $id_attribute} class="selected"{/if}>
                                                                <a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" id="color_{$id_attribute|intval}" name="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" class="color_pick{if ($group.default == $id_attribute)} selected{/if}"{if !$img_color_exists && isset($colors.$id_attribute.value) && $colors.$id_attribute.value} style="background:{$colors.$id_attribute.value|escape:'html':'UTF-8'};"{/if} title="{$colors.$id_attribute.name|escape:'html':'UTF-8'}">
                                                                    {if $img_color_exists}
                                                                        <img src="{$img_col_dir}{$id_attribute|intval}.jpg" alt="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" title="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" width="20" height="20" />
                                                                    {/if}
                                                                </a>
                                                            </li>
                                                            {if ($group.default == $id_attribute)}
                                                                {$default_colorpicker = $id_attribute}
                                                            {/if}
                                                        {/foreach}
                                                    </ul>
                                                    <input type="hidden" class="color_pick_hidden" name="{$groupName|escape:'html':'UTF-8'}" value="{$default_colorpicker|intval}" />
                                                {elseif ($group.group_type == 'radio')}
                                                    <ul>
                                                        {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                                            <li>
                                                                <input type="radio" class="attribute_radio" name="{$groupName|escape:'html':'UTF-8'}" value="{$id_attribute}" {if ($group.default == $id_attribute)} checked="checked"{/if} />
                                                                <span>{$group_attribute|escape:'html':'UTF-8'}</span>
                                                            </li>
                                                        {/foreach}
                                                    </ul>
                                                {/if}
                                            </div> <!-- end attribute_list -->
                                        </fieldset>
                                    {/if}
                                {/foreach}
                            </div> <!-- end attributes -->
                        {/if}
                    </div> <!-- end product_attributes -->
                </div> <!-- end box-info-product -->
                </form>
                {/if}
                {* & PRODUCT BUY BLOCK *}
            </div>
            {* & INFO TAB *}
            {* DESCRIPTION TAB *}
            {if $product->description}
            <div id="pdtBox2" class="tab-pane">
                <div class="rte">{$product->description}</div>
            </div>
            {/if}
            {* & DESCRIPTION TAB *}
            {* DATA SHEET TAB *}
            {if !$content_only}
                {if isset($features) && $features}
                <div id="pdtBox3" class="tab-pane">
                <!-- Data sheet -->
                    <table class="table-data-sheet">			
                        {foreach from=$features item=feature}
                            <tr class="{cycle values="odd,even"}">
                                {if isset($feature.value)}			    
                                    <td>{$feature.name|escape:'html':'UTF-8'}</td>
                                    <td>{$feature.value|escape:'html':'UTF-8'}</td>
                                {/if}
                            </tr>
                        {/foreach}
                    </table>
                <!--end Data sheet -->
                </div>
                {/if}
                {* & DATA SHEET TAB *}
                {* CUSTOMIZATION TAB *}
                {if $product->customizable}
                <div id="pdtBox4" class="tab-pane">
                    <!--Customization -->
                    <form method="post" action="{$customizationFormTarget}" enctype="multipart/form-data" id="customizationForm" class="form-horizontal clearfix">
                        <div class="infoCustomizable alert alert-info">
                            {l s='After saving your customized product, remember to add it to your cart.'}
                            {if $product->uploadable_files}
                                <br />
                            {l s='Allowed file formats are: GIF, JPG, PNG'}{/if}
                        </div>
                        {if $product->uploadable_files|intval}
                            <div class="customizableProductsFile">
                                <h5 class="product-heading-h5">{l s='Pictures'}</h5>
                                <ul id="uploadable_files" class="clearfix">
                                    {counter start=0 assign='customizationField'}
                                    {foreach from=$customizationFields item='field' name='customizationFields'}
                                        {if $field.type == 0}
                                            <li class="customizationUploadLine{if $field.required} required{/if}">{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
                                                {if isset($pictures.$key)}
                                                    <div class="customizationUploadBrowse">
                                                        <img src="{$pic_dir}{$pictures.$key}_small" alt="" />
                                                        <a href="{$link->getProductDeletePictureLink($product, $field.id_customization_field)|escape:'html':'UTF-8'}" title="{l s='Delete'}" >
                                                            <img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="customization_delete_icon" width="11" height="13" />
                                                        </a>
                                                    </div>
                                                {/if}
                                                <div class="customizationUploadBrowse form-group">
                                                    <label class="customizationUploadBrowseDescription col-sm-2 control-label">
                                                        {if $field.required}<sup>*</sup>{/if}
                                                        {if !empty($field.name)}
                                                            {$field.name}
                                                        {else}
                                                            {l s='Please select an image file from your computer'}
                                                        {/if}
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <input type="file" name="file{$field.id_customization_field}" id="img{$customizationField}" class="form-control customization_block_input {if isset($pictures.$key)}filled{/if}" />
                                                    </div>
                                                </div>
                                            </li>
                                            {counter}
                                        {/if}
                                    {/foreach}
                                </ul>
                            </div>
                        {/if}
                        {if $product->text_fields|intval}
                            <div class="customizableProductsText">
                                <h5 class="product-heading-h5">{l s='Text'}</h5>
                                <ul id="text_fields">
                                    {counter start=0 assign='customizationField'}
                                    {foreach from=$customizationFields item='field' name='customizationFields'}
                                        {if $field.type == 1}
                                            <li class="customizationUploadLine form-group {if $field.required} required{/if}">
                                                <label for ="textField{$customizationField}" class="col-sm-2 control-label">
                                                    {if $field.required}<sup>*</sup>{/if}
                                                    {assign var='key' value='textFields_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
                                                    {if !empty($field.name)}
                                                        {$field.name}
                                                    {/if}
                                                </label>
                                                <div class="col-sm-8">
                                                    <textarea name="textField{$field.id_customization_field}" class="form-control customization_block_input" id="textField{$customizationField}" rows="3" cols="20">{strip}{if isset($textFields.$key)}{$textFields.$key|stripslashes}{/if}{/strip}</textarea>
                                                </div>
                                            </li>
                                            {counter}
                                        {/if}
                                    {/foreach}
                                </ul>
                            </div>
                        {/if}
                            <div class="clearfix">
                                <p class="clear required pull-left"><sup>*</sup> {l s='required fields'}</p>	
                                <p id="customizedDatas">
                                    <input type="hidden" name="quantityBackup" id="quantityBackup" value="" />
                                    <input type="hidden" name="submitCustomizedDatas" value="1" />
                                    <button class="btn exclusive no-border-radius" name="saveCustomization">
                                        <span>{l s='Save'}</span>
                                    </button>
                                    <span id="ajax-loader" class="unvisible">
                                        <img src="{$img_ps_dir}loader.gif" alt="loader" />
                                    </span>
                                </p>
                            </div>
                    </form>
                    <!--end Customization -->
                </div>
                {/if}
                {* & CUSTOMIZATION TAB *}
                {* DOWNLOAD TAB *}
                {if isset($attachments) && $attachments}
                <div id="pdtBox5" class="tab-pane">
                <!--Download -->
                {foreach from=$attachments item=attachment name=attachements}
                    {if $smarty.foreach.attachements.iteration %3 == 1}<div class="row">{/if}
                        <div class="col-lg-4">
                            <h4><a href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">{$attachment.name|escape:'html':'UTF-8'}</a></h4>
                            <p class="text-muted">{$attachment.description|escape:'html':'UTF-8'}</p>
                            <a class="btn btn-default btn-block" href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">
                                <i class="icon-download"></i>
                                {l s="Download"} ({Tools::formatBytes($attachment.file_size, 2)})
                            </a>
                            <hr>
                        </div>
                    {if $smarty.foreach.attachements.iteration %3 == 0 || $smarty.foreach.attachements.last}</div>{/if}
                {/foreach}
                <!--end Download -->
                </div>
                {/if}
                {* & DOWNLOAD TAB *}
                {if isset($HOOK_PRODUCT_TAB_CONTENT) && $HOOK_PRODUCT_TAB_CONTENT|trim}{$HOOK_PRODUCT_TAB_CONTENT}{/if}
            {/if}
        </div>
    </div>
    {* ADD TO CART AND PRICE *}
    <div class="box-cart-bottom clearfix {if $PS_CATALOG_MODE && !isset($groups) && $product->quantity > 0}hidden"{/if}">
        <div id="productButtons" {if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || (isset($restricted_country_mode) && $restricted_country_mode) || $PS_CATALOG_MODE} class="unvisible"{/if}>
            <div id="add_to_cart" class="buttons_bottom_block no-print">
                <button type="submit" name="Submit" class="btn btn-primary exclusive no-border-radius">
                    <span>{l s='Add to cart'}</span>
                </button>
            </div>
            {if isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS}{$HOOK_PRODUCT_ACTIONS}{/if}
        </div>
        <div class="content_prices clearfix">
            {if $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                {if $product->ecotax != 0}
                    <div class="price-ecotax">
                        <img src="{$img_dir}eco.gif" 
                             title="{l s='Include'} {if $priceDisplay == 2}{$ecotax_tax_exc|convertAndFormatPrice}{else}{$ecotax_tax_inc|convertAndFormatPrice}{/if} {l s='for green tax'}
                                    {if $product->specificPrice && $product->specificPrice.reduction}{l s='(not impacted by the discount)'}{/if}"
                        />
                    </div>
                {/if}
                <!-- prices -->
                <div class="price">
                    <div id="old_price"{if (!$product->specificPrice || !$product->specificPrice.reduction) && $group_reduction == 0} class="hidden"{/if}>
                        {if $priceDisplay >= 0 && $priceDisplay <= 2}
                            {hook h="displayProductPriceBlock" product=$product type="old_price"}
                            <span id="old_price_display">{if $productPriceWithoutReduction > $productPrice}{convertPrice price=$productPriceWithoutReduction}{/if}</span>
                            <!-- {if $tax_enabled && $display_tax_label == 1}{if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}{/if} -->
                        {/if}
                    </div>
                    <div class="our_price_display" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        {if $product->quantity > 0}<link itemprop="availability" href="http://schema.org/InStock"/>{/if}
                        {if $priceDisplay >= 0 && $priceDisplay <= 2}
                            <br><span id="our_price_display" itemprop="price">{if $product->wholesale_price}{if $product->wholesale_price > 0}{l s='Wholesaler'}:&nbsp;{convertPrice price=$product->wholesale_price}<br><br>{l s='Retail'}:&nbsp;{/if}{/if}</span>
                            <span style="float:right" id="our_price_display" itemprop="price">{convertPrice price=$product->price}</span>
                            <!--{if $tax_enabled  && ((isset($display_tax_label) && $display_tax_label == 1) || !isset($display_tax_label))}
                                {if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}
                            {/if}-->
                            <meta itemprop="priceCurrency" content="{$currency->iso_code}" />
                            {hook h="displayProductPriceBlock" product=$product type="price"}
                        {/if}
                    </div>
                    {if $priceDisplay == 2}
                        <br />
                        <span id="pretaxe_price">
                            <span id="pretaxe_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL)}</span>
                            {l s='tax excl.'}
                        </span>
                    {/if}
                </div> <!-- end prices -->
                <div id="reduction_amount" {if !$product->specificPrice || $product->specificPrice.reduction_type != 'amount' || $product->specificPrice.reduction|floatval ==0} style="display:none"{/if}>
                    <span id="reduction_amount_display">
                        {if $product->specificPrice && $product->specificPrice.reduction_type == 'amount' && $product->specificPrice.reduction|intval !=0}
                            -{convertPrice price=$productPriceWithoutReduction-$productPrice|floatval}
                        {/if}
                    </span>
                </div>
                {if $packItems|@count && $productPrice < $product->getNoPackPrice()}
                    <div class="pack_price">{l s='Instead of'} <span style="text-decoration: line-through;">{convertPrice price=$product->getNoPackPrice()}</span></div>
                {/if}
                {if !empty($product->unity) && $product->unit_price_ratio > 0.000000}
                    {math equation="pprice / punit_price"  pprice=$productPrice  punit_price=$product->unit_price_ratio assign=unit_price}
                    <div class="unit-price"><span id="unit_price_display">{convertPrice price=$unit_price}</span> {l s='per'} {$product->unity|escape:'html':'UTF-8'}</div>
                    {hook h="displayProductPriceBlock" product=$product type="unit_price"}
                {/if}
            {/if} {*close if for show price*}
            {hook h="displayProductPriceBlock" product=$product type="weight"}
            <div class="clear"></div>
        </div> <!-- end content_prices -->
    </div> <!-- end box-cart-bottom -->
    {* & ADD TO CART AND PRICE *}
    {if isset($HOOK_EXTRA_RIGHT) && $HOOK_EXTRA_RIGHT}{$HOOK_EXTRA_RIGHT}{/if}
</div> <!-- end pb-right-column-->

</div> <!-- end primary_block -->

{if !$content_only}
<div class="panel-group" id="productFooter">
    {if isset($accessories) && $accessories}
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <div id="accesories_prev"></div>
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#productFooter" href="#accesoriesBlock">{l s='Accessories'}</a>
            </h4>
            <div id="accesories_next"></div>
        </div>
        <div id="accesoriesBlock" class="panel-collapse collapse">
            <div class="panel-body">
                <ul class="bxslider clearfix" data-prev="#accesories_prev" data-next="#accesories_next">
                    {foreach from=$accessories item=accessory name=accessories_list}
                        {if ($accessory.allow_oosp || $accessory.quantity_all_versions > 0 || $accessory.quantity > 0) && $accessory.available_for_order && !isset($restricted_country_mode)}
                            {assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}
                            <li class="item product-box ajax_block_product{if $smarty.foreach.accessories_list.first} first_item{elseif $smarty.foreach.accessories_list.last} last_item{else} item{/if} product_accessories_description">
                                <div class="product_desc">
                                    <a href="{$accessoryLink|escape:'html':'UTF-8'}" title="{$accessory.legend|escape:'html':'UTF-8'}" class="product-image product_image">
                                        <img class="lazyOwl" src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$accessory.legend|escape:'html':'UTF-8'}" width="{$homeSize.width}" height="{$homeSize.height}"/>
                                    </a>
                                    <div class="block_description">
                                        <a href="{$accessoryLink|escape:'html':'UTF-8'}" title="{l s='More'}" class="product_description">
                                            {$accessory.description_short|strip_tags|truncate:25:'...'}
                                        </a>
                                    </div>
                                </div>
                                <div class="s_title_block">
                                    <h5 class="product-name">
                                        <a href="{$accessoryLink|escape:'html':'UTF-8'}">
                                            {$accessory.name|truncate:20:'...':true|escape:'html':'UTF-8'}
                                        </a>
                                    </h5>
                                    {if $accessory.show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                                        <span class="price">
                                            {if $priceDisplay != 1}
                                            {displayWtPrice p=$accessory.price}{else}{displayWtPrice p=$accessory.price_tax_exc}
                                            {/if}
                                        </span>
                                    {/if}
                                </div>
                                {if !$PS_CATALOG_MODE && ($accessory.allow_oosp || $accessory.quantity > 0)}
                                    <div class="no-print">
                                        <a class="btn button no-border-radius ajax_add_to_cart_button" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$accessory.id_product|intval}&amp;token={$static_token}&amp;add")|escape:'html':'UTF-8'}" data-id-product="{$accessory.id_product|intval}" title="{l s='Add to cart'}">
                                            <span>{l s='Add to cart'}</span>
                                        </a>
                                    </div>
                                {/if}
                            </li>
                        {/if}
                    {/foreach}
                </ul>
            </div>
        </div>
    </div>
    {/if}
    {if isset($HOOK_PRODUCT_FOOTER) && $HOOK_PRODUCT_FOOTER}{$HOOK_PRODUCT_FOOTER}{/if}
</div>
    {if isset($packItems) && $packItems|@count > 0}
        <section id="blockpack">
            <h3 class="page-product-heading">{l s='Pack content'}</h3>
            {include file="$tpl_dir./product-list.tpl" products=$packItems}
        </section>
    {/if}
{/if}

{strip}
    {if $thumbs_position == 'bottom'}
        {addJsDef thumbs_axis='x'}
    {elseif $thumbs_position == 'left' || $thumbs_position == 'right'}
        {addJsDef thumbs_axis='y'}
    {/if}
    
    {addJsDefL name=labelNotAvailable}{l s='Not Available' js=1}{/addJsDefL}

    
{if isset($smarty.get.ad) && $smarty.get.ad}
    {addJsDefL name=ad}{$base_dir|cat:$smarty.get.ad|escape:'html':'UTF-8'}{/addJsDefL}
{/if}
{if isset($smarty.get.adtoken) && $smarty.get.adtoken}
    {addJsDefL name=adtoken}{$smarty.get.adtoken|escape:'html':'UTF-8'}{/addJsDefL}
{/if}
{addJsDef allowBuyWhenOutOfStock=$allow_oosp|boolval}
{addJsDef availableNowValue=$product->available_now|escape:'quotes':'UTF-8'}
{addJsDef availableLaterValue=$product->available_later|escape:'quotes':'UTF-8'}
{addJsDef attribute_anchor_separator=$attribute_anchor_separator|addslashes}
{addJsDef attributesCombinations=$attributesCombinations}
{addJsDef currencySign=$currencySign|html_entity_decode:2:"UTF-8"}
{addJsDef currencyRate=$currencyRate|floatval}
{addJsDef currencyFormat=$currencyFormat|intval}
{addJsDef currencyBlank=$currencyBlank|intval}
{addJsDef currentDate=$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
{if isset($combinations) && $combinations}
    {addJsDef combinations=$combinations}
    {addJsDef combinationsFromController=$combinations}
    {addJsDef displayDiscountPrice=$display_discount_price}
    {addJsDefL name='upToTxt'}{l s='Up to' js=1}{/addJsDefL}
{/if}
{if isset($combinationImages) && $combinationImages}
    {addJsDef combinationImages=$combinationImages}
{/if}
{addJsDef customizationFields=$customizationFields}
{addJsDef default_eco_tax=$product->ecotax|floatval}
{addJsDef displayPrice=$priceDisplay|intval}
{addJsDef ecotaxTax_rate=$ecotaxTax_rate|floatval}
{addJsDef group_reduction=$group_reduction}
{if isset($cover.id_image_only)}
    {addJsDef idDefaultImage=$cover.id_image_only|intval}
{else}
    {addJsDef idDefaultImage=0}
{/if}
{addJsDef img_ps_dir=$img_ps_dir}
{addJsDef img_prod_dir=$img_prod_dir}
{addJsDef id_product=$product->id|intval}
{addJsDef jqZoomEnabled=$jqZoomEnabled|boolval}
{addJsDef maxQuantityToAllowDisplayOfLastQuantityMessage=$last_qties|intval}
{addJsDef minimalQuantity=$product->minimal_quantity|intval}
{addJsDef noTaxForThisProduct=$no_tax|boolval}
{addJsDef customerGroupWithoutTax=$customer_group_without_tax|boolval}
{addJsDef oosHookJsCodeFunctions=Array()}
{addJsDef productHasAttributes=isset($groups)|boolval}
{addJsDef productPriceTaxExcluded=($product->getPriceWithoutReduct(true)|default:'null' - $product->ecotax)|floatval}
{addJsDef productBasePriceTaxExcluded=($product->base_price - $product->ecotax)|floatval}
{addJsDef productBasePriceTaxExcl=($product->base_price|floatval)}
{addJsDef productReference=$product->reference|escape:'html':'UTF-8'}
{addJsDef productAvailableForOrder=$product->available_for_order|boolval}
{addJsDef productPriceWithoutReduction=$productPriceWithoutReduction|floatval}
{addJsDef productPrice=$productPrice|floatval}
{addJsDef productUnitPriceRatio=$product->unit_price_ratio|floatval}
{addJsDef productShowPrice=(!$PS_CATALOG_MODE && $product->show_price)|boolval}
{addJsDef PS_CATALOG_MODE=$PS_CATALOG_MODE}
{if $product->specificPrice && $product->specificPrice|@count}
    {addJsDef product_specific_price=$product->specificPrice}
{else}
    {addJsDef product_specific_price=array()}
{/if}
{if $display_qties == 1 && $product->quantity}
    {addJsDef quantityAvailable=$product->quantity}
{else}
    {addJsDef quantityAvailable=0}
{/if}
{addJsDef quantitiesDisplayAllowed=$display_qties|boolval}
{if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'percentage'}
    {addJsDef reduction_percent=$product->specificPrice.reduction*100|floatval}
{else}
    {addJsDef reduction_percent=0}
{/if}
{if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'amount'}
    {addJsDef reduction_price=$product->specificPrice.reduction|floatval}
{else}
    {addJsDef reduction_price=0}
{/if}
{if $product->specificPrice && $product->specificPrice.price}
    {addJsDef specific_price=$product->specificPrice.price|floatval}
{else}
    {addJsDef specific_price=0}
{/if}
{addJsDef specific_currency=($product->specificPrice && $product->specificPrice.id_currency)|boolval}
{addJsDef stock_management=$stock_management|intval}
{addJsDef taxRate=$tax_rate|floatval}
{addJsDefL name=doesntExist}{l s='This combination does not exist for this product. Please select another combination.' js=1}{/addJsDefL}
{addJsDefL name=doesntExistNoMore}{l s='This product is no longer in stock' js=1}{/addJsDefL}
{addJsDefL name=doesntExistNoMoreBut}{l s='with those attributes but is available with others.' js=1}{/addJsDefL}
{addJsDefL name=fieldRequired}{l s='Please fill in all the required fields before saving your customization.' js=1}{/addJsDefL}
{addJsDefL name=uploading_in_progress}{l s='Uploading in progress, please be patient.' js=1}{/addJsDefL}
{addJsDefL name='product_fileDefaultHtml'}{l s='No file selected' js=1}{/addJsDefL}
{addJsDefL name='product_fileButtonHtml'}{l s='Choose File' js=1}{/addJsDefL}
{/strip}
{/if}
