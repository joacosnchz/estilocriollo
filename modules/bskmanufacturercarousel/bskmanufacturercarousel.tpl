<div id="bskmanufacturercarousel" class="clearfix">
    <h4>
        <a href="{$link->getPageLink('manufacturer')}" title="{l s='Manufacturers' mod='blockmanufacturer'}">{l s='Manufacturers' mod='bskmanufacturercarousel'}</a>
    </h4>
    {if $manufacturers}
        <div class="table">
            <div id="bskmanucar_prev" class="btn btn-primary btn-prev table-cell"><i class="icon-chevron-left"></i></div>
            <div class="table-cell">
                <div class="carousel">
                    <ul>
                        {foreach $manufacturers as $manufacturer}
                            <li data-href="{$link->getManufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html'}">
                                <img src="{$img_manu_dir}{$manufacturer.image}" alt="{$manufacturer.name}" />
                                {if $show_name}
                                    <span class="name">{$manufacturer.name}</span>
                                {/if}
                                {if $show_prod_nb && $manufacturer.nb_products > 0}
                                    <span class="prod_nb label label-danger">{$manufacturer.nb_products}</span>
                                {/if}
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
            <div id="bskmanucar_next" class="btn btn-primary btn_next table-cell"><i class="icon-chevron-right"></i></div>
        </div>
    {else}
        <div class="alert alert-warning">
            {l s='No manufacturers to display' mod='bskmanufacturercarousel'}
        </div>                
    {/if}
</div>