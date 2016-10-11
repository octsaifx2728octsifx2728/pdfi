<?php
class filtersTypeTransaction_chunk extends chunk_base implements chunk{
  protected $_plantillas=array("html/main.html","html/radio.html","html/main_select.html","html/option.html");
  protected $_scripts=array("js/filters.js");
  protected $_selfpath="chunks/filtersTypeTransaction/";
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
      if($this->_params["select"]){
        $plantilla_radio=$this->loadPlantilla(3);
        $plantilla=$this->loadPlantilla(2);
      }
      else {
        $plantilla_radio=$this->loadPlantilla(1);
        $plantilla=$this->loadPlantilla(0);
      }
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
      
      
      foreach($prots as $t=>$p){
          $form=$p->getNewForm();
          foreach($form[0]["fields"][0]["options"] as $trans){
              $key=md5($trans["name"].$t);
              $p1=array(
                "VALOR"=>$trans["name"],
                "TITULO"=>$trans["label"],
                "NEW"=>$trans["name"]=="tipovrcomparto"?"new":"",
                "NOMBRE"=>"tipovr",
                "KEY"=>$key,
            "STATIC"=>$this->_params["static"]?"1":"0",
                "CHECKED"=>($tipovr==$trans["name"])?" checked='checked' ":"",
                "SELECTED"=>($tipovr==$trans["name"])?" selected":"",
                  "EXTRAS"=>"fieldParent_".$t
                );
              $transacciones[]=$this->parse($plantilla_radio, $p1);
          }
          $p=array(
              "VALOR"=>$t,
              "TITULO"=>$p->nombreS,
              "NOMBRE"=>"tipoobjeto",
                "NEW"=>"",
                "KEY"=>"",
            "STATIC"=>$this->_params["static"]?"1":"0",
              "CHECKED"=>$tipoobjeto==$t?" checked='checked' ":"",
              "SELECTED"=>$tipoobjeto==$t?" selected":"",
                  "EXTRAS"=>""
          );
          $tipos.=$this->parse($plantilla_radio, $p);
      }
    $p=array(
        "PROTOTIPOS"=>$tipos,
        "STATIC"=>$static,
        "TRANSACCIONES"=>"<form>".implode("</form><form>",$transacciones)."</form>",
        "TIPOOBJETO"=>$tipoobjeto,
        "KEY"=>md5("FILTER".rand())    
    );
    return parent::out($plantilla,$p);
  }
}
