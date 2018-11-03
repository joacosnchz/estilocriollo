<?php

require_once strstr(__DIR__, 'themes', true).'config'.DIRECTORY_SEPARATOR.'config.inc.php';
require_once strstr(__DIR__, 'themes', true).'init.php';
require_once __DIR__.'/blocklayered.php';

Context::getContext()->controller->php_self = 'category';
$blockLayered = new BlockLayeredBSK();
echo $blockLayered->ajaxCall();