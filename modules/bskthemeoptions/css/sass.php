<?php

/* @todo-bsk move in intermediary module */
class ThemeCss {
    
    public $vars;
    public $xml;
    public $css;

    public function __construct($vars, $xml) {
        $this->vars = $vars;
        $this->xml = $xml;
        $this->css = '';
    }
    
    public function getContent() {
        extract($this->vars);
    
        if (isset($googleFonts) && $googleFonts != '') {
            $this->cssBlock("
                @import url('http://fonts.googleapis.com/css?family={$googleFonts}');
            ");
        }

        /* General */
        if (isset($fadeInScroll) && $fadeInScroll != 'on') {
            $this->cssBlock("#index .fadeInScroll { opacity: 1; }");
        }
        
        if (isset($linksColor)) {
            $linksColor = json_decode($linksColor, true);
            $this->cssBlock("a { color: {$linksColor[0]['value']}; }");
            $this->cssBlock("a:hover { color: {$linksColor[1]['value']}; }");
            $this->cssBlock("#sdssearch_block_top .btn.button-search { background: {$linksColor[0]['value']}; }"); // blog search button
        }

        if (isset($priceColor)) {
            $this->cssBlockFromRules('$priceColor', "color: {$priceColor};");
        }

        if (isset($textColor)) {
            $this->cssBlock("body { color: {$textColor}; }");
        }
        
        if (isset($sectionsFrameBorder) && $sectionsFrameBorder != 'on') {
            $this->cssBlock(".frame_wrap { border-color: #fff; }");
        }
        
        if (isset($sectionsFrameShadow) && $sectionsFrameShadow != 'on') {
            $this->cssBlock("
                .frame_wrap, #hookTopMenu, .blue_block { 
                    -webkit-box-shadow: none;
                    box-shadow: none;
                }");
        }
        
        if (isset($sectionsTabColor)) {
            $sectionsTabColor = json_decode($sectionsTabColor, true);
            $this->cssBlock("
                .frame_wrap_header > .fwh-title,
                .frame_wrap_header.nav.nav-tabs > li > a {
                    color: {$sectionsTabColor[0]['value']};
                }
            ");
            $this->cssBlock("
                .frame_wrap_header > .fwh-title,
                .frame_wrap_header.nav.nav-tabs .active > a {
                    border-color: {$sectionsTabColor[1]['value']};
                }
            ");
        }
        
        if (isset($standardButtonColor)) {
            $standardButtonColor = json_decode($standardButtonColor, true);
            $this->cssBlock("
                .button {
                    color: {$standardButtonColor[0]['value']};
                    background-color: {$standardButtonColor[1]['value']};
                    border-color: {$standardButtonColor[2]['value']};
                }
            ");
            $this->cssBlock("
                .exclusive:hover {
                    background-color: {$standardButtonColor[1]['value']};
                }
            ");        
        }
        
        if (isset($exclusiveButtonColor)) {
            $exclusiveButtonColor = json_decode($exclusiveButtonColor, true);
            $this->cssBlock("
                .exclusive {
                    color: {$exclusiveButtonColor[0]['value']};
                    background-color: {$exclusiveButtonColor[1]['value']};
                    border-color: {$exclusiveButtonColor[2]['value']};
                }
                .exclusive.btn-svg .icon-svg { fill: {$exclusiveButtonColor[0]['value']}; }
            ");
            $this->cssBlock("
                .exclusive:hover { color: {$exclusiveButtonColor[1]['value']}; }
                .exclusive.btn-svg:hover .icon-svg{ fill: {$exclusiveButtonColor[1]['value']}; }
            ");
        }
        
        if (isset($navSecondaryShadow) && $navSecondaryShadow != 'on') {
            $this->cssBlock("
                header #navSecondary {
                    -moz-box-shadow: none;
                    -webkit-box-shadow: none;
                    box-shadow: none;
                }
            ");
        }
        
        if (isset($footer)) {
            $footer = json_decode($footer, true);
            $this->cssBlock("
                .footer-container { 
                    background-color: {$footer[2]['value']};
                    color: {$footer[1]['value']};
                }
                .footer-container #footer h4 { color: {$footer[0]['value']}; }
                .footer-container #footer ul li a { color: {$footer[1]['value']}; }
            ");
        }
        /* & General */
        
        /* Typography */
        if (isset($mainFont)) {
            $this->cssBlockFromRules('$psansFamily', "font-family: {$mainFont};");
        }
        
        if (isset($textFont)) {
            $this->cssBlock("body { font-family: {$textFont}; }");
        }
        
        if (isset($textSize)) {
            $this->cssBlock("body { font-size: {$textSize}; }");
        }
        
        if (isset($menuFont)) {
            $this->cssBlock("
                .sf-menu > li > a,
                .sf-menu > li > ul > li > a {
                    font-family: {$menuFont};
                }
            ");
        }
        
        if (isset($menuLinkSize)) {
            $this->cssBlock(".sf-menu > li > a { font-size: {$menuLinkSize}; }");
        }
        /* & Typography */
        
        /* Background */
        if (isset($bkgColor)) {
            $this->cssBlock("body { background-color: {$bkgColor}; }");
        }
        
        $page_css = "#page {\n";
        if (isset($bkgPattern) && $bkgPattern != 0) {
            $bkgPattern = GEAR_PARENT_NAME.'/img/patterns/'.$bkgPattern.'.png';
            if (is_file(_PS_MODULE_DIR_.$bkgPattern)) {
                $bkgPattern = _MODULE_DIR_.$bkgPattern;
                $page_css .= "\tbackground-image: url({$bkgPattern});\n";
            }
        } else if (is_string($bkgUpload)) {
            $bkgUpload = 'img/gear/'.$bkgUpload;
            if (is_file(_PS_THEME_DIR_.$bkgUpload)) {
                $page_css .= "\tbackground-image: url(../{$bkgUpload});\n";
            }
        }
        if (isset($bkgRepeat)) {
            $bkgRepeat = GearOption::checkedRadioValue($bkgRepeat);
            if (is_string($bkgRepeat)) {
                $page_css .= "\tbackground-repeat: {$bkgRepeat};\n";
            }
        }
        if (isset($bkgPosition)) {
            $bkgPosition = GearOption::checkedRadioValue($bkgPosition);
            if (is_string($bkgPosition)) {
                $page_css .= "\tbackground-position: {$bkgPosition};\n";
            }
        }
        if (isset($bkgFixed) && $bkgFixed == 'on') {
            $page_css .= "\tbackground-attachment: fixed;\n";
        }
        $page_css .= "}";
        $this->cssBlock($page_css);
        /* & Background */
        
        /* Layout */
        if (isset($headerNav)) {
            $headerNav = json_decode($headerNav, true);
            $this->cssBlock("
                #header .nav { 
                    color: {$headerNav[0]['value']};
                    background: {$headerNav[2]['value']};
                }
                
                #header .nav a { color: {$headerNav[0]['value']}; }
                #header .nav a:hover { color: {$headerNav[1]['value']}; }
            ");
        }
        
        if (isset($headerNavTextSize)) {
            $this->cssBlock("#header .nav { font-size: {$headerNavTextSize}; }");
        }
        
        if (isset($navSecondaryBkg)) {
            $this->cssBlock("header #navSecondary { background: {$navSecondaryBkg}; }");
        }
        
        if (isset($hookTopMenuBkg)) {
            $this->cssBlock("#hookTopMenu { background: {$hookTopMenuBkg}; }");
        }
        
        if (isset($hookTopMenu)) {
            $hookTopMenu = json_decode($hookTopMenu, true);
            $this->cssBlock("#hookTopMenu .sf-menu > li > a { color: {$hookTopMenu[0]['value']}; }");
            $this->cssBlock("
                #hookTopMenu .sf-menu > li.sfHover > a,
                #hookTopMenu .sf-menu > li > a:hover {
                    color: {$hookTopMenu[1]['value']};
                    background: {$hookTopMenu[2]['value']};
                }
            ");
        }
        
        if (isset($verticalMenuHead)) {
            $verticalMenuHead = json_decode($verticalMenuHead, true);
            $this->cssBlock("
                #hookTopMenu .cat_title h2 {
                    color: {$verticalMenuHead[0]['value']};
                    background: {$verticalMenuHead[1]['value']};
                }
            ");
        }
        
        if (isset($verticalMenu)) {
            $verticalMenu = json_decode($verticalMenu, true);
            $this->cssBlock("
                #bskblockcategories .tree > li > a { color: {$verticalMenu[0]['value']}; }
                #bskblockcategories .tree > li:hover { background: {$verticalMenu[3]['value']}; }
                #bskblockcategories .tree > li:hover > a { color: {$verticalMenu[1]['value']}; }
                #bskblockcategories.fixed .frame_wrap_content { background: {$verticalMenu[2]['value']}; }
            ");
        }
        
        if (isset($curLangBlock)) {
            $curLangBlock = json_decode($curLangBlock, true);
            $this->cssBlock("
                header #header_scl { color: {$curLangBlock[0]['value']}; }
                    
                #currencies-block-top #setCurrency,
                #languages-block-top .current { border-color: {$curLangBlock[1]['value']}; }
                    
                #header_scl .toogle_content > li > a,
                #languages-block-top ul li.selected,
                #languages-block-top ul li:hover { color: {$curLangBlock[2]['value']}; }
                    
                #currencies-block-top ul,
                #languages-block-top ul { background: {$curLangBlock[3]['value']}; }
                    
                #currencies-block-top ul li,
                #languages-block-top ul li { border-color: {$curLangBlock[4]['value']}; }
            ");
        }
        
        if (isset($customHomeBlock)) {
            $customHomeBlock = json_decode($customHomeBlock, true);
            $this->cssBlock("
                .blue_block { 
                    background-color: {$customHomeBlock[0]['value']};
                    border-color: {$customHomeBlock[1]['value']};
                }
                .blue_block .border-right,
                #cmsinfo_block,
                #cmsinfo_block > div {
                    border-color: {$customHomeBlock[1]['value']};
                }
            ");
        }
        /* & Layout */
        
        /* Product */
        if (isset($prodLabelsColor)) {
            $this->cssBlock("
                #product .box-label,
                #product .new-box,
                #product .sale-box {
                    background-color: {$prodLabelsColor};
                }
            ");
        }
        /* & Product */
        
        /* Category */
        if (isset($plLabelsColor)) {
            $this->cssBlock("
                .product_list .box-label,
                .product_list .new-box,
                .product_list .sale-box {
                    background-color: {$plLabelsColor};
                }
            ");
        }
        /* & Category */
        
        /* Tools */
        if (isset($useCssEditor) && isset($cssEditor) && $useCssEditor == 'on' && is_string($cssEditor) ) {
            $this->cssBlock("/* CUSTOM CSS */\n\n{$cssEditor}");
        }
        /* & Tools */
        
        return $this->css;
    }
    
    /**
     * Create css block
     * @param string $code
     * @param boolean $append add to $this->css
     * @return type
     */
    protected function cssBlock($code, $append = true) {
        $code = trim($code);
        if ($append) {
            $this->css .= $code."\n\n";
        }
        return $code;
    }
    
    /**
     * Create css block of rules
     * @param string|array $rules string | array with value and name | array of rules
     * @param string $code
     * @param boolean $append add to $this->css
     * @return string
     */
    protected function cssBlockFromRules($rules, $code, $append = true) {
        if (is_string($rules)) { // Eg. '$priceColor'
            $rules = $this->getRules($rules);
        } else if (is_array($rules)) { // Eg. array('value'=>'$priceColor', 'name'=>'color')
            if (isset($rules['value'])) {
                $rules = $this->getRules($rules['value'], isset($rules['name']) ? $rules['name'] : null);
            }
        }
        $css = "";
        if (!empty($rules)) {
            foreach ($rules as $i => $rule) {
                if ($i == 0) {
                    $css .= "\n";
                } else {
                    $css .= ",\n";
                }
                $css .= $rule;
            }
            $css .= " { {$code} }\n\n";
        }
        if ($append) {
            $this->css .= $css;
        }
        return $css;
    }
    
    /**
     * Get array of rules that match var and name
     * @param string $var
     * @param string $name
     * @return array
     */
    protected function getRules($var, $name=null) {
        if (is_string($var)) {
            foreach ($this->xml->property as $property) {
                $value = $property->value->__toString();
                if ($var == $value) {
                    $rules = array();
                    if (!is_null($name)) {
                        if (isset($property->name) && $name == $property->name->__toString()) {
                            foreach ($property->file as $pf) {
                                foreach ($pf->rule as $rule) {
                                    $rules[] = $rule->__toString();
                                }
                            }
                            return $rules;
                        }
                    } else if (!isset ($property->name)) {
                        foreach ($property->file as $pf) {
                            foreach ($pf->rule as $rule) {
                                $rules[] = $rule->__toString();
                            }
                        }
                        return $rules;
                    }
                }
            }
        }
        return false;
    }

}