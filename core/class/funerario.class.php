<?php
global $core;
$core->loadClass("inmueble");

class funerario_inmueble extends inmueble{
    public $nombreS='$$funerario$$';
    public $nombreP='$$funerarios$$';
    public $typename="funerario";
    public $sublinktext=array(
        "nichos"=>'$$ver_este_nicho$$',
        "cripta"=>'$$ver_este_cripta$$',
        "tumbas"=>'$$ver_este_tumba$$'
        );
  protected $_meinteresa=array(
      "default"=>'$$meinteresaesteinmueble$$',
      "tipovrventa"=>array(
            "default"=>'$$meinteresacompraresteinmueble$$',
            "casa"=>'$$meinteresacomprarcasa$$',
            "departamento"=>'$$meinteresacomprardepartamento$$'
          ),
      "tipovrrenta"=>array(
            "default"=>'$$meinteresarentaresteinmueble$$',
            "casa"=>'$$meinteresarentarcasa$$',
            "departamento"=>'$$meinteresarentardepartamento$$'
          ),
      "tipovrcomparto"=>array(
            "default"=>'$$meinteresacompartiresteinmueble$$',
            "casa"=>'$$meinteresacompartircasa$$',
            "departamento"=>'$$meinteresacompartirdepartamento$$'
          )
  );
    protected $_tabla="funerarios";
    protected $_idField="id_expediente";
    protected $_fieldTypes=array("amueblado"=>"int");
  protected $_precioRanges=array(
      "tipovrventa"=>array(0,100000),
      "tipovrrenta"=>array(0,100000)
  );
    protected $_subtipos=array(
                    array(
                        "name"=>"nichos",
                        "label"=>'$$nichos$$',
                        "options"=>array(),
                        "params"=>""
                        ),
                    array(
                        "name"=>"criptas",
                        "label"=>'$$criptas$$',
                        "options"=>array(),
                        "params"=>""
                        ),
                    array(
                        "name"=>"tumbas",
                        "label"=>'$$tumba$$',
                        "options"=>array(),
                        "params"=>""
                        ));
    public $typeid=6;
    public $description='$$funerario_description$$';
    
  protected $_transaccionTypes=array(
                    array(
                        "name"=>"tipovrventa",
                        "label"=>'$$venta$$',
                        "options"=>array(),
                        "params"=>""
                        ),
                    array(
                        "name"=>"tipovrrenta",
                        "label"=>'$$renta$$',
                        "params"=>""
                        )
                   
           
        );
    protected $_fieldsBasic=array(
        
        array(
            "name"=>"anio",
            "label"=>'$$anio$$',
            "type"=>"select",
            "selected"=>"2013",
                    "ranges"=>array(
                            "tipovrventa"=>array(1900,2013),
                            "tipovrrenta"=>array(1900,2013),
                            "tipovrcomparto"=>array(1900,2013)
                        ),
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
                            "1919"=>1919,
                            "1920"=>1920,
                            "1921"=>1921,
                            "1922"=>1922,
                            "1923"=>1923,
                            "1924"=>1924,
                            "1925"=>1925,
                            "1926"=>1926,
                            "1927"=>1927,
                            "1928"=>1928,
                            "1929"=>1929,
                            "1930"=>1930,
                            "1931"=>1931,
                            "1932"=>1932,
                            "1933"=>1933,
                            "1934"=>1934,
                            "1935"=>1935,
                            "1936"=>1936,
                            "1937"=>1937,
                            "1938"=>1938,
                            "1939"=>1939,
                            "1940"=>1940,
                            "1941"=>1941,
                            "1942"=>1942,
                            "1943"=>1943,
                            "1944"=>1944,
                            "1945"=>1945,
                            "1946"=>1946,
                            "1947"=>1947,
                            "1948"=>1948,
                            "1949"=>1949,
                            "1950"=>1950,
                            "1951"=>1951,
                            "1952"=>1952,
                            "1953"=>1953,
                            "1954"=>1954,
                            "1955"=>1955,
                            "1956"=>1956,
                            "1957"=>1957,
                            "1958"=>1958,
                            "1959"=>1959,
                            "1960"=>1960,
                            "1961"=>1961,
                            "1962"=>1962,
                            "1963"=>1963,
                            "1964"=>1964,
                            "1965"=>1965,
                            "1966"=>1966,
                            "1967"=>1967,
                            "1968"=>1968,
                            "1969"=>1969,
                            "1970"=>1970,
                            "1971"=>1971,
                            "1972"=>1972,
                            "1973"=>1973,
                            "1974"=>1974,
                            "1975"=>1975,
                            "1976"=>1976,
                            "1977"=>1977,
                            "1978"=>1978,
                            "1979"=>1979,
                            "1980"=>1980,
                            "1981"=>1981,
                            "1982"=>1982,
                            "1983"=>1983,
                            "1984"=>1984,
                            "1985"=>1985,
                            "1986"=>1986,
                            "1987"=>1987,
                            "1988"=>1988,
                            "1989"=>1989,
                            "1990"=>1990,
                            "1991"=>1991,
                            "1992"=>1992,
                            "1993"=>1993,
                            "1994"=>1994,
                            "1995"=>1995,
                            "1996"=>1996,
                            "1997"=>1997,
                            "1998"=>1998,
                            "1999"=>1999,
                            "2000"=>2000,
                            "2011"=>2011,
                            "2012"=>2012,
                            "2013"=>2013),
            "params"=>""
            )
    );
    protected $_itemsGeneral=array(
        "circuito"=>array("nombre"=>'$$circuito$$'),
        "sistema_seguridad"=>array("nombre"=>'$$sistema_seguridad$$'),
        "jardin"=>array("nombre"=>'$$jardin$$'),
        "vista"=>array("nombre"=>'$$vista$$'),
        "parque"=>array("nombre"=>'$$parque$$'),
        "playa"=>array("nombre"=>'$$playa$$'),
        "servicios"=>array("nombre"=>'$$serviciosfunerarios$$'),
        "discapacitados"=>array("nombre"=>'$$discapacitados$$'),
        "ecologico"=>array("nombre"=>'$$ecologico$$'),
        "capilla"=>array("nombre"=>'$$capilla$$'),
        "iglesia"=>array("nombre"=>'$$iglesia$$'),
        "basilica"=>array("nombre"=>'$$basilica$$'),
        "limpieza"=>array("nombre"=>'$$limpieza$$'),
        "subterraneo"=>array("nombre"=>'$$subterraneo$$'),
        "alairelibre"=>array("nombre"=>'$$alairelibre$$'),
        "velatorios"=>array("nombre"=>'$$velatorios$$')
        );
    public function funerario_inmueble($id_expediente){
        
                $this->id=$id_expediente;
    }
}