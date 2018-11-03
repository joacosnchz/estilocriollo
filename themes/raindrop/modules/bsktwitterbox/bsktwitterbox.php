<?php

class BskTwitterBoxBSK extends BskTwitterBox {

    public function hookfooterBar() {
        $this->context->controller->addJS($this->_path.'footer_bar.js');
        $settings = unserialize(Configuration::get($this->name . '_settings'));

        $this->context->smarty->assign(array(
            'user'          => $settings['user'],
            'widget_id'     => $settings['widget_id'],
            'tweets_limit'  => $settings['tweets_limit'],
            'follow_btn'    => $settings['follow_btn']
        ));

        return $this->display(__FILE__, $this->name . '_scroll.tpl');
    }

}
