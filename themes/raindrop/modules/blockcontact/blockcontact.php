<?php

if (!defined('_CAN_LOAD_FILES_'))
    exit;

class BlockcontactBSK extends Blockcontact {

    public function hookDisplayNavLinks($params) {
        global $smarty;
        if (!$this->isCached('nav.tpl', $this->getCacheId()))
            $smarty->assign(array(
                'telnumber' => Configuration::get('BLOCKCONTACT_TELNUMBER'),
                'email' => Configuration::get('BLOCKCONTACT_EMAIL')
            ));
        return $this->display(__FILE__, 'nav.tpl');
    }

}