<?php
/**********
 * Options Model
 **********/
class GearOption extends ObjectModel {
    public $id;
    public $id_bskgear_options;
    public $name;
    public $value;
    public $label;
    public $id_section;
    public $active;
    public $description;
    public $field;
    public $order;

    public static $definition = array(
        'table' => 'bskgear_options',
        'primary'   => 'id_bskgear_options',
        'multishop' => true,
        'fields'    => array(
            'name'          => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'value'         => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'label'         => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'id_section'    => array('type' => self::TYPE_INT, 'required' => true),
            'active'        => array('type' => self::TYPE_BOOL, 'required' => true),
            'description'   => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'field'         => array( 'type' => self::TYPE_STRING , 'validate' => 'isString' ),
            'order'         => array('type' => self::TYPE_INT),
        )
    );
    
    /**
     * Add a new option and save it into DB
     * 
     * @param string $name Variable identifier
     * @param string $value Serialized data
     * @param string $label Displayed name
     * @param int $id_section Section id
     * @param string $field Serialized array with mandatory key type and other options
     * @param string $description Description of the option to be displayed
     * @param int $order Order to be displayed
     * @param int $id_shop Associeted shop
     * @param boolean $active Enable/Disable option
     * @return int option id
     */
    public static function addOption($name, $value, $label, $id_section, $field, $description='', $order=100, $id_shop = 1, $active=true) {
        $opt = new GearOption();
        $opt->name = $name;
        $opt->value = $value;
        $opt->label = $label;
        $opt->id_section = $id_section;
        $opt->field = $field;
        $opt->description = $description;
        $opt->order = $order;
        $opt->active = $active;
        if( $opt->add() && $opt->associateTo($id_shop) ) {
            return $opt->id;
        }
        return false;
    }
    
    /**
     * Get option by name
     * @param string $name
     * @param int $id_shop
     * @return GearOption
     */
    public static function getByName($name, $id_shop=1) {
        $sql = '
            SELECT o.'.self::$definition['primary'].' AS id
            FROM  `'._DB_PREFIX_.self::$definition['table'].'` AS o
            INNER JOIN  `'._DB_PREFIX_.self::$definition['table'].'_shop` AS s ON o.'.self::$definition['primary'].' = s.'.self::$definition['primary'].'
            WHERE o.name="'.$name.'" AND s.id_shop='.$id_shop.';';
        $result = Db::getInstance()->executeS($sql);
        if (count($result)) {
            return new GearOption( intval( $result[0]['id'] ) );
        }
        return new GearOption();
    }
    
    /**
     * Get all options by section id
     * @param int $id_section Section id
     * @param int $id_shop Associeted shop
     * @param boolean $only_active Return only active options
     * @return array
     */
    public static function getBySection($id_section, $id_shop=1, $only_active=true) {
        $sql = '
            SELECT o.'.self::$definition['primary'].' AS id
            FROM  `'._DB_PREFIX_.self::$definition['table'].'` AS o
            INNER JOIN  `'._DB_PREFIX_.self::$definition['table'].'_shop` AS s ON o.'.self::$definition['primary'].' = s.'.self::$definition['primary'].'
            WHERE o.id_section='.$id_section.' AND s.id_shop='.$id_shop;
        if($only_active) $sql .= ' AND o.active=TRUE';
        $sql .= ' ORDER BY o.order, o.'.self::$definition['primary'].';';
        $result = Db::getInstance()->executeS($sql);
        
        $options = array();
        foreach ($result as $row) {
            array_push( $options, new GearOption( intval( $row['id'] ) ) );
        }
        
        return $options;
    }

    /**
     * Get all option objects
     * @param int $id_shop
     * @param boolean $only_active
     * @return array Contains GearOption objects
     */
    public static function getAll($id_shop=1, $only_active=true) {
        $sql = '
            SELECT o.'.self::$definition['primary'].' AS id
            FROM  `'._DB_PREFIX_.self::$definition['table'].'` AS o
            INNER JOIN  `'._DB_PREFIX_.self::$definition['table'].'_shop` AS s ON o.'.self::$definition['primary'].' = s.'.self::$definition['primary'].'
            WHERE s.id_shop='.$id_shop;
        if($only_active) $sql .= ' AND o.active=TRUE';
        $sql .= ' ORDER BY o.id_section, o.order;';
        $result = Db::getInstance()->executeS($sql);
        
        $options = array();
        foreach ($result as $row) {
            array_push( $options, new GearOption( intval( $row['id'] ) ) );
        }
        
        return $options;
    }
    
    /**
     * Remove all options for a shop from database
     * @param int $id_shop
     * @return boolean
     */
    public static function deleteAll($id_shop) {
        $sql = '
            DELETE o
            FROM  `'._DB_PREFIX_.self::$definition['table'].'` AS o
            INNER JOIN  `'._DB_PREFIX_.self::$definition['table'].'_shop` AS s ON o.'.self::$definition['primary'].' = s.'.self::$definition['primary'].'
            WHERE s.id_shop='.$id_shop;
        return Db::getInstance()->execute($sql);
    }

    /**
     * Remove all options for all shops from database
     * @return boolean
     */
    public static function truncate() {
        $sql = 'TRUNCATE `'._DB_PREFIX_.self::$definition['table'].'`; TRUNCATE `'._DB_PREFIX_.self::$definition['table'].'_shop`;';
        return Db::getInstance()->execute($sql);
    }

    /**
     * Create table structure
     * @return boolean
     */
    public static function createTables() {
        return self::createTable() && self::createShopTable();
    }
    
    /**
     * Remove table structure
     * @return boolean
     */
    public static function dropTables() {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'bskgear_options` , `'._DB_PREFIX_.'bskgear_options_shop`');
    }

    /**
     * Create table
     * @return boolean
     */
    public static function createTable() {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'bskgear_options` (
                `id_bskgear_options` int(10) unsigned NOT NULL auto_increment,
                `name` varchar(255) NOT NULL,
                `value` varchar(10000) NOT NULL,
                `label` varchar(255),
                `id_section` int(10) unsigned NOT NULL,
                `active` tinyint(1) NOT NULL,
                `description` varchar(255) default NULL,
                `field` varchar(10000) NOT NULL,
                `order` int(10) unsigned NOT NULL,
            PRIMARY KEY  (`id_bskgear_options`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;
        ');
    }
    
    /**
     * Create table to support multishop
     * @return boolean
     */
    public static function createShopTable() {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'bskgear_options_shop` (
                `id_bskgear_options` int(10) unsigned NOT NULL auto_increment,
                `id_shop` int(10) unsigned NOT NULL,
            PRIMARY KEY (`id_bskgear_options`, `id_shop`)
          ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;
        ');
    }
    
    /**
     * Get the checked value of radio option
     * @param GearOption|json $option
     * @return boolean
     */
    public static function checkedRadioValue($option) {
        if (is_a($option, 'GearOption') && Validate::isLoadedObject($option) && $option->field == 'radio') {
            $option = json_decode($option->value, true);
        } else if (is_string($option)) {
            $option = json_decode($option, true);
        }
        if (is_array($option)) {
            foreach ($option as $input) {
                if ($input['checked']) {
                    $option = $input['value'];
                    break;
                }
            }
            return $option;
        }
        return false;
    }
    
    /**
     * Test if switch option is on
     * @return boolean
     */
    public function isOn() {
        if ($this->field == 'switch' && $this->value == 'on') {
                return true;
        }
        return false;
    }
}

/**********
 * Sections(Tabs) Model
 **********/
class GearSection extends ObjectModel {
    public $id;
    public $id_bskgear_section;
    public $name;
    public $label;
    public $active;
    public $description;
    public $order;
    
    public static $definition = array(
        'table' => 'bskgear_sections',
        'primary'   => 'id_bskgear_section',
        'multishop' => true,
        'fields'            => array(
            'name'          => array( 'type' => self::TYPE_STRING , 'validate' => 'isString' , 'required' => true ),
            'label'         => array( 'type' => self::TYPE_STRING , 'validate' => 'isString' , 'required' => true ),
            'active'        => array( 'type' => self::TYPE_BOOL , 'required' => true ),
            'description'   => array( 'type' => self::TYPE_STRING , 'validate' => 'isString' ),
            'order'         => array('type' => self::TYPE_INT),
        )
    );
    
    /**
     * Add a new section and save it into D
     * 
     * @param string $name Variable identifier
     * @param string $label Displayed name
     * @param int $order Order to be displayed
     * @param int $id_shop Asocieted shop
     * @param string $description
     * @param boolean $active Enable/Disable section
     * @return int section id
     */
    public static function addSection($name, $label, $order=100, $id_shop = 1, $description='', $active=true) {
        $section = new GearSection();
        $section->name = $name;
        $section->label = $label;
        $section->order = $order;
        $section->description = $description;
        $section->active = $active;
        if( $section->add() && $section->associateTo($id_shop) ){
            return $section->id;
        }
        return false;
    }
    
    /**
     * Get section by name
     * @param string $name
     * @param int $id_shop
     * @return GearSection
     */
    public static function getByName($name, $id_shop=1) {
        $sql = '
            SELECT o.'.self::$definition['primary'].' AS id
            FROM  `'._DB_PREFIX_.self::$definition['table'].'` AS o
            INNER JOIN  `'._DB_PREFIX_.self::$definition['table'].'_shop` AS s ON o.'.self::$definition['primary'].' = s.'.self::$definition['primary'].'
            WHERE o.name="'.$name.'" AND s.id_shop='.$id_shop.';';
        $result = Db::getInstance()->executeS($sql);
        if (count($result)) {
            return new GearSection( intval( $result[0]['id'] ) );
        }
        return new GearSection();
    }
    
    /**
     * Get all section objects
     * @param int $id_shop
     * @param boolean $only_active
     * @return array Contains GearSection objects
     */
    public static function getAll($id_shop=1, $only_active=true) {
        $sql = '
            SELECT o.'.self::$definition['primary'].' AS id
            FROM  `'._DB_PREFIX_.self::$definition['table'].'` AS o
            INNER JOIN  `'._DB_PREFIX_.self::$definition['table'].'_shop` AS s ON o.'.self::$definition['primary'].' = s.'.self::$definition['primary'].'
            WHERE s.id_shop='.$id_shop;
        if($only_active) $sql .= ' AND o.active=TRUE';
        $sql .= ' ORDER BY o.order, o.'.self::$definition['primary'].';';
        $result = Db::getInstance()->executeS($sql);
        
        $sections = array();
        foreach ($result as $row) {
            array_push( $sections, new GearSection( intval( $row['id'] ) ) );
        }
        
        return $sections;
    }
    
    /**
     * Remove all sections for a shop from database
     * @param int $id_shop
     * @return boolean
     */
    public static function deleteAll($id_shop) {
        $sql = '
            DELETE o
            FROM  `'._DB_PREFIX_.self::$definition['table'].'` AS o
            INNER JOIN  `'._DB_PREFIX_.self::$definition['table'].'_shop` AS s ON o.'.self::$definition['primary'].' = s.'.self::$definition['primary'].'
            WHERE s.id_shop='.$id_shop;
        return Db::getInstance()->execute($sql);
    }

    /**
     * Remove all sections for all shops from database
     * @return boolean
     */
    public static function truncate() {
        $sql = 'TRUNCATE `'._DB_PREFIX_.self::$definition['table'].'`; TRUNCATE `'._DB_PREFIX_.self::$definition['table'].'_shop`;';
        return Db::getInstance()->execute($sql);
    }
    
    /**
     * Create table structure
     * @return boolean
     */
    public static function createTables() {
        return self::createTable() && self::createShopTable();
    }
    
    /**
     * Remove table structure
     * @return boolean
     */
    public static function dropTables() {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'bskgear_sections` , `'._DB_PREFIX_.'bskgear_sections_shop`');
    }
    
    /**
     * Create table
     * @return boolean
     */
    public static function createTable() {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'bskgear_sections` (
                `id_bskgear_section` int(10) unsigned NOT NULL auto_increment,
                `label` varchar(255) NOT NULL,
                `name` varchar(255) NOT NULL,
                `active` tinyint(1) NOT NULL,
                `description` varchar(255) default NULL,
                `order` int(10) unsigned NOT NULL,
            PRIMARY KEY  (`id_bskgear_section`)
          ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;
        ');
    }
    
    /**
     * Create table to support multishop
     * @return boolean
     */
    public static function createShopTable() {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'bskgear_sections_shop` (
                `id_bskgear_section` int(10) unsigned NOT NULL auto_increment,
                `id_shop` int(10) unsigned NOT NULL,
            PRIMARY KEY (`id_bskgear_section`, `id_shop`)
          ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;
        ');
    }
}
