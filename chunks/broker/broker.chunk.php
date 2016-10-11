<?php
class broker_chunk extends chunk_base implements chunk{
    protected $_selfpath         = 'chunks/broker/',
              $_plantillas       = array('data_contact'       => 'html/broker_data_contact.html',
                                         'data_contact_owner' => 'html/broker_data_contact_owner.html',
                                         'experiencias'       => 'html/broker_experiencias.html',
                                         'phones'             => 'html/broker_phones.html',
                                         'emails'             => 'html/broker_emails.html',
                                         'websites'           => 'html/broker_websites.html',
                                         'comment'            => 'html/broker_comment.html',
                                         'star'               => 'html/broker_comment_star.html',
                                         'new_feed'           => 'html/broker_new_feed.html');

    var $params = array();



    function broker_chunk($params = array()){
        $this->params           = $params;
        $this->_plantillasAdmin = $this->_plantillas;
    }

    function out($params = array()){
        global $user, $user_view;

        $this->_adminMode = ($user->id == $user_view->id);
        $p                = array('nombre' => $user_view->get('nombre_pant'),
                                  'email'  => $user_view->get('usuario'));

        if(isset($this->params['data'])){
            switch($this->params['data']){
                case 'contacto': {
                    $plantilla          = $this->_adminMode ? $this->loadPlantilla('data_contact_owner') : $this->loadPlantilla('data_contact');
                    $plantilla_phones   = $this->loadPlantilla('phones');
                    $plantilla_emails   = $this->loadPlantilla('emails');
                    $plantilla_websites = $this->loadPlantilla('websites');

                    $p['phones']     = array();
                    $p['emails']     = array();
                    $p['websites']   = array();
                    $phones          = $user_view->getBrokerContactInfo('phone');
                    $emails          = $user_view->getBrokerContactInfo('email');
                    $websites        = $user_view->getBrokerContactInfo('website');
                    $social_networks = $user_view->getBrokerContactInfo('social_networks');

                    foreach($phones as $phone){
                        $p['phones'][] = $this->parse($plantilla_phones,
                                                      array('phoneType' => '$$'.$phone['type'].'$$',
                                                            'phone'     => $phone['info'],
                                                            'infoID'    => $phone['id']));
                    }

                    foreach($emails as $email){
                        $p['emails'][] = $this->parse($plantilla_emails,
                                                      array('emailType' => '$$'.$email['type'].'$$',
                                                            'email'     => $email['info'],
                                                            'infoID'    => $email['id']));
                    }

                    foreach($websites as $website){
                        $p['websites'][] = $this->parse($plantilla_websites,
                                                        array('websiteType' => '$$'.$website['type'].'$$',
                                                              'website'     => $website['info'],
                                                              'infoID'      => $website['id']));
                    }

                    $p['phones']   = implode('', $p['phones']);
                    $p['emails']   = implode('', $p['emails']);
                    $p['websites'] = implode('', $p['websites']);

                    if(count($social_networks) > 0){
                        if(isset($social_networks[0]['linkedin']) and !empty($social_networks[0]['linkedin'])){
                            $p['linkedIn']          = $social_networks[0]['linkedin'];
                            $p['LinkedInAddOREdit'] = '<span class="social_network_opts fa fa-pencil"></span>';

                        }else{
                            $p['LinkedInAddOREdit'] = '<span class="social_network_opts fa fa-plus"></span>';
                        }

                        if(isset($social_networks[0]['twitter']) and !empty($social_networks[0]['twitter'])){
                            $p['twitter']          = $social_networks[0]['twitter'];
                            $p['TwitterAddOREdit'] = '<span class="social_network_opts fa fa-pencil"></span>';

                        }else{
                            $p['TwitterAddOREdit'] = '<span class="social_network_opts fa fa-plus"></span>';
                        }

                        if(isset($social_networks[0]['google_plus']) and !empty($social_networks[0]['google_plus'])){
                            $p['googlePlus']     = $social_networks[0]['google_plus'];
                            $p['GPlusAddOREdit'] = '<span class="social_network_opts fa fa-pencil"></span>';

                        }else{
                            $p['GPlusAddOREdit'] = '<span class="social_network_opts fa fa-plus"></span>';
                        }

                        if(isset($social_networks[0]['youtube']) and !empty($social_networks[0]['youtube'])){
                            $p['youTube']          = $social_networks[0]['youtube'];
                            $p['YouTubeAddOREdit'] = '<span class="social_network_opts fa fa-pencil"></span>';

                        }else{
                            $p['YouTubeAddOREdit'] = '<span class="social_network_opts fa fa-plus"></span>';
                        }

                        if(isset($social_networks[0]['facebook']) and !empty($social_networks[0]['facebook'])){
                            $p['facebook']          = $social_networks[0]['facebook'];
                            $p['FacebookAddOREdit'] = '<span class="social_network_opts fa fa-pencil"></span>';

                        }else{
                            $p['FacebookAddOREdit'] = '<span class="social_network_opts fa fa-plus"></span>';
                        }

                    }else{
                        $p['linkedIn']          = '';
                        $p['LinkedInAddOREdit'] = '<span class="social_network_opts fa fa-plus"></span>';

                        $p['twitter']          = '';
                        $p['TwitterAddOREdit'] = '<span class="social_network_opts fa fa-plus"></span>';

                        $p['googlePlus']     = '';
                        $p['GPlusAddOREdit'] = '<span class="social_network_opts fa fa-plus"></span>';

                        $p['youTube']          = '';
                        $p['YouTubeAddOREdit'] = '<span class="social_network_opts fa fa-plus"></span>';

                        $p['facebook']          = '';
                        $p['FacebookAddOREdit'] = '<span class="social_network_opts fa fa-plus"></span>';
                    }

                    break;
                }
                case 'experiencias': {
                    $plantilla    = $this->loadPlantilla('experiencias');
                    $experiencias = $user_view->getBrokerData('experience');
                    $p['exp']     = array();

                    foreach($experiencias as $exp){
                        $exp_duracion = $exp['fecha_fin'] - $exp['fecha_inicio'];

                        $p['exp'][] = $this->parse($plantilla, array('expID'            => $exp['id_experiencia'],
                                                                     'exp_titulo'       => $exp['titulo'],
                                                                     'exp_fecha_inicio' => tdate($exp['fecha_inicio']),
                                                                     'exp_fecha_fin'    => is_numeric($exp['fecha_fin']) ? tdate($exp['fecha_fin'], 'd F Y') : '$$'.$exp['fecha_fin'].'$$',
                                                                     'exp_duracion'     => calc_time($exp['fecha_inicio'], (is_numeric($exp['fecha_fin']) ? $exp['fecha_fin'] : time()), 'month'),
                                                                     'exp_descripcion'  => $exp['descripcion']));
                    }

                    $plantilla = implode('', $p['exp']);
                    break;
                }
                case 'news': {
                    $plantilla     = '';
                    $new_plantilla = $this->loadPlantilla('new_feed');
                    $news          = $user_view->getBrokerData('news');

                    foreach($news as $new){
                        $plantilla .= $this->parse($new_plantilla, array('newID'           => $new['id_user'].'_'.$new['date'],
                                                                         'new_content'     => $new['status'],
                                                                         'new_attachments' => show_attachments($new['attachments']),
                                                                         'new_date'        => tdate($new['date'])));
                    }

                    break;
                }
                case 'rating': {
                    $rating = $user_view->getBrokerData('rating');
                    $star   = $this->loadPlantilla('star');
                    $stars  = '';

                    for($s = 1; $s <= intval($rating[0]['rating']); $s++){
                        $stars .= $this->parse($star, array('star_num'  => $s,
                                                            'star_size' => 'lg'));
                    }

                    $plantilla = $stars;
                    break;
                }
                case 'comments': {
                    $plantilla  = $this->loadPlantilla('comment');
                    $star       = $this->loadPlantilla('star');
                    $comments   = $user_view->getBrokerData('comments');
                    $p['cmnts'] = array();

                    foreach($comments as $comment){
                        $commenter = new User($comment['id_user_calificador']);
                        $stars     = '';

                        for($s = 1; $s <= intval($comment['calificacion']); $s++){
                            $stars .= $this->parse($star, array('star_num'  => $s,
                                                                'star_size' => 'lg'));
                        }

                        $p['cmnts'][] = $this->parse($plantilla, array('commentID'     => $comment['id_user'].'_'.$comment['id_user_calificador'],
                                                                       'commenter'     => $commenter->get('nombre_pant'),
                                                                       'comment_stars' => $stars,
                                                                       'comment'       => $comment['comentario'],
                                                                       'comment_date'  => tdate($comment['date'], 'd F Y')));
                    }

                    $plantilla = implode('', $p['cmnts']);
                    break;
                }
                case 'info_personal': {
                    $plantilla = $user_view->getBrokerData('info_personal')[0]['info_personal'];
                    break;
                }
                case 'info_extra': {
                    $plantilla = $user_view->getBrokerData('info_extra')[0]['info_extra'];
                    break;
                }
                default:
                    $plantilla = 'No se reconoce la petición';
            }
        }

        return parent::out($plantilla, $p);
    }
}
