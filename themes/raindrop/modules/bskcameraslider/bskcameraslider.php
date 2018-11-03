<?php

class BskCameraSliderBSK extends BskCameraSlider {
    
    public function hookDisplaySlider() {
        return $this->hookdisplayTopColumn();
    }
    
    public function hookDisplaySlider2() {
        return $this->hookdisplayTopColumn();
    }

}
