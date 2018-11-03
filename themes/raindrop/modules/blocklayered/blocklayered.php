<?php

!defined('_PS_VERSION_') && exit;

require_once _PS_MODULE_DIR_.'blocklayered'.DIRECTORY_SEPARATOR.'blocklayered.php';

class BlockLayeredBSK extends BlockLayered {

    public function ajaxCall() {
        require_once _PS_MODULE_DIR_.'bskthemeoptions'.DIRECTORY_SEPARATOR.'gear'.DIRECTORY_SEPARATOR.'gear.php';
        global $smarty;
        
        $btn_cart_type = GearOption::checkedRadioValue(GearOption::getByName('plBtnType', $this->context->shop->id)); // add to cart button type
        $plRating = GearOption::getByName('plRating', $this->context->shop->id); // show rating on product-list
        
        $smarty->assign(array(
            'btn_cart_type' => $btn_cart_type,
            'plRating' => $plRating->isOn()
        ));

        echo parent::ajaxCall();
    }

}
