<?php

/*
 * BitSHOK Facebook Box
 * 
 * @author BitSHOK <office@bitshok.net>
 * @copyright 2012 BitSHOK
 * @version 1.0
 * @license http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 */

if (!defined('_PS_VERSION_'))
    exit;

class BskFacebookBox extends Module {

    public function __construct() {
        $this->name = 'bskfacebookbox'; // internal identifier, unique and lowercase
        $this->tab = 'social_networks'; // backend module coresponding category
        $this->version = '1.0'; // version number for the module
        $this->author = 'BitSHOK'; // module author
        $this->need_instance = 0; // load the module when displaying the "Modules" page in backend

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Facebook Box'); // public name
        $this->description = $this->l('Display Facebook box'); // public description
    }

    /*
     * Install this module
     */

    public function install() {
        if (!parent::install() ||
                !$this->registerHook('displayHeader') ||
                !$this->registerHook('displayFooter'))
            return false;

        $this->initConfiguration(); // set default values for settings

        return true;
    }

    /*
     * Uninstall this module
     */

    public function uninstall() {
        if (!parent::uninstall())
            return false;

        $this->deleteConfiguration(); // delete settings

        return parent::uninstall();
    }

    /*
     * Header of pages hook (Technical name: displayHeader)
     */

    public function hookHeader() {
        $this->context->controller->addCSS($this->_path . 'style.css');

        $appId = Configuration::get($this->name . '_appId');
        $sdkLink = '//connect.facebook.net/es_LA/all.js#xfbml=1';
        if (!empty($appId))
            $sdkLink .= "&appId={$appId}";
        $this->context->smarty->assign('sdkLink', $sdkLink);
        return $this->display(__FILE__, 'bskfacebookbox_sdk.tpl');
    }

    public function hookFooter() {
        $settings = unserialize(Configuration::get($this->name . '_settings'));
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
        return $this->display(__FILE__, 'bskfacebookbox_footer.tpl');
    }

    public function hookLeftColumn() {

        $settings = unserialize(Configuration::get($this->name . '_settings'));

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

        return $this->display(__FILE__, 'bskfacebookbox_footer.tpl');

    }

    /**
     * Configuration page
     */
    public function getContent() {
        return $this->postProcess() . $this->renderForm();
    }
    
    protected function renderForm() {
        $colorscheme_options = array(
            array(
                'id_option' => 'light',
                'name'      => 'Light'
            ),
            array(
                'id_option' => 'dark',
                'name'      => 'Dark'
            )
        );
        
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Facebook box'),
                    'icon'  => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Page'),
                        'type'  => 'text',
                        'name'  => 'fbpage',
                        'class' => 'fixed-width-lg'
                    ),
                    array(
                        'label' => $this->l('Width'),
                        'type'  => 'text',
                        'name'  => 'width',
                        'class' => 'fixed-width-lg'
                    ),
                    array(
                        'label' => $this->l('Height'),
                        'type'  => 'text',
                        'name'  => 'height',
                        'class' => 'fixed-width-lg'
                    ),
                    array(
                        'label'     => $this->l('Select'),
                        'type'      => 'select',
                        'name'      => 'colorscheme',
                        'class'     => 'fixed-width-lg',
                        'options'   => array(
                            'query' => $colorscheme_options,
                            'id'    => 'id_option',
                            'name'  => 'name'
                        )
                    ),
                    array(
                        'label'     => $this->l('Show Header'),
                        'type'      => 'switch',
                        'name'      => 'show_header',
                        'values'    => array(
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
                        )
                    ),
                    array(
                        'label'     => $this->l('Show Stream'),
                        'type'      => 'switch',
                        'name'      => 'show_stream',
                        'values'    => array(
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
                        )
                    ),
                    array(
                        'label'     => $this->l('Show Faces'),
                        'type'      => 'switch',
                        'name'      => 'show_faces',
                        'values'    => array(
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
                        )
                    ),
                    array(
                        'label'     => $this->l('Show Border'),
                        'type'      => 'switch',
                        'name'      => 'show_border',
                        'values'    => array(
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
                        )
                    ),
                    array(
                        'label' => $this->l('App ID'),
                        'type'  => 'text',
                        'class' => 'fixed-width-lg',
                        'name'  => 'appId'
                    )
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
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        $helper->submit_action = 'saveBtn';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        return $helper->generateForm(array($fields_form));
    }
    
    /*
     * Process data from Configuration page after form submition
     */
    protected function postProcess() {
        $output = '';
        if (Tools::isSubmit('saveBtn')) {
            $settings = array(
                'fbpage' => Tools::getValue('fbpage'),
                'width' => Tools::getValue('width'),
                'height' => Tools::getValue('height'),
                'colorscheme' => Tools::getValue('colorscheme'),
                'show_header' => Tools::getValue('show_header'),
                'show_stream' => Tools::getValue('show_stream'),
                'show_faces' => Tools::getValue('show_faces'),
                'show_border' => Tools::getValue('show_border')
            );
            Configuration::updateValue($this->name . '_settings', serialize($settings));
            Configuration::updateValue($this->name . '_appId', Tools::getValue('appId'));
            
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        
        return $output;
    }

    /**
     * Set the default values for Configuration page settings
     */
    protected function initConfiguration() {
        $settings = array(
            'fbpage' => 'bitshok',
            'width' => 175,
            'height' => '',
            'colorscheme' => 'dark',
            'show_header' => '',
            'show_stream' => '',
            'show_faces' => '1',
            'show_border' => '',
        );
        Configuration::updateValue($this->name . '_settings', serialize($settings)); // create a prestashop variable with the settings
        Configuration::updateValue($this->name . '_appId', '');
    }

    /**
     * Delete configuration from database
     */
    protected function deleteConfiguration() {
        Configuration::deleteByName($this->name . '_settings');
        Configuration::deleteByName($this->name . '_appId');
    }
    
    protected function getConfigFieldsValues() {
        $fields = array();
        
        $settings = unserialize( Configuration::get($this->name.'_settings') );
        $appId = Configuration::get($this->name.'_appId');
        
        $fields['fbpage']       = $settings['fbpage'];
        $fields['width']        = $settings['width'];
        $fields['height']       = $settings['height'];
        $fields['show_header']  = $settings['show_header'];
        $fields['show_stream']  = $settings['show_stream'];
        $fields['show_faces']   = $settings['show_faces'];
        $fields['show_border']  = $settings['show_border'];
        $fields['colorscheme']  = $settings['colorscheme'];
        $fields['appId']        = $appId;
        
        return $fields;
    }

}
