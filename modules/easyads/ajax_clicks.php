<?php

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('easyads.php');

$module = new EasyAds();
$ad = new Banner(Tools::getValue('id'), Tools::getValue('id_lang'));
$ad->clickThrough(null, null, Tools::getValue('id_lang')); // incremet click stats by one