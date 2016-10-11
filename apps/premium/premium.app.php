<?php
class premium_app {
    public function getItems(){
        global $core;
        $search=&$core->getApp("search");
        $vr=$core->getFilter("tipovr");
        $items=$search->internalSearch(
                    array(
                        array("`i`.`fecvenpremium`",">",date("'Y-m-d H:i:s'")),
                       // array("`a`.`tipotransaccion`","=","'".$vr."'")
                        )
                    );
        return $items;
    }
}