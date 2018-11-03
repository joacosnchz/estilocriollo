<?php

class Link extends LinkCore {
    
    /**
     * Get image type formated name for theme if exists
     * Eg. medium or medium_default returns medium_{theme_name} or {theme_name}_medium if exists, otherwise returns medium_default
     * @param string $name
     * @return string
     */
    public static function getImageTypeThemeName($name) {
        $name = str_replace('_default', '', $name);
        return ImageType::getFormatedName($name);
    }

    public function getImageLink($name, $ids, $type = null, $idOver = NULL) {
        if (isset($type)) {
            $type = self::getImageTypeThemeName($type);
        }
        
        $not_default = false;
        // legacy mode or default image
        $theme = ((Shop::isFeatureActive() && file_exists(_PS_PROD_IMG_DIR_ . $ids . ($type ? '-' . $type : '') . '-' . (int) Context::getContext()->shop->id_theme . '.jpg')) ? '-' . Context::getContext()->shop->id_theme : '');
        if ((Configuration::get('PS_LEGACY_IMAGES') && (file_exists(_PS_PROD_IMG_DIR_ . $ids . ($type ? '-' . $type : '') . $theme . '.jpg'))) || ($not_default = strpos($ids, 'default') !== false)) {
            if ($this->allow == 1 && !$not_default)
                $uri_path = __PS_BASE_URI__ . $ids . ($type ? '-' . $type : '') . $theme . '/' . $name . '.jpg';
            else
                $uri_path = _THEME_PROD_DIR_ . $ids . ($type ? '-' . $type : '') . $theme . '.jpg';
        } else {
            // if ids if of the form id_product-id_image, we want to extract the id_image part
            $split_ids = explode('-', $ids);
            $id_image = (isset($split_ids[1]) ? $split_ids[1] : $split_ids[0]);
            $theme = ((Shop::isFeatureActive() && file_exists(_PS_PROD_IMG_DIR_ . Image::getImgFolderStatic($id_image) . $id_image . ($type ? '-' . $type : '') . '-' . (int) Context::getContext()->shop->id_theme . '.jpg')) ? '-' . Context::getContext()->shop->id_theme : '');

            if (isset($idOver) && Configuration::get('BSK_SECOND_IMAGE')) {
                $result = Db::getInstance()->ExecuteS('SELECT id_image FROM ' . _DB_PREFIX_ . 'image WHERE id_product = ' . $idOver . ' AND position = 2');
                $id_image_over = $result[0]['id_image'];
            } else {
                $id_image_over = 0;
            }

            if ($id_image_over != 0) {
                $id_image = $id_image_over;
            }

            if ($this->allow == 1)
                $uri_path = __PS_BASE_URI__ . $id_image . ($type ? '-' . $type : '') . $theme . '/' . $name . '.jpg';
            else
                $uri_path = _THEME_PROD_DIR_ . Image::getImgFolderStatic($id_image) . $id_image . ($type ? '-' . $type : '') . $theme . '.jpg';
        }

        return $this->protocol_content . Tools::getMediaServer($uri_path) . $uri_path;
    }
    
    public function getCatImageLink($name, $id_category, $type = null) {
        if (isset($type)) {
            $type = self::getImageTypeThemeName($type);
        }
        return parent::getCatImageLink($name, $id_category, $type);
    }

}
