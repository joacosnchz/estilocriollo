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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if isset($plRating) && $plRating}
    {if isset($nbComments) && $nbComments > 0}
        <div class="comments_note" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <div class="star_content clearfix">
                {assign var="roundAvgRating" value=($averageTotal*2)|floor /2}
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
                <meta itemprop="ratingValue" content = "{if isset($ratings.avg)}{$ratings.avg|round:1|escape:'html':'UTF-8'}{else}{$averageTotal|round:1|escape:'html':'UTF-8'}{/if}" />
                <meta itemprop="bestRating" content = "5" />
            </div>
            <span class="nb-comments"><span itemprop="reviewCount">{$nbComments}</span> {l s='Review(s)' mod='productcomments'}</span>
        </div>
    {else}
        <div class="comments_note">
            <div class="star_content no_comments">
                {for $i=0 to 4}
                    <div class="star star_off"></div>
                {/for}
            </div>
        </div>
    {/if}
{/if}