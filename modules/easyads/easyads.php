<?php

/*
 * BitSHOK Block Advertising
 * 
 * @author BitSHOK <office@bitshok.net>
 * @copyright 2014 BitSHOK
 * @version 1.0
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_'))
    exit;

require 'classes/Banner.php';

class EasyAds extends Module {
    
    public $clickStats = true;

    public function __construct() {
        $this->name = 'easyads'; // internal identifier, unique and lowercase
        $this->tab = 'advertising_marketing'; // backend module coresponding category - optional since v1.6
        $this->version = '1.0'; // version number for the module
        $this->author = 'BitSHOK'; // module author
        $this->need_instance = 0; // load the module when displaying the "Modules" page in backend
        
        $this->push_filename = _PS_CACHE_DIR_.'push/'.$this->name;
        $this->allow_push = true;
        $this->push_time_limit = 180;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Easy Ads'); // public name
        $this->description = $this->l('Add advertising blocks everywhere on your page'); // public description

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); // confirmation message at uninstall
    }

    /**
     * Install this module
     * @return boolean
     */
    public function install() {
        return  parent::install() &&
                Banner::createTables() &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('dashboardZoneTwo') &&
                $this->registerHook('dashboardData') &&
                $this->registerDisplayHooks();
    }

    /**
     * Uninstall this module
     * @return boolean
     */
    public function uninstall() {
        return  Banner::dropTables() &&
                parent::uninstall();
    }
    
    /**
     * Register hooks that display banners
     * @return boolean
     */
    public function registerDisplayHooks() {
        return  $this->registerHook('displayBanner') &&
                $this->registerHook('displayNav') &&
                $this->registerHook('displayHome') &&
                $this->registerHook('displayTop') &&
                $this->registerHook('displayTopColumn') &&
                $this->registerHook('displayLeftColumn') &&
                $this->registerHook('displayRightColumn') &&
                $this->registerHook('displayLeftColumnProduct') &&
                $this->registerHook('displayRightColumnProduct') &&
                $this->registerHook('displayFooterProduct') &&
                $this->registerHook('displayShoppingCartFooter') &&
                $this->registerHook('displayOrderDetail') &&
                $this->registerHook('displayOrderConfirmation') &&
                $this->registerHook('displayPaymentTop') &&
                $this->registerHook('displayFooter') &&
                $this->registerHook('displayMaintenance');
    }
    
    /***** CONFIGURATION PAGE *****/

    /**
     * Configuration page
     */
    public function getContent() {
        return  $this->configHeader().
                $this->postProcess().
                $this->renderForm().
                $this->renderBannerLists();
    }
    
    /**
     * Configuration page: style and script
     */
    public function configHeader() {
        $this->context->controller->addCss($this->_path.'/css/configure.css');
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->context->controller->addJs($this->_path.'/js/configure.js');
    }
    
    protected function renderBannerLists() {
        $output = '';
        $hooks = $this->getDisplayHookList();
        foreach ($hooks as $hook) {
            $banners = Banner::getByHook($hook['id_hook'] , $this->context->language->id, $this->context->shop->id);
            if (!empty($banners)) {
                $output .= $this->renderList($banners, $hook);
            }
        }
        return $output;
    }
    
    /**
     * Configuration page: banners list
     */
    protected function renderList($banners, $hook) {
        $fields_list = array(
            'id_item' => array(
                'title' => $this->l(''),
                'type' => 'movable'
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'type' => 'text',
                'class' => 'field'
            ),
            'image' => array(
                'title' => $this->l('Image'),
                'type' => 'image',
                'image_baseurl' => Banner::getRelativeImagePath($this->context->language->iso_code),
                'class' => 'field'
            ),
            'url' => array(
                'title' => $this->l('Link'),
                'type' => 'link',
                'class' => 'field link'
            ),
            'target' => array(
                'title' => $this->l('New window'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'target',
                'class' => 'field target'
            ),
            'embed' => array(
                'title' => $this->l('Embed'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'embed',
                'class' => 'field embed no-action'
            ),
            'embed_popup' => array(
                'title' => $this->l('Embed Pop-up'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'embed_popup',
                'class' => 'field embed_popup'
            ),
            'active' => array(
                'title' => $this->l('Active'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'status',
                'class' => 'field'
            )
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->identifier = 'id_item';
        $helper->table = $this->name;
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->module = $this;
        $helper->title = $this->l($hook['name']);
        $helper->list_id = $helper->title; // form id the name of the hook
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        
        return $helper->generateList($banners, $fields_list);
    }
    
    /**
     * Configuration page add/edit banner form
     */
    protected function renderForm() {
        $isEdit = false;
        if (Tools::isSubmit('update'.$this->name)) {
            $isEdit = true;
        }
        
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => ($isEdit ? $this->l('Edit Ad') : $this->l('Add New Ad')),
                    'icon' => ($isEdit ? 'icon-edit' : 'icon-camera')
                ),
                'input' => array(
                    array(
                        'type' => 'file_lang',
                        'label' => $this->l('Upload Image'),
                        'name' => 'image_upload',
                        'lang' => true,
                        'required' => true
                    ),
                    array(
                        'label' => $this->l('Title'),
                        'type'  => 'text',
                        'lang'  => true,
                        'name'  => 'title',
                        'required' => true
                    ),
                    array(
                        'label' => $this->l('Link'),
                        'type'  => 'text',
                        'lang'  => true,
                        'name'  => 'url'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('New window'),
                        'name' => 'target',
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
                        'type' => 'select',
                        'label' => $this->l('Hook'),
                        'desc' => $this->l('Choose on what hook to display the ad'),
                        'name' => 'hook',
                        'options' => array(
                            'query' => $this->getDisplayHookList(),
                            'id' => 'id_hook',
                            'name' => 'name'
                        ),
                        'required' => true
                    ),
                    array(
                        'type' => 'exceptions',
                        'label' => $this->l('Exceptions'),
                        'msg' => $this->l('Choose on what pages not to display the ad'),
                        'name' => 'exceptions',
                        'exceptions' => $this->_getExceptionsList()
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Embed'),
                        'desc' => $this->l('Embed video or other web component'),
                        'name' => 'embed',
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
                        'label' => $this->l('Embed code'),
                        'type'  => 'textarea',
                        'name'  => 'embed_code'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display embed in pop-up'),
                        'name' => 'embed_popup',
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
                        'label' => $this->l('Class'),
                        'type'  => 'text',
                        'name'  => 'class'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active'),
                        'name' => 'active',
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
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'button pull-right'
                )
            )
        );
        
        
        $helper = new HelperForm();
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $language = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $this->getFieldsValues($isEdit),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'image_baseurl' => Banner::getRelativeImagePath()
        );

        $helper->submit_action = ($isEdit ? 'editBtn' : 'addBtn');
        if ($isEdit) {
            $helper->tpl_vars['id_item'] = Tools::getValue('id_item');
            $helper->show_cancel_button = true;
            $helper->back_url = $this->getBackUrl();
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_item');
        }

        $helper->override_folder = '/';
        

        return $helper->generateForm(array($fields_form));
    }
    
    /**
     * Configuration page: process data after form submition.
     */
    protected function postProcess() {
        $validation = $this->_postValidate();
        if (is_string($validation)) {
            return $validation;
        }
        
        /* Add new banner */
        if (Tools::isSubmit('addBtn')) {
            $languages = Language::getLanguages(false);
            $title = array(); $url = array(); $images = array();
            foreach($languages as $lang) {
                $title[$lang['id_lang']] = Tools::getValue('title_' . $lang['id_lang']);
                $url[$lang['id_lang']] = Tools::getValue('url_' . $lang['id_lang']);
                $images[$lang['id_lang']] = $this->upload_image($_FILES['image_upload_'.$lang['id_lang']], Banner::getServerImagePath($lang['iso_code'], false));
            }
            
            $output = '';
            foreach ($images as &$error) {
                if (is_array($error)) {
                    $output .= $this->displayError($error[0]);
                    $error = '';
                }
            }
            if($output !== '') return $output;
            
            if ( 
                !Banner::create(
                    Tools::getValue('hook'),
                    $title,
                    $url,
                    Tools::getValue('exceptions'),
                    Tools::getValue('embed'),
                    Tools::getValue('embed_code'),
                    Tools::getValue('embed_popup'),
                    Tools::getValue('target'),
                    $images,
                    100,
                    Tools::getValue('class'),
                    Tools::getValue('active')
            )) {
                return $this->displayError($this->l('An error occured while creating banner'));
            }
            $this->clearCache();
            return $this->displayConfirmation($this->l('Ad created succesfully'));
        }
        
        /* Edit banner */
        if (Tools::isSubmit('editBtn')) {
            $images = array();
            $languages = Language::getLanguages(false);
            $title = array(); $url = array(); $images = array();
            foreach($languages as $lang) {
                $title[$lang['id_lang']] = Tools::getValue('title_' . $lang['id_lang']);
                $url[$lang['id_lang']] = Tools::getValue('url_' . $lang['id_lang']);
                $images[$lang['id_lang']] = $this->upload_image($_FILES['image_upload_'.$lang['id_lang']], Banner::getServerImagePath($lang['iso_code'], false));
            }
            if (
                !Banner::edit(
                    Tools::getValue('id_item'), 
                    Tools::getValue('hook'), 
                    $title, 
                    $url, 
                    Tools::getValue('exceptions'),
                    Tools::getValue('embed'),
                    Tools::getValue('embed_code'),
                    Tools::getValue('embed_popup'),
                    Tools::getValue('target'),
                    $images, 
                    null,
                    Tools::getValue('class'),
                    Tools::getValue('active')
            )) {
                return $this->displayError($this->l('An error occured while updating banner target'));
            }
            $this->clearCache();
            return $this->displayConfirmation($this->l('Ad edited succesfully'));
        }
        
        /* Delete banner */
        if (Tools::isSubmit('delete'.$this->name)) {
            if (!Banner::remove(Tools::getValue('id_item'))) {
                return $this->displayError($this->l('Could not delete banner'));
            }
            $this->clearCache();
            return $this->displayConfirmation($this->l('Ad removed'));
        }
        
        /* Toggle New window on banner list */
        if (Tools::isSubmit('target'.$this->name)) {
            if (!Banner::setField(Tools::getValue('id_item') , 'target', '_toggle')) {
                return $this->displayError($this->l('An error occured while updating banner target'));
            }
            $this->clearCache();
        }
        
        /* Toggle Active on banner list */
        if (Tools::isSubmit('status'.$this->name)) {
            if (!Banner::setField(Tools::getValue('id_item') , 'active', '_toggle')) {
                return $this->displayError($this->l('An error occured while updating banner status'));
            }
            $this->clearCache();
        }
    }
    
    /**
     * Configuration page: process data after form submition.
     */
    protected function _postValidate() {
        $errors = array();
        
        if (
                Tools::isSubmit('target'.$this->name) || 
                Tools::isSubmit('status'.$this->name) ||
                Tools::isSubmit('delete'.$this->name) ||
                Tools::isSubmit('editBtn')
        ) {
            // validate id_item
            if (!Validate::isUnsignedInt( Tools::getValue('id_item') )) {
                $errors[] = 'Invalid banner id';
            }
        }
        
        if (
                Tools::isSubmit('addBtn') ||
                Tools::isSubmit('editBtn')
        ) {
            // validate title
            $languages = Language::getLanguages(false);
            $valid = false;
            foreach($languages as $lang) {
                if (Tools::getValue('title_'.$lang['id_lang']) != '') {
                    $valid = true;
                    break;
                }
            }
            if (!$valid) $errors[] = 'Enter a title';
        }
        
        if (Tools::isSubmit('addBtn')) {
            // validate choose file
            $valid = false;
            foreach ($_FILES as $file) {
                if (!empty($file['tmp_name'])) {
                    $valid = true;
                    break;
                }
            }
            if (!$valid) $errors[] = 'Upload an image';
        }
        
        if (!empty($errors)) {
            return $this->displayError(implode('<br>', $errors));
        } else {
            return true;
        }
        
    }
    
    /*
     *  Configuration page: display input values into the add/edit form
     */
    protected function getFieldsValues($isEdit) {
        $languages = Language::getLanguages(false);
        $fields = array();
        
        if ($isEdit) {
            $fields['id_item'] = (int)Tools::getValue('id_item');
            $banner = new Banner((int)Tools::getValue('id_item'), null, $this->context->shop->id);
            foreach($languages as $lang) {
                $fields['title'][$lang['id_lang']] = $banner->title[$lang['id_lang']];
                $fields['url'][$lang['id_lang']] = $banner->url[$lang['id_lang']];
                $fields['image'][$lang['id_lang']] = $banner->image[$lang['id_lang']];
            }
            $fields['hook'] = $banner->hook;
            $fields['exceptions'] = $banner->exceptions;
            $fields['embed'] = $banner->embed;
            $fields['embed_code'] = $banner->embed_code;
            $fields['embed_popup'] = $banner->embed_popup;
            $fields['target'] = $banner->target;
            $fields['class'] = $banner->class;
            $fields['active'] = $banner->active;
        } else {
            foreach($languages as $lang) {
                $fields['title'][$lang['id_lang']] = '';
                $fields['url'][$lang['id_lang']] = '';
            }
            $fields['hook'] = '';
            $fields['exceptions'] = '';
            $fields['embed'] = false;
            $fields['embed_code'] = '';
            $fields['embed_popup'] = true;
            $fields['target'] = true;
            $fields['class'] = '';
            $fields['active'] = true;
        }
        
        return $fields;
    }
    
    /**
     * Get back url
     * @return string
     */
    protected function getBackUrl() {
        $current_index = AdminController::$currentIndex;
        $token = Tools::getAdminTokenLite('AdminModules');

        $back = Tools::safeOutput(Tools::getValue('back', ''));

        if (!isset($back) || empty($back)) {
            $back = $current_index . '&amp;configure=' . $this->name . '&token=' . $token;
        }
        
        return $back;
    }
    
    
    /**
     * Get the list of hooks that display banners
     * @return array
     */
    protected function getDisplayHookList() {
        $exclude_hook = array('displayHeader', 'dashboardData', 'dashboardZoneTwo');
        $hook_list = $this->getRegisteredHookList();
        foreach ($hook_list as $key => $hook) {
            foreach ($exclude_hook as $exclude) {
                if ($hook['name'] == $exclude) {
                    unset($hook_list[$key]);
                }
            }
        }
        return $hook_list;
    }
    
    /**
    * Get the list of registered hooks
    * @param int $id_shop
    * @return array
    */
   private function getRegisteredHookList($id_shop=null) {
       if (is_null($id_shop)) {
           $id_shop = $this->context->shop->id;
       }

       $sql = '
           SELECT h.id_hook, h.name
           FROM `'._DB_PREFIX_.'hook` AS h
           JOIN `'._DB_PREFIX_.'hook_module` AS hm ON h.id_hook = hm.id_hook
           WHERE hm.id_module = '.(int)($this->id).' AND hm.id_shop = '.(int)$id_shop;
       $result = Db::getInstance()->executeS($sql);

       return $result;
   }
   
   private function _getExceptionsList() {
        $controllers = Dispatcher::getControllers(_PS_FRONT_CONTROLLER_DIR_);
        ksort($controllers);
        return array_keys($controllers);
    }
    
    /**
     * Upload an image
     * @param array $file
     * @param string $path upload directory
     * @param string $name change file name
     * @param boolean $encrypt encrypt file name
     * @param boolean $mkpath create path if not exists
     * @return string|array error|filename
     */
    private function upload_image($file, $path, $name=null, $encrypt=true, $mkpath = true) {
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
            if (!is_dir($path) && $mkpath) { // create path if not exists
                mkdir($path, 0777, true);
            }
            $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'BSK');
            $salt = sha1(microtime());
            if (is_string($name)) { // fixed name
                $encrypt = false;
                $file['name'] = $name.'.'.$type;
            }
            if ($error = ImageManager::validateUpload($file))
                return array(error);
            elseif (!$temp_name || !move_uploaded_file($file['tmp_name'], $temp_name))
                return array('Cannot move temp image file');
            elseif (!ImageManager::resize($temp_name, $path.DIRECTORY_SEPARATOR.($encrypt ? Tools::encrypt($file['name'].$salt).'.'.$type : $file['name']), null, null, $type))
                return array('An error occurred during the image upload process.');
            if (isset($temp_name))
                @unlink($temp_name);
            
            return ($encrypt ? Tools::encrypt($file['name'].$salt).'.'.$type : $file['name']);
        }
    }
    
    /***** END CONFIGURATION PAGE *****/
    
    /***** DASHBOARD *****/
    
    public function hookDashboardZoneTwo($params) {
        if (!$this->clickStats) return ;
            
        $this->context->controller->addJS($this->_path.'js/dashboard.js');
        $this->context->controller->addCSS($this->_path.'css/dashboard.css');
        
        $this->context->smarty->assign(array(
            'date_from' => Tools::displayDate($params['date_from']),
            'date_to' => Tools::displayDate($params['date_to'])));
        return $this->display(__FILE__, 'dashboard_zone_two.tpl');
    }
    
    public function hookDashboardData($params) {
        $chartName = $this->name.'_line_chart';
        $selectedHook = 'displayTop';
        $selectedHookId = 0;
        
        $regHooks = $this->getDisplayHookList();
        foreach ($regHooks as &$hook) {
            $hook['stats'] = Banner::getClickStatsByHook($hook['id_hook'], $params['date_from'], $params['date_to']);
            if ($hook['name'] == $selectedHook) {
                $selectedHookId = $hook['id_hook'];
            }
        }
        
        return array(
            'data_select' => array(
                'el' => 'easyads_select',
                'options' => $regHooks,
                'selected' => $selectedHookId,
                'chart' => $chartName
            ),
            'data_chart' => array(
                $chartName => array(
                    'chart_type' => $chartName,
                    'data' => Banner::getClickStatsByHook($selectedHookId, $params['date_from'], $params['date_to'])
                )
            )
        );
    }

    /***** & DASHBOARD *****/
    
    /***** FRONT *****/

    /**
     * Hook: displayHeader
     */
    public function hookHeader() {
        $this->context->controller->addCSS($this->_path . 'style.css');
        $this->context->controller->addJqueryPlugin('fancybox');
        $this->context->controller->addJS($this->_path . 'script.js');
        Media::addJsDef(array('easyads_stats' => $this->clickStats));
        Media::addJsDef(array('easyads_dir' => $this->_path));
    }
    
    /**
     * Display banners associated to hook name
     * @param string $hook_name
     * @return string
     */
    protected function hookdisplay($hook_name) {
        $hook = Hook::getIdByName($hook_name);
        $banners = Banner::getByHook($hook, $this->context->language->id, $this->context->shop->id, true);
        if (!empty($banners)) {
            $this->smarty->assign(array(
                'banners' => $banners,
                'hook' => $hook_name,
                'image_path' => Banner::getRelativeImagePath($this->context->language->iso_code)
            ));
            return $this->display(__FILE__, 'hook.tpl');
        }
        return '';
    }
    
    /**
     * Hook: displayBanner
     */
    public function hookDisplayBanner() {
        return $this->hookdisplay('displayBanner');
    }
    
    /**
     * Hook: displayNav
     */
    public function hookDisplayNav() {
        return $this->hookdisplay('displayNav');
    }

    /**
     * Hook: displayHome
     */
    public function hookDisplayHome() {
        return $this->hookdisplay('displayHome');
    }
    
    /**
     * Hook: displayTop
     */
    public function hookDisplayTop() {
        return $this->hookdisplay('displayTop');
    }
    
    /**
     * Hook: displayTopColumn
     */
    public function hookDisplayTopColumn() {
        return $this->hookdisplay('displayTopColumn');
    }
    
    /**
     * Hook: displayLeftColumn
     */
    public function hookDisplayLeftColumn() {
        return $this->hookdisplay('displayLeftColumn');
    }
    
    /**
     * Hook: displayRightColumn
     */
    public function hookDisplayRightColumn() {
        return $this->hookdisplay('displayRightColumn');
    }
    
    /**
     * Hook: displayLeftColumnProduct
     */
    public function hookDisplayLeftColumnProduct() {
        return $this->hookdisplay('displayLeftColumnProduct');
    }
    
    /**
     * Hook: displayRightColumnProduct
     */
    public function hookDisplayRightColumnProduct() {
        return $this->hookdisplay('displayRightColumnProduct');
    }
    
    /**
     * Hook: displayFooter
     */
    public function hookDisplayFooterProduct() {
        return $this->hookdisplay('displayFooterProduct');
    }
    
    /**
     * Hook: displayShoppingCartFooter
     */
    public function hookDisplayShoppingCartFooter() {
        return $this->hookdisplay('displayShoppingCartFooter');
    }
    
    /**
     * Hook: displayOrderDetail
     */
    public function hookDisplayOrderDetail() {
        return $this->hookdisplay('displayOrderDetail');
    }
    
    /**
     * Hook: displayOrderConfirmation
     */
    public function hookDisplayOrderConfirmation() {
        return $this->hookdisplay('displayOrderConfirmation');
    }
    
    /**
     * Hook: displayPaymentTop
     */
    public function hookDisplayPaymentTop() {
        return $this->hookdisplay('displayPaymentTop');
    }
    
    /**
     * Hook: displayFooter
     */
    public function hookDisplayFooter() {
        return $this->hookdisplay('displayFooter');
    }
    
    /**
     * Hook: displayMaintenance
     */
    public function hookDisplayMaintenance() {
        return $this->hookdisplay('displayMaintenance');
    }
    
    public function clearCache() {
        $this->_clearCache('hook.tpl', $this->getCacheId());
    }
    
    /***** END FRONT *****/

}
