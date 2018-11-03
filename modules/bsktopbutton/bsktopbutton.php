<?php

/*
 * BitSHOK Top Button Module
 * 
 * @author BitSHOK <office@bitshok.net>
 * @copyright 2012 BitSHOK
 * @version 1.0
 * @license http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 */

if (!defined('_PS_VERSION_'))
    exit;

class BskTopButton extends Module {

    public function __construct() {
        $this->name = 'bsktopbutton'; // internal identifier, unique and lowercase
        $this->tab = 'other'; // backend module coresponding category
        $this->version = '1.0'; // version number for the module
        $this->author = 'BitSHOK'; // module author
        $this->need_instance = 0; // load the module when displaying the "Modules" page in backend

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('BitSHOK Go Top Button Module'); // public name
        $this->description = $this->l('Adds a go top button to the store.'); // public description

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); // confirmation message at uninstall
    }

    /**
     * Install this module
     * @return boolean
     */
    public function install() {
        if (!parent::install() ||
                !$this->registerHook('displayHeader') ||
                !$this->registerHook('displayFooter'))
            return false;

        $this->initConfiguration(); // set default values for settings

        return true;
    }

    /**
     * Uninstall this module
     * @return boolean
     */
    public function uninstall() {
        if (!parent::uninstall())
            return false;

        $this->deleteConfiguration(); // delete settings

        return true;
    }

    /**
     * Header of pages hook (Technical name: displayHeader)
     */
    public function hookHeader() {
        $this->context->controller->addCSS($this->_path . 'style.css');
        $this->context->controller->addJS($this->_path . 'script.js');
    }

    /**
     * Homepage content hook (Technical name: displayHome)
     */
    public function hookDisplayFooter() {
        $this->context->smarty->assign(array(
            'text'  => Configuration::get('text', $this->context->language->id),
            'style' => Configuration::get('style')
        ));

        return $this->display(__FILE__, $this->name . '.tpl');
    }
    
    /**
     * Configuration page
     */
    public function getContent() {
        $this->context->controller->addJS($this->_path . 'admin_script.js');
        
        $this->_html = '';
        
        $this->_html .= $this->postProcess();
        $this->_html .= $this->renderForm();
        
        return $this->_html;
    }
    
    public function renderForm() {
        $options = array(
            array(
                'id_option' => 1,
                'name'      => 'Text'
            ),
            array(
                'id_option' => 2,
                'name'      => 'Image'
            )
        );
        
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Go Top Button Settings'),
                    'icon'  => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type'      => 'select',
                        'label'     => $this->l('Style'),
                        'name'      => 'style',
                        'class'     => 'fixed-width-md',
                        'options'   => array(
                            'query'     => $options,
                            'id'        => 'id_option',
                            'name'      => 'name'
                        )
                    ),
                    array(
                        'label' => $this->l('Text'),
                        'type'  => 'text',
                        'lang'  => true,
                        'name'  => 'text',
                        'desc'  => $this->l('The text which will be displayed on the button.')
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
        $helper->submit_action = 'saveBtn';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
                . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * Process data from Configuration page after form submition
     * @return string message
     */
    protected function postProcess() {
        $output = '';
        if (Tools::isSubmit('saveBtn')) { // save data
            $languages = Language::getLanguages(false);
            $values = array();
            
            foreach($languages as $lang) {
                $values['text'][$lang['id_lang']] = Tools::getValue('text_' . $lang['id_lang']);
            }
            
            Configuration::updateValue('text', $values['text']);
            Configuration::updateValue('style', Tools::getValue('style'));

            // display success message
            return $this->displayConfirmation($this->l('The settings have been successfully saved!'));
        }

        return $output;
    }

    /*
     *  Display input values into the form after process
     */
    public function getConfigFieldsValues() {
        $languages = Language::getLanguages(false);
        $fields = array();
        
        foreach($languages as $lang) {
            $fields['text'][$lang['id_lang']] = Tools::getValue('text_' . $lang['id_lang'], Configuration::get('text', $lang['id_lang']));
        }
        
        $fields['style'] = Tools::getValue('style');
        
        return $fields;
    }

    /**
     * Set the default values for Configuration page settings
     */
    protected function initConfiguration() {
        $languages = Language::getLanguages(false);
        $values = array();

        foreach ($languages as $lang) {
            $values['text'][$lang['id_lang']] = 'Go Top';
        }

        Configuration::updateValue('style', '1');
        Configuration::updateValue('text', $values['text']);
    }

    /**
     * Delete configuration from database
     */
    protected function deleteConfiguration() {
        Configuration::deleteByName('text');
        Configuration::deleteByName('style');
    }

}
