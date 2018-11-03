<?php

if (!defined('_PS_VERSION_'))
    exit;

class BskManufacturerCarouselBSK extends BskManufacturerCarousel {

    public function hookDisplayHome($param) {
        $param['image_type'] = Link::getImageTypeThemeName('craftsman');
        return parent::hookDisplayHome($param);
    }
    
}
