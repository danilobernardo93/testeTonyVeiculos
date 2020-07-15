<?php

$cabecalho = array('Content-Type: application/json','accept: application/json');

$param = '?';

$requisicao = $_POST['requisicao'];
unset($_POST['requisicao']);

foreach ($_POST as $key => $value) {
    $param.=$key.'='.str_replace(" ","%20",$value).'&';
}

$paramentros = substr($param,0,-1);
$url       = 'http://localhost/tony-veiculos/api/'.$paramentros;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,            $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requisicao);
curl_setopt($ch, CURLOPT_HTTPHEADER,     $cabecalho);

$resposta = curl_exec($ch);

curl_close($ch);

echo json_encode($resposta);
