<?php
class searchplaces_app{
  function search($query){
    $url="https://maps.googleapis.com/maps/api/place/textsearch/json?query=$query&key=AIzaSyCCgAekZtgQD2XrU7JoJPm0KAat03yLBas&sensor=false&callback=sss";


    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'paynalton');

    $respuesta=curl_exec($ch);
    curl_close($ch);
    echo $respuesta;
  }
}
