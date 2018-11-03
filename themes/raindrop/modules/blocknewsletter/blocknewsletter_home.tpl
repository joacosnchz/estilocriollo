<!-- Block Newsletter module-->
<div id="newsletter_blue_block" class="block border-right col-lg-6">
    <h4>{l s='Subscribe to our Newsletter' mod='blocknewsletter'}</h4>
    <div class="block_content">
        <form action="{$link->getPageLink('index')|escape:'html':'UTF-8'}" method="post">
            <div class="form-group center-block col-xs-12 col-lg-8 {if isset($msg) && $msg } {if $nw_error}form-error{else}form-ok{/if}{/if}" >
                <div class="input-group">
                    <input class="inputNew form-control grey newsletter-input" id="newsletter-input" type="text" name="email" size="18" value="{if isset($msg) && $msg}{$msg}{elseif isset($value) && $value}{$value}{else}{l s='Enter your e-mail' mod='blocknewsletter'}{/if}" />
                    <span class="input-group-btn">
                        <button type="submit" name="submitNewsletter" class="btn btn-default">
                            <i class="icon-envelope"></i>
                        </button>
                    </span>
                </div>
                <input type="hidden" name="action" value="0" />
            </div>
        </form>
    </div>
</div>
<!-- /Block Newsletter module-->
{strip}
    {if isset($msg) && $msg}
        {addJsDef msg_newsl=$msg|@addcslashes:'\''}
    {/if}
    {if isset($nw_error)}
        {addJsDef nw_error=$nw_error}
    {/if}
    {addJsDefL name=placeholder_blocknewsletter}{l s='Enter your e-mail' mod='blocknewsletter' js=1}{/addJsDefL}
    {if isset($msg) && $msg}
        {addJsDefL name=alert_blocknewsletter}{l s='Newsletter : %1$s' sprintf=$msg js=1 mod="blocknewsletter"}{/addJsDefL}
    {/if}
{/strip}