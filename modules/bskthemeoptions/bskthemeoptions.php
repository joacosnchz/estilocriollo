<?php

/*
 * BSK Theme Options
 */

if (!defined('_PS_VERSION_'))
    exit;

require_once 'classes/bsktools.php';
require_once 'classes/bskThemeConfig.php';
require_once 'gear/gear.php';

class BskThemeOptions extends Module {
    
    public function __construct() {
        $this->name = 'bskthemeoptions';
        $this->tab = 'administration';
        $this->version = '1.4';
        $this->author = 'BitSHOK';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('BitSHOK GEAR Theme Options');
        $this->description = $this->l('Theme Options for RainDrop Theme');

        $this->confirmUninstall = $this->l('The RainDrop Theme depends on this module. Are you sure you want to uninstall?');
    }

    /**
     * Install module
     * @return boolean
     */
    public function install() {
        return  parent::install() &&
                bskTools::addHooks( bskThemeConfig::customHooks() ) && // install custom positons
                Gear::installModel() && // create gear tables
                $this->registerHook('actionFrontControllerSetMedia') &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('displayFooter');
        
    }

    /**
     * Uninstall module
     * @return boolean
     */
    public function uninstall() {
        return  bskTools::deleteHooks( bskThemeConfig::customHooks() ) &&
                Gear::uninstallModel() && // remove gear tables
                Gear::resetInit() &&
                parent::uninstall();
    }

    /**********************************
     * Position Hooks
     * ********************************/

    /**
     * Hook displayHeader
     */
    public function hookHeader() {
        $header_type = GearOption::checkedRadioValue(GearOption::getByName('layoutHeaderType', $this->context->shop->id));
        $vertical_menu_fixed = GearOption::getByName('layoutVerticalMenuStick', $this->context->shop->id); // stick vertical menu to hookTopMenu
        $plProductDisplay = GearOption::checkedRadioValue(GearOption::getByName('plProductDisplay', $this->context->shop->id)); // grid or list
        $btn_cart_type = GearOption::checkedRadioValue(GearOption::getByName('plBtnType', $this->context->shop->id)); // add to cart button type
        $navSecondaryFixed = GearOption::getByName('navSecondaryFixed', $this->context->shop->id); // navSecondary fixed on scroll
        $plRating = GearOption::getByName('plRating', $this->context->shop->id); // show rating on product-list
        $this->context->smarty->assign(array(
            'header_type' => $header_type,
            'vertical_menu_fixed' => $vertical_menu_fixed->isOn(),
            'plProductDisplay' => $plProductDisplay,
            'btn_cart_type' => $btn_cart_type,
            'navSecondaryFixed' => $navSecondaryFixed->isOn(),
            'plRating' => $plRating->isOn()
        ));
        
        // Retina logo
        if (Configuration::get('PS_LOGO_MOBILE')) {
            $this->context->smarty->assign(array(
                'logo_image_width'     => Configuration::get('SHOP_LOGO_MOBILE_WIDTH') / 2,
                'logo_image_height'    => Configuration::get('SHOP_LOGO_MOBILE_HEIGHT') / 2
            ));
        }
        
        // Pace.js (the page loader script)
        $pageLoader = GearOption::getByName('pageLoader', $this->context->shop->id);
        if ($pageLoader->isOn()) {
            $this->context->controller->addJS(_THEME_DIR_ . 'libs/pace/pace.js');
            $this->context->controller->addCSS(_THEME_DIR_ . 'libs/pace/pace.css');
        }
        $this->context->smarty->assign('pageLoader', $pageLoader->isOn());
        
        // if nav_main_links hook
        if (bskTools::hookHasModules('displayNavLinks')) {
            $this->context->smarty->assign('HOOK_NAV_MAIN_LINKS', Hook::exec('displayNavLinks'));
        }
        
        // if top_menu hook
        if (bskTools::hookHasModules('displayTopMenu')) {
            $this->context->smarty->assign('HOOK_TOP_MENU', Hook::exec('displayTopMenu'));
        }

        // if footer_bar hook
        if (bskTools::hookHasModules('footerBar')) {
            $this->context->smarty->assign('FOOTER_BAR', Hook::exec('footerBar'));
        }
        
        // if bsk_subcategories hook
        if (bskTools::hookHasModules('displayBskSubcategories')) {
            $this->context->smarty->assign('HOOK_BSK_SUBCATEGORIES', Hook::exec('displayBskSubcategories'));
        }

        /* Home Page */
        if (bskTools::pageIs('home')) {
            // fadein on scroll
            $fadeInScroll = GearOption::getByName('fadeInScroll', $this->context->shop->id);
            $this->context->smarty->assign('fadeInScroll', $fadeInScroll->isOn());
            
            // home slider hook
            if (bskTools::hookHasModules('displaySlider')) {
                $this->context->smarty->assign('HOOK_SLIDER', Hook::exec('displaySlider'));
            }
            
            // home slider hook
            if (bskTools::hookHasModules('displaySlider2')) {
                $this->context->smarty->assign('HOOK_SLIDER2', Hook::exec('displaySlider2'));
            }

            // home ads hook
            if (bskTools::hookHasModules('displayAds')) {
                $this->context->smarty->assign('HOOK_ADS', Hook::exec('displayAds'));
            }

            // home blue block
            if (bskTools::hookHasModules('displayBlueBlock')) {
                $this->context->smarty->assign('HOOK_BLUE_BLOCK', Hook::exec('displayBlueBlock'));
            }
        }
        /* & Home Page */
        
        /* Category Page */
        if (bskTools::pageIs('category')) {
            $show_subcat = GearOption::getByName('subcategories', $this->context->shop->id);
            $this->context->smarty->assign('show_subcat', $show_subcat->isOn());
        }
        /* & Category Page */

        /* Product Page */
        if (bskTools::pageIs('product')) {
            $this->context->controller->addCSS(_THEME_DIR_ . 'libs/mcustomscrollbar/jquery.mCustomScrollbar.css');
            $this->context->controller->addJS(_THEME_DIR_ . 'libs/mcustomscrollbar/jquery.mCustomScrollbar.concat.min.js');
            
            $product_image_format = GearOption::checkedRadioValue(GearOption::getByName('prodImageType', $this->context->shop->id));
            $thumbs_position = GearOption::checkedRadioValue(GearOption::getByName('prodThumbsPosition', $this->context->shop->id));
            
            $quickview = $this->context->smarty->getVariable('content_only')->value;
            if ($quickview) {
                $product_image_format = 'landscape';
                $thumbs_position = 'right';
            }
            
            $this->context->smarty->assign(array(
                'product_image_format' => $product_image_format, // portrait | landscape
                'thumbs_position' => $thumbs_position // bottom | left | right
            ));
            
            // if products Prev/Next hook
            if (bskTools::hookHasModules('productsNextPrev')) {
                $this->context->smarty->assign('HOOK_PREV_NEXT', Hook::exec('productsNextPrev'));
            }
        }
        /* & Product Page */
    }
    
    /**
     * Action FrontController setMedia()
     */
    public function hookActionFrontControllerSetMedia() {
        $cdn_jquery = GearOption::getByName('cdn_jquery', $this->context->shop->id);
        $cdn_jquery = $cdn_jquery->isOn();
        $jquery_lib = array(
            'cdn' => $cdn_jquery,
            'js' => array('//code.jquery.com/jquery-1.11.0.min.js', '//code.jquery.com/jquery-migrate-1.2.1.min.js')
        );
        
        $cdn_bootstrap = GearOption::getByName('cdn_bootstrap', $this->context->shop->id);
        $cdn_bootstrap = $cdn_bootstrap->isOn();
        $bootstrap_lib = array(
            'cdn' => $cdn_bootstrap,
            'css' => '//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css',
            'js' => '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js'
        );
        
        if (!$jquery_lib['cdn']) {
            $jquery_lib['js'] = array(_THEME_DIR_.'libs/jquery/jquery-1.11.0.min.js', _THEME_DIR_.'libs/jquery/jquery-migrate-1.2.1.min.js');
        }
        if (!$bootstrap_lib['cdn']) {
            $bootstrap_lib['css'] = _THEME_DIR_.'libs/bootstrap/css/bootstrap.min.css';
            $bootstrap_lib['js'] = _THEME_DIR_.'libs/bootstrap/js/bootstrap.min.js';
        }
        
        $this->context->controller->removeJS(Media::getJqueryPath()); // remove PS jQuery
        array_push($jquery_lib['js'], $bootstrap_lib['js']);
        array_splice($this->context->controller->js_files, 0, 0, $jquery_lib['js']); // add new jquery and bootstrap js
        
        $this->context->controller->css_files = array_merge(array($bootstrap_lib['css'] => 'all'), $this->context->controller->css_files); // add bootstrap css
    }
    
    /**
     * Hook displayFooter
     */
    public function hookFooter() {
        $this->context->controller->addCSS(_THEME_CSS_DIR_.'gear.css');
        $this->context->controller->addJS(_THEME_JS_DIR_.'script.js');
    }

    /*
     * Configuration page
     */

    public function getContent() {
        return '
        <script type="text/javascript">
        function set_size(ht){
            $("#bskGearFramework").height(ht);
        }
        </script>
        <object id="bskGearFramework" style="width:100%; height:800px;" type="text/html" data="' . $this->_path . 'gear/gear.php?start&id_lang=' . $this->context->language->id . '&id_shop=' . $this->context->shop->id . '"></object>';
    }

}
