<div class="panel"><h3><i class="icon-list-ul"></i> {l s='Slides list' mod='bskcameraslider'}

        <span class="panel-heading-action">
		<a id="desc-product-new" class="list-toolbar-btn"
           href="{$link->getAdminLink('AdminModules')}&configure=bskcameraslider&addSlide=1">
            <label>
                <span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new"
                      data-html="true">
                    <i class="process-icon-new "></i>
                </span>
            </label>
        </a>
	</span>
    </h3>

    <div id="slidesContent">
        <div id="slides">
            {foreach from=$slides item=slide}
                <div id="slides_{$slide.id_slide}" class="panel">
                    <div class="row">
                        <div class="col-lg-1">
                            <span><i class="icon-arrows "></i></span>
                        </div>
                        <div class="col-md-3">
                            <img src="{$image_baseurl}{$slide.image}" alt="{$slide.title}" class="img-thumbnail" />
                        </div>
                        <div class="col-md-8">
                            <h4 class="pull-left">#{$slide.id_slide} - {$slide.title}</h4>
                            <div class="btn-group-action pull-right">
                                {$slide.status}

                                <a class="btn btn-default"
                                   href="{$link->getAdminLink('AdminModules')}&configure=bskcameraslider&id_slide={$slide.id_slide}">
                                    <i class="icon-edit"></i>
                                    {l s='Edit' mod='bskcameraslider'}
                                </a>
                                <a class="btn btn-default"
                                   href="{$link->getAdminLink('AdminModules')}&configure=bskcameraslider&delete_id_slide={$slide.id_slide}">
                                    <i class="icon-trash"></i>
                                    {l s='Delete' mod='bskcameraslider'}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
</div>