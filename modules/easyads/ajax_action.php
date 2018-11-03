<?php

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('easyads.php');

$module = new EasyAds();
$action = Tools::getValue('action');

if ($action == 'updatePosition') {
    $ids = Tools::getValue('ids');
    if (is_array($ids)) {
        foreach ($ids as $pos => $id) {
            Banner::setField($id, 'position', $pos);
        }
    }
}

$module->clearCache();