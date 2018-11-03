<?php

/**
 * Examples of how to add different types of options
 */

/* add a section */
GearSection::addSection('general', 'General');

/* radio */
$value = array(
    array('label' => 'No repeat', 'value' => 'no-repeat', 'checked' => false),
    array('label' => 'X Y', 'value' => 'repeat', 'checked' => true),
    array('label' => 'X', 'value' => 'repeat-x', 'checked' => false),
    array('label' => 'Y', 'value' => 'repeat-y', 'checked' => false),
);
GearOption::addOption('bkgRepeat', json_encode($value), 'Repeat', 3, 'radio');

/* switch */
GearOption::addOption('useCssEditor', 'on', 'Use Custom CSS', 3, 'switch');

/* color */
GearOption::addOption('priceColor', '#ff4848', 'Price Color', 1, 'color');

/* color_array */
$value = array(
    array('label' => 'Text', 'value' => '#fff'),
    array('label' => 'Background', 'value' => 'rgba(0,0,0,0.5)'),
    array('label' => 'Hover', 'value' => '#000')
);
GearOption::addOption('buttonName', json_encode($value), 'Example button', 1, 'color_array');

/* pattern */
GearOption::addOption('bkgPattern', 0, 'Pattern', 2, 'pattern', 'patterns can be used only if there is no background image set');

/* google fonts */
GearOption::addOption('googleFonts', '', 'Google Fonts', 2, 'gfonts');

/* choose font */
GearOption::addOption('headersFont', 'psans', 'Headers Font', 2, 'font');

/* upload one image */
GearOption::addOption('bkgUpload', '', 'Upload Image', 3, 'upload_one_image');

/* css editor */
GearOption::addOption('useCssEditor', 'on', 'Custom CSS', 7, 'switch');
GearOption::addOption('cssEditor', '/* Put your custom css code here */', 'CSS', 7, 'css_editor');

/* button */
$value = array(
    'name' => 'Run',
    'class' => 'btn-class'
);
GearOption::addOption('btnVar', json_encode($value), 'This Button', 1, 'button');