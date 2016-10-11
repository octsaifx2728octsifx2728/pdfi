<?php
class actividades_app {
	function report(user $usuario, $actividad, $link="",$nivel=0,$tipo=0,$objeto=0,$tipoobjeto=0,$inmueble=null){
                global $core;
		$q="insert into `actividades`(`usuario`,`actividad`,`nivel`,`fecha`,`link`,`tipo`,`objeto`,`tipoobjeto`)
			values(
				'".mysql_real_escape_string($usuario->id)."',
				'".mysql_real_escape_string($actividad)."',
				'".mysql_real_escape_string(strval($nivel))."',
				now(),
				'".mysql_real_escape_string(strval($link))."',
				'".mysql_real_escape_string(strval($tipo))."',
				'".mysql_real_escape_string(strval($objeto))."',
				'".mysql_real_escape_string(strval($tipoobjeto))."'
				)";
		$r=mysql_query($q);
                
                
                
                if($inmueble){
                    /*

                    $facebook=$core->getApp("facebook");
                    $facebook->publisherPostInmueble($inmueble,"en","#espacios New Property");
                    $facebook->publisherPostInmueble($inmueble,"es","#espacios Nueva Propiedad");
                    $ln=$core->getApp("linkedin");
                    $response = $ln->publisherPostInmueble($inmueble);
                    //
                    $twitter = $core->getApp("twitter");
                    $twitter->publisherPostInmueble($inmueble);
                     * 
                     */
                }
		return mysql_error();
	}
	function get($index=0,$order="desc",$max=10){
		
	    global $config,$core;
		
            $manager=&$core->getApp("inmueblesManager");
	    $db=&$core->getDB(0,2);
		$q="select `a`.`id` as 'id',
				`a`.`fecha` as 'fecha',
				`a`.`actividad` as 'actividad',
				`a`.`link` as 'link',
				`a`.`usuario` as 'usuario',
				`a`.`nivel` as 'nivel',
				`a`.`tipo` as 'tipo',
				`a`.`objeto` as 'objeto',
				`a`.`tipoobjeto` as 'tipoobjeto',
				`u`.`nombre_pant` as 'nombre_pant'
			from `actividades`as`a`
				left join `usuarios` as `u` on  `a`.`usuario`=`u`.`id_cliente`
			
			order by `fecha` ".strval($order)." 
			limit ".(intval($index)?intval($index):"0").",".(intval($max)?intval($max):"0");
                
                        
                
		$r=mysql_query($q);
		$actividades=array();
		$lastid=false;
    $lexicon= $core->getLexicon();
	    while($i=mysql_fetch_array($r,MYSQL_ASSOC)){
	    	$lastid=$lastid?$lastid:$i["id"];
		    if($lexicon){
		      $i["actividad"]=html_entity_decode($lexicon->traduce($i["actividad"]),0,'UTF-8');
			$pageKey=$lexicon->traduce('$$fb_page_ID$$');
			$twitterAccount=$lexicon->traduce('$$twitterAccount$$');
		    }
			$usuario=new user($i["usuario"]);
                $in=$manager->getInmueble($i["objeto"],$i["tipoobjeto"]);
                $foto=$in?$in->getImage():"";
                $foto=$in?$foto[0]->path:"";
                
	    	$p=array(
	    		"id"=>$i["id"],
	    		"fecha"=>strval(strtotime($i["fecha"])),
	    		"actividad"=>$i["actividad"],
	    		"descripcion"=>$in?$in->get("descripcion"):"",
                        "imagen"=>$foto,
	    		"link"=>$i["link"],
	    		"usuario"=>$i["usuario"],
	    		"nivel"=>$i["nivel"],
	    		"tipo"=>$i["tipo"],
	    		"objeto"=>$i["objeto"],
	    		"tipoobjeto"=>$i["tipoobjeto"],
	    		"nombre_pant"=>$i["nombre_pant"],
	    		"avatar"=>$usuario->getAvatar(),
	    		"userlink"=>$usuario->getLink()
				);
			$actividades[strval(strtotime($i["fecha"]))]=(object)$p;
		}
                /*
                $cache=$core->getApp("cache");
                if(!$facebookFeed=$cache->loadData("facebook_page_".$pageKey)){
                    $facebook=$core->getApp("facebook");
                    $facebookFeed=$facebook->get("/".$pageKey."/feed",$config->defaults["facebook"]->app_token);
                    $cache->storeData("facebook_page_".$pageKey,$facebookFeed,time()+300);
                }
                 * */
                /*
                $facebookFeed=  json_decode($facebookFeed);
                foreach($facebookFeed->data as $i){
                    if(strpos($i->message,"#espacios")===false){
                        $p=array(
                            "id"=>$i->id,
                            "fecha"=>strval(strtotime($i->created_time)),
                            "actividad"=>$i->message,
                            "link"=>$i->link,
                            "usuario"=>"",
                            "nivel"=>"0",
                            "tipo"=>"2",
                            "objeto"=>"",
                            "tipoobjeto"=>"",
                            "nombre_pant"=>"",
                            "avatar"=>"",
                            "userlink"=>"",
                            "fb_data"=>$i
                                    );
                        $actividades[strval(strtotime($i->created_time))]=(object)$p;
                    }
                }
                
                if(!$twitterFeed=$cache->loadData("twitter_user_timeline_".$twitterAccount)){
                    $twitter=$core->getApp("twitter");
                    $twitterFeed=$twitter->get("1/statuses/user_timeline",array("screen_name"=>$twitterAccount));
                    $cache->storeData("twitter_user_timeline_".$twitterAccount,$twitterFeed,time()+300);
                }
                $twitterFeed=  json_decode($twitterFeed);
                
                foreach($twitterFeed as $i){
	    	$p=array(
	    		"id"=>$i->id,
	    		"fecha"=>$i->created_at,
	    		"actividad"=>$i->text,
	    		"link"=>"http://twitter.com/".$twitterAccount,
	    		"usuario"=>"",
	    		"nivel"=>"0",
	    		"tipo"=>"3",
	    		"objeto"=>"",
	    		"tipoobjeto"=>"",
	    		"nombre_pant"=>$i->user->screen_name,
	    		"avatar"=>$i->user->profile_image_url,
	    		"userlink"=>$i->user->url,
                        "twitter_data"=>$i
				);
                   // $actividades[strval(strtotime($i->created_at))]=(object)$p;
                }
                $ln_company="2507662";
                $ln_company="2507662";
                if(!$linkedinFeed=$cache->loadData("linkedin_company_updates".$ln_company)){
                    
                    $linkedin=$core->getApp("linkedin");
                    $ln_token=$linkedin->getAToken();
                    if($ln_token){
                        $linkedinFeed=$linkedin->get("companies/".$ln_company."/updates",$ln_token);
                        $cache->storeData("linkedin_company_updates".$ln_company,$linkedinFeed->asXML(),time()+300);
                    }
                }
                else{
                    $linkedinFeed=  simplexml_load_string($linkedinFeed);
                }
                foreach($linkedinFeed as $i){
                        $pub=$i->xpath("update-content/company-status-update/share/comment");
                        //print_r($pub);
                        $pub=(string)$pub[0];
                        //echo $pub."<hr>";
                    if(strpos($pub,"#espacios")===false){
                        $time=$i->xpath("timestamp");
                        $time=(float)$time[0]/1000;
                    $p=array(
                            "id"=>strval($time),
                            "fecha"=>strval($time),
                            "actividad"=>$pub,
                            "link"=>"http://www.linkedin.com/company/e-spacios-com",
                            "usuario"=>"",
                            "nivel"=>"0",
                            "tipo"=>"4",
                            "objeto"=>"",
                            "tipoobjeto"=>"",
                            "nombre_pant"=>"",
                            "avatar"=>"",
                            "userlink"=>"",
                            "linkedin_data"=>$i
				);
                    $actividades[strval($time)]=(object)$p;
                    }
                }
                */
                ksort($actividades);
		return array_reverse ($actividades,true);
	}
}
