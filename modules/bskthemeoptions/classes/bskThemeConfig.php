<?php

/**
 * @todo-bsk Transform this class into intermediary module between theme and themeoptions
 */
class bskThemeConfig {
    
    /**
     * Custom Positions ( Hooks )
     * @return array
     */
    public static function customHooks() {
        $hooks = array();
        // $hooks[] = array('name' => 'hookName', 'title' => 'hook title', 'description' => 'hook decription');
        
        // Display Nav Links Position
        $hooks[] = array('name' => 'displayNavLinks', 'title' => 'Main nav bar - links', 'description' => 'An area to display links');

        // Display Slider Position
        $hooks[] = array('name' => 'displaySlider', 'title' => 'Slider header position', 'description' => 'Display the slider in header');
        
        // Display Slider Position 2
        $hooks[] = array('name' => 'displaySlider2', 'title' => 'Slider second position', 'description' => 'Display slider inside the center column');
        
        // Display Top Menu
        $hooks[] = array('name' => 'displayTopMenu', 'title' => 'Display Top Menu', 'description' => 'Show horizontal/vertical menu');

        // Display Ads Position
        $hooks[] = array('name' => 'displayAds', 'title' => 'Advertising position', 'description' => 'display ads in header');

        // Display Newsletter and/or Reassurance block Position
        $hooks[] = array('name' => 'displayBlueBlock', 'title' => 'Home Blue Block', 'description' => 'Newsletter and/or Reassurance block');
        
        // Display Products next/prev links
        $hooks[] = array('name' => 'productsNextPrev', 'title' => 'Products next/prev links', 'description' => 'Add next/prev links to product page.');
        
        // Display Newsletter and/or Reassurance block Position
        $hooks[] = array('name' => 'footerBar', 'title' => 'Footer Bar', 'description' => 'Footer Bar with Twitter');
        
        return $hooks;
    }
    
    /**
     * Configuration to match the theme out of the box
     * @return boolean
     */
    public static function installFixtures($path) {
        // Disable Live configurator
        Configuration::updateValue('PS_TC_ACTIVE', 0);
        
        // Payment logos block set cms id for Secure Payment
        Configuration::updateValue('PS_PAYMENT_LOGO_CMS_ID', 5);
        
        // CMS block enable some links and disable Powerd by PrestaShop
        Configuration::updateValue('FOOTER_BEST-SALES', 1);
	Configuration::updateValue('FOOTER_CONTACT', 1);
	Configuration::updateValue('FOOTER_SITEMAP', 1);
        Configuration::updateValue('FOOTER_POWEREDBY', 0);
        
        // Custom CMS information block change Reassurance content to match Home Blue Block
        require_once _PS_MODULE_DIR_.'blockcmsinfo/infoClass.php';
        $reassurance = new infoClass(1);
        $languages = $languages = Language::getLanguages();
        foreach ($languages as $lang) {
            // @todo-bsk move images in /img/cms/
            $reassurance->text[$lang['id_lang']] = '
                <ul class="reassurance">
                    <li><img src="'.$path.'/img/blockcmsinfo/banknote.png" /><strong>MONEY BACK GUARANTEE</strong></li>
                    <li><strong><img src="'.$path.'/img/blockcmsinfo/shipping.png" />FREE SHIPPING</strong></li>
                    <li><strong><img src="'.$path.'/img/blockcmsinfo/payment.png" />100% SECURE PAYMENT</strong></li>
                </ul>';
            
                // CMS block set footer information as About Us
                $footer_about_us = '
                    <h4>About Us</h4>
                    <div class="toggle-footer">
                        <p>Aliquam fringilla magna vel purus porttitor, nec pharetra lacus posuere. Duis gravida pellentesque sapien ac vestibulum.</p>
                        <br>
                        <p>Suspendisse neque lorem, egestas id posuere a, ullamcorper vel dui. Quisque porttitor risus arcu, vitae ullamcorper justo laoreet sed.</p>
                    </div>';
                Configuration::updateValue('FOOTER_CMS_TEXT_'.$lang['id_lang'], '');
                Configuration::updateValue('FOOTER_CMS_TEXT_'.$lang['id_lang'], $footer_about_us, true);
        }
        $reassurance->update();
        
        return true;
    }
    
}
