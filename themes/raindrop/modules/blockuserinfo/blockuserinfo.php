<?php

if (!defined('_PS_VERSION_'))
    exit;

class BlockUserInfoBSK extends BlockUserInfo {

    public function hookDisplayNavLinks($params) {
        return $this->display(__FILE__, 'nav.tpl');
    }

}
