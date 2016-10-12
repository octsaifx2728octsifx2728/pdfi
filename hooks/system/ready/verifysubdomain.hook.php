<?php

class verifysubdomain_hook implements hook{
    public function fire(){
        global $core,$config;
        $domain=&$core->getEnviromentVar("domain");
        if($domain->subdomain!="www"
                &&$domain->domain!="e-spacios.local"
                &&$domain->domain!="espacios.magodeozmexico.com"
                &&$domain->domain!="dagny.e-spacios.com"
                &&$domain->domain!="e-spacios.dev3"
                ){
            //header("location:".$config->paths["urlbase"]);
            //echo $domain->domain;
            //exit;
        }
    }
}
