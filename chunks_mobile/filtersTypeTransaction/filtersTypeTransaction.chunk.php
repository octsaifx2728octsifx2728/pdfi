<?php
class filtersTypeTransaction_chunk extends chunk_base implements chunk{
  protected $_plantillas=array("html/main.html","html/option.html","html/select.html");
  protected $_scripts=array("js/filters.js");
  protected $_styles=array("css_mobile/filtersTypeTransaction.css");
  protected $_selfpath="chunks_mobile/filtersTypeTransaction/";
  protected $_params=array();
  public function filtersTypeTransaction_chunk($params=array()){
      $this->_params=$params;
  }
  public function out($params=array()){
      global $core;
      $manager=$core->getApp("inmueblesManager");
      $prots=$manager->getPrototypes();
      
      $tipos="";
      $transacciones=array();
      $plantilla_option=$this->loadPlantilla(1);
      $plantilla_select=$this->loadPlantilla(2);
      $plantilla=$this->loadPlantilla(0);
      
    if($this->_params["static"])
        {
        $static="1";
        $tipoobjeto= $core->getFilter("tipoobjeto");
        $tipovr= $core->getFilter("tipovr");
        }
    else {
        $core->setFilter("tipoobjeto","residencial");
        $core->setFilter("tipovr","tipovrventa");
        $static=$static;
      $tipoobjeto="residencial";
      
      $tipovr="tipovrventa";
        }
        
      
      $transpars="";
      foreach($prots as $t=>$p){
          $form=$p->getNewForm();
          $transacciones="";
          foreach($form[0]["fields"][0]["options"] as $trans){
              $key=md5($trans["name"].$t);
              $p1=array(
                "VALOR"=>$trans["name"],
                "TITULO"=>$trans["label"],
                "NEW"=>$trans["name"]=="tipovrcomparto"?"new":"",
                "NOMBRE"=>"tipovr",
                "KEY"=>$key,
            "STATIC"=>$this->_params["static"]?"1":"0",
                "CHECKED"=>($tipovr==$trans["name"])?" selected='selected' ":"",
                "SELECTED"=>($tipovr==$trans["name"])?" selected":"",
                  "EXTRAS"=>"fieldParent_".$t
                );
              $transacciones.=$this->parse($plantilla_option, $p1);
          }
          $transpars.=$this->parse($plantilla_select, array("NOMBRE"=>"fieldParent_".$t,"OPTIONS"=>$transacciones));
          $p=array(
              "VALOR"=>$t,
              "TITULO"=>$p->nombreS,
              "NOMBRE"=>"tipoobjeto",
                "NEW"=>"",
                "KEY"=>"",
            "STATIC"=>$this->_params["static"]?"1":"0",
              "CHECKED"=>$tipoobjeto==$t?" selected='selected' ":"",
              "SELECTED"=>$tipoobjeto==$t?" selected":"",
                  "EXTRAS"=>""
          );
          $tipos.=$this->parse($plantilla_option, $p);
      }
    $p=array(
        "PROTOTIPOS"=>$this->parse($plantilla_select, array("NOMBRE"=>"","OPTIONS"=>$tipos)),
        "STATIC"=>$static,
        "TRANSACCIONES"=>$transpars,
        "TIPOOBJETO"=>$tipoobjeto,
        "KEY"=>md5("FILTER".rand())    
    );
    return parent::out($plantilla,$p);
  }
}
