<?php
class inmuebleImages_chunk extends chunk_base implements chunk{
            protected $_selfpath="chunks/inmuebleImages/";
            protected $_plantillas=array(
                'html/inmuebleImages.html',
                'html/image.html',
                'html/videolink.html',
                'html/image360.html'
            );
            protected $_scripts=array(
                'js/pay_slider.js',
                'js/jquery.zoom-min.js'
            );
            protected $_styles=array(
                'css/pay_slider.css'
            );
	function out($vals=array()){
		global $inmueble,$user;
		
		if($inmueble){
			$imagenes=$inmueble->getImages($user->id==$inmueble->cliente);
			$videos=$inmueble->getVideos($user->id==$inmueble->cliente);
                        
                        $plantilla= $this->loadPlantilla(0);
                        $plantilla_img= $this->loadPlantilla(1);
                        $plantilla_video= $this->loadPlantilla(2);
                        $plantilla_360= $this->loadPlantilla(3);
                        
                        
			$x=1;
			$imgs="";
			foreach($imagenes as $img){
                            if(file_exists($img->path)&&!is_dir($img->path)){
				$plant= $img->panoramica?$plantilla_360:$plantilla_img;
				$p=array(
					"PATH"=>
                                                //(intval($inmueble->get("vendido"))?"stamp/vendido/":"").
                                                    $img->path,
					"CLASS"=>"image image_".$x, 
                                        "TITLE"=>$inmueble->get("titulo")
					);
					$imgs.=$this->parse($plant,$p);
					$x++;
                            }
			}
			$x=1;
			foreach($videos as $v){
				$p=array(
					"PATH"=>$v,
					"CLASS"=>"video video_".$x
					);
					$imgs.=$this->parse($plantilla_video,$p);
					$x++;
			}
			$p=array(
				"IMAGENES"=>$imgs
				);
                        return parent::out($plantilla,$p);
		}
		
	}
}
