<?php
class selectTipoInmueble_chunk extends chunk_base implements chunk{
    protected $_selfpath   = 'chunks/selectTipoInmueble/',
              $_plantillas = array('html/main.html',
                                   'html/li.html'),
              $_styles     = array();

    public function out($params = array()) {
        global $core;

        $plantilla    = $this->loadPlantilla(0);
        $plantilla_li = $this->loadPlantilla(1);
        $manager      = $core->getApp('inmueblesManager');
        $prototipos   = $manager->getPrototypes();
        $opciones     = '';

        foreach($prototipos as $p){
            $p = array(
                'NOMBRE'      => $p->nombreS,
                'TYPEID'      => $p->typeid,
                'TYPE'        => $p->typename,
                'DESCRIPTION' => $p->description
            );

            $opciones .= $this->parse($plantilla_li, $p);
        }

        $p = array(
            'KEY'      => md5('plant'.time().rand()),
            'OPCIONES' => $opciones
        );

        return parent::out($plantilla, $p);
    }
}
