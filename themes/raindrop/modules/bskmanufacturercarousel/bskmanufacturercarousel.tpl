<div id="bskmanufacturercarousel" class="clearfix">
    <div class="frame_wrap frame_wrap_header clearfix">
        <div class="fwh-title">
            <a href="{$link->getPageLink('manufacturer')}" title="{l s='Manufacturers' mod='blockmanufacturer'}">{l s='Fabricantes' mod='bskmanufacturercarousel'}</a>
        </div>
        <div class="fwh-nav">
            <div id="bskmanucar_prev" class="btn btn-arrow btn-prev"><i class="icon-chevron-left"></i></div>
            <div id="bskmanucar_next" class="btn btn-arrow btn_next"><i class="icon-chevron-right"></i></div>
        </div>
    </div>
    <div class="frame_wrap frame_wrap_content">
        {if $manufacturers}
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
        {else}
            <div class="alert alert-warning">
                {l s='No manufacturers to display' mod='bskmanufacturercarousel'}
            </div>                
        {/if}
    </div>
</div>
