<?php

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('bskcameraslider.php');

$context = Context::getContext();
$camera_slider = new BskCameraSlider();
$slides = array();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $camera_slider->secure_key || !Tools::getValue('action')) {
    die(1);
}

if (Tools::getValue('action') == 'updateSlidesPosition' && Tools::getValue('slides')) {

    $slides = Tools::getValue('slides');

    foreach ($slides as $position => $id_slide) {
        $res = Db::getInstance()->execute('
			UPDATE `' . _DB_PREFIX_ . 'bskcameraslider_slides` SET `position` = ' . (int) $position . '
			WHERE `id_bskcameraslider_slides` = ' . (int) $id_slide
        );
    }

    $camera_slider->clearCache();
}