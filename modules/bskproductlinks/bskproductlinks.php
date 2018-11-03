<?php
/*
 * BitSHOK Starter Module
 * 
 * @author BitSHOK <office@bitshok.net>
 * @copyright 2012 BitSHOK
 * @version 1.0
 * @license http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 */

if (!defined('_PS_VERSION_')) exit;

class BskProductLinks extends Module{
   
    public function __construct(){
        $this->name = 'bskproductlinks'; // internal identifier, unique and lowercase
        $this->tab = 'other'; // backend module coresponding category
        $this->version = '1.0'; // version number for the module
        $this->author = 'BitSHOK'; // module author
        $this->need_instance = 0; // load the module when displaying the "Modules" page in backend

        parent::__construct();

        $this->displayName = $this->l('BitSHOK Product Links Module'); // public name
        $this->description = $this->l('Prev, Next links for product page.'); // public description
        
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); // confirmation message at uninstall
    }

    /**
     * Install this module
     * @return boolean
     */
    public function install(){
        if (!parent::install() ||
            !$this->registerHook('displayHeader') ||
            !$this->registerHook('productsNextPrev') )
                return false;
        
        return true;
    }

    /**
     * Uninstall this module
     * @return boolean
     */
    public function uninstall(){
        if (!parent::uninstall())
            return false;
        
        return true;
    }

    /**
     * Header of pages hook (Technical name: displayHeader)
     */
    public function hookHeader(){
        $this->context->controller->addCSS($this->_path.'css/style.css');
        $this->context->controller->addJS($this->_path.'js/script.js');
    }

    public function hookProductsNextPrev(){
        global $smarty, $cookie, $link;

        $id_product = intval(Tools::getValue('id_product'));

        if (!$id_product)
            return true;

        $product = new Product($id_product, false, intval($cookie->id_lang));
        $productInLastVisitedCategory = Product::idIsOnCategoryId(intval($_GET['id_product']), array('0' => array('id_category' => intval($cookie->last_visited_category))));

        if ((!isset($cookie->last_visited_category) OR !$productInLastVisitedCategory) AND Validate::isLoadedObject($product))
            $cookie->last_visited_category = intval($product->id_category_default);

        $category = new Category(intval($cookie->last_visited_category), intval($cookie->id_lang));
        $cat_products = $category->getProducts(intval($cookie->id_lang), 1, 1000000);

        $loop = 0;

        $prevLink = NULL;
        $prevName = NULL;
        $nextLink = NULL;
        $nextName = NULL;

        if (is_array($cat_products))
            for ($i = 0; $i < sizeof($cat_products); $i++) {
                if ($cat_products[$i]['id_product'] == $id_product) {
                    if ($i > 0) {
                        $cat_product = new Product($cat_products[$i - 1]['id_product'], false, intval($cookie->id_lang));
                        $prevLink = $link->getProductLink($cat_products[$i - 1]['id_product'], $cat_product->link_rewrite, $category->link_rewrite, $cat_product->ean13);
                        $prevName = $cat_products[$i - 1]['name'];
                    } elseif ($loop AND sizeof($cat_products) > 1) {
                        $cat_product = new Product($cat_products[sizeof($cat_products) - 1]['id_product'], false, intval($cookie->id_lang));
                        $prevLink = $link->getProductLink($cat_products[sizeof($cat_products) - 1]['id_product'], $cat_product->link_rewrite, $category->link_rewrite, $cat_product->ean13);
                        $prevName = $cat_products[sizeof($cat_products) - 1]['name'];
                    }

                    if ($i < sizeof($cat_products) - 1) {
                        $cat_product = new Product($cat_products[$i + 1]['id_product'], false, intval($cookie->id_lang));
                        $nextLink = $link->getProductLink($cat_products[$i + 1]['id_product'], $cat_product->link_rewrite, $category->link_rewrite, $cat_product->ean13);
                        $nextName = $cat_products[$i + 1]['name'];
                    } elseif ($loop AND sizeof($cat_products) > 1) {
                        $cat_product = new Product($cat_products[0]['id_product'], false, intval($cookie->id_lang));
                        $nextLink = $link->getProductLink($cat_products[0]['id_product'], $cat_product->link_rewrite, $category->link_rewrite, $cat_product->ean13);
                        $nextName = $cat_products[0]['name'];
                    }
                }
            }

        $homepage = 0;

        $smarty->assign(array('prevLink' => $prevLink, 'prevName' => $prevName, 'nextLink' => $nextLink, 'nextName' => $nextName));
        $smarty->assign('path', Tools::getFullPath($homepage ? $category->id : ($category->id == 1 ? $product->id_category_default : $category->id), $product->name));

        if (!$homepage AND $category->id == 1)
            $cookie->last_visited_category = intval($product->id_category_default);

        return $this->display(__FILE__, $this->name.'.tpl');
    }
    
}
