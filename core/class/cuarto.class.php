<?php
global $core;
$core->loadClass("inmueble");

class cuarto_inmueble extends inmueble{
    public $nombreS='$$comparto$$';
    public $nombreP='$$comparto$$';
    public $description='$$cuarto_description$$';
    public $typename="cuarto";
    public $typeid=3;
    protected $_tabla="cuartos";
    protected $_idField="id_expediente";
    protected $_fieldTypes=array("amueblado"=>"int");
    protected $_transaccionTypes=array(
        array(
            "name"=>"comparto",
            "label"=>'$$comparto$$',
            "type"=>"button",
            "options"=>array(),
            "params"=>""
            )
        );
    protected $_fieldsBasic=array(
                array(
                    "name"=>"m2",
                    "label"=>'$$m2$$',
                    "type"=>"number",
                    "params"=>""
                    ),
                array(
                    "name"=>"preciom2",
                    "label"=>'$$preciom2$$',
                    "type"=>"number",
                    "params"=>""
                    ),
        array(
            "name"=>"amueblado",
            "label"=>'$$amueblado$$',
            "type"=>"checkbox",
            "params"=>""
            ),
        array(
            "name"=>"habitaciones",
            "label"=>'$$habitaciones$$',
            "type"=>"number",
            "params"=>""
            ),
        array(
            "name"=>"banos",
            "label"=>'$$banos$$',
            "type"=>"number",
            "params"=>""
            ),
        array(
            "name"=>"estacionamientos",
            "label"=>'$$estacionamientos$$',
            "type"=>"number",
            "params"=>""
            ),
        array(
            "name"=>"anio",
            "label"=>'$$anio$$',
            "type"=>"select",
            "options"=>array("1900"=>1900,
                            "1901"=>1901,
                            "1902"=>1902,
                            "1903"=>1903,
                            "1904"=>1904,
                            "1905"=>1905,
                            "1906"=>1906,
                            "1907"=>1907,
                            "1908"=>1908,
                            "1909"=>1909,
                            "1910"=>1910,
                            "1911"=>1911,
                            "1912"=>1912,
                            "1913"=>1913,
                            "1914"=>1914,
                            "1915"=>1915,
                            "1916"=>1916,
                            "1917"=>1917,
                            "1918"=>1918,
                            "1919"=>1919),
            "params"=>""
            )
        );
    protected $_itemsGeneral=array(
        "cocina"=>array("nombre"=>'$$cocina$$'),
        "biblioteca"=>array("nombre"=>'$$biblioteca$$'),
        "cuarto_servicio"=>array("nombre"=>'$$cuarto_servicio$$'),
        "estudio"=>array("nombre"=>'$$estudio$$'),
        "tv"=>array("nombre"=>'$$tv$$'),
        "chimenea"=>array("nombre"=>'$$chimenea$$'),
        "cava"=>array("nombre"=>'$$cava$$'),
        "vestidor"=>array("nombre"=>'$$vestidor$$'),
        "bodega"=>array("nombre"=>'$$bodega$$'),
        "circuito"=>array("nombre"=>'$$circuito$$'),
        "lavado"=>array("nombre"=>'$$lavado$$'),
        "elevador"=>array("nombre"=>'$$elevador$$'),
        "elevadors"=>array("nombre"=>'$$elevadors$$'),
        "tintoreria"=>array("nombre"=>'$$tintoreria$$'),
        "aire"=>array("nombre"=>'$$aire$$'),
        "calefaccion"=>array("nombre"=>'$$calefaccion$$'),
        "portero"=>array("nombre"=>'$$portero$$'),
        "red"=>array("nombre"=>'$$red$$'),
        "sistema_seguridad"=>array("nombre"=>'$$sistema_seguridad$$'),
        "jardin"=>array("nombre"=>'$$jardin$$'),
        "terraza"=>array("nombre"=>'$$terraza$$'),
        "vista"=>array("nombre"=>'$$vista$$'),
        "parque"=>array("nombre"=>'$$parque$$'),
        "playa"=>array("nombre"=>'$$playa$$'),
        "muelle"=>array("nombre"=>'$$muelle$$'),
        "alberca"=>array("nombre"=>'$$alberca$$'),
        "jacuzzi"=>array("nombre"=>'$$jacuzzi$$'),
        "salon"=>array("nombre"=>'$$salon$$'),
        "servicios"=>array("nombre"=>'$$servicios$$'),
        "gimnasio"=>array("nombre"=>'$$gimnasio$$'),
        "spa"=>array("nombre"=>'$$spa$$'),
        "tenis"=>array("nombre"=>'$$tenis$$'),
        "golf"=>array("nombre"=>'$$golf$$'),
        "club"=>array("nombre"=>'$$club$$'),
        "sjuegos"=>array("nombre"=>'$$sjuegos$$'),
        "fiestas"=>array("nombre"=>'$$fiestas$$'),
        "juegos"=>array("nombre"=>'$$juegos$$'),
        "mascotas"=>array("nombre"=>'$$mascotas$$'),
        "condominios"=>array("nombre"=>'$$condominios$$'),
        "ecologico"=>array("nombre"=>'$$ecologico$$'),
        "discapacitados"=>array("nombre"=>'$$discapacitados$$'),
        "helipuerto"=>array("nombre"=>'$$helipuerto$$'),
        "fumar"=>array("nombre"=>'$$fumar$$')
        );
    
    public function casa_inmueble($id_expediente){
                $this->id=$id_expediente;
    }
}