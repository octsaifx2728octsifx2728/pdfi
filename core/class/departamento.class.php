<?php
global $core;
$core->loadClass("inmueble");

class departamento_inmueble extends inmueble{
    public $nombreS='$$departamento$$';
    public $nombreP='$$departamentos$$';
    public $typename="departamento";
    public $typeid=2;
    public $description='$$departamento_description$$';
    protected $_tabla="departamentos";
    protected $_idField="id_expediente";
    protected $_fieldTypes=array("amueblado"=>"int");
    protected $_itemsGeneral=array(
        "cocina"=>array("nombre"=>'$$cocina$$')
        );
    
    public function casa_inmueble($id_expediente){
                $this->id=$id_expediente;
    }
}