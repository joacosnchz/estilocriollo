<?php

class ProductCommentsBSK extends ProductComments {

    public function hookDisplayProductListReviews($params) {
        require_once(parent::getLocalPath() . 'ProductComment.php');

        $average = ProductComment::getAverageGrade((int) $params['product']['id_product']);
        $this->smarty->assign(array(
            'product' => $params['product'],
            'averageTotal' => $average['grade'],
            'nbComments' => (int) (ProductComment::getCommentNumber((int) $params['product']['id_product']))
        ));
        return $this->display(__FILE__, 'productcomments_reviews.tpl', $this->getCacheId((int) $params['product']['id_product']));
    }

}
