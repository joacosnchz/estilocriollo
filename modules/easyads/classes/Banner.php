<?php

class Banner extends ObjectModel {
    
    /**
     * Relative path to image
     * @var string 
     * @see Banner::getAbsoluteImagePath()
     */
    public static $image_path = 'bskadv';

    public $id;
    public $id_item;
    public $embed;
    public $embed_code;
    public $embed_popup;
    public $target;
    public $hook;
    public $exceptions;
    public $position;
    public $class;
    public $active;
    // Lang fields
    public $image;
    public $title;
    public $url;

    public static $definition = array(
        'table' => 'easyads',
        'primary'   => 'id_item',
        'multilang' => true,
        'multishop' => true,
        'fields' => array(
            'id_item' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'hook' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'exceptions' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'embed' => array('type' => self::TYPE_BOOL),
            'embed_code' => array('type' => self::TYPE_HTML, 'validate' => 'isString', 'size' => 10000),
            'embed_popup' => array('type' => self::TYPE_BOOL),
            'target' => array('type' => self::TYPE_BOOL),
            'position' => array('type' => self::TYPE_INT),
            'class' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'active' => array('type' => self::TYPE_BOOL),
            // Lang fields
            'image' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'lang' => true),
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'lang' => true),
            'url' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'lang' => true)
        )
    );
    
    public function __construct($id = null, $id_lang = null, $id_shop = null) {
        parent::__construct($id, $id_lang, $id_shop);
        $this->image_dir = self::$image_path;
        $this->image_format = 'jpg';
    }
    
    /**
     * Create table structure
     * @return boolean
     */
    public static function createTables() {
        return self::createTable() && self::createLangTable() && self::createShopTable() && self::createStatsTable();
    }
    
    /**
     * Remove table structure
     * @return boolean
     */
    public static function dropTables() {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS 
            `'._DB_PREFIX_.self::$definition['table'].'` ,
            `'._DB_PREFIX_.self::$definition['table'].'_lang` ,
            `'._DB_PREFIX_.self::$definition['table'].'_shop` ,
            `'._DB_PREFIX_.self::$definition['table'].'_stats`');
    }
    
    /**
     * Create table
     * @return boolean
     */
    protected static function createTable() {
        return (
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.self::$definition['table'].'`') &&
        Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'` (
                `'.self::$definition['primary'].'` int(10) unsigned NOT NULL auto_increment,
                `hook` int(10) unsigned NOT NULL,
                `exceptions` varchar(255),
                `embed` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
                `embed_code` varchar(10000),
                `embed_popup` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
                `target` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
                `position` int(10) unsigned NOT NULL,
                `class` varchar(255),
                `active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
            PRIMARY KEY  (`'.self::$definition['primary'].'`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;
        '));
    }
    
    /**
     * Create table to support multilanguage
     * @return boolean
     */
    protected static function createLangTable() {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'_lang` (
                `'.self::$definition['primary'].'` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `image` varchar(255),
                `title` varchar(255),
                `url` varchar(255),
            PRIMARY KEY (`'.self::$definition['primary'].'`, `id_lang`)
          ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;
        ');
    }
    
    /**
     * Create table to support multishop
     * @return boolean
     */
    protected static function createShopTable() {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'_shop` (
                `'.self::$definition['primary'].'` int(10) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
            PRIMARY KEY (`'.self::$definition['primary'].'`, `id_shop`)
          ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;
        ');
    }
    
    /**
     * Create table for click through stats
     * @return boolean
     */
    protected static function createStatsTable() {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'_stats` (
                `id_stats` int(10) unsigned NOT NULL auto_increment,
                `'.self::$definition['primary'].'` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `clicks` int(10) unsigned NOT NULL,
                `date` date NOT NULL,
            PRIMARY KEY (`id_stats`)
          ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;
        ');
    }
    
    /**
     * Get array of banners from db by hook name
     * @param string $hook
     * @param int $id_lang
     * @param int $id_shop
     * @param boolean $active
     * @return array
     */
    public static function getByHook($hook, $id_lang=null, $id_shop=null, $active=false) {
        if (!is_int($id_lang)) $id_lang = (int)Context::getContext()->language->id;
        if (!is_int($id_shop)) $id_shop = (int)Context::getContext()->shop->id;
        
        $sql = '
            SELECT b.'.self::$definition['primary'].', bl.title, bl.image, bl.url, b.exceptions, b.embed, b.embed_code, b.embed_popup, b.target, b.position, b.class, b.active
            FROM  `'._DB_PREFIX_.self::$definition['table'].'` AS b
            JOIN  `'._DB_PREFIX_.self::$definition['table'].'_lang` AS bl ON b.'.self::$definition['primary'].' = bl.'.self::$definition['primary'].'
            JOIN  `'._DB_PREFIX_.self::$definition['table'].'_shop` AS bs ON b.'.self::$definition['primary'].' = bs.'.self::$definition['primary'].'
            WHERE b.hook='.$hook.' AND bl.id_lang='.$id_lang.' AND bs.id_shop='.$id_shop;
        if($active) $sql .= ' AND b.active=TRUE';
        $sql .= ' ORDER BY b.position;';
        $result = Db::getInstance()->executeS($sql);
        
        return $result;
    }
    
    /**
     * Create a new banner into DB
     * @param string $hook assigned hook, module must be registered on this hook
     * @param array|string $title
     * @param array|string $url
     * @param string $exceptions,
     * @param boolean $embed,
     * @param string $embed_code,
     * @param boolean $embed_popup
     * @param boolean $target
     * @param array|string $image
     * @param int $position
     * @param boolean $active
     * @param int $id_lang
     * @param int $id_shop
     * @return int Banner id
     */
    public static function create($hook, $title=null, $url=null, $exceptions=null, $embed=false, $embed_code=null, $embed_popup=true, $target=true, $image=null, $position=100, $class=null, $active=true, $id_lang=null, $id_shop=null) {
        if (is_null($id_lang)) {
            $id_lang = (int)Context::getContext()->language->id;
        }
        if (is_null($id_shop)) {
            $id_shop = (int)Context::getContext()->shop->id;
        }
        
        $banner = new Banner();
        $banner->hook = $hook;
        $banner->exceptions = $exceptions;
        $banner->embed = $embed;
        $banner->embed_code = $embed_code;
        $banner->embed_popup = $embed_popup;
        $banner->target = $target;
        $banner->position = $position;
        $banner->class = $class;
        $banner->active = $active;
        if (is_array($image)) {
            $banner->image = $image;
        } else if (is_string($image)) {
            $banner->image[$id_lang] = $image;
        }
        if (is_array($title)) {
            $banner->title = $title;
        } else if (is_string($title)) {
            $banner->title[$id_lang] = $title;
        }
        if (is_array($url)) {
            $banner->url = $url;
        } else if (is_string($url)) {
            $banner->url[$id_lang] = $url;
        }
        
        if( $banner->add() && $banner->associateTo($id_shop) ) {
            return $banner->id;
        }
        return false;
    }
    
    /**
     * Remove Banner from DB and delete associated images
     * @param int $id
     * @return boolean
     */
    public static function remove($id) {
        $banner = new Banner((int)$id);
        if (Validate::isLoadedObject($banner)) {
            self::deleteImages($banner->image);
            return $banner->delete();
        }
    }
    
    /**
     * Delete banner images
     * @param array|string $image
     */
    public static function deleteImages($image) {
        if (is_array($image)) {
            $images = $image;
        } else if (is_string($image)) {
            $images = array($image);
        }
        foreach ($images as $id_lang => $image) {
            $file = self::getServerImagePath(Language::getIsoById($id_lang)).$image;
            if (file_exists($file)) {
                @unlink($file);
            }
        }
    }
    
    /**
     * Edit banner into DB
     * Pass null field to not change it
     * @param int $id
     * @param string $hook
     * @param array|string $title
     * @param array|string $url
     * @param boolean $embed
     * @param string $embed_code
     * @param boolean $embed_popup
     * @param boolean $target
     * @param array|string $image
     * @param int $position
     * @param boolean $active
     * @return boolean
     */
    public static function edit($id, $hook=null, $title=null, $url=null, $exceptions=null, $embed=null, $embed_code=null, $embed_popup=null, $target=null, $image=null, $position=null, $class=null, $active=null) {
        $banner = new Banner((int)$id);
        if (Validate::isLoadedObject($banner)) {
            self::_editField($banner->hook, $hook);
            self::_editField($banner->exceptions, $exceptions);
            self::_editField($banner->embed, $embed);
            self::_editField($banner->embed_code, $embed_code);
            self::_editField($banner->embed_popup, $embed_popup);
            self::_editField($banner->target, $target);
            self::_editField($banner->position, $position);
            self::_editField($banner->class, $class);
            self::_editField($banner->active, $active);
            // Lang fields
            self::_editField($banner->image, $image, true, true);
            self::_editField($banner->title, $title, true);
            self::_editField($banner->url, $url, true);
            
            return $banner->update();
        } else {
           return false;
        }
    }
    
    /**
     * Edit banner field with input
     * @param Banner_field $field Banner field refferece
     * @param array|string $input substitute value
     * @param boolean $lang Banner field is multilang
     */
    private static function _editField(&$field, $input=null, $lang=false, $image=false) {
        if (!is_null($input)) {
            if ($lang) {
                if (!is_array($input)) {
                    $input = array($input);
                }
                foreach ($input as $id_lang => $value) {
                    if (!isset($field[$id_lang]) || ($value && $value != $field[$id_lang])) {
                        if ($image) self::deleteImages ($value); // delete old image
                        $field[$id_lang] = $value;
                    }
                }
            } else if ($input != $field) {
                if ($image) self::deleteImages ($field); // delete old image
                $field = $input;
            }
        }
    }

    /**
     * Set one or multiple fields
     * <br>
     * Usage:<br>
     * Banner::setField(1, array('hook' => 'displayHome', 'position' => 3));<br>
     * Banner::setField(1, 'hook', 'displayHome');<br>
     * Banner::setField(1, 'active', '_toggle');
     * 
     * @param int $id
     * @param array|string $field array(field => value) | name of the field
     * @param mixed $value field value | '_toggle' change boolean field status
     * @return boolean
     */
    public static function setField($id, $field=null, $value=null) {
        if (Validate::isUnsignedInt((int)$id)) {
            $banner = new Banner((int)$id);
            if (Validate::isLoadedObject($banner)) {
                $fields = array();
                if (is_array($field)) { // array(field => value)
                    $fields = $field;
                } else if (is_string($field)) {
                    $fields[$field] = $value;
                }
                foreach ($fields as $field => $value) {
                    if (array_key_exists($field, self::$definition['fields'])) { // is valid banner field
                        if ($value == '_toggle') { // boolean field
                            $banner->{$field} = !$banner->{$field};
                        } else {
                            $banner->{$field} = $value;
                        }
                    }
                }
                return $banner->update();
            }
        }
        return false;
    }
    
    /**
     * Get relative image path
     * @param string $append append path string
     * @param boolean $end_slash add ending slash
     * @return string
     */
    public static function getRelativeImagePath($append=null, $end_slash=true) {
        return self::getImagePath(false, $append, $end_slash);
    }
    
    /**
     * Get server image path
     * @param string $append append path string
     * @param boolean $end_slash add ending slash
     * @return string
     */
    public static function getServerImagePath($append=null, $end_slash=true) {
        return self::getImagePath(true, $append, $end_slash);
    }
    
    /**
     * Get image path on server
     * @param boolean $type server or relative path
     * @param string $append append path string
     * @param boolean $end_slash add ending slash
     * @return string
     */
    public static function getImagePath($type=false, $append=null, $end_slash=true) {
        if ($type) { // server path
            $path = _PS_IMG_DIR_.self::$image_path;
        } else{ // relative path
            $path = _PS_IMG_.self::$image_path;
        }
        
        $dir_sep = $type ? DIRECTORY_SEPARATOR : '/';
        if (is_string($append) && $append !== '') {
            $path .= $dir_sep.$append;
        }
        if ($end_slash) {
            $path .= $dir_sep;
        }
        
        return $path;
    }
    
    public static function getClickStatsByHook($hook, $date_start=null, $date_end=null, $id_lang=null, $id_shop=null, $active=false) {
        if (!is_int($id_lang)) $id_lang = (int)Context::getContext()->language->id;
        if (!is_int($id_shop)) $id_shop = (int)Context::getContext()->shop->id;
        
        $data = array();
        $banners = self::getByHook($hook, $id_lang, $id_shop, $active);
        foreach ($banners as $banner) {
            $banner = new Banner($banner['id_item'], $id_lang, $id_shop);
            $bdata = array(
                'key' => $banner->title,
                'disabled' => !$banner->active
            );
            $stats = $banner->getClickStats($date_start, $date_end, $id_lang);
            if (!empty($stats)) {
                foreach ($stats as &$stat) {
                    $stat['x'] = strtotime($stat['x']);
                }
            } else {
                $stats[] = array('x'=>time(), 'y'=>0); // blank data
            }
            $bdata['values'] = $stats;
            $data[] = $bdata;
        }
        
        return $data;
    }
    
    /**
     * Set a click through stat for a specific date
     * @param int $clicks send as null to increment clicks by one
     * @param DateTime $date
     * @param int $id_lang
     * @return boolean
     */
    public function clickThrough($clicks=null, $date=null, $id_lang=null) {
        if (ValidateCore::isLoadedObject($this)) {
            if (is_null($date)) $date = new DateTime();
            if (!is_int($id_lang)) $id_lang = (int)Context::getContext()->language->id;
            
            $stats = $this->getClickStats($date, null, $id_lang);
            if (empty($stats)) { // there are no stats yet
                return Db::getInstance()->insert(self::$definition['table'].'_stats', array(
                    'id_item' => $this->id,
                    'id_lang' => $id_lang,
                    'clicks' => is_int($clicks) ? $clicks : 1,
                    'date' => $date->format('Y-m-d')
                ));
            } else {
                return Db::getInstance()->update(self::$definition['table'].'_stats', array(
                    'clicks' => is_int($clicks) ? $clicks : ++$stats[0]['y']
                ), self::$definition['primary'].'='.$this->id.' AND id_lang='.$id_lang.' AND date="'.$date->format('Y-m-d').'"');
            }
        }
    }
    
    /**
     * Get click stats for one or interval date
     * @param DateTime $date_start
     * @param DateTime $date_end send as null to return stats for one date
     * @param int $id_lang
     * @return array Returns false if there aren't any stats available
     */
    public function getClickStats($date_start=null, $date_end=null, $id_lang=null) {
        if (ValidateCore::isLoadedObject($this)) {
            if (!is_int($id_lang)) $id_lang = (int)Context::getContext()->language->id;
            if (!is_a($date_start, 'DateTime')) {
                $date_start = new DateTime(is_string($date_start) ? $date_start : 'now');
            }
            if (!is_a($date_end, 'DateTime')) {
                $date_end = new DateTime(is_string($date_end) ? $date_end : 'now');
                if ($date_start > $date_end) $date_end = $date_start;
            }

            $sql = 'SELECT date AS x, clicks AS y FROM '._DB_PREFIX_.self::$definition['table'].'_stats WHERE '.self::$definition['primary'].'='.$this->id;
            if ($date_start == $date_end) {
                $sql .= ' AND date="'.$date_start->format('Y-m-d').'"'; // one date
            } else {
                $sql .= ' AND date BETWEEN "'.$date_start->format('Y-m-d').'" AND "'.$date_end->format('Y-m-d').'"'; // interval date
            }

            return Db::getInstance()->executeS($sql);
        }
    }
    
}
