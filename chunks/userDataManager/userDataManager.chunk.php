<?php
class userDataManager_chunk extends chunk_base implements chunk{
    protected $_selfpath        = 'chunks/userDataManager/',
              $_plantillasAdmin = array('html/userDataManager.html','html/userDataManager1.html'),
              $_plantillas      = array('html/userDataManager.html','html/userDataManager2.html');

    var $params;

    function userDataManager_chunk($params = array()){
        $this->params = $params;
    }

    function out($params = array()){
        global $user, $user_view;

        $this->_adminMode = ($user->id == $user_view->id);
        $plantilla        = $this->params['plantilla'] ? $this->loadPlantilla(intval($this->params['plantilla'])) : $this->loadPlantilla(0);


        $p = array(
            'nombre'             => $user_view->get('nombre_pant'),
            'email'              => $user_view->get('usuario'),
            'tel'                => $user_view->get('telefono'),
            'tel1'               => $user_view->get('telefono1'),
            'tel2'               => $user_view->get('telefono2'),
            'telefono_selected'  => $user_view->get('telefonotag') == '$$telefono$$' ? 'selected="selected"' : '',
            'casa_selected'      => $user_view->get('telefonotag') == '$$casa$$'     ? 'selected="selected"' : '',
            'oficina_selected'   => $user_view->get('telefonotag') == '$$oficina$$'  ? 'selected="selected"' : '',
            'movil_selected'     => $user_view->get('telefonotag') == '$$movil$$'    ? 'selected="selected"' : '',
            'otro_selected'      => $user_view->get('telefonotag') == '$$otro$$'     ? 'selected="selected"' : '',

            'telefono_selected1' => $user_view->get('telefonotag1') == '$$telefono$$' ? 'selected="selected"' : '',
            'casa_selected1'     => $user_view->get('telefonotag1') == '$$casa$$'     ? 'selected="selected"' : '',
            'oficina_selected1'  => $user_view->get('telefonotag1') == '$$oficina$$'  ? 'selected="selected"' : '',
            'movil_selected1'    => $user_view->get('telefonotag1') == '$$movil$$'    ? 'selected="selected"' : '',
            'otro_selected1'     => $user_view->get('telefonotag1') == '$$otro$$'     ? 'selected="selected"' : '',

            'telefono_selected2' => $user_view->get('telefonotag2') == '$$telefono$$' ? 'selected="selected"' : '',
            'casa_selected2'     => $user_view->get('telefonotag2') == '$$casa$$'     ? 'selected="selected"' : '',
            'oficina_selected2'  => $user_view->get('telefonotag2') == '$$oficina$$'  ? 'selected="selected"' : '',
            'movil_selected2'    => $user_view->get('telefonotag2') == '$$movil$$'    ? 'selected="selected"' : '',
            'otro_selected2'     => $user_view->get('telefonotag2') == '$$otro$$'     ? 'selected="selected"' : ''
        );

        return parent::out($plantilla, $p);
    }
}
