<?php

/*
 * BitSHOK Manufacturers Carousel
 * 
 * @author BitSHOK <office@bitshok.net>
 * @copyright 2014 BitSHOK
 * @version 0.9
 * @license http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 */

if (!defined('_PS_VERSION_'))
    exit;

class BskManufacturerCarousel extends Module {

    public function __construct() {
        $this->name = 'bskmanufacturercarousel'; // internal identifier, unique and lowercase
        $this->tab = 'front_office_features'; // backend module coresponding category - optional since v1.6
        $this->version = '0.9'; // version number for the module
        $this->author = 'BitSHOK'; // module author
        $this->need_instance = 0; // load the module when displaying the "Modules" page in backend

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Manufacturers Carousel'); // public name
        $this->description = $this->l('Display a manufacturers carousel on your homepage'); // public description

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); // confirmation message at uninstall
    }

    /**
     * Install this module
     * @return boolean
     */
    public function install() {
        $config = array(
            'show_name' => true,
            'show_prod_nb' => true
        );
        Configuration::updateValue($this->name, json_encode($config));
        
        return  parent::install() &&
                $this->registerHook('displayHome') &&
                $this->registerHook('actionObjectManufacturerDeleteAfter') &&
                $this->registerHook('actionObjectManufacturerAddAfter') &&
                $this->registerHook('actionObjectManufacturerUpdateAfter');
    }

    /**
     * Uninstall this module
     * @return boolean
     */
    public function uninstall() {
        Configuration::deleteByName($this->name);
        
        return parent::uninstall();
    }

    /**
     * Configuration page
     */
    public function getContent() {
        return $this->postProcess() . $this->renderForm();
    }
    
    /*
     * Configuration page form builder
     */
    public function renderForm() {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Manufacturer Carousel'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Show name'),
                        'name' => 'show_name',
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'label' => $this->l('Show product number'),
                        'name' => 'show_prod_nb',
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'button pull-right'
                )
            )
        );
        
        
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'saveBtn';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
                . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
        );

        return $helper->generateForm(array($fields_form));
    }
    
    /*
     * Process data from Configuration page after form submition.
     */
    public function postProcess() {
        if (Tools::isSubmit('saveBtn')) {
            $config = array(
                'show_name' => Tools::getValue('show_name'),
                'show_prod_nb' => Tools::getValue('show_prod_nb')
            );
            Configuration::updateValue($this->name, json_encode($config));
            
            return $this->displayConfirmation($this->l('Settings updated'));
        }
    }
    
    /*
     *  Display input values into the form after process
     */
    public function getConfigFieldsValues() {
        return json_decode(Configuration::get($this->name), true);
    }
    
    /**
     * Output scripts and styles
     */
    public function outputHeader() {
        $this->context->controller->addJqueryPlugin(array('scrollTo', 'serialScroll'));
        $this->context->controller->addJS($this->_path . 'js/jquery.touchSwipe.min.js');
        $this->context->controller->addJS($this->_path . 'js/bskcarousel.js');
        
        $this->context->controller->addCSS($this->_path . 'style.css');
        $this->context->controller->addJS($this->_path . 'script.js');
    }

    /**
     * Hook: displayHome
     * @param array $param For override $param[image_type]
     */
    public function hookDisplayHome($param) {
        $this->outputHeader();
        
        $manufacturers = Manufacturer::getManufacturers(true);
        if (!isset($param['image_type'])) {
            $param['image_type'] = ImageType::getFormatedName('small');
        }
        foreach ($manufacturers as &$manufacturer) {
            $manufacturer['image'] = $this->context->language->iso_code.'-default'.$param['image_type'];
            $mimage = _PS_MANU_IMG_DIR_ . $manufacturer['id_manufacturer'].'-'.$param['image_type'].'.jpg';
            if (file_exists($mimage)) {
                $manufacturer['image'] = $manufacturer['id_manufacturer'].'-'.$param['image_type'].'.jpg';
            }
        }
        $this->smarty->assign('manufacturers', $manufacturers);
        $this->smarty->assign(json_decode(Configuration::get($this->name), true));

        return $this->display(__FILE__, $this->name . '.tpl');
    }
    
    public function hookActionObjectManufacturerUpdateAfter($params) {
        $this->_clearCache($this->name . '.tpl');
    }

    public function hookActionObjectManufacturerAddAfter($params) {
        $this->_clearCache($this->name . '.tpl');
    }

    public function hookActionObjectManufacturerDeleteAfter($params) {
        $this->_clearCache($this->name . '.tpl');
    }

}
