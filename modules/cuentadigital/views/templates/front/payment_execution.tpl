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
*  @author DSNEmpresas SH <jsanchez@dsnempresas.com.ar>
*  @copyright  2015 DSNEmpresas
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of DSNEmpresas SH
*}

{capture name=path}
	<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='cuentadigital'}">{l s='Checkout' mod='cuentadigital'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Check payment' mod='cuentadigital'}
{/capture}

<h2>{l s='Order summary' mod='cuentadigital'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if isset($nbProducts) && $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.' mod='cuentadigital'}</p>
{else}

<form action="{$link->getModuleLink('cuentadigital', 'validation', [], true)|escape:'html'}" method="post">
	<div class="box">
		<h3 class="page-subheading">{l s='Check payment' mod='cuentadigital'}</h3>
		<p>
			<img src="{$this_path_cuentadigital}logo.png" alt="{l s='Check' mod='cuentadigital'}" style="float:left; margin: 0px 10px 5px 0px;" />
			{l s='You have chosen pay with CuentaDigital.' mod='cuentadigital'}
			<br/><br />
			{l s='Here is a short summary of your order:' mod='cuentadigital'}
		</p>
		<p style="margin-top:20px;">
			- {l s='The total amount of your order comes to:' mod='cuentadigital'}
			<span id="amount" class="price">{displayPrice price=$total}</span>
			{if $use_taxes == 1}
				{l s='(tax incl.)' mod='cuentadigital'}
			{/if}
		</p>
		<p>
			- 
			{if isset($currencies) && $currencies|@count > 1}
				{l s='We accept several currencies to receive payments by CuentaDigital.' mod='cuentadigital'}
				<br /><br />
				{l s='Choose one of the following:' mod='cuentadigital'}
				<select id="currency_payement" name="currency_payement" onchange="setCurrency($('#currency_payement').val());">
				{foreach from=$currencies item=currency}
					<option value="{$currency.id_currency}" {if isset($currencies) && $currency.id_currency == $cust_currency}selected="selected"{/if}>{$currency.name}</option>
				{/foreach}
				</select>
			{else}
				{l s='We allow the following currencies to be sent by CuentaDigital:' mod='cuentadigital'}&nbsp;<b>{$currencies->name}</b>
				<input type="hidden" name="currency_payement" value="{$currencies->id}" />
			{/if}
		</p>
		<p>
			- {l s='We will generate a ticket for you to pay.' mod='cuentadigital'}
		</p>
		<p>
			- <b>{l s='Please confirm your order by clicking \'I confirm my order\'.' mod='cuentadigital'}</b>
		</p>
	</div>

	<p class="cart_navigation clearfix" id="cart_navigation">
		<button type="submit" class="button btn btn-default button-medium" /><span>{l s='I confirm my order' mod='cuentadigital'}<i class="icon-chevron-right right"></i></span></button>
		<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}" class="button-exclusive btn btn-default button_large"><i class="icon-chevron-left"></i>{l s='Other payment methods' mod='cuentadigital'}</a>
	</p>
</form>
{/if}
