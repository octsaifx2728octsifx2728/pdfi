<?php

class landing_handler implements handler{
    public function run($task, $params = array()) {
      global $core, $document;
      switch($task){
          case "apps":
              $document=$core->getDocument("apps.html");
              $document->addStyle("css/landing-apps.css");
              break;
      }
      return "noConsumido";
    }
}
