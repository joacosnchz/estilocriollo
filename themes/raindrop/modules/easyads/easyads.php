<?php

if (!defined('_PS_VERSION_'))
    exit;


class EasyAdsBSK extends EasyAds {
    
    /**
     * Hook: displayAds
     */
    public function hookDisplayAds() {
        return $this->hookdisplay('displayAds');
    }
    
}
