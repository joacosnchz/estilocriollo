<?php

if (!defined('_PS_VERSION_'))
    exit;

class BlockLanguagesBSK extends BlockLanguages {

    public function hookDisplayNav($params) {
        return $this->display(__FILE__, 'blocklanguages.tpl');
    }

}
