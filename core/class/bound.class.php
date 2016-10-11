<?php
class bound extends objeto{
    public $id;
    protected $_tabla="bounds";
    function bound($id){
        $this->id=$id;
    }
    public function get($nombre,$usuario=false){
        global $core;
        $db=&$core->getDB(0,2);
        switch($nombre){
            case "expire":
                if($usuario){
                    $q="select `expire` from `bounds_stock` where `bound`='".intval($this->id)."' and `user`='".intval($usuario->id)."' limit 1";
                    //echo $q."<hr>";
                    if($r=$db->query($q)){
                        if($i=$r->fetch_assoc()){
                            return $i["expire"];
                        }
                    }
                    
                    }
                else {
                    return parent::get($nombre);
                }
                break;
            default:
                
                return parent::get($nombre);
        }
    }
}
