<?php

class BskBlockAdvertisingBSK extends BskBlockAdvertising {
    
    /**
     * Hook custom: displayAds
     */
    public function hookDisplayAds() {
        return $this->hookdisplay('displayAds');
    }

}
