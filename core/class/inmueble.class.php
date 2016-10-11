<?php
global $core;
$core->loadClass("objeto");
class inmueble extends objeto{
  var $id;
  var $cliente;
  public $typeid=0;
  public $linktext='$$ver_este_inmueble$$';
  public $sublinktext=array();
  public $ontransactiontext=array(
      "tipovrventa"=>'$$en_venta$$',
      "tipovrrenta"=>'$$en_renta$$',
      "tipovrcomparto"=>'$$en_compartido$$',
  );
  protected $_itemsGeneralParsed=false;
  protected $_tabla="inmuebles";
  protected $_fieldTypes=array();
  protected $_precioRanges=array(
      "tipovrventa"=>array(0,50000000),
      "tipovrrenta"=>array(0,100000),
      "tipovrcomparto"=>array(0,100000)
  );
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
                        "options"=>array(
                            array(
                                "name"=>"tipovrrentacorto",
                                "label"=>'$$corto_plazo$$',
                                "type"=>'checkbox',
                                "params"=>""
                                ),
                            array(
                                "name"=>"tipovrrentalargo",
                                "label"=>'$$largo_plazo$$',
                                "type"=>'checkbox',
                                "params"=>""
                                )
                            ),
                        "params"=>""
                        ),
                    array(
                        "name"=>"tipovrcomparto",
                        "label"=>'$$comparto$$',
                        "options"=>array(),
                        "params"=>"new"
                        )
                   
           
        );
  protected $_fieldsBasic=array();
  protected $_itemsGeneral=array();
  protected $_items=array();
  protected $_meinteresa=array(
      "default"=>'$$meinteresaesteinmueble$$',
      "tipovrventa"=>array(
            "default"=>'$$meinteresacompraresteinmueble$$'
          ),
      "tipovrrenta"=>array(
            "default"=>'$$meinteresarentaresteinmueble$$'
          ),
      "tipovrcomparto"=>array(
            "default"=>'$$meinteresacompartiresteinmueble$$'
          )
  );
  protected $_fieldsBase=array('id_cliente'=>'id_cliente',
      'id_expediente_old'=>'id_expediente_old',
      'id_expediente'=>'id_expediente',
      'tipoobjeto'=>'tipoobjeto',
      'subtipo'=>'subtipo',
      'fecha_alta'=>'fecha_alta',
      'titulo'=>'titulo',
      'descripcion'=>'descripcion',
      'precio'=>'precio',
      'precio_moneda'=>'precio_moneda',
      'fecvennormal'=>'fecvennormal',
      'fecvenpremium'=>'fecvenpremium',
      'borrado'=>'borrado',
      'coordenaday'=>'coordenaday',
      'coordenadax'=>'coordenadax'
      );
  protected $_fields=array();
  function inmueble($id,$cliente){
    $this->id=$id;
    $this->cliente=$cliente;
  }
  public function getTabla(){
      return $this->_tabla;
  }
  public function getID(){
      return $this->id."_".$this->get("id_cliente")."_".$this->get("tipoobjeto");
  }
  function cloneToType($tipo){
      global $core;
      $app=&$core->getApp("inmueblesManager");
      $db=&$core->getDB(0,2);
      $prototypes=$app->getPrototypes();
      foreach($prototypes as $p){
          if($p->typeid==$tipo){
              $proto=$p;
              break;
          }
      }
      if(!$proto){
          return false;
      }
      $t1=$this->_tabla;
      $t2=$proto->_tabla;
      $q="select * from `".$t1."` where `id_expediente`='".intval($this->id)."' limit 1";
      if($r=$db->query($q)){
          $q2="insert into `".$t2."`(`id_cliente`)values(".$this->get("id_cliente").")";
          if($r2=$db->query($q2)){
              $proto->id=$r2->insert_id;
              $campos=$r->fetch_fields();
              foreach($campos as $c){
                    $proto->set($c->name,$this->get($c->name));
                  }
              $anuncio=$this->getAnuncio();
              //print_r($r2);
              $anuncio->set("idinmueble",$proto->id);
              $anuncio->set("tipoinmueble",$proto->get("tipoobjeto"));
              $this->borrar();
              return $proto;
            }
      }
  }
  public function build(user $user){
      global $core;
      
      
      $db=&$core->getDB(0,2);
      $q="insert into `".$db->real_escape_string($this->_tabla)."`
          (`".$db->real_escape_string($this->_idField)."`,`fecha_alta`,`id_cliente`)
          values (NULL,now(),'".($user->id?intval($user->id):"0")."')";
      //echo $q;
      $r=$db->query($q);
      //print_r($db);
      $this->id=intval($db->insert_id);
      
      $q="insert into `anuncios`(`tipoinmueble`,`idinmueble`,`user`,`fecha_alta`,`incompleto`,`activo`)
            values('".intval($this->get("tipoobjeto"))."',
                '".intval($this->id)."','".($user->id?intval($user->id):"0")."',now(),1,0)";
      
      
      $db->query($q);
      
      if(!$user->id){
          $anuncio=$this->getAnuncio();
          $anuncio->set("huerfano","1");
          $anuncio->set("huerfano_since",date("Y-m-d H:i:s"));
      }
      
  }
  public function getmeinteresa(){
      if(array_key_exists($this->get("tipovr"), $this->_meinteresa)){
          if(array_key_exists($this->get("subtipo"), $this->_meinteresa[$this->get("tipovr")])){
            return $this->_meinteresa[$this->get("tipovr")][$this->get("subtipo")];
          }
          else{
            return $this->_meinteresa[$this->get("tipovr")]["default"];
              }
      }
      else{
        return $this->_meinteresa["default"];
      }
  }
  public function get($nombre){
      global $core;
      switch($nombre){
          case "preciom2":
              return $this->getPreciom2($core->getEnviromentVar("currency"));
              break;
          case "meinteresa":
              return $this->getmeinteresa();
              break;
          case "m2s":
              $metricas=$core->getApp("metricaconverter");
              return $metricas->convert(parent::get("m2s"),$this->get("metrica"),$core->getEnviromentVar("metrica"));
              break;
          case "m2s-raw":
              return parent::get("m2s",$valor);
              break;
          case "m2-raw":
              return parent::get("m2",$valor);
              break;
          case "m2":
              $metricas=$core->getApp("metricaconverter");
              return $metricas->convert(parent::get("m2"),$this->get("metrica"),$core->getEnviromentVar("metrica"));
              break;
          case "primeraimagen":
              $img= $this->getImage();
              $img=$img[0]->path;
              if(!$img){
                $img="galeria/imagenes/sinimagen.jpg";
                }
              
              return $img;
              break;
          case "tipovrnombre":
              return $this->ontransactiontext[$this->get("tipovr")];
              break;
          case "tipoobjetonombre":
              return $this->nombreS;
              break;
          case "producto":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("producto");
              }
              break;
          case "direccion":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("direccion");
              }
              break;
          case "vendido":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("vendido");
              }
              break;
          case "subtiponombre":
              foreach($this->_subtipos as $st){
              if($st["name"]==$this->get("subtipo")){
                  return $st["label"];
              }
              }
              break;
          case "bound":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("bound");
              }
              break;
          case "activo":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("activo");
              }
              break;
          case "posicion":
              $valor=explode("_",$valor,2);
              return ($this->get("coordenaday")."_".$this->get("coordenadax"));
              break;
          case "tipovrrentacorto":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("tipotransaccion_1");
              }
              break;
          case "fecvenpremium":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("fecvenpremium");
              }
              break;
          case "fecvenoferta":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("fecvenoferta");
              }
              break;
          case "fecvennormal":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("fecvennormal");
              }
              break;
          case "tipovrrentalargo":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("tipotransaccion_2");
              }
              break;
          case "tipovr":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->get("tipotransaccion");
              }
              break;
          default:
              return parent::get($nombre,$valor);
      }
  }
  public function set($nombre,$valor){
      switch($nombre){
          case "direccion":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->set("direccion",$valor);
              }
              break;
          case "activo":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->set("activo",$valor);
              }
              break;
          case "posicion":
              $valor=explode("_",$valor,2);
              if ($this->set("coordenaday",$valor[0])&&$this->set("coordenadax",$valor[1])){
                  $this->reportToGeoURL();
                  return true;
              }
              return false;
              break;
          case "tipovrrentacorto":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->set("tipotransaccion_1",$valor);
              }
              break;
          case "tipovrrentalargo":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->set("tipotransaccion_2",$valor);
              }
              break;
          case "fecvenpremium":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  $anuncio->set("fecvenpremium",$valor);
              }
              return parent::set($nombre,$valor);
              break;
          case "fecvennormal":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  $anuncio->set("fecvennormal",$valor);
              }
              return parent::set($nombre,$valor);
              break;
          case "id_cliente":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  $anuncio->set("user",$valor);
              }
              return parent::set($nombre,$valor);
              break;
          case "tipovr":
              $anuncio=$this->getAnuncio();
              if($anuncio){
                  return $anuncio->set("tipotransaccion",$valor);
              }
              break;
          default:
              return parent::set($nombre,$valor);
      }
  }
  function reportToGeoURL(){
      if($this->isActive()){
        $link=$this->getURL();
        $ch = curl_init("http://geourl.org/ping/?p=".urlencode($link));
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
        curl_exec($ch);
        }
  }
  function purge(){
      global $core,$user;
      $q="select * from `anuncios` where `user`='".intval($user->id)."' and `incompleto`=1 limit 1";
      $db=&$core->getDB(0,2);
      if($r=$db->query($q)){
          while($i=$r->fetch_assoc()){
              switch($i["tipoinmueble"]){
                  case "1":
                      $tabla="casas";
                      break;
                  case "2":
                      $tabla="departamentos";
                      break;
                  case "3":
                      $tabla="cuartos";
                      break;
                  
              }
            $q="delete from `".$tabla."` where `id`='".$i["idinmueble"]."'";
            $db->query($q);
            }
      }
      $q="delete from `anuncios` where `user`='".intval($user->id)."' and `incompleto`=1 limit 1";
      $db->query($q);     
  }
  function saveForce(){
      global $core,$user;
      $db=&$core->getDB(0,2);
      $q="insert into `".$db->real_escape_string($this->_tabla)."`
          (`".$db->real_escape_string($this->_idField)."`,`fecha_alta`,`id_cliente`)
          values (NULL,now(),'".intval($user->id)."')";
      $r=$db->query($q);
      $this->id=intval($db->insert_id);
      $q="insert into `anuncios`(`tipoinmueble`,`idinmueble`,`user`,`fecha_alta`,`incompleto`,`activo`)
            values('".intval($this->get("tipoobjeto"))."',
                '".intval($this->id)."','".$this->get("id_cliente")."',now(),1,0)";
      $db->query($q);
      return $this;
  }
  function getNewForm(){
      global $config,$core;
      
      
   
      
      include_once 'config/currencyconverter.config.php';
      $monedas=array();
      
      foreach($config->currencyconverter as $k=>$v){
          $monedas[$k]=$v["descripcion"]."( ".$k.$v["simbolo"]." )";
      }
      $tmoneda=$this->get("precio_moneda");
      if(!$tmoneda){
          $tmoneda=$core->getEnviromentVar("currency");
          $this->set("precio_moneda",$tmoneda);
      }
      $tipovr=$this->get("tipovr");
      if(!$tipovr){
          $this->set("tipovr","tipovrventa");
          $tipovr="tipovrventa";
      }
      $tipo=$this->get("subtipo");
      if(!$tipo)
      {
          $tipo=$this->_subtipos[0]["name"];
          $this->set("subtipo",$tipo);
      }
      foreach($this->_fieldsBasic as $k=>$fb){
          $fb["value"]=$this->get($fb["name"]);
          $this->_fieldsBasic[$k]=$fb;
      }
      $fields=array(
          array(
              "name"=>"transaccion",
              "title"=>'$$transaccion$$',
              "description"=>'$$transaccion_descripcion$$',
              "fields"=>array(
                    array(
                  "name"=>"tipovr",
                  "label"=>'$$tipovr$$',
                  "type"=>"selectable",
                  "value"=>$tipovr,
                  "hint"=>'$$tipovr_hint$$',
                  "params"=>"fieldRequired_1",
                    "options"=>$this->_transaccionTypes
                    )
                  )
                  ),
          array(
              "name"=>"tipo",
              "title"=>'$$tipo$$',
              "description"=>'$$tipo_descripcion$$',
              "value"=>$tipo,
              "fields"=>array(
                array(
                    "name"=>"subtipo",
                    "label"=>'$$tipo$$',
                    "type"=>"selectable",
                    "value"=>$this->get("subtipo"),
                    "options"=>$this->_subtipos,
                    "params"=>""
                    )
              )
              ),
          array(
              "name"=>"general",
              "title"=>'$$informacion_general$$',
              "description"=>'$$informacion_general_descripcion$$',
              "fields"=>array(
                  array("name"=>"titulo",
                        "label"=>'$$titulo$$',
                        "hint"=>'$$titulo_hint$$',
                        "value"=>$this->get("titulo"),
                        "type"=>'text',
                        "params"=>'fieldRequired_1 fieldMinSize_5'
                      ),
                  
                array(
                    "name"=>"precio",
                    "label"=>'$$precio$$',
                    "type"=>"currency",
                    "symbol"=>$config->currencyconverter[$tmoneda]["simbolo"],
                    "key"=>$tmoneda,
                    "value"=>$this->get("precio"),
                    "hint"=>'$$precio_hint$$',
                    "params"=>"fieldRequired_1 fieldMin_0",
                    "ranges"=>$this->_precioRanges
                    ),
                array(
                    "name"=>"precio_moneda",
                    "label"=>'$$precio_moneda$$',
                    "type"=>"selectmoneda",
                    "value"=>$tmoneda,
                    "options"=>$monedas,
                    "params"=>""
                    ),
                  array("name"=>"descripcion",
                        "label"=>'$$descripcion$$',
                        "type"=>'textarea',
                        "value"=>$this->get("descripcion"),
                        "params"=>"fieldMinSize_0 fieldMaxSize_700"
                      ),
                  array("name"=>"direccion",
                        "label"=>'$$direccion$$',
                        "hint"=>'$$direccion_hint$$',
                        "value"=>$this->get("direccion"),
                        "type"=>'textarea',
                        "params"=>'fieldRequired_0 fieldMinSize_0'
                      )
                  
              )
          ),
          array(
              "name"=>"adicional",
              "title"=>'$$informacion_adicional$$',
              "description"=>'$$informacion_adicional_descripcion$$',
              "fields"=>$this->_fieldsBasic
          ),
          array(
              "name"=>"caracteristicas",
              "title"=>'$$caracteristicas$$',
              "description"=>'$$caracteristicas_descripcion$$',
              "fields"=>$this->getItemsGeneral(true)
          ),
          array(
              "name"=>"posicion",
              "title"=>'$$posicion$$',
              "description"=>'$$posicion_descripcion$$',
              "fields"=>array(
                  array(
                    "name"=>"posicion",
                    "label"=>'$$posicion$$',
                    "value"=>$this->get("posicion"),
                    "hint"=>'$$posicion_hint$$',
                    "type"=>"posicion",
                    "params"=>"fieldRequired_1"
                      )
                )
          )
      );
      
      return $fields;
  }
  public function getItemsGeneral($admin=false){
      if($admin){
          $items=array();
          foreach($this->_itemsGeneral as $k=>$v){
              $items[]=array(
                    "name"=>$k,
                    "label"=>$v["nombre"],
                    "type"=>"checkbox",
                    "value"=>$this->get($k),
                    "params"=>"");
          }
          return $items;
      }
      if(!$this->_itemsGeneralParsed){
          foreach($this->_itemsGeneral as $k=>$v){
              $this->_itemsGeneral2[$k]=(object)$v;
              $this->_itemsGeneral2[$k]->valor=$this->get($k);
          }
      }
      return $this->_itemsGeneral2;
  }
  public function getFields(){
      $basic=array();
      foreach($this->_fieldsBasic as $fb){
          $basic[$fb["name"]]=$fb["name"];
      }
      $general=array();
      foreach(array_keys($this->_itemsGeneral) as $fb){
          $basic[$fb]=$fb;
      }
      $fields=array_merge($this->_fieldsBase,$this->_fields,$basic,$general);
      return $fields;
  }
  public function getPretendAnuncio(){
      global $core;
      /*if($anuncio=$this->getAnuncio()){
          return $anuncio;
      }*/
      $db=&$core->getDB(0,2);
      $search=&$core->getApp("search");
      $core->loadClass("user");
      $user=new user($this->get("id_cliente"));
     // $user=new user(4);
      $inms=$search->searchByUser($user);
      
      $q="select SUM(`bs`.`cantidad`) as 'total', `bs`.`bound` as 'bound' from `bounds_stock` as `bs` where `user`='".intval($user->id)."' group by `bs`.`bound`";
     
      
      if($r=$db->query($q)){
          while($i=$r->fetch_assoc()){
              switch($i["bound"]){
                  case "1":
                      $freemium=$i["total"];
                      break;
                  case "2":
                      $standar=$i["total"];
                      break;
                  case "3":
                      $corp_1=$i["total"];
                      break;
                  case "4":
                      $corp_2=$i["total"];
                      break;
              }
          }
      }
      foreach($inms as $i){
          $anuncio=$i->getAnuncio();
          $bound=$anuncio->getBound();
          switch($bound->id){
              case "1":
                  $freemium--;
                  break;
              case "2":
                  $standar--;
                  break;
              case "3":
                  $corp_1--;
                  break;
              case "4":
                  $corp_2--;
                  break;
          }
      }
      $core->loadClass("producto");
      if($this->get("tipovr")=="tipovrcomparto"){
        $product=new producto(13);
        return array("bound"=>1,"fotos"=>20,"videos"=>5,"fotos360"=>10,"gratis"=>1,"product"=>array("id"=>13,
                                                                            "descripcion"=>$product->get("descripcion"),
                                                                            "nombre"=>$product->get("nombre"),
                                                                            "precio"=>$product->get("precio"),
                                                                            "dias"=>$product->get("dias"))); 
      }
      if($user->getAvaliables(1)){
        $product=new producto(1);
        return array("bound"=>1,"fotos"=>20,"videos"=>5,"fotos360"=>10,"gratis"=>1,"product"=>array("id"=>1,
                                                                            "descripcion"=>$product->get("descripcion"),
                                                                            "nombre"=>$product->get("nombre"),
                                                                            "precio"=>$product->get("precio"),
                                                                            "dias"=>$product->get("dias"))); 
      }
      if($user->getAvaliables(3)){
        $product=new producto(6);
        //print_r($product);
          return array("bound"=>3,"fotos"=>20,"videos"=>5,"fotos360"=>10,"gratis"=>1,"product"=>array("id"=>6,
                                                                            "descripcion"=>$product->get("descripcion"),
                                                                            "nombre"=>'$$publicar$$',
                                                                            "precio"=>0,
                                                                            "dias"=>$product->get("dias")));
      }
      if($user->getAvaliables(4)){
        $product=new producto(9);
          return array("bound"=>4,"fotos"=>20,"videos"=>5,"fotos360"=>10,"gratis"=>1,"product"=>array("id"=>9,
                                                                            "descripcion"=>$product->get("descripcion"),
                                                                            "nombre"=>'$$publicar$$',
                                                                            "precio"=>0,
                                                                            "dias"=>$product->get("dias")));
      }
      /*if($standar){
      $product=new producto(2);
      return  array("bound"=>2,"fotos"=>20,"videos"=>5,"fotos360"=>10,"product"=>array("id"=>2,
                                                                            "descripcion"=>$product->get("descripcion"),
                                                                            "nombre"=>$product->get("nombre"),
                                                                            "precio"=>$product->get("precio"),
                                                                            "dias"=>$product->get("dias")));
      }*/
      $product=new producto(2);
      return  array("bound"=>2,"fotos"=>20,"videos"=>5,"fotos360"=>10,"product"=>array("id"=>2,
                                                                            "descripcion"=>$product->get("descripcion"),
                                                                            "nombre"=>$product->get("nombre"),
                                                                            "precio"=>$product->get("precio"),
                                                                            "dias"=>$product->get("dias")));
  }
  public function getAnuncio(){
      global $core;
      $db=&$core->getDB(0,2);
      $q="select `id` from anuncios where 
          `tipoinmueble`='".(intval($this->typeid)?intval($this->typeid):"0")."' 
              and `idinmueble`='".intval($this->id)."' and `user`='".intval($this->get("id_cliente"))."' limit 1";
      //echo $q;
      
      
      if($r=$db->query($q)){
          //echo $q;
          if($i=$r->fetch_assoc()){
            $core->loadClass("anuncio");
            //print_r($i);
            return new anuncio($i["id"]);
            }
      }
  }
  function getPrecio($tipo){
      global $core;
      if($this->get("precio_moneda")==$tipo){
          return $this->get("precio");
      }
      $currency=&$core->getApp("currencyconverter");
      $divisaDestino=$currency->getDivisa($tipo);
      $divisaOrigen=$currency->getDivisa($this->get("precio_moneda"));
      $precio=(1/$divisaOrigen)*$divisaDestino*intval($this->get("precio"));
      return $precio;
  }
  function getPreciom2($moneda=null,$metrica=null){
      global $core;
      
      $moneda=$moneda?$moneda:$core->getEnviromentVar("currency");
      $metrica=$metrica?$metrica:$core->getEnviromentVar("metrica");
      
      $precio=$this->getPrecio($moneda);
      
      $m2=$this->get("m2-raw");
      $m2s=$this->get("m2s-raw");
      
      if($metrica!=$this->get("metrica")){
          $mc=$core->getApp("metricaconverter");
          $m2=$mc->convert($m2,$this->get("metrica"),$metrica);
          $m2s=$mc->convert($m2s,$this->get("metrica"),$metrica);
      }
      
      if(floatval($m2)){
          return $precio/$m2;
      }
      else {
          return $precio/$m2s;
      }
  }
  
  
  
  
  
  function getImage($id=0,$max=1,$_360=0){
    global $core;
    

    $db=&$core->getDB(0,2);
    $q="select * from `imagenes_inmuebles` where `id_expediente`='".intval($this->id)."'
	  and `tipo`='".intval($this->get("tipoobjeto"))."'
              and `panoramica`='".intval($_360)."'
              order by `num_imagen`,`id` 
              limit ".intval($id).",".intval($max);
    
     $myfile = fopen("/var/www/vhosts/e-spacios.com/httpdocs1/test.log", "w");               
                fwrite($myfile, $q);
                fclose($myfile);
    
    $i=array();
    if($r=$db->query($q)){
        while($im= $r->fetch_assoc()){
            if(file_exists($im["path"])&&$this->get("id_cliente")==$im["id_cliente"]){
                
                    array_push($i  , (object)$im);
                }
           else {
               /*
               $q="delete from `imagenes_inmuebles` where `id`=? limit 1";
               if($sm2=$db->prepare($q)){
                   $sm2->bind_param("i",$im["id"]);
                   $sm2->execute();
               }
                * 
                */
           }
        }
        
        
        
        
        return $i;
    }
    
        
            
        
    
        
        
    
  }
    function getVideos(){
        global $core;
        $db=&$core->getDB(0,2);
	$q="select `videourl` 
                    from  `inmuebles_video` 
                    where `id_cliente`='".intval($this->get("id_cliente"))."' 
                        and `id_expediente`='".intval($this->id)."'
                        and `tipo`='".intval($this->get("tipoobjeto"))."'
                    limit 5";
	$videos=array();
        if($r=$db->query($q)){
            while($i= $r->fetch_assoc()){
                if($i["videourl"]){
                    $videos[]=$i["videourl"];
                }
            }
         }
	return $videos;
        }
    function getImages($all=false){
	    global $core;
	    $db=&$core->getDB(0,2);
            $maxFotos=$this->getAllowedFotos();
            $maxFotos360=$this->getAllowedFotos360();
            
            if($all){
		$maxFotos=1000;
		$maxFotos360=1000;
		}
             
            $imagenes=$this->getImage(0,$maxFotos);
           
            
            
            
            
                        
            
            
            
            $imagenes2=$this->getImage(0,$maxFotos,1);
            
           
            
            
		
	    $imagenes=array_merge($imagenes,$imagenes2);
            
            
            
	    return $imagenes;
	}
  public function getURL($lang=false){
  global $config,$core;
  $lang=$lang?$lang:$core->getEnviromentVar("languaje");
  $tiposvr=array(
        "tipovrventa"=>'$$venta$$',
        "tipovrrenta"=>'$$renta$$',
        "tipovrcomparto"=>'$$comparto$$'
        );
    return $config->paths["urlbase"].($lang?"/".$lang:"").
            "/app/inmueble/"
            .$this->id."-".$this->get("id_cliente")."-".$this->get("tipoobjeto")."/".
            str_replace(".","",$this->nombreS)."-".$tiposvr[$this->get("tipovr")]."-".preg_replace('/%[A-Z0-9][A-Z0-9]/','_',urlencode(str_replace(array(" ","/"),"-",$this->get("titulo"))));
  }
  public function delFoto($id){
  	global $config,$core;
        $db=&$core->getDB(0,2);
        $q="select `path` 
            from `imagenes_inmuebles` 
            where `id`=? limit 1";
        if($sm=$db->prepare($q)){
            $sm->bind_param("i",$id);
            if($sm->execute()){
                $sm->bind_result($path);
                while($sm->fetch()){
                    if(file_exists($path)){
                        unlink($path);
                    }
                }
            }
        }
        $q2="delete from `imagenes_inmuebles` where `id`='".$id."' limit 1";
        $db->query($q2);
        return $id;
  }
  public function addFoto($path,$es360=false){
  	
        global $config,$core;
        
        $db=&$core->getDB(0,2);
        
       
        //file_put_contents("/var/www/vhosts/e-spacios.com/httpdocs1/test.log", json_encode($inmueble->get("fecvenoferta")) . " > " . time() , FILE_APPEND | LOCK_EX);
        
        
        $path=str_replace("/var/www/vhosts/e-spacios.com/httpdocs/","",$path);
        
        
        
        
        
        
	if(strpos($path,"http")===false&&file_exists($config->paths["base"].$path)){
        
        
          
        //if(strpos($path,"http")===false&&file_exists($path)){
            
        
            
                
            
           $imgpath=$config->paths["base"].$path;
            //$imgpath=$path;
            $path=tempnam($config->paths["fotos"], "foto_");
            
            
            if(copy ( $imgpath , $path)){
                
                
                
                
                //$path=str_replace($config->paths["base"],"",$path);
                
                $path=str_replace("/var/www/vhosts/e-spacios.com/httpdocs/","",$path);
                
                
                
                $q="insert into `imagenes_inmuebles`(`id_cliente`,`id_expediente`,`path`,`panoramica`,`tipo`)
                    values(?,?,?,?,?)";
                
                if($sm=$db->prepare($q)){
                    $es360=$es360?1:0;
                    
                    $id_client = $this->get("id_cliente")==0?$_SESSION["id_clienteSfx"]:$this->get("id_cliente"); 
                    
                    
                     
                    
                    $sm->bind_param("iisii",$id_client,$this->id,$path,$es360,$this->get("tipoobjeto"));
                    if($sm->execute()){
                        return array("path"=>$path,"id"=>$sm->insert_id);  
                    }
                };
            }
        }
  }
  public function addVideo($key,$posicion=-1){
  	global $config,$core;
    $db=&$core->getDB(0,2);
        $q="select `id`,`posicion` from `inmuebles_video`
                where `id_expediente`='".intval($this->id)."'
                    and `tipo`='".intval($this->get("tipoobjeto"))."'
                    and `posicion`='".intval($posicion)."'
                limit 1";
        if($r=$db->query($q)){
            $existe=$r->num_rows;
        }
	if($existe){
            $q="update `inmuebles_video` set `videourl`=? 
                where 
                    `id_cliente`=?
                    and `id_expediente`=?
                    and `tipo`=?
                    and `posicion`=?
                limit 1";
        }
        else {
            $q="insert into `inmuebles_video`(`videourl`,`id_cliente`,`id_expediente`,`tipo`,`posicion`) 
                values(?,?,?,?,?)";
        }
        if($sm2=$db->prepare($q)){
            
                $sm2->bind_param("siiii",$key,$this->get("id_cliente"),$this->id,$this->get("tipoobjeto"),$posicion);
                $sm2->execute();
                return $posicion;
            }
  }
  public function submitComanda($comanda){
  	$q="select `campo_inmueble` as 'campo',
  				`dias` as 'dias'
  		from `formasdepago`
  		where `id`='".mysql_real_escape_string($comanda["pago"])."'
  			and `tipo`='".mysql_real_escape_string($comanda["tipo"])."'
  		limit 1";
  	$r=mysql_query($q);
	$i=mysql_fetch_array($r);
	$q="update `inmuebles`
		set `".mysql_real_escape_string($i["campo"])."`= now() + INTERVAL ".mysql_real_escape_string($i["dias"])." DAY
      where `id_cliente`='".mysql_real_escape_string($this->cliente)."'
      and `id_expediente`='".mysql_real_escape_string($this->id)."'";
  	$r=mysql_query($q);
  }
  public function borrar(){
  	global $config,$core;
    $db=&$core->getDB(0,2);
    $fotos=$this->getImages();
    foreach($fotos as $f){
    	$this->delFoto($f->id);
    }
   $q="delete from `inmuebles_video`  where `id_expediente`='".intval($this->id)."'
      and `tipo`='".intval($this->get("tipoobjeto"))."'";
  
    $db->query($q);
    $anuncio=$this->getAnuncio();
    if($anuncio){
        $anuncio->borrar();
        }
    $q="delete from `actividades` where `objeto`='".intval($this->id)."' and `tipoobjeto`='".intval($this->get("tipoobjeto"))."' and `usuario`='".intval($this->get("id_cliente"))."'";
    $db->query($q);
    $q="delete from `bounds_user_inmueble` 
        where 
        `user`='".intval($this->get("id_cliente"))."'
        and `inmueble`='".intval($this->id)."'
        and `tipo`='".intval($this->get("tipoobjeto"))."'";
    $db->query($q);
    return parent::borrar();
  }
  public function getAllowedVideos(){
  	if(!$this->_allowedVideos){
	  	$q="select `b`.`videos` as 'videos'
	  		from `bounds` as `b` 
	  		left join `bounds_user_inmueble` as `bui` on `b`.`id`=`bui`.`bound`
	  		where `bui`.`user`='".mysql_real_escape_string($this->cliente)."'
	  			and `bui`.`inmueble`='".mysql_real_escape_string($this->id)."'
	  		limit 1";
	  	$r=mysql_query($q);
		$i=mysql_fetch_array($r,MYSQL_ASSOC);
		$this->_allowedVideos=$i["videos"];
	  	}
  	return intval($this->_allowedVideos);
  }
  public function getAllowedFotos(){
      global $core;
      $db=&$core->getDB(0,2);
  	if(!$this->_allowedFotos){
	  	$q="select `b`.`fotos` as 'fotos'
	  		from `bounds` as `b` 
	  		left join `bounds_user_inmueble` as `bui` on `b`.`id`=`bui`.`bound`
                        left join `anuncios` as `a` on `a`.`id`=`bui`.`anuncio`
	  		where `bui`.`user`='".intval($this->get("id_cliente"))."'
	  			and `a`.`idinmueble`='".intval($this->id)."'
	  			and `a`.`tipoinmueble`='".intval($this->get("tipoobjeto"))."'
	  		limit 1";
                
                 
                 
                if($r=$db->query($q)){
                    $i=$r->fetch_assoc();
                    $this->_allowedFotos=$i["fotos"];
                    }
	  	}
  	return intval($this->_allowedFotos);
  }
  public function getAllowedFotos360(){
      global $core;
      $db=&$core->getDB(0,2);
  	if(!$this->_allowedFotos360){
	  	$q="select `b`.`fotos2` as 'fotos2'
	  		from `bounds` as `b` 
	  		left join `bounds_user_inmueble` as `bui` on `b`.`id`=`bui`.`bound`
                        left join `anuncios` as `a` on `a`.`id`=`bui`.`anuncio`
	  		where `bui`.`user`='".intval($this->get("id_cliente"))."'
	  			and `a`.`idinmueble`='".intval($this->id)."'
	  			and `a`.`tipoinmueble`='".intval($this->get("tipoobjeto"))."'
	  		limit 1";
                if($r=$db->query($q)){
                    $i=$r->fetch_assoc();
                    $this->_allowedFotos360=$i["fotos2"];
                    }
	  	}
  	return intval($this->_allowedFotos360);
  }
  public function isActive(){
      
       //file_put_contents('/var/www/html/test.log', strtotime("2016-04-07") . " " .time(), FILE_APPEND | LOCK_EX);
      
        $active=  ((strtotime($this->get("fecvennormal"))+94608000)>time())&&$this->get("activo");
        
        
        if($active == 1){
              return $active;
            
        }else if($active == 0){
            $active= ((strtotime($this->get("fecvenpremium"))+94608000)>time())&&$this->get("activo");
        }else{
            $active=  ((strtotime($this->get("fecvenoferta"))+94608000)>time())&&$this->get("activo");
        }
        
        
	
  	return $active;
  }
  public function getVencimiento(){
      global $core;
    $core->loadClass("bound");
    $core->loadClass("user");
    
    $bound=new bound($this->get("bound"));
    $user=new user($this->get("id_cliente"));
    
    //echo $this->get("fecvennormal")."::";
   // echo $bound->get("expire",$user)."||".$this->get("bound");
    
    $venc=strtotime($this->get("fecvennormal"));
    $expire= strtotime($bound->get("expire",$user));
        
    if($bound->get("stockable")&&$expire>0){
    

        if($venc<$expire){
            return $venc;
        }
            else {
            return $expire;
            }
    }
    else {
        return $venc;
    }
  }
  public function notificar($notificacion){
  	global $config, $core;
  	$plantilla="notificacion_caducar".$notificacion.".html";
  	$mailer=$core->getApp("mailer");
	
	$titulos=array("",'[[e-spacios.com]]Anuncio por Caducar','[[e-spacios.com]]Anuncio Caducado');
	
	$usuario=new user($this->cliente);
			$mailer->send(array(
				"destinatario"=>$usuario->get("usuario"),
				"asunto"=>$titulos[$notificacion],
				"plantilla"=>"html/".$plantilla,
				"variables"=>array(
					"NOMBRE"=>$usuario->get("nombre_pant"),
					"TITLE"=>$this->get("titulo"),
					"URL"=>$this->getURL(),
					"URLU"=>"http://www.e-spacios.com/".$usuario->getLink()
					)
					
					)
				);
  	$q="delete from `notificaciones` where `id_inmueble`='".mysql_real_escape_string($this->id)."' and `id_cliente`='".mysql_real_escape_string($this->cliente)."'";
	mysql_query($q);
	$q="insert into `notificaciones` (`id_inmueble`,`id_cliente`,`tipo`,`fecha`) 
			values(
				'".mysql_real_escape_string($this->id)."',
				'".mysql_real_escape_string($this->cliente)."',
				'".mysql_real_escape_string($notificacion)."',
				now()
				)";
	mysql_query($q);
        
  }
}

