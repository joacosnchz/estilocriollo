<?php

if (!defined('_CAN_LOAD_FILES_'))
    exit;

class BlocksocialBSK extends Blocksocial {

    public function hookDisplayBlueBlock() {
        if (!$this->isCached('blocksocial_blueblock.tpl', $this->getCacheId()))
            $this->smarty->assign(array(
                'facebook_url' => Configuration::get('BLOCKSOCIAL_FACEBOOK'),
                'twitter_url' => Configuration::get('BLOCKSOCIAL_TWITTER'),
                'rss_url' => Configuration::get('BLOCKSOCIAL_RSS'),
                'youtube_url' => Configuration::get('BLOCKSOCIAL_YOUTUBE'),
                'google_plus_url' => Configuration::get('BLOCKSOCIAL_GOOGLE_PLUS'),
                'pinterest_url' => Configuration::get('BLOCKSOCIAL_PINTEREST'),
            ));

        return $this->display(__FILE__, 'blocksocial_blueblock.tpl', $this->getCacheId());
    }

}
