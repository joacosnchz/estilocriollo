<?php

if (!defined('_PS_VERSION_'))
    exit;

class BlockSearchBSK extends BlockSearch {

    public function hookDisplayNav($params) {
        return $this->hookTop($params);
    }
    
    public function hookDisplayTop($params) {
        return $this->hookTop($params);
    }

}
