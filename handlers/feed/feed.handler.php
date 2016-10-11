<?php
class feed_handler implements handler{
    private $extras_en=array(
        "/houses-for-sale-in-mexico-city.html",
        "/apartments-for-rent-in-mexico-city.html",
        "/houses-for-sale-in-bogota-colombia.html",
        "/apartments-for-rent-in-bogota-colombia.html",
        "/houses-for-sale-in-guadalajara-mexico.html",
        "/apartments-for-rent-in-guadalajara-mexico.html",
        "/houses-for-sale-in-santiago-chile.html",
        "/apartments-for-rent-in-santiago-chile.html",
        "/houses-for-sale-in-la-victoria-peru.html",
        "/apartments-for-rent-in-la-victoria-peru.html",
        "/houses-for-sale-in-acapulco.html",
        "/apartments-for-rent-in-acapulco.html",
        "/houses-for-sale-in-medellin-colombia.html",
        "/apartments-for-rent-in-medellin-colombia.html",
        "/houses-for-sale-in-monterrey-mexico.html",
        "/apartments-for-rent-in-monterrey-mexico.html",
        "/houses-for-sale-in-puebla-mexico.html",
        "/apartments-for-rent-in-puebla-mexico.html");
    private $extras_es=array(
        "/casas-en-venta-mexico-df.html",
        "/departamentos-en-renta-mexico-df.html",
        "/casas-en-venta-bogota-colombia.html",
        "/departamentos-en-renta-bogota-colombia.html",
        "/casas-en-venta-guadalajara-mexico.html",
        "/departamentos-en-renta-guadalajara-mexico.html",
        "/casas-en-venta-santiago-de-chile.html",
        "/departamentos-en-renta-santiago-de-chile.html",
        "/casas-en-venta-en-la-victoria-peru.html",
        "/departamentos-en-renta-en-la-victoria-peru.html",
        "/casas-en-venta-en-acapulco.html",
        "/departamentos-en-renta-en-acapulco.html",
        "/casas-en-venta-en-medellin-colombia.html",
        "/departamentos-en-renta-en-medellin-colombia.html",
        "/casas-en-venta-en-monterrey-mexico.html",
        "/departamentos-en-renta-en-monterrey-mexico.html",
        "/casas-en-venta-en-puebla-mexico.html",
        "/departamentos-en-renta-en-puebla-mexico.html");
    private $extras=array(
        "/app/searchbounds/-21.7808136:-53.63748099999998:-55.0577146:-73.56036010000003:Venta%20Casa%20Argentina",
        "/app/searchbounds/70:-50:42:-142:Canada",
        "/app/searchbounds/-17.4983293:-66.41820159999997:-55.9797808:-75.69678650000003:Venta%20Casa%20Chile",
        "/app/searchbounds/-0.038777:-68.65232900000001:-18.3515803:-81.32850409999998:Venta%20Casa%20Peru",
        "/app/searchbounds/49.38:-66.94:25.82:-124.38999999999999:Venta%20Casa%20Estados%20Unidos",
        "/app/searchbounds/19.5919189:-98.94018549999998:19.0482787:-99.36418349999997:Venta%20Casa%20Ciudad%20de%20México,%20D.F.,%20México",
        "/app/searchbounds/22.561968:114.40644509999993:22.153415:113.83507899999995:Venta%20Casa%20Hong%20Kong",
        "/app/searchbounds/45.244:5.097999999999956:35.17300000000001:-12.524000000000001:Venta%20Casa%20España",
        "/app/searchbounds/60.856553:1.7627095999999938:49.8669422:-8.64935719999994:Venta%20Casa%20Reino%20Unido",
        "/app/searchbounds/12.4585:-66.851923:-4.22711:-79.05584699999997:Venta%20Casa%20Colombia",
        "/app/searchbounds/-9.2268057:153.6386738:-43.658327:112.92397210000001:Venta%20Casa%20Australia",
        "/app/searchbounds/16.9284774:-99.72324179999998:16.7387787:-100.00207060000002:Venta%20Casa%20Acapulco,%20GRO,%20México",
        "/app/searchbounds/40.9152414:-73.7002721:40.495908:-74.2557349:Venta%20Casa%20Nueva%20York,%20EEUU",
        "/app/searchbounds/25.8556059:-80.14240029999996:25.709042:-80.3195991:Venta%20Casa%20Miami,%20Florida,%20EEUU",
        "/app/searchbounds/43.8554579:-79.1161932:43.5810846:-79.63921900000003:Venta%20Casa%20Toronto,%20Ontario,%20Canadá",
        "/app/searchbounds/-34.5265464:-58.3351447:-34.7051589:-58.53145219999999:Venta%20Casa%20Buenos%20Aires,%20Argentina",
        "/app/searchbounds/4.8371001:-73.99631:3.72977:-74.45176989999999:Venta%20Casa%20Bogotá,%20Colombia",
        "/app/searchbounds/41.4695761:2.228009899999961:41.320004:2.069525800000065:Venta%20Casa%20Barcelona,%20España",
        "/app/searchbounds/51.6723432:0.14787969999997586:51.38494009999999:-0.351468299999965:Venta%20Casa%20Londres,%20Reino%20Unido",
        "/app/searchbounds/19.5918522:-98.94008529999996:19.0482207:-99.36417030000001:Venta%20Casa%20Distrito%20Federal,%20México",
        "/app/searchbounds/40.5635903:-3.52491150000003:40.3120639:-3.834161799999947:Venta%20Casa%20Madrid,%20Espana",
        "/app/searchbounds/-33.0487158:-69.76899430000003:-34.2878805:-70.9577496:Venta%20Casa%20Santiago,%20Región%20Metropolitana%20de%20Santiago%20de%20Chile,%20Chile",
        "/app/searchbounds/-12.0308632:-77.00203110000001:-12.0798252:-77.08833950000002:Venta%20Casa%20Lima,%20Perú",
        "/app/searchbounds/49.31409499999999:-123.02306799999997:49.1998604:-123.22474:Venta%20Casa%20Vancouver,%20Colombia%20Británica,%20Canadá",
        "/app/searchbounds/42.023131:-87.524044:41.6443349:-87.94026689999998:Venta%20Casa%20Chicago,%20Illinois,%20EEUU",
        "/app/searchbounds/34.3373061:-118.1552891:33.7036917:-118.6681759:Venta%20Casa%20Los%20Ángeles,%20California,%20EEUU",
        "/app/searchbounds/20.7438466:-103.26376170000003:20.6037373:-103.40706460000001:Venta%20Casa%20Guadalajara,%20JAL,%20México",
        "/app/searchbounds/25.7972378:-100.18418910000003:25.5003874:-100.42196860000001:Venta%20Casa%20Monterrey,%20NL,%20México"
        );
    public function run($task,$params=array()){
        global $document,$config,$core;
        switch($task){
            case "sitemap":
                $document=  simplexml_load_string('<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"></urlset>');
                
                $l=$_GET["lang"]?$_GET["lang"]:"en";
                
                $url=$document->addChild("url");
                $url->addChild("loc",$config->paths["urlbase"].($_GET["lang"]?"/".$_GET["lang"]."/":""));
                $url->addChild("lastmod",date("Y-m-d"));
                $url->addChild("changefreq","always");
                $url->addChild("priority","0.8");
                
                $extrasl="extras_".$l;
                
                foreach($this->$extrasl as $e){
                    $url=$document->addChild("url");
                    $url->addChild("loc",$config->paths["urlbase"].$e);
                    $url->addChild("lastmod",date("Y-m-d"));
                    $url->addChild("changefreq","weekly");
                    $url->addChild("priority","0.5");
                }
                
                foreach($this->extras as $e){
                $url=$document->addChild("url");
                $url->addChild("loc",$config->paths["urlbase"].($_GET["lang"]?"/".$_GET["lang"]:"").$e);
                $url->addChild("lastmod",date("Y-m-d"));
                $url->addChild("changefreq","weekly");
                $url->addChild("priority","0.5");
                }
                $searcher=$core->getApp("search");
                $inmuebles=$searcher->getTop("rand()",2000);
                $lexicon=$core->getLexicon();
                foreach($inmuebles as $inm){
                    $url=$document->addChild("url");
                    $url->addChild("loc",$lexicon->traduce($inm->getURL($_GET["lang"]?$_GET["lang"]:"")));
                    $url->addChild("lastmod",date("Y-m-d",strtotime($inm->get("fecha_alta"))));
                    $url->addChild("changefreq","monthly");
                $url->addChild("priority","0.3");
                
                }
                break;
        }
    }
}
