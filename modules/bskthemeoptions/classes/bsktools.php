<?php

/**
 * Helper class that extends PrestaShop functionality
 * Use this instead of creating aditional functions in overrides
 */
class bskTools {
    
    /**
     * Add positions to DB
     * @param array $hook array('name' => 'hookName', 'title' => 'hook title', 'description' => 'hook decription'), works with array of hooks
     * @return boolean
     */
    public static function addHooks($hook) {
        if (is_array($hook) && isset($hook[0]) && is_array($hook[0])) {
            $hooks = $hook;
        } else if (is_array($hook) && isset($hook['name']) && is_string($hook['name'])) {
            $hooks = array($hook);
        } else {
            return false;
        }
        
        $result = true;
        foreach ($hooks as $hook) {
            $result &= DB::getInstance()->insert('hook', array(
                'name' => $hook['name'],
                'title' => $hook['title'],
                'description' => $hook['description']
            ));
        }
        return $result;
    }

    /**
     * Delete positions from DB
     * @param array $hook array('name' => 'hookName', 'title' => 'hook title', 'description' => 'hook decription'), works with array of hooks
     * @return boolean
     */
    public static function deleteHooks($hook) {
        if (is_array($hook) && is_array($hook[0])) {
            $hooks = $hook;
        } else if (is_array($hook) && is_string($hook['name'])) {
            $hooks = array($hook);
        } else {
            return false;
        }
        
        $result = true;
        foreach ($hooks as $hook) {
            $result &= DB::getInstance()->delete('hook', 'name="' . $hook['name'] . '"');
        }
        return $result;
    }
    
    /**
     * Check if is on page name
     * @param string $name
     * @return boolean
     */
    public static function pageIs($name) {
        $alias = array(
            'home' => 'index',
            'myaccount' => 'my-account'
        );
        
        $context = Context::getContext();
        $page_name = $context->smarty->getVariable('page_name')->value;
        
        if (array_key_exists($name, $alias)) { // replace alias with real name
            $name = $alias[$name];
        }
        
        return $name == $page_name;
    }
    
    /**
     * Test if hook has modules set
     * 
     * @param string $hook_name The name of the hook
     * @return boolean
     */
    public static function hookHasModules($hook_name) {
        $module_list = Hook::getHookModuleExecList($hook_name);
        if (!is_array($module_list))
            return false;
        //check exceptions
        $context = Context::getContext();
        foreach ($module_list as $array) {
            if (!($moduleInstance = Module::getInstanceByName($array['module'])))
                continue;

            // blocklayered module is displayed only on category pages
            if ($array['module'] == 'blocklayered') {
                $category = (int) Tools::getValue('id_category', Tools::getValue('id_category_layered', 1));
                if ($category == 1)
                    continue;
            }

            // Check permissions
            $exceptions = $moduleInstance->getExceptions($array['id_hook']);
            $controller = Dispatcher::getInstance()->getController();

            if (in_array($controller, $exceptions))
                continue;

            //retro compat of controller names
            $matching_name = array(
                'authentication' => 'auth',
                'compare' => 'products-comparison',
            );
            if (isset($matching_name[$controller]) && in_array($matching_name[$controller], $exceptions))
                continue;
            if (Validate::isLoadedObject($context->employee) && !$moduleInstance->getPermission('view', $context->employee))
                continue;

            return true;
        }
        return false;
    }
    
    /**
     * Create smarty functions for Bootstrap responsive
     * 
     * @param Smarty $smarty
     */
    public static function init_bsresponsive(&$smarty) {
        smartyRegisterFunction($smarty, 'function', 'bsvisible', array('Tools', 'bsvisible')); // @see Tools::bsvisible()
    }
    
    /**
     * Smarty function for Bootstrap responsive utilities classes
     * Return class only when bootstrap is responsive.
     * Eg. {bsvisible val='true' media='md'}
     * 
     * @param type $param
     * @param type $smarty
     * @return string
     * @throws SmartyException
     */
    public static function bsvisible($param, &$smarty){
        if( !array_key_exists('val', $param) || !is_bool($param['val']) ) throw new SmartyException('Bootstrap echo parameter val is missing or invalid.');
        if( !array_key_exists('media', $param) || !in_array($param['media'], array('xs','sm','md','lg'))  ) throw new SmartyException('Bootstrap echo parameter media is missing or invalid.');
        
        extract($param);
        $is_responsive = $smarty->getVariable('is_responsive')->value;
        if( $is_responsive || $media == 'lg' ){
            if($val) return 'visible-'.$media;
            else return 'hidden-'.$media;
        } else if($val) return 'hidden-lg';
        else return '';
    }
    
}
