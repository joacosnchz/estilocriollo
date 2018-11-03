{include file="$tpl_dir./errors.tpl"}
{if isset($category)}
    {if $category->id AND $category->active}
        {if $scenes || $category->description || $category->id_image}
            <div class="category-image frame_wrap">
                {if $scenes}
                    <div class="content_scene">
                        <!-- Scenes -->
                        {include file="$tpl_dir./scenes.tpl" scenes=$scenes}
                        {if $category->description}
                            <div class="cat_desc rte">
                                {if Tools::strlen($category->description) > 350}
                                    <div id="category_description_short">{$description_short}</div>
                                    <div id="category_description_full" class="unvisible">{$category->description}</div>
                                    <a href="{$link->getCategoryLink($category->id_category, $category.link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more">{l s='More'}</a>
                                {else}
                                    <div>{$category->description}</div>
                                {/if}
                            </div>
                        {/if}
                    </div>
                {else}
                    <!-- Category image -->
                    {if $category->id_image}
                    <div class="content_scene_cat_bg">
                        <img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}" alt="{$category->name|escape:'html':'UTF-8'}" />
                        {if $category->description}
                            <div class="cat_desc">
                                <span class="category-name">
                                    {strip}
                                        {$category->name|escape:'html':'UTF-8'}
                                        {if isset($categoryNameComplement)}
                                            {$categoryNameComplement|escape:'html':'UTF-8'}
                                        {/if}
                                    {/strip}
                                </span>
                                {if Tools::strlen($category->description) > 350}
                                    <div id="category_description_short" class="rte">{$description_short}</div>
                                    <div id="category_description_full" class="unvisible rte">{$category->description}</div>
                                    <a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more">{l s='More'}</a>
                                {else}
                                    <div class="rte">{$category->description}</div>
                                {/if}
                            </div>
                        {/if}
                    </div>
                    {/if}
                {/if}
            </div>
        {/if}
        {if isset($subcategories) && isset($show_subcat) && $show_subcat}
            <div id="subcategories" class="visible-lg">
                <div class="frame_wrap frame_wrap_header clearfix">
                    <div class="fwh-title">{l s='Subcategories'}</div>
                </div>
                <div class="frame_wrap frame_wrap_content subcategory-frame-wrap clearfix">
                    <ul id="splash">
                        {foreach from=$subcategories item=subcategory}
                            <li>
                                {if $subcategory.id_image}
                                    <img class="img-responsive" src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'subcategory')}" alt="{$subcategory.name|escape:'html':'UTF-8'}" />
                                {else}
                                    <img class="replace-2x img-responsive" src="{$img_cat_dir}default-medium_default.jpg" alt="" width="{$mediumSize.width}" height="{$mediumSize.height}" />
                                {/if}
                                <div class="caption clearfix">
                                    <a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}">{$subcategory.name|escape:'html':'UTF-8'}</a>
                                    {if $subcategory.description}
                                        <p class="splash-text clearfix">{$subcategory.description}</p>
                                    {/if}
                                </div>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
        {/if}
        {if $products}
            <div class="frame_wrap frame_wrap_header clearfix">
                <div class="fwh-title">
                    {$category->name|escape:'html':'UTF-8'}{if isset($categoryNameComplement)}&nbsp;{$categoryNameComplement|escape:'html':'UTF-8'}{/if}</h1>
                </div>
                <div class="frame_secondary">
                    <div class="product-count">
                        {if ($n*$p) < $nb_products }
                            {assign var='productShowing' value=$n*$p}
                        {else}
                            {assign var='productShowing' value=($n*$p-$nb_products-$n*$p)*-1}
                        {/if}
                        {if $p==1}
                            {assign var='productShowingStart' value=1}
                        {else}
                            {assign var='productShowingStart' value=$n*$p-$n+1}
                        {/if}
                        {if $nb_products > 1}
                            {l s='Showing %1$d - %2$d of %3$d items' sprintf=[$productShowingStart, $productShowing, $nb_products]}
                            {else}
                            {l s='Showing %1$d - %2$d of 1 item' sprintf=[$productShowingStart, $productShowing]}
                        {/if}
                    </div>
                </div>
            </div>
            <div class="frame_wrap frame_wrap_content">
                <div class="content_sortPagiBar clearfix">
                    <div class="sortPagiBar clearfix">
                        {include file="./product-sort.tpl"}
                        {include file="./nbr-product-page.tpl"}
                        {include file="./product-compare.tpl"}
                    </div>
                </div>
                {include file="./product-list.tpl" products=$products}
            </div>
            <div class="content_sortPagiBar">
                <div class="bottom-pagination-content clearfix">
                    {include file="./pagination.tpl" paginationId='bottom'}
                </div>
            </div>
        {/if}
    {elseif $category->id}
        <p class="alert alert-warning">{l s='This category is currently unavailable.'}</p>
    {/if}
{/if}