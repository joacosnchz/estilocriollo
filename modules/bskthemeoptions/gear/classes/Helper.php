<?php

class GearHelper {
    
    public $smarty;
    public $templates_dir;


    public function __construct($smarty) {
        $this->smarty = $smarty;
        $this->templates_dir = 'views'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR;
    }
    
    /**
     * Create content output for options
     * @param GearOption $option
     * @return string
     * @throws SmartyException
     */
    public function createOption($option) {
        $output = '';
        switch ($option->field) {
            case 'switch':
                $value = '';
                if($option->value == 'on') $value = 'checked';
                
                $output .= $this->_defaultOption($option->field, $option, $value);

                break;
                
            case 'gfonts':
                $option_content = $this->smarty->createTemplate($this->templates_dir.$option->field.'.tpl');
                $active_fonts = array();
                if ($option->value != '') {
                    $active_fonts = explode('|', $option->value);
                } else {
                    $option->value = '';
                }
                $option_content->assign(array(
                    'name' => $option->name,
                    'value' => $option->value,
                    'active_fonts' => $active_fonts
                ));
                
                $output .= $this->_wrapOption($option_content, $option);
                
                break;
                
            case 'upload_one_image':
                $value = null;
                if (!empty($option->value)) {
                    $value = _THEME_IMG_DIR_.'gear/'.$option->value;
                }

                $output .= $this->_defaultOption($option->field, $option, $value);

                break;
            
            case 'css_editor':
                $option_content = $this->smarty->createTemplate($this->templates_dir.$option->field.'.tpl');
                $option_content->assign(array(
                    'name' => $option->name,
                    'value' => $option->value
                ));
                
                $output .= $option_content->fetch();
                
                break;

            default:
                $output .= $this->_defaultOption($option->field, $option);
                
                break;
        }
        
        return $output;
    }
    
    /**
     * Get option value and update at submit
     * @param GearOption $option
     * @return array Errors
     * @throws SmartyException
     */
    public function processOption($option) {
        $errors = array();
        if (!ctype_upper(str_replace('_', '', $option->name))) {
        
            switch ($option->field) {
                case 'color_array':
                    $colors = json_decode($option->value, true);
                    foreach ($colors as $key => &$color) {
                        $color['value'] = Tools::getValue($option->name.'_'.$key);
                    }

                    $option->value = json_encode($colors);
                    if(!$option->save()) $errors[] = $option->label.': not saved';

                    break;

                case 'radio':
                    $value = json_decode($option->value, true);
                    foreach ($value as &$radio) {
                        if ($radio['value'] == Tools::getValue($option->name)) {
                            $radio['checked'] = true;
                        } else {
                            $radio['checked'] = false;
                        }
                    }
                    $option->value = json_encode($value);
                    if(!$option->save()) $errors[] = $option->label.': not saved';

                    break;

                case 'upload_one_image':
                    $path = _PS_THEME_DIR_.'img'.DIRECTORY_SEPARATOR.'gear';
                    $image = $this->upload_image($_FILES[$option->name], $path);
                    if (is_array($image)){
                        $errors = array_merge($errors, $image);
                    } else {
                        $old_image = $option->value;
                        $option->value = $image;
                        if(!$option->save()) $errors[] = $option->label.': not saved';
                        else @unlink($old_image); //delete old image
                    }

                    break;

                case 'button':
                    break;

                default:
                    $option->value = Tools::getValue($option->name);
                    if(!$option->save()) $errors[] = $option->label.': not saved';

                    break;
               }
           
        } else { // variables that are also stored in ps_configuration table (all chars are uppercase)
            switch ($option->field) {
                case 'switch':
                    $option->value = Tools::getValue($option->name);
                    if ($option->value == 'on') {
                        Configuration::updateValue($option->name, true);
                    } else {
                        Configuration::updateValue($option->name, false);
                    }
                    if(!$option->save()) $errors[] = $option->label.': not saved';

                    break;

                default:
                    $option->value = Tools::getValue($option->name);
                    Configuration::updateValue($option->name, $option->value);
                    if(!$option->save()) $errors[] = $option->label.': not saved';
                    
                    break;
            }
        }
        
        return $errors;
    }
    
    /**
     * Output a default option based on the tpl with $name and $value
     * @param string $tpl Filename without tpl extension
     * @param GearOption $option
     * @param mixed $value
     * @return string
     * @throws SmartyException
     */
    public function _defaultOption($tpl='sample', $option, $value=null) {
        if (!isset($option)) {
            throw new SmartyException('GearOption is not set!');
        }
        
        $option_content = $this->smarty->createTemplate($this->templates_dir.$tpl.'.tpl');
        $option_content->assign('name', $option->name);
        if (isset($value)){
            $option_content->assign('value', $value);
        } else {
            $json_value = json_decode($option->value, true);
            if (is_array($json_value)) {
                $option_content->assign('value', $json_value);
            } else {
                $option_content->assign('value', $option->value);
            }
        } 

        return $this->_wrapOption($option_content, $option);
    }
    
    /**
     * Wrap option into default begining and end code
     * @param Smarty_Internal_Template $option_content
     * @return string
     */
    public function _wrapOption($option_content, $option) {
        $option_begin = $this->smarty->createTemplate($this->templates_dir.'option_begin.tpl');
        $option_end = $this->smarty->createTemplate($this->templates_dir.'option_end.tpl');
        $option_begin->assign(array(
            'option_label' => $option->label,
            'option_desc' => $option->description
        ));
        $output = $option_begin->fetch();
        $output .= $option_content->fetch();
        $output .= $option_end->fetch();
        return $output;
    }
    
    /**
     * Create content output for sections
     * @param array $data
     * @return string
     */
    public function createTemplate($data) {
        $output = '';
        foreach ($data as $key => $section) {
            $section_begin = $this->smarty->createTemplate($this->templates_dir.'section_begin.tpl');
            $section_end = $this->smarty->createTemplate($this->templates_dir.'section_end.tpl');
            $section_begin->assign(array(
                'section_name' => $section->name,
                'section_label' => $section->label
            ));
            if($key==0) $section_begin->assign ('active', true) ; // active .tab-pane
            $output .= $section_begin->fetch();
            
            if (count($section->options)) {
                foreach ($section->options as $option) {
                    $output .= $this->createOption($option);
                }
            } else {
                $output .= self::displayAlert('<strong>No options available</strong>', 'warning', false);
            }
            
            $output .= $section_end->fetch();
        }
        
        return $output;
    }
    
    /**
     * Update all options at submit
     * @param array $data
     * @return array Errors
     */
    public function processData($data) {
        $errors = array();
        foreach ($data as $section) {
            if (count($section->options)) {
                foreach ($section->options as $option) {
                    $errors = array_merge($errors, $this->processOption($option));
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Process data after form submition
     * @return string alert message
     */
    public function postProcess($data) {
        if (Tools::isSubmit('installFixtures')) {
            require_once _PS_MODULE_DIR_.GEAR_PARENT_NAME.'/classes/bskThemeConfig.php';
            bskThemeConfig::installFixtures(_MODULE_DIR_.GEAR_PARENT_NAME);
            Tools::clearSmartyCache();
            return self::displayAlert('Patch applied!', 'success');
        }
        
        if (Tools::isSubmit('saveSubmit')){ // save data
            $errors = $this->processData($data);
            if (!$errors) {
                require_once 'FrontStyle.php';
                FrontStyle::generateGearCss($data);
                Tools::clearSmartyCache();
                return self::displayAlert('The configuration has been saved succesfully!', 'success');
            } else {
                $msg = 'Some of the options were not saved!<br>';
                foreach ($errors as $error) {
                    $msg .= $error.'<br>';
                }
                return self::displayAlert($msg, 'danger');
            }
        }
        
        return '';
    }
    
    /**
     * Upload an image
     * @param array $file File submited
     * @param string $path Directory to upload
     * @return string|array File name | Errors
     */
    private function upload_image($file, $path) {
        $errors = array();
        $type = Tools::strtolower(Tools::substr(strrchr($file['name'], '.'), 1));
        $imagesize = @getimagesize($file['tmp_name']);
        if (isset($file) &&
            isset($file['tmp_name']) &&
            !empty($file['tmp_name']) &&
            !empty($imagesize) &&
            in_array(Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)), array(
                    'jpg',
                    'gif',
                    'jpeg',
                    'png'
            )) &&
            in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
        ){
            $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'BSK');
            $salt = sha1(microtime());
            if ($error = ImageManager::validateUpload($file))
                $errors[] = $error;
            elseif (!$temp_name || !move_uploaded_file($file['tmp_name'], $temp_name))
                return false;
            elseif (!ImageManager::resize($temp_name, $path.DIRECTORY_SEPARATOR.Tools::encrypt($file['name'].$salt).'.'.$type, null, null, $type))
                $errors[] = 'An error occurred during the image upload process.';
            if (isset($temp_name))
                @unlink($temp_name);
            
            if (!$errors) {
                return Tools::encrypt($file['name'].$salt).'.'.$type;
            } else {
                return $errors;
            }
        }
        
        $error[] = 'An error occurred with the image to be upload';
        return $errors;
    }
    
    /**
     * Display alert message
     * @param string $msg
     * @param string $type success, info, warning, danger
     * @param boolean $close Show close button
     * @return string
     */
    public static function displayAlert($msg, $type='warning', $close=true) {
        $alert = "<div class='alert alert-{$type}'>";
        if ($close) {
            $alert .= "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
        }
        $alert .= "{$msg}</div>";
        return $alert;
    }
    
}
