<?php
require __DIR__ . '/../bootstrap.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
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
class PayPalValidationModuleFrontController extends ModuleFrontController {
	
	public function postProcess() {
		$cart = $this->context->cart;

		if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
			Tools::redirect('index.php?controller=order&step=1');

		// Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == 'paypal')
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

		$this->module->validateOrder((int)$cart->id, Configuration::get('PS_OS_PAYPAL'), $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);

		$clientId = Configuration::get('PAYPAL_CLIENTID');
		$clientSecret = Configuration::get('PAYPAL_CLIENTSEC');

		// Config api context for PayPal with clientId and clientSecret
		$bootstrap = new bootstrap();
		$apiContext = $bootstrap->getApiContext($clientId, $clientSecret);

		// LOS SIGUIENTES DATOS LOS DEBERIA OBTENER DE LA VARIABLE $cart

		// ### Payer
		// A resource representing a Payer that funds a payment
		// For paypal account payments, set payment method
		// to 'paypal'.
		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		// ### Itemized information
		// (Optional) Lets you specify item wise
		// information
		$items = array();
		foreach($cart->getProducts() as $product):
			$item = new Item();
			$item->setName($product['name']);
			$item->setCurrency($this->context->currency->iso_code);
			$item->setQuantity($product['cart_quantity']);
			$item->setPrice($product['price_wt']);

			$items[] = $item;
		endforeach;

		$itemList = new ItemList();
		$itemList->setItems($items);

		// ### Additional payment details
		// Use this optional field to set additional
		// payment information such as tax, shipping
		// charges etc.
		$tax = 100 - (($cart->getOrderTotal(false, Cart::ONLY_PRODUCTS) * 100) / $cart->getOrderTotal(true, Cart::ONLY_PRODUCTS));
		$details = new Details();
		$details->setShipping($cart->getOrderTotal(true, Cart::ONLY_SHIPPING))
			->setTax($tax)
			->setSubtotal($cart->getOrderTotal(true, Cart::ONLY_PRODUCTS));

		// ### Amount
		// Lets you specify a payment amount.
		// You can also specify additional details
		// such as shipping, tax.
		$amount = new Amount();
		$amount->setCurrency($this->context->currency->iso_code)
			->setTotal($cart->getOrderTotal())
			->setDetails($details);

		// ### Transaction
		// A transaction defines the contract of a
		// payment - what is the payment for and who
		// is fulfilling it. 
		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setItemList($itemList)
			->setDescription("Compra en estilocriollo.com.ar")
			->setInvoiceNumber(uniqid());

		// ### Redirect urls
		// Set the urls that the buyer must be redirected to after 
		// payment approval/ cancellation.
		$baseUrl = getBaseUrl();
		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true")
			->setCancelUrl("$baseUrl/ExecutePayment.php?success=false");

		// ### Payment
		// A Payment Resource; create one using
		// the above types and intent set to 'sale'
		$payment = new Payment();
		$payment->setIntent("authorize")
			->setPayer($payer)
			->setRedirectUrls($redirectUrls)
			->setTransactions(array($transaction));

		// For Sample Purposes Only.
		$request = clone $payment;

		// ### Create Payment
		// Create a payment by calling the 'create' method
		// passing it a valid apiContext.
		// (See bootstrap.php for more on `ApiContext`)
		// The return object contains the state and the
		// url to which the buyer must be redirected to
		// for payment approval
		try {
			$payment->create($apiContext);
		} catch (Exception $ex) {
			ResultPrinter::printError("Created Payment Authorization Using PayPal. Please visit the URL to Authorize.", "Payment", null, $request, $ex);
			exit(1);
		}

		// ### Get redirect url
		// The API response provides the url that you must redirect
		// the buyer to. Retrieve the url from the $payment->getLinks()
		// method
		$approvalUrl = $payment->getApprovalLink();

		Tools::redirect($approvalUrl);

		//Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
	}
}
