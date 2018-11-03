<?php

if (!defined('_PS_VERSION_'))
    exit;

class BlockcmsinfoBSK extends Blockcmsinfo {

    public function hookDisplayBlueBlock($params) {
        return $this->hookHome($params);
    }

}
