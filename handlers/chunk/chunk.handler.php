<?php

class chunk_handler implements handler{
    public function run($task, $params = array()) {
        global $core,$result;
        $chunk=$core->getChunk($task,json_decode(str_replace("\\","",$_GET["options"])));
        if($chunk){
                  $result["error"]="0";
                  $result["errorDescription"]="OK";
                  $result["chunk"]=$chunk->out((array)json_decode($_GET["options"]));
        }
    }
}