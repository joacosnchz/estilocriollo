<?php

class BlocktopmenuBSK extends Blocktopmenu {

    public function hookDisplayTopMenu($params) {
        return $this->hookDisplayTop($params);
    }

}
