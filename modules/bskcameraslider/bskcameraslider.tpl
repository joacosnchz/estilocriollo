<script type="text/javascript">
    var autoAdvance = '{$bskcameraslider.autoAdvance}';
    var mobileAutoAdvance = '{$bskcameraslider.mobileAutoAdvance}';
    var barDirection = '{$bskcameraslider.barDirection}';
    var barPosition = '{$bskcameraslider.barPosition}';
    var fx = '{$bskcameraslider.fx}';
    var height = '{$bskcameraslider.height}';
    var hover = '{$bskcameraslider.hover}';
    var loader = '{$bskcameraslider.loader}';
    var loaderColor = '{$bskcameraslider.loaderColor}';
    var loaderBgColor = '{$bskcameraslider.loaderBgColor}';
    var loaderOpacity = '{$bskcameraslider.loaderOpacity}';
    var loaderPadding = '{$bskcameraslider.loaderPadding}';
    var loaderStroke = '{$bskcameraslider.loaderStroke}';
    var minHeight = '{$bskcameraslider.minHeight}';
    var navigation = '{$bskcameraslider.navigation}';
    var navigationHover = '{$bskcameraslider.navigationHover}';
    var pagination = '{$bskcameraslider.pagination}';
    var playPause = '{$bskcameraslider.playPause}';
    var pauseOnClick = '{$bskcameraslider.pauseOnClick}';
    var pieDiameter = '{$bskcameraslider.pieDiameter}';
    var piePosition = '{$bskcameraslider.piePosition}';
    var portrait = '{$bskcameraslider.portrait}';
    var time = '{$bskcameraslider.time}';
    var transPeriod = '{$bskcameraslider.transPeriod}';
    var imagePath = '{$modules_dir}bskcameraslider/images/';
</script>
{if isset($bskcameraslider_slides)}
    <div id="slider_wrapper" class="clearfix">
        <div class="camera_wrap {$bskcameraslider.skin}" id="camera_wrap_1">
            {foreach from=$bskcameraslider_slides item=slide}
                {if $slide.active}
                    {if $slide.video}
                        <div data-src="{$smarty.const._MODULE_DIR_}/bskcameraslider/images/slides/{$slide.image}">
                            <iframe src="{$slide.video}" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                        </div>
                    {else}
                        <div {if $slide.url}data-link="{$slide.url}"{/if} data-src="{$smarty.const._MODULE_DIR_}/bskcameraslider/images/slides/{$slide.image}" title="{$slide.title}" {if $slide.legend}alt="{$slide.legend}"{/if}>
                            {if $slide.description}
                                <div class="camera_caption fadeFromBottom">
                                    {$slide.description}
                                </div>
                            {/if}
                        </div>
                    {/if}
                {/if}
            {/foreach}
        </div><!-- #camera_wrap_1 -->
    </div>
{/if}