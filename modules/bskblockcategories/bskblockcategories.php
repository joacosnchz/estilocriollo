<?php

/*
 * BitSHOK Starter Module
 * 
 * @author BitSHOK <office@bitshok.net>
 * @copyright 2014 BitSHOK
 * @version 0.1
 * @license http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 */

if (!defined('_PS_VERSION_'))
    exit;

class BskBlockCategories extends Module {

    public function __construct() {
        $this->name = 'bskblockcategories'; // internal identifier, unique and lowercase
        $this->tab = ''; // backend module coresponding category - optional since v1.6
        $this->version = '0.1'; // version number for the module
        $this->author = 'BitSHOK'; // module author
        $this->need_instance = 0; // load the module when displaying the "Modules" page in backend

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Vertical menu with categories'); // public name
        $this->description = $this->l('Vertical menu with categories for PrestaShop 1.6.x'); // public description

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); // confirmation message at uninstall
    }

    /**
     * Install this module
     * @return boolean
     */
    public function install() {
        return  parent::install() &&
                $this->initConfig() &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('displayLeftColumn');
    }

    /**
     * Uninstall this module
     * @return boolean
     */
    public function uninstall() {
        return  Configuration::deleteByName($this->name) &&
                parent::uninstall();
    }
    
    /**
     * Set the default configuration
     * @return boolean
     */
    protected function initConfig() {
        $languages = Language::getLanguages(false);
        $config = array();

        foreach ($languages as $lang) {
            $config['quote'][$lang['id_lang']] = 'The secret of getting ahead is getting started. The secret of getting started is breaking your complex overwhelming tasks into small manageable tasks, and then starting on the first one.';
            $config['author'][$lang['id_lang']] = 'Mark Twain';
        }
        
        return Configuration::updateValue($this->name, json_encode($config));
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
    public function hookDisplayHome() {
        $config = json_decode(Configuration::get($this->name), true);
        $this->smarty->assign(array(
            'quote' => $config['quote'][$this->context->language->id],
            'author' => $config['author'][$this->context->language->id]
        ));

        return $this->display(__FILE__, $this->name . '.tpl');
    }
    
    public function hookDisplayLeftColumn($params) {
        $from_category = Configuration::get('PS_HOME_CATEGORY');

        $category = new Category($from_category, $this->context->language->id);

        $cacheId = $this->getCacheId($category ? $category->id : null);

        if (!$this->isCached($this->name.'.tpl', $cacheId)) {
            $range = '';
            $maxdepth = 4;
            if ($category) {
                if ($maxdepth > 0)
                    $maxdepth += $category->level_depth;
                $range = 'AND nleft >= ' . $category->nleft . ' AND nright <= ' . $category->nright;
            }

            $resultIds = array();
            $resultParents = array();
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
			FROM `' . _DB_PREFIX_ . 'category` c
			INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = ' . (int) $this->context->language->id . Shop::addSqlRestrictionOnLang('cl') . ')
			INNER JOIN `' . _DB_PREFIX_ . 'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = ' . (int) $this->context->shop->id . ')
			WHERE (c.`active` = 1 OR c.`id_category` = ' . (int) Configuration::get('PS_HOME_CATEGORY') . ')
			AND c.`id_category` != ' . (int) Configuration::get('PS_ROOT_CATEGORY') . '
			' . ((int) $maxdepth != 0 ? ' AND `level_depth` <= ' . (int) $maxdepth : '') . '
			' . $range . '
			AND c.id_category IN (
				SELECT id_category
				FROM `' . _DB_PREFIX_ . 'category_group`
				WHERE `id_group` IN (' . pSQL(implode(', ', Customer::getGroupsStatic((int) $this->context->customer->id))) . ')
			)
			ORDER BY `level_depth` ASC, ' . (Configuration::get('BLOCK_CATEG_SORT') ? 'cl.`name`' : 'cs.`position`') . ' ' . (Configuration::get('BLOCK_CATEG_SORT_WAY') ? 'DESC' : 'ASC'));
            foreach ($result as &$row) {
                $resultParents[$row['id_parent']][] = &$row;
                $resultIds[$row['id_category']] = &$row;
            }

            $blockCategTree = $this->getTree($resultParents, $resultIds, $maxdepth, ($category ? $category->id : null));
            $this->smarty->assign('blockCategTree', $blockCategTree);

            if (file_exists(_PS_THEME_DIR_ . 'modules/'.$this->name.'/'.$this->name.'.tpl'))
                $this->smarty->assign('branche_tpl_path', _PS_THEME_DIR_ . 'modules/'.$this->name.'/category-tree-branch.tpl');
            else
                $this->smarty->assign('branche_tpl_path', _PS_MODULE_DIR_ . $this->name.'/category-tree-branch.tpl');
        }
        return $this->display(__FILE__, $this->name.'.tpl', $cacheId);
    }
    
    public function getTree($resultParents, $resultIds, $maxDepth, $id_category = null, $currentDepth = 0) {
        if (is_null($id_category))
            $id_category = $this->context->shop->getCategory();

        $children = array();
        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth))
            foreach ($resultParents[$id_category] as $subcat)
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);

        if (!isset($resultIds[$id_category]))
            return false;

        $return = array(
            'id' => $id_category,
            'link' => $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']),
            'name' => $resultIds[$id_category]['name'],
            'desc' => $resultIds[$id_category]['description'],
            'children' => $children
        );

        return $return;
    }

    protected function getCacheId($name = null) {
        $cache_id = parent::getCacheId();

        if ($name !== null)
            $cache_id .= '|' . $name;

        return $cache_id . '|' . implode('-', Customer::getGroupsStatic($this->context->customer->id));
    }

    public function hookCategoryAddition($params) {
        $this->_clearCache($this->name.'.tpl');
    }

    public function hookCategoryUpdate($params) {
        $this->_clearCache($this->name.'.tpl');
    }

    public function hookCategoryDeletion($params) {
        $this->_clearCache($this->name.'.tpl');
    }

    public function hookActionAdminMetaControllerUpdate_optionsBefore($params) {
        $this->_clearCache($this->name.'.tpl');
    }

}
