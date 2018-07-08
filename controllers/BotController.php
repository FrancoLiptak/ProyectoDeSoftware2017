<?php

$returnArray = true;
$rawData = file_get_contents('php://input');
$response = json_decode($rawData, $returnArray);
$id_del_chat = $response['message']['chat']['id'];


// Obtener comando (y sus posibles parametros)
$regExp = '#^(\/[a-zA-Z0-9\/]+?)(\ .*?)$#i';


$tmp = preg_match($regExp, $response['message']['text'], $aResults);

if (isset($aResults[1])) {
    $cmd = trim($aResults[1]);
    $cmd_params = trim($aResults[2]);
} else {
    $cmd = trim($response['message']['text']);
    $cmd_params = '';
}

$msg = array();
$msg['chat_id'] = $response['message']['chat']['id'];
$msg['text'] = null;
$msg['disable_web_page_preview'] = true;
$msg['reply_to_message_id'] = $response['message']['message_id'];
$msg['reply_markup'] = null;

switch ($cmd) {
case '/start':
    $msg['text']  = 'Hola ' . $response['message']['from']['first_name'] .
               " Usuario: " . $response['message']['from']['username'] . '!' . PHP_EOL;
    $msg['text'] .= '¿Como puedo ayudarte? /help';
    $msg['reply_to_message_id'] = null;
    break;

case '/help':
    $msg['text']  = 'Los comandos disponibles son estos:' . PHP_EOL;
    $msg['text'] .= '/start Inicializa el bot' . PHP_EOL;
    $msg['text'] .= '/turnos dd-mm-aaaa Muestra los turnos disponibles del día' . PHP_EOL;
    $msg['text'] .= '/reservar dd-mm-aaaa hh:mm Realiza la reserva del turno' . PHP_EOL;
    $msg['text'] .= '/help Muestra esta ayuda';
    $msg['reply_to_message_id'] = null;
    break;

case '/reservar':

    $cmd_params = explode(" ", $cmd_params);
    $url = 'https://grupo11.proyecto2017.linti.unlp.edu.ar/app/api-turnos.php/turnos/'.$cmd_params[0].'/fecha/'.$cmd_params[1].'/hora/'.$cmd_params[2];
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded",
            'method'  => 'POST',
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $decoded = json_decode($result);

    if(is_numeric($decoded)){
        $msg['text'] = "Su número de turno es: $decoded" . PHP_EOL;
    }else{
        $msg['text'] = $decoded;
    }

    $msg['reply_to_message_id'] = null;
    break;

case '/turnos':
    $cmd_params = explode(" ", $cmd_params);
    $var = file_get_contents('https://grupo11.proyecto2017.linti.unlp.edu.ar/app/api-turnos.php/turnos/'.$cmd_params[0]);
    $decoded = json_decode($var, true);
    $msg['reply_to_message_id'] = null;
    

    if(is_array($decoded)){

        $first = true;
        $msg['text'] = 'Los turnos disponibles son:' . PHP_EOL;
        foreach ($decoded as $hora) {
            if (!$first) {
                $msg['text'] .= ' | ';
            } else {
                $first = false;
            }
//            $msg['text'] .= str_replace(':', '-', substr($hora['horario'], 0, -3));
            $msg['text'] .= $hora['horario'];
        }
    }else{
        $msg['text'] = $decoded;
    }
    
    break;

default:
        $msg['text']  = 'Lo siento, no es un comando válido.' . PHP_EOL;
        $msg['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
        break;
}

$url = 'https://api.telegram.org/bot506270499:AAHyehyoM2YpsdOoh6QqocY3JlMRQHzD7W0/sendMessage';

$options = array(
'http' => array(
    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
    'method'  => 'POST',
    'content' => http_build_query($msg)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

exit(0);



