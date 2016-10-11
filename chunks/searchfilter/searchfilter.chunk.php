<?php
class searchfilter_chunk extends chunk_base implements chunk{
    protected $_params=array();
    protected $_plantillas=array('html/residencial.html',
        'html/residencial.html',
        'html/checkbox.html',
        "html/residencial.html",
        "html/range.html");
    protected $_scripts=array("js/searchfilter.js","js/payFilters.js");
    protected $_styles=array("css/ui-lightness/jquery-ui-1.8.23.custom.css","css/payFilters.css");
    protected $_selfpath="chunks/searchfilter/";
    private $_rangos=array("precio",
                "habitaciones",
                "banos",
                "m2",
                "preciom2",
                "m2s",
                "anio",
                "estacionamientos");
    
  function out($params=array()){
        global $core;
        $search=&$core->getApp("search");
        $manager=&$core->getApp("inmueblesManager");
        $prototypes=$manager->getPrototypes();
        $currency=$core->getApp("currencyconverter");
        
        
        
        
        
        $tipoobjeto=$core->getFilter("tipoobjeto")?$core->getFilter("tipoobjeto"):"residencial";
        //echo $tipoobjeto;
        $prototipo=$prototypes[$tipoobjeto];
        $plantilla=$this->loadPlantilla(3);
        $checkbox=$this->loadPlantilla(2);
        $range=$this->loadPlantilla(4);
        if(!$prototipo){
           $prototipo=$prototypes["residencial"]; 
        }
        $fields=$prototipo->getNewForm();
        
        $subtipos=$fields[1]["fields"][0];
        $subtiposp="";
        $core->setFilter("subtipo","");
        $subtp=explode("_",$core->getFilter("subtipo"));
        foreach($subtipos["options"] as $st){
            $checked="";
            foreach($subtp as $stv){
                if($stv==$st["name"]){
                    $checked=" checked='checked'";
                    break;
                }
            }
            $p=array(
                "TITULO"=>$st["label"],
                "VALUE"=>$st["name"],
                "NAME"=>"subtipo",
                "ID"=>"_subtipo_".$st["name"],
                "CHECKED"=>$checked
                );
            $subtiposp.=$this->parse($checkbox, $p);
        }
        
        $generalp="";
        $general=$fields[2]["fields"];
        foreach($general as $g){
            switch($g["type"]){
                case "currency":
                    $rangos=$g["ranges"][$core->getFilter("tipovr")];
                    if(!$rangos){
                        $rangos=$g["ranges"]["tipovrventa"];
                        $core->setFilter("tipovr","tipovrventa");
                    }
                    $val=$core->setFilter($g["name"]."1",$rangos[0]."-".$rangos[1]);
                    $val=$core->getFilter($g["name"]."1");
                    $val=explode("-",$val);
                    $p=array(
                        "MIN"=>$currency->directConvert($rangos[0],"MXN",$core->getEnviromentVar("currency")),
                        "MAX"=>$currency->directConvert($rangos[1],"MXN",$core->getEnviromentVar("currency")),
                        "START"=>$currency->directConvert($val[0],"MXN",$core->getEnviromentVar("currency")),
                        "END"=>$currency->directConvert($val[1],"MXN",$core->getEnviromentVar("currency")),
                        "PSTART"=>  number_format($currency->directConvert($val[0],"MXN",$core->getEnviromentVar("currency")),0,".",","),
                        "PEND"=>  number_format($currency->directConvert($val[1],"MXN",$core->getEnviromentVar("currency")),0,".",","),
                        "SUB"=>"",
                        "PRE"=>"$",
                        "NAME"=>$g["name"],
                        "TITULO"=>$g["label"].'<span class="precio_divisa converDivisa">
                            <span class=" moneda_'.$core->getEnviromentVar("currency").'"><div class="icon"></div></span>
              </span>'
                    );
                    
                    $generalp.=$this->parse($range, $p);
                    break;                
            }
        }
        
        $adicionalp="";
        $adicionalp2="";
        $adicional=$fields[3]["fields"];
         foreach($adicional as $a){
            if($a["name"]=="metrica"){
                continue;
            }
            switch($a["type"]){
                case "select":
                case "number":
                    $rangos=$a["ranges"][$core->getFilter("tipovr")];
                    if(!$rangos){
                        $rangos=$a["ranges"]["tipovrventa"];
                        $core->setFilter("tipovr","tipovrventa");
                    }
                    
                    $val=$core->setFilter($g["name"]."1",$rangos[0]."-".$rangos[1]);
                    $val=$core->getFilter($a["name"]."1");
                    $val=explode("-",$val);
                    switch($a["name"]){
                        case "m2":
                            $a["label"]='$$metrica_'.$core->getEnviromentVar("metrica").'2$$';
                            if($core->getEnviromentVar("metrica")=="pies"){
                                $rangos[1]=$rangos[1]*10;
                            }
                            break;
                        case "m2s":
                            $a["label"]='$$metrica_'.$core->getEnviromentVar("metrica").'$$';
                            if($core->getEnviromentVar("metrica")=="pies"){
                                $rangos[1]=$rangos[1]*10;
                            }
                            break;
                    }
                    $p=array(
                        "MIN"=>$rangos[0],
                        "MAX"=>$rangos[1],
                        "START"=>$rangos[0],
                        "END"=>$rangos[1],
                        "PSTART"=>  ($a["name"]=="anio"?$rangos[0]:number_format($rangos[0],0,".",",")),
                        "PEND"=>  ($a["name"]=="anio"?$rangos[1]:number_format($rangos[1],0,".",",")),
                        "SUB"=>"",
                        "PRE"=>"",
                        "NAME"=>$a["name"]."1",
                        "TITULO"=>$a["label"]
                    );
                    $adicionalp.=$this->parse($range, $p);
                    break;
                case "checkbox":
                    $val=$core->setFilter($a["name"],0);
                    $val=$core->getFilter($a["name"]);
                    
                    $p=array(
                        "TITULO"=>$a["label"],
                        "VALUE"=>"1",
                        "NAME"=>$a["name"],
                        "ID"=>"_".$a["name"]."_".$a["name"],
                        "CHECKED"=>$val?" checked='checked' ":""
                    );
                    $adicionalp2.=$this->parse($checkbox, $p);
                    break;
            }
         }
        $caractp="";
        $caract=$fields[4]["fields"];
        foreach($caract as $c){
                    $val=$core->getFilter($c["name"]);
                    $p=array(
                        "TITULO"=>$c["label"],
                        "VALUE"=>"1",
                        "NAME"=>$c["name"],
                        "ID"=>"_".$c["name"]."_".$c["name"],
                        "CHECKED"=>$val?" checked='checked' ":""
                    );
                    $caractp.=$this->parse($checkbox, $p);
        }
         
        $p=array(
            "SUBTIPOS"=>$subtiposp,
            "GENERAL"=>$generalp,
            "ADICIONAL"=>$adicionalp,
            "ADICIONAL2"=>$adicionalp2,
            "CARACT"=>$caractp
            
        );
        return parent::out($plantilla,$p);
        
        
        
        
        
        
        $plantilla=$this->loadPlantilla(0);
        
        
        
        $casa=$core->getFilter("casa");
        $p=array(
		"VR1"=>"checked='checked'",
		"VR2"=>"",
		"VR2"=>"");
        $vr=$core->getFilter("tipovr");
        foreach($this->_rangos as $k){
            $top=$search->getTop($k);
            $pot=$search->getTop($k,1,true);
            if(!$pot||!$top){
                continue;
            }
            $d=explode("-",$core->getFilter($k."1"));
            $p[strtoupper($k)."0"]=intval($d[0]);
            $p[strtoupper($k)."1"]=intval($d[1])?intval($d[1]):60000000;
            
            switch($k){
                case "m2s":
                    $p[strtoupper($k)."MIN"]=$vr==2?0:0;
                    $p[strtoupper($k)."MAX"]=$vr==2?10000:10000;
                    if($d[0]>=10000)$p[strtoupper($k)."0"]=0;
                    break;
                case "m2":
                    $p[strtoupper($k)."MIN"]=0;
                    $p[strtoupper($k)."MAX"]=1000;
                    if($d[0]>=1000)$p[strtoupper($k)."0"]=0;
                    break;
                case "preciom2":
                    $tope=$vr==2?1000:100000;
                    $p[strtoupper($k)."MIN"]=0;
                    $p[strtoupper($k)."MAX"]=$currency->directConvert($tope,"MXN",$core->getEnviromentVar("currency"));
                    break;
                case "precio":
                    $tope=$vr==2?100000:50000000;
                    $p[strtoupper($k)."MIN"]=0;
                    $p[strtoupper($k)."MAX"]=$currency->directConvert($tope,"MXN",$core->getEnviromentVar("currency"));
                    
                    break;
                default:
                    $p[strtoupper($k)."MIN"]=intval($pot->get($k));
                    $p[strtoupper($k)."MAX"]=intval($top->get($k));
                    break;
            }
        }
     foreach($core->getFilters(true) as $k=>$v){
         //print_r($k.":".$v.";");
         switch($k){
            case "casa":
		 $p["casaChecked"]=$casa==1?" checked='checked' ":"";
		 $p["departamentoChecked"]=$casa==2?" checked='checked' ":"";
                break;
            default:
                $p[$k."Checked"]=intval($v)?" checked='checked' ":"";
            }
     }
        
        
    return $parsed2.(parent::out($plantilla,$p));
  }
}
