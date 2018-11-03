{if isset($orderby) AND isset($orderway)}
    <ul id="view_nav" class="clearfix">
        <li class="display-title">{l s='View:'}</li>
        <li id="grid"><a rel="nofollow" href="#" title="{l s='Grid'}"><span class="glyphicon glyphicon-th-large"></span></a></li>
        <li id="list"><a rel="nofollow" href="#" title="{l s='List'}"><span class="glyphicon glyphicon-th-list"></span></a></li>
    </ul>
    {* On 1.5 the var request is setted on the front controller. The next lines assure the retrocompatibility with some modules *}
    {if !isset($request)}
        <!-- Sort products -->
        {if isset($smarty.get.id_category) && $smarty.get.id_category}
            {assign var='request' value=$link->getPaginationLink('category', $category, false, true)}
        {elseif isset($smarty.get.id_manufacturer) && $smarty.get.id_manufacturer}
            {assign var='request' value=$link->getPaginationLink('manufacturer', $manufacturer, false, true)}
        {elseif isset($smarty.get.id_supplier) && $smarty.get.id_supplier}
            {assign var='request' value=$link->getPaginationLink('supplier', $supplier, false, true)}
        {else}
            {assign var='request' value=$link->getPaginationLink(false, false, false, true)}
        {/if}
    {/if}
    <form id="productsSortForm{if isset($paginationId)}_{$paginationId}{/if}" action="{$request|escape:'html':'UTF-8'}" class="productsSortForm" role="form">
        <div class="select clearfix form-group">
            <label for="selectProductSort{if isset($paginationId)}_{$paginationId}{/if}" class="control-label">{l s='Sort by'}</label>
            <select id="selectProductSort{if isset($paginationId)}_{$paginationId}{/if}" class="selectProductSort form-control">
                <option value="{$orderbydefault|escape:'html':'UTF-8'}:{$orderwaydefault|escape:'html':'UTF-8'}" {if $orderby eq $orderbydefault}selected="selected"{/if}>--</option>
                {if !$PS_CATALOG_MODE}
                    <option value="price:asc" {if $orderby eq 'price' AND $orderway eq 'asc'}selected="selected"{/if}>{l s='Price: Lowest first'}</option>
                    <option value="price:desc" {if $orderby eq 'price' AND $orderway eq 'desc'}selected="selected"{/if}>{l s='Price: Highest first'}</option>
                {/if}
                <option value="name:asc" {if $orderby eq 'name' AND $orderway eq 'asc'}selected="selected"{/if}>{l s='Product Name: A to Z'}</option>
                <option value="name:desc" {if $orderby eq 'name' AND $orderway eq 'desc'}selected="selected"{/if}>{l s='Product Name: Z to A'}</option>
                {if $PS_STOCK_MANAGEMENT && !$PS_CATALOG_MODE}
                    <option value="quantity:desc" {if $orderby eq 'quantity' AND $orderway eq 'desc'}selected="selected"{/if}>{l s='In stock'}</option>
                {/if}
                <option value="reference:asc" {if $orderby eq 'reference' AND $orderway eq 'asc'}selected="selected"{/if}>{l s='Reference: Lowest first'}</option>
                <option value="reference:desc" {if $orderby eq 'reference' AND $orderway eq 'desc'}selected="selected"{/if}>{l s='Reference: Highest first'}</option>
            </select>
        </div>
    </form>
    <!-- /Sort products -->
    {if !isset($paginationId) || $paginationId == ''}
        {addJsDef request=$request}
    {/if}
{/if}
