<?php
/**
 * BitSHOK Gear
 * Admin Configuration Interface for BitSHOK Themes
 * 
 * @version 0.1.3
 * @author BitSHOK <bitshok@gmail.com>
 */

define('__DIR__', dirname(__FILE__));
define('GEAR_PARENT_NAME', 'bskthemeoptions');
define('GEAR_PARENT_FILE', __DIR__.DIRECTORY_SEPARATOR.GEAR_PARENT_NAME.'.php');
define('GEAR_NAME', 'gear');
define('GEAR_DIR', GEAR_PARENT_NAME.DIRECTORY_SEPARATOR.GEAR_NAME);
require_once str_replace('modules'.DIRECTORY_SEPARATOR.GEAR_DIR, 'config'.DIRECTORY_SEPARATOR.'config.inc.php', __DIR__);

/* @todo-bsk move in intermediary module */
define('GEAR_SASS_VARS', _PS_MODULE_DIR_.GEAR_DIR.'/classes/sassvars.xml');
define('GEAR_SASS_RULES', _PS_MODULE_DIR_.GEAR_PARENT_NAME.'/sassrules.xml');

/* Development mode */
define('GEAR_DEV', _PS_MODE_DEV_ ? _PS_MODE_DEV_ : false);

require_once 'classes/Model.php';

class Gear extends ModuleCore {
    
    public $id_shop;
    public $id_lang;

    public $title = 'BitSHOK Gear';
    public $css_files = array();
    public $js_files = array();
    public $cdn_jquery = true;
    public $cdn_bootstrap = true;
    
    /**
     * Signal if a configuration file was imported
     * @var boolean
     * @see Gear->prepareData()
     */
    private $imported = false;

    public function __construct() {
        $this->id_shop = (int)Tools::getValue('id_shop');
        $this->id_lang = (int)Tools::getValue('id_lang');
        
        $this->name = GEAR_PARENT_NAME;
        parent::__construct();
    }

    /**
     * Render the user interface to be displayed
     * @return string
     */
    public function renderUI() {
        require_once 'classes/Helper.php';
        $this->init();
        
        $form_msg = $this->process_export_import();
        $this->prepareHead();
        $data = $this->prepareData();
        
        $helper = new GearHelper($this->context->smarty);
        $form_msg .= $helper->postProcess($data);
        $content = $helper->createTemplate($data);
        
        $this->smarty->assign(array(
            'form_action'   => Tools::safeOutput($_SERVER['REQUEST_URI']),
            'gear_path'     => __DIR__,
            'img_dir'       => _THEME_IMG_DIR_,
            'form_msg'      => $form_msg
        ));
        
        $ui = $this->display(GEAR_PARENT_FILE, GEAR_NAME.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'header.tpl');
        $ui .= $content;
        $ui .= $this->display(GEAR_PARENT_FILE, GEAR_NAME.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'footer.tpl');
        
        return $ui;
    }
    
    /**
     * Get options grouped by section name
     * @return array
     */
    public function prepareData() {
        $sections = GearSection::getAll($this->id_shop);
        $this->smarty->assign('sections', $sections);
        $data = array();
        foreach($sections as $section) {
            $opts = GearOption::getBySection($section->id, $this->id_shop);
            $std = new stdClass();
            $std->id = $section->id;
            $std->name = $section->name;
            $std->label = $section->label;
            $std->options = $opts;
            array_push($data, $std);
        }
        
        if ($this->imported) { // generate css after import
            require_once 'classes/FrontStyle.php';
            FrontStyle::generateGearCss($data);
        }
        
        return $data;
    }
    
    /**
     * Prepare styles, script and plugins to be displayed on <head>
     */
    public function prepareHead() {
        // add jQuery
        if($this->cdn_jquery) {
            $this->addJS('//code.jquery.com/jquery-2.1.0.min.js', true);
            $this->addJS('//code.jquery.com/jquery-migrate-1.2.1.min.js', true);
        } else {
            $this->addJS(array('jquery-2.1.0.min', 'jquery-migrate-1.2.1.min'));
        }
        // add bootstrap
        if($this->cdn_bootstrap) {
            $this->addCSS('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css', true);
            $this->addJS('//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js', true);
        } else {
            $this->addCSS('bootstrap.min');
            $this->addJS('bootstrap.min');
        }
        
        $this->addCSS(array('bootstrap-switch.min', 'spectrum'));
        $this->addJS(array('plugins'));
        
        // ace code editor
        $this->addJS('libs/ace/ace.js', true);
        
        $this->addCSS('style');
        $this->addJS('script');
        
        $this->smarty->assign(array(
            'title'     => $this->title,
            'css_files' => $this->css_files,
            'js_files'  => $this->js_files,
        ));
    }
    
    /**
     * Event handler for export and import
     * @return string Alert message
     */
    public function process_export_import() {
        if(Tools::isSubmit('exportSubmit')){ // export data
            $this->_export();
        } else if(!empty($_FILES['importFile']['tmp_name'])){ // import data
            $file = $_FILES['importFile'];
            $type = Tools::strtolower(Tools::substr(strrchr($file['name'], '.'), 1));
            if ( $type == 'json' ){
                return $this->_import($file['tmp_name']);
            } else {
                return GearHelper::displayAlert('Please use a valid JSON file for import', 'danger');
            }
        }
    }
    
    /**
     * Prompt user to save configuration
     */
    private function _export() {
        $file = $this->writeJSON('load');
        if(file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }
    
    /**
     * Import a configuration file
     * @param string $file File path
     * @param boolean $backup create a backup file before import
     * @param boolean $update update values if already exist into database
     * @return string Alert message
     */
    private function _import($file, $backup=true, $update=true) {
        // backup configuration
        if ($backup) {
            if (!$this->writeJSON('load')) {
                return GearHelper::displayAlert('Could not create backup before import', 'danger');
            }
        }
        if (!$update) { // delete old configuration
            if (!GearOption::deleteAll($this->id_shop)){
                return GearHelper::displayAlert('An error occured when removing initial options', 'danger');
            }
            if (!GearSection::deleteAll($this->id_shop)){
                return GearHelper::displayAlert('An error occured when removing initial sections', 'danger');
            }
        }
        // import new configuration
        $errors = $this->readJSON($file, true, $update);
        $output = '';
        foreach ($errors as $err) {
            $output .= GearHelper::displayAlert($err, 'danger');
        }
        if (!$output) {
            $this->imported = true;
            $output = GearHelper::displayAlert('Import successfull', 'success');
        }
        return $output;
    }

    /**
     * Add a css file
     * @param string $filename The name of the file or files in the css folder or the full file path
     * @param boolean $set_path Set to true to use the full file path. It doesn't work with array of filenames
     */
    private function addCSS($filename, $set_path = false) {
        if(is_array($filename)){
            foreach ($filename as $file) {
                array_push($this->css_files, 'css/'.$file.'.css');
            }
        } else if($set_path) {
            array_push($this->css_files, $filename);
        } else {
            array_push($this->css_files, 'css/'.$filename.'.css');
        }
    }

    /**
     * Add a js file
     * @param string $filename The name of the file or files in the js folder or the full file path
     * @param boolean $set_path Set to true to use the full file path. It doesn't work with array of filenames
     */
    private function addJS($filename, $set_path = false) {
        if(is_array($filename)){
            foreach ($filename as $file) {
                array_push($this->js_files, 'js/'.$file.'.js');
            }
        } else if($set_path) {
            array_push($this->js_files, $filename);
        } else {
            array_push($this->js_files, 'js/'.$filename.'.js');
        }
    }
    
    /**
     * Read and decode a JSON file
     * @param string $file File path
     * @param boolean $save Save decoded data into database
     * @param boolean $update update values if already exist into database
     * @return array Errors if occur, option structure if $save is false
     * @throws SmartyException
     */
    private function readJSON($file, $save=true, $update=true) {
        if(!file_exists($file)) throw new SmartyException("File doesn't exists!'");
        $data = json_decode( file_get_contents($file) );
        if($save) {
            $errors = array();
            if (!$update) { // create new sections and options
                foreach ($data as $section) {
                    $section->id = GearSection::addSection(
                            $section->name,
                            $section->label,
                            $section->order,
                            $this->id_shop,
                            $section->description,
                            $section->active
                    );
                    if ($section->id) {
                        foreach ($section->options as $option) {
                            $option->id = GearOption::addOption(
                                    $option->name,
                                    $option->value,
                                    $option->label,
                                    $section->id,
                                    $option->field,
                                    $option->description,
                                    $option->order,
                                    $this->id_shop,
                                    $option->active
                            );
                            if(!$option->id) $errors[] = 'Option '.$option->name.' could not be saved.<br>';
                        }
                    } else {
                        $errors[] = 'Section '.$section->name.' could not be saved.<br>';
                    }
                }
            } else { // override existing options
                foreach ($data as $section) {
                    $dbsection = GearSection::getByName($section->name);
                    $dbsection->label = $section->label;
                    $dbsection->active = $section->active;
                    $dbsection->description = $section->description;
                    $dbsection->order = $section->order;
                    
                    if ($dbsection->save() && $dbsection->associateTo($this->id_shop)) { // add or update section
                        foreach ($section->options as $option) {
                            $dboption = GearOption::getByName($option->name);
                            $dboption->id_section = $dbsection->id;
                            $dboption->value = $option->value;
                            $dboption->label = $option->label;
                            $dboption->active = $option->active;
                            $dboption->description = $option->description;
                            $dboption->field = $option->field;
                            $dboption->order = $option->order;
                            
                            if (!$dboption->save() && !$dboption->associateTo($this->id_shop)) { // add or update option
                                if(!$option->id) $errors[] = 'Option '.$option->name.' could not be saved.<br>';
                            }
                        }
                    } else {
                        $errors[] = 'Section '.$section->name.' could not be saved.<br>';
                    }
                }
            }
            
            return $errors;
        } else {
            return $data;
        }
    }
    
    /**
     * Encode sections and options into a JSON file
     * @param string $dir Directory to write
     * @return string|boolean File path | failed to write
     */
    private function writeJSON($dir) {
        $sections = GearSection::getAll($this->id_shop, false);
        $data = array();
        foreach($sections as $section) {
            $std = new stdClass();
            $std->name = $section->name;
            $std->label = $section->label;
            $std->active = $section->active;
            $std->description = $section->description;
            $std->order = $section->order;
            
            $options = GearOption::getBySection($section->id, $this->id_shop, false);
            $std->options = array();
            foreach ($options as $option) {
                $opt = new stdClass();
                $opt->name = $option->name;
                $opt->value = $option->value;
                $opt->label = $option->label;
                $opt->active = $option->active;
                $opt->description = $option->description;
                $opt->field = $option->field;
                $opt->order = $option->order;
                
                array_push($std->options, $opt);
            }
            
            array_push($data, $std);
        }
        
        $file = $dir.DIRECTORY_SEPARATOR.date('YmdHis').'.json';
        if (file_put_contents($file, json_encode($data))) {
            return $file;
        }
        return false;
    }
    
    /**
     * Create model in database
     * @return type
     */
    public static function installModel() {
        return GearSection::createTables() && GearOption::createTables();
    }
    
    /**
     * Remove model from database
     * @return boolean
     */
    public static function uninstallModel() {
        return GearSection::dropTables() && GearOption::dropTables();
    }
    
    /**
     * Remove all sections and options and reset gear init
     * @devmode
     * @return boolean
     */
    public function forceReset() {
        if (GEAR_DEV && GearSection::truncate() && GearOption::truncate()){
            return self::resetInit();
        }
    }
    
    /**
     * Reset gear init for all shops
     * @return boolean
     */
    public static function resetInit() {
        $result = true;
        $shops = Shop::getShops(false, null, true);
        foreach ($shops as $id_shop) {
            $result &= Configuration::updateValue(GEAR_NAME.'_init', false, false, null, $id_shop);
        }
        return $result;
    }
    
    /**
     * Create XML file with sass rules
     * @devmode
     */
    public function generateSassRulesXml() {
        if (GEAR_DEV) {
            require_once 'classes/FrontStyle.php';
            $fs = FrontStyle::getInstance();
            $fs->mapRulesFromDir(_PS_THEME_DIR_.'sass/', GEAR_SASS_RULES);
        }
    }
    
    /**
     * Populate gear with the default options
     */
    public function init() {
//        $this->forceReset(); // uncomment to reset to default
//        $this->generateSassRulesXml(); // uncomment to create xml file with sass rules
//        $this->_import(__DIR__.'\load\layout2.json', false, false);
        
//        GearOption::addOption('curLangBlockBorder', 'rgba(22, 125, 178, 0.8)', 'Currency and Language block bottom border', 4, 'color', '', 100, 1);
//        GearOption::addOption('curLangBlockBorder', 'rgba(255, 255, 255, 1)', 'Currency and Language block bottom border', 18, 'color', '', 100, 4);
        
//        $value = array(
//            array('label' => 'Title', 'value' => '#333'),
//            array('label' => 'Text', 'value' => '#000'),
//            array('label' => 'Background', 'value' => 'rgba(0, 0, 0, 0.1)')
//        );
//        GearOption::addOption('footer', json_encode($value), 'Footer', 1, 'color_array', '', 100, 1);
//        GearOption::addOption('footer', json_encode($value), 'Footer', 15, 'color_array', '', 100, 4);
        
        if (!Configuration::get(GEAR_NAME.'_init', null, null, $this->id_shop)) {
            $this->_import('load'.DIRECTORY_SEPARATOR.'default.json', false, false);
            Configuration::updateValue(GEAR_NAME.'_init', true, false, null, $this->id_shop);
        }
    }
    
}


/***********************************
 *          Show Content
 ***********************************/
if( isset($_GET['start']) ){
    $gear = new Gear();
    echo $gear->renderUI();
}
