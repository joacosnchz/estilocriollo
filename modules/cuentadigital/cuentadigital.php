<?php
/*
* 2015 PrestaShop
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
if(!defined('_PS_VERSION_'))
	exit;

class CuentaDigital extends PaymentModule {
	
	public function __construct() {
		$this->name = 'cuentadigital';
		$this->tab = 'payments_gateways';
		$this->version = '1.0.0';
		$this->author = 'DSNEmpresas - Joaquin Sanchez';
		$this->controllers = array('payment', 'validation');
		$this->need_instance = 1;
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
		$this->bootstrap = true;

		$this->currencies = true;
		$this->currencies_mode = 'radio';

		parent::__construct();

		$this->displayName = $this->l('CuentaDigital');
		$this->description = $this->l('Payment Gateway for CuentaDigital.');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

		$this->context->smarty->assign('cuentadigital', $this->name);

		if (!Configuration::get('CUENTADIGITAL'))      
		  $this->warning = $this->l('No name provided');

		if(!Configuration::get('CUENTADIGITAL_CLIENTID'))
			$this->warning = $this->l('No client id provided');
	}

	public function install() {
		if (Shop::isFeatureActive())
			Shop::setContext(Shop::CONTEXT_ALL);

		if (!parent::install() ||
			!$this->registerHook('payment') ||
			!$this->registerHook('paymentReturn') ||
			!$this->registerHook('header') ||
			!Configuration::updateValue('CUENTADIGITAL', 'cuentadigital')
			)
				return false;

		return true;
	}

	public function uninstall() {
		if (!parent::uninstall() ||
			!Configuration::deleteByName('CUENTADIGITAL') ||
			!Configuration::deleteByName('CUENTADIGITAL_CLIENTID')
		)
			return false;

		return true;
	}

	public function getContent() {
		$output = null;
	 
		if (Tools::isSubmit('submit'.$this->name))
		{
			$clientid = strip_tags(strval(Tools::getValue('CUENTADIGITAL_CLIENTID')));
			if(empty($clientid)) {
				$output .= $this->displayError($this->l('Plase, complete all the fields on the form'));
			}
			else {
				$output .= $this->displayConfirmation($this->l('Your settings have been saved.'));
				Configuration::updateValue('CUENTADIGITAL_CLIENTID', $clientid);
			}
		}

		return $output.$this->displayForm();
	}



	public function displayForm() {
		// Get default language
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		 
		// Init Fields form array
		$fields_form[0]['form'] = array(
		    'legend' => array(
		        'title' => $this->l('Settings'),
		    ),
		    'input' => array(
		        array(
		            'type' => 'text',
		            'label' => $this->l('CLIENT ID'), // id of the cuentadigital client
		            'name' => 'CUENTADIGITAL_CLIENTID',
		            'required' => true
		        )
		    ),
		    'submit' => array(
		        'title' => $this->l('Save'),
		        'class' => 'button'
		    )
		);
		 
		$helper = new HelperForm();
		 
		// Module, token and currentIndex
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		 
		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		 
		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true;        // false -> remove toolbar
		$helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit'.$this->name;
		$helper->toolbar_btn = array(
		    'save' =>
		    array(
		        'desc' => $this->l('Save'),
		        'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
		        '&token='.Tools::getAdminTokenLite('AdminModules'),
		    ),
		    'back' => array(
		        'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
		        'desc' => $this->l('Back to list')
		    )
		);
		 
		// Load current value
 		$helper->fields_value['CUENTADIGITAL_CLIENTID'] = Configuration::get('CUENTADIGITAL_CLIENTID');
		 
		return $helper->generateForm($fields_form);
	}

	public function hookPayment($params) {
		if (!$this->active)
			return;
		if (!$this->checkCurrency($params['cart']))
			return;

		$this->context->smarty->assign(array(
			'this_path' => $this->_path,
			'this_path_cuentadigital' => $this->_path,
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/',
			'cuentadigital_payment_path' => $this->context->link->getModuleLink('cuentadigital', 'payment'),
		));

		return $this->display(__FILE__, 'payment.tpl');
	}

	public function hookPaymentReturn($params) {
		if (!$this->active)
			return;

		/* $state = $params['objOrder']->getCurrentState();
		if (in_array($state, array(Configuration::get('PS_OS_CHEQUE'), Configuration::get('PS_OS_OUTOFSTOCK'), Configuration::get('PS_OS_OUTOFSTOCK_UNPAID'))))
		{
			$this->smarty->assign(array(
				'total_to_pay' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
				'chequeName' => $this->chequeName,
				'chequeAddress' => Tools::nl2br($this->address),
				'status' => 'ok',
				'id_order' => $params['objOrder']->id
			));
			if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
				$this->smarty->assign('reference', $params['objOrder']->reference);
		}
		else
			$this->smarty->assign('status', 'failed');
		return $this->display(__FILE__, 'payment_return.tpl'); */
	}

	public function hookDisplayHeader() {
		$this->context->controller->addCSS($this->_path.'css/cuentadigital.css', 'all');
	}

	public function checkCurrency($cart) {
		$currency_order = new Currency((int)($cart->id_currency));
		$currencies_module = $this->getCurrency((int)$cart->id_currency);

		if(is_array($currencies_module)):
			foreach($currencies_module as $currency_module):
				if($currency_order->id == $currency_module['id_currency']):
					return true;
				endif;
			endforeach;
		else:
			if($currencies_module->id == $currency_order->id):
				return true;
			endif;
		endif;

		return false;
	}
}