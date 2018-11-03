<?php

if (!defined('_PS_VERSION_'))
    exit;

class BskFacebookBoxBSK extends BskFacebookBox {

    public function hookDisplayBlueBlock() {
        $settings = $this->blueblockdata();
        $this->context->smarty->assign(array(
            'fbpage' => $settings['fbpage'],
            'width' => $settings['width'],
            'height' => $settings['height'],
            'colorscheme' => $settings['colorscheme'],
            'show_header' => $settings['show_header'],
            'show_stream' => $settings['show_stream'],
            'show_faces' => $settings['show_faces'],
            'show_border' => $settings['show_border'],
        ));
        
        return $this->display(__FILE__, 'bskfacebookbox.tpl');
    }
    
    /**
     * Get configuration settings
     * @return array
     */
    public function blueblockdata() {
        $settings = unserialize(Configuration::get($this->name . '_settings'));
        // default configuration for blue block
        if (!Configuration::get($this->name.'_blueblock')) {
            $settings['width'] = 360;
            $settings['height'] = 300;
            $settings['colorscheme'] = 'dark';
            Configuration::updateValue($this->name . '_settings', serialize($settings));
            Configuration::updateValue($this->name.'_blueblock', true);
        }
        return $settings;
    }

}
