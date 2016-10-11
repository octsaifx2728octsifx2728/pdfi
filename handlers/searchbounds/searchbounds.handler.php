<?php
class searchbounds_handler implements handler{
    public function run($task, $params = array()) {
        global $core,$result;
    $app=$core->getApp("search");
    $resultados=array();
    
    if($_GET["filters"]){
        $fltrs=$core->getFilters();
        $filters=explode(":",$_GET["filters"]);
        foreach($filters as $fil){
            $fil=explode("=",$fil,2);
            $f=$fil[1];
            $k=$fil[0];
            if($f==="true"){
                $f=1;
                }
             
            $core->setFilter($k,$f);
            
            }
        }
        //print_r($_SESSION);
 if($_GET["tipoobjeto"]){
          $core->setFilter("tipoobjeto",$_GET["tipoobjeto"]);
      }
      if($_GET["tipovr"]){
          $core->setFilter("tipovr",$_GET["tipovr"]);
      }

    if($app){
      $resultados=$app->searchbounds($task,null,null,true);
    }
    if(!count($resultados)){
      $result["error"]="2";
      $result["errorDescription"]='$$sin_resultados$$';
      $result["type"]="bounds";
      $result["query"]=$task;
      }
    else {
      $chunk=$core->getChunk("jsonResults");
      $chunk2=$core->getChunk("parsedResults");
      $result["error"]="0";
      $result["errorDescription"]="OK";
      $result["resultCount"]=count($resultados);
      $result["results"]=$chunk->out(array("api"=>true));
      $result["parsedresults"]=$chunk2->out();
      $result["type"]="bounds";
      $result["query"]=$task;

    }
    }
}
