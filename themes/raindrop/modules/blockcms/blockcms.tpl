{if $block == 1}
    <!-- Block CMS module -->
    {foreach from=$cms_titles key=cms_key item=cms_title}
        <section id="informations_block_left_{$cms_key}" class="block informations_block_left">
            <div class="frame_wrap frame_wrap_header clearfix">
                <div class="fwh-title"><a href="{$cms_title.category_link|escape:'html':'UTF-8'}">{if !empty($cms_title.name)}{$cms_title.name}{else}{$cms_title.category_name}{/if}</a></div>
            </div>
    <div class="block_content list-block frame_wrap frame_wrap_content">
        <ul>
            {foreach from=$cms_title.categories item=cms_page}
                {if isset($cms_page.link)}
                    <li class="bullet">
                        <a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.name|escape:'html':'UTF-8'}">
                            {$cms_page.name|escape:'html':'UTF-8'}
                        </a>
                    </li>
                {/if}
            {/foreach}
            {foreach from=$cms_title.cms item=cms_page}
                {if isset($cms_page.link)}
                    <li>
                        <a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.meta_title|escape:'html':'UTF-8'}">
                            {$cms_page.meta_title|escape:'html':'UTF-8'}
                        </a>
                    </li>
                {/if}
            {/foreach}
            {if $cms_title.display_store}
                <li>
                    <a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" title="{l s='Our stores' mod='blockcms'}">
                        {l s='Our stores' mod='blockcms'}
                    </a>
                </li>
            {/if}
        </ul>
    </div>
</section>
{/foreach}
<!-- /Block CMS module -->
{else}
    <!-- MODULE Block footer -->
    <section class="footer-block col-xs-12 col-sm-3 col-md-3 col-lg-3" id="block_various_links_footer">
        <h4>{l s='Information' mod='blockcms'}</h4>
        <ul class="toggle-footer">
            {if $show_price_drop && !$PS_CATALOG_MODE}
                <li class="item">
                    <a href="{$link->getPageLink('prices-drop')|escape:'html'}" title="{l s='Specials' mod='blockcms'}">{l s='Specials' mod='blockcms'}</a>
                </li>
            {/if}
            {if $show_new_products}
                <li class="item">
                    <a href="{$link->getPageLink('new-products')|escape:'html'}" title="{l s='New products' mod='blockcms'}">{l s='New products' mod='blockcms'}</a>
                </li>
            {/if}
            {if $show_best_sales && !$PS_CATALOG_MODE}
                <li class="item">
                    <a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='Best sellers' mod='blockcms'}">{l s='Best sellers' mod='blockcms'}</a>
                </li>
            {/if}
            {if $display_stores_footer}
                <li class="item">
                    <a href="{$link->getPageLink('stores')|escape:'html'}" title="{l s='Our stores' mod='blockcms'}">{l s='Our stores' mod='blockcms'}</a>
                </li>
            {/if}
            {if $show_contact}
                <li class="item">
                    <a href="{$link->getPageLink($contact_url, true)|escape:'html'}" title="{l s='Contact us' mod='blockcms'}">{l s='Contact us' mod='blockcms'}</a>
                </li>
            {/if}
            {foreach from=$cmslinks item=cmslink}
                {if $cmslink.meta_title != ''}
                    <li class="item">
                        <a href="{$cmslink.link|addslashes|escape:'html'}" title="{$cmslink.meta_title|escape:'html':'UTF-8'}">{$cmslink.meta_title|escape:'html':'UTF-8'}</a>
                    </li>
                {/if}
            {/foreach}
            {if $show_sitemap}
                <li>
                    <a href="{$link->getPageLink('sitemap')|escape:'html'}" title="{l s='Sitemap' mod='blockcms'}">{l s='Sitemap' mod='blockcms'}</a>
                </li>
            {/if}
            {if $display_poweredby}
                <li class="last_item">{l s='[1]Ecommerce software by %s[/1]' mod='blockcms' sprintf=['PrestaShop™'] tags=['<a class="_blank" href="http://www.prestashop.com">']}</li>
                {/if}
        </ul>
    </section>
    {if $footer_text}
        <section class="footer-block col-xs-12 col-sm-3 col-md-3 col-lg-3" id="blockcms_footer_text">
            {$footer_text}
        </section>
    {/if}
    
    {if $display_poweredby}
        <section class="bottom-footer col-xs-12">
            <div>
                &copy; 2014 {l s='[1]Ecommerce software by %s[/1]' mod='blockcms' sprintf=['PrestaShop™'] tags=['<a class="_blank" href="http://www.prestashop.com">']}
            </div>
        </section>
    {/if}
    <!-- /MODULE Block footer -->
{/if}
