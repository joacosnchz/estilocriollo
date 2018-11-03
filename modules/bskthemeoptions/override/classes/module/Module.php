<?php
/**
 * Override of Module Core
 * Use this override to override (^^) module classes
 *
 * @version 1.0.0
 * @author Julien BREUX &lt;julien.breux@prestashop.com&gt;
 */
class Module extends ModuleCore
{
	/**
	  * Return an instance of the specified module
	  *
	  * @param string $module_name Module name
	  * @return Module
	  */
	public static function getInstanceByName($module_name)
	{
		if (!isset(self::$_INSTANCE[$module_name]))
		{
			if (Tools::file_exists_cache(_PS_MODULE_DIR_.$module_name.'/'.$module_name.'.php'))
			{
				include_once(_PS_MODULE_DIR_.$module_name.'/'.$module_name.'.php');

				$override_module_file = _PS_THEME_DIR_.'modules/'.$module_name.'/'.$module_name.'.php';
				if (file_exists($override_module_file))
				{
					require_once $override_module_file;
					$module_name .= 'BSK';
				}

				if (class_exists($module_name, false))
					return self::$_INSTANCE[$module_name] = new $module_name;
			}
			return false;
		}
		return self::$_INSTANCE[$module_name];
	}
}