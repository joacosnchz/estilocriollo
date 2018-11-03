<?php
/*
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
*/

/**
 * @since 1.6.0
 */
class CuentaDigitalValidationModuleFrontController extends ModuleFrontController {
	
	public function postProcess() {
		$cart = $this->context->cart;

		if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
			Tools::redirect('index.php?controller=order&step=1');

		// Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == 'cuentadigital')
			{
				$authorized = true;
				break;
			}

		if (!$authorized)
			die($this->module->l('This payment method is not available.', 'validation'));

		$customer = new Customer($cart->id_customer);

		if (!Validate::isLoadedObject($customer))
			Tools::redirect('index.php?controller=order&step=1');

		$currency = $this->context->currency;
		$total = (float)$cart->getOrderTotal(true, Cart::BOTH);

		$mailVars =	array();

		// Obtener el primer estado de este modulo
		$order_state = Db::getInstance()->ExecuteS('
			SELECT * FROM ' . _DB_PREFIX_ . 'order_state 
			WHERE module_name = "cuentadigital" limit 1
		');

		$this->module->validateOrder((int)$cart->id, $order_state[0]['id_order_state'], $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);

		$clientId = Configuration::get('CUENTADIGITAL_CLIENTID');

		$concepto = 'Productos: ';
		foreach($cart->getProducts() as $product):
			$concepto .= $product['name'] . ' ';
		endforeach;

		// Obtener el codigo de referencia de la orden
		$order_reference = Db::getInstance()->ExecuteS('
			SELECT * FROM ' . _DB_PREFIX_ . 'orders 
			WHERE id_order = "' . $this->module->currentOrder . '"
		');

		header('Location: https://www.cuentadigital.com/api.php?id=' . $clientId . '&codigo=' . $order_reference[0]['reference'] . '&precio=120&m0=&m1=&m2=&m3=&venc=7&concepto=' . $concepto . '&moneda=' . $currency->iso_code . '&site=estilocriollo.com.ar');

		// Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
	}
}