<?php

class Resource{

    public static function getResource($url){

        $ch = curl_init($url); // recurso curl

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Accept: application/json'),
            CURLOPT_SSL_VERIFYPEER => false,
        );

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        $resource = json_decode($response, true);

        if(! empty($resource)){
            return $resource;
        }else{
            return false;
        }
    }

    public static function getAllResources(){
        $resource = [];

        array_push( $resource, Resource::getResource("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-documento") );

        array_push( $resource, Resource::getResource("https://api-referencias.proyecto2017.linti.unlp.edu.ar/obra-social") );

        array_push( $resource, Resource::getResource("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-agua") );

        array_push( $resource, Resource::getResource("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-vivienda") );

        array_push( $resource, Resource::getResource("https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-calefaccion") );
        
        return $resource;
    }

}