<?php
function geraSenha($size = 8, $maiusculas = true, $minusculas = true, $numeros = true){
    $min = 'abcdefghijklmnopqrstuvwxyz';
    $mai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '0123456789';
    
    $retorno = '';
    $caracteres = '';
    
    if($minusculas){
        $caracteres .= $min;
    }
    if($maiusculas){
        $caracteres .= $mai;
    }
    if($numeros){
        $caracteres .= $num;
    }
    
    $len = strlen($caracteres);
    for($n = 1; $n <= $size; $n++){
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand-1];
    }
    
    return $retorno;
}