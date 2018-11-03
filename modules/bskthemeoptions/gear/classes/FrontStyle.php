<?php

class FrontStyle {
    /**
     * File to output css
     * @var string 
     */
    protected $css_file;
    
    /**
     * Sass parser object
     * @var SassParser 
     */
    protected $sass;
    
    /**
     * Fake mixins that are not supported
     * @var array
     */
    public $fake_mixin = array('opacity', 'box-shadow', 'box-sizing', 'background-clip', 'sub-heading');
    
    public static function generateGearCss($data) {
        if ((!$xml = simplexml_load_file(GEAR_SASS_RULES)) && GEAR_DEV) {
            throw new SmartyException('Unable to load sass variables xml');
        }
        return self::render($data, $xml);
    }
    
    /**
     * Create css file from options and xml parsed sass rules
     * @param array $data @see Gear->prepareData()
     * @param SimpleXMLElement $xml
     * @return int
     */
    public static function render($data, $xml) {
        require_once '../css/sass.php';
        $fs = self::getInstance();
        $themeCss = new ThemeCss($fs->getVars($data), $xml);
        $css = $themeCss->getContent();
        return file_put_contents($fs->css_file, $css);
    }
    
    /**
     * Put all options in an array
     * @param array $data array of GearSection with GearOptions @see Gear->prepareData()
     * @return array
     */
    private function getVars($data) {
        $option = array();
        if (!empty($data)) {
            foreach ($data as $section) {
                foreach ($section->options as $opt) {
                    $option[$opt->name] = $opt->value;
                }
            }
        }
        return $option;
    }
    
    /**
     * Create XML with css rules from directory with scss files
     * @param string $dir
     * @param string $xml_file
     * @return boolean
     * @throws SmartyException
     */
    public function mapRulesFromDir($dir, $xml_file = null) {
        if (is_dir($dir)) {
            $dir = new RecursiveDirectoryIterator($dir);
            if ((!$xml = simplexml_load_file(GEAR_SASS_VARS)) && GEAR_DEV) {
                throw new SmartyException('Unable to load sass variables xml');
            }
            
            foreach ($xml->property as $property) {
                foreach (new RecursiveIteratorIterator($dir) as $filepath => $file) {
                    $filename = $file->getFilename();
                    if (preg_match('/^(?!_).+\.scss$/i', $filename)) { // files that don't start with _ and end with .scss
                        $root = $this->sass->parse($filepath, true);
                        if (isset($property->name)) {
                            $rules = $this->getRulesByProperty($root, $property->value->__toString(), $property->name->__toString());
                        } else {
                            $rules = $this->getRulesByProperty($root, $property->value->__toString());
                        }
                        if (!empty($rules)) {
                            $pf = $property->addChild('file');
                            $pf->addAttribute('filename', $filename);
                            foreach ($rules as $rule) {
                                $pf->addChild('rule', $rule);
                            }
                        }
                    }
                }
            }
            
            return $xml->asXML($xml_file);
        }
        return false;
    }

    /**
     * Get css rules that have a property from scss file
     * @param string $root SASS tree or SCSS file path
     * @param string $var variable or string used as property value
     * @param string $name property name (optional)
     * @return array CSS rules
     */
    public function getRulesByProperty($root, $var, $name=null) {
        if (is_string($var)) {
            if (is_string($root) && is_file($root)) {
                $root = $this->sass->parse($root, true); // create sass tree
            }
            
            if (is_a($root, 'SassRootNode')) {
                $this->searchRuleNode($root, $var, $name);
                $rawRules = $root->render($this->createSassContext());
                // extract only rules
                $rules = array();
                while ($rule = strstr($rawRules, '{', true)) {
                    $rawRules = str_replace(strstr($rawRules, '}', true).'}', '', $rawRules);
                    $rules[] = trim(preg_replace('/\s+/', ' ', trim($rule)));
                }
                return $rules;
            }
        }
        return false;
    }
    
    /**
     * Keep in the sass tree only the nodes that have the searched property
     * @param SassRootNode $node
     * @param string $var
     * @param string $name
     */
    protected function searchRuleNode(&$node, $var, $name=null) {
        if (is_a($node, 'SassRuleNode') || is_a($node, 'SassRootNode')) {
            foreach ($node->children as $i => &$child_node) {
                if (is_a($child_node, 'SassPropertyNode')) {
                    if (trim($child_node->value) == $var) {
                        if (is_string($name) && trim($child_node->name) !== $name) {
                                unset($node->children[$i]);
                        }
                    } else {
                        unset($node->children[$i]);
                    }
                } else if (
                        is_a($child_node, 'SassMixinNode') || 
                        is_a($child_node, 'SassMediaNode') ||
                        is_a($child_node, 'SassDirectiveNode') ||
                        is_a($child_node, 'SassCommentNode') ||
                        is_a($child_node, 'SassImportNode')
                ) {
                    unset($node->children[$i]);
                } else {
                    $this->searchRuleNode($child_node, $var, $name);
                }
            }
        }
    }
    
    /**
     * SassContext with unsupported mixins
     * @return SassContext
     */
    protected function createSassContext() {
        $context = new SassContext();
        $fakeMixin = new stdClass();
        $fakeMixin->args = array();
        $fakeMixin->children = array();
        foreach ($this->fake_mixin as $name) {
            $context->addMixin($name, $fakeMixin);
        }
        return $context;
    }
    
    /* Singleton */
    public static function getInstance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }
    protected function __construct() {
        $this->css_file = _PS_THEME_DIR_.'css/'.'gear.css';
        
        require_once('libs/phpsass/SassParser.php');
        $basepath = $_SERVER['PHP_SELF'];
        $basepath = substr($basepath, 0, strrpos($basepath, '/') + 1);
        $options = array(
            'style' => 'nested',
            'cache' => false,
            'debug' => false,
            'syntax' => 'scss',
            'basepath' => $basepath.'libs/phpsass/',
            'extensions' => array('compass' => array())
        );
        $this->sass = new SassParser($options);
    }
    private function __clone() {}
    private function __wakeup() {}
    
}