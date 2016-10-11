<?php
class urlShorter_app{
    function acortar($url){
        global $core,$config;
        $db=&$core->getDB(0,2);
        $q="Select `short` from `urlShorter` where `long`='".  base64_encode($url)."'";
        if($r=$db->query($q)){
            if($i=$r->fetch_assoc()){
                return $config->paths["urlbase"]."/+/".base64_encode($i["short"]);
            }
        }
        $q="insert into `urlShorter`(`long`)values('".base64_encode($url)."')";
        if($db->query($q)){
            $short=  base64_encode($db->insert_id);
            return $config->paths["urlbase"]."/+/".$short;
        }
    }
    function alargar($key){
        global $core,$config;
        $db=&$core->getDB(0,2);
        $q="select `long` from `urlShorter` where `short`='".base64_decode($key)."'";
        if($r=$db->query($q)){
            if($i=$r->fetch_assoc()){
                return base64_decode($i["long"]);
            }
        }
    }
}
