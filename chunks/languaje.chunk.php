<?php
class languaje_chunk {
  function out(){
    global $user,$config;
    $plantilla= file_get_contents($config->paths["chunks/html"].'languaje.html');
    return $plantilla;
  }
}
