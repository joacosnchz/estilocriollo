<?php

if (!defined('_PS_VERSION_'))
    exit;

class BlockCurrenciesBSK extends BlockCurrencies {

    private function _prepareHook($params) {
        if (Configuration::get('PS_CATALOG_MODE'))
            return false;

        if (!Currency::isMultiCurrencyActivated())
            return false;

        $this->smarty->assign('blockcurrencies_sign', $this->context->currency->sign);

        return true;
    }

    public function hookDisplayNav($params) {
        if ($this->_prepareHook($params))
            return $this->display(__FILE__, 'blockcurrencies.tpl');
    }

    public function hookDisplayHeader($params) {
        if (Configuration::get('PS_CATALOG_MODE'))
            return;
        $this->context->controller->addCSS(($this->_path) . 'blockcurrencies.css', 'all');
    }

}
