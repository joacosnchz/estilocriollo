<?php

/*
 * BitSHOK Starter Module
 * 
 * @author BitSHOK <office@bitshok.net>
 * @copyright 2012 BitSHOK
 * @version 1.0
 * @license http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 */

if (!defined('_PS_VERSION_'))
    exit;

class BskSubcategories extends Module {

    public function __construct() {
        $this->name = 'bsksubcategories'; // internal identifier, unique and lowercase
        $this->tab = ''; // backend module coresponding category - optional since v1.6
        $this->version = '1.0'; // version number for the module
        $this->author = 'BitSHOK'; // module author
        $this->need_instance = 0; // load the module when displaying the "Modules" page in backend

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('BitSHOK Subcategories'); // public name
        $this->description = $this->l('A fancy way to display subcategories'); // public description

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); // confirmation message at uninstall
    }

    /**
     * Install this module
     * @return boolean
     */
    public function install() {
        if ( !parent::install() ||
                !$this->registerHook('displayHeader') )
            return false;

        return true;
    }

    /**
     * Uninstall this module
     * @return boolean
     */
    public function uninstall() {
        if (!parent::uninstall())
            return false;

        return true;
    }

    /**
     * Header of pages hook (Technical name: displayHeader)
     */
    public function hookHeader() {
        $this->context->controller->addCSS($this->_path . 'css/style.css');
        $this->context->controller->addJS($this->_path . 'js/jquery.zaccordion.min.js');
        $this->context->controller->addJS($this->_path . 'js/script.js');
    }
}
