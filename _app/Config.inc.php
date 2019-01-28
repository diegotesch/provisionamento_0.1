<?php

//CONFIGURACOES DE DATA E HORA
date_default_timezone_set("Brazil/East");

//CONSTANTES
define('HOME', 'http://localhost/provissionamento/');

//CONFIGURACOES DO SITE ####################
define('HOST', 'localhost');
define('USER', 'provisionamento');
define('PASS', 'sSYnrHEnc3K8vbh3'); //senha para site sSYnrHEnc3K8vbh3
define('DB', 'provisionamento');//bd site provisionamento

define('VIAON_HOST', '177.101.80.55');
define('VIAON_USER', 'cliente_r');
define('VIAON_PASS', 'Cl13nt_R'); 
define('VIAON_DB', 'mkData');

//AUTOLOAD DE CLASSS #######################
function __autoload($class) {
    $cDir = array('Conn', 'Helpers', 'Models', 'Files', 'PDF');
    $iDir = null;

    foreach ($cDir as $dirName) {
        if (!$iDir && file_exists(__DIR__ . "/{$dirName}/{$class}.class.php") && !is_dir($dirName)) {
            include_once(__DIR__ . "/{$dirName}/{$class}.class.php");
            $iDir = true;
        }
    }

    if (!$iDir) {
        trigger_error("Não foi possível incluir {$class}.class.php", E_USER_ERROR);
    }
}

//TRATAMENTO DE ERROS ######################
//CSS CONSTANTES :: MENSAGENS DE ERRO
define('MSG_OK', 'success');
define('MSG_INFO', 'info');
define('MSG_ALERTA', 'block');
define('MSG_ERRO', 'error');

//WSERRO :: EXIBE ERROS LANÇADOS :: FRONT
function PROVErro($ErrMsg, $ErrNo, $ErrDie = null) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? MSG_INFO : ($ErrNo == E_USER_WARNING ? MSG_ALERTA : ($ErrNo == E_USER_ERROR ? MSG_ERRO : $ErrNo)));
    echo "<p class=\"alert alert-{$CssClass}\">{$ErrMsg}<span class=\"ajax_close\"></span></p>";
    if ($ErrDie) {
        die;
    }
}

//PHPERRO :: PERSONALIZA O GATILHO DO PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? MSG_INFO : ($ErrNo == E_USER_WARNING ? MSG_ALERTA : ($ErrNo == E_USER_ERROR ? MSG_ERRO : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">";
    echo "<b>Erro na Linha: {$ErrLine} ::</b> {$ErrMsg} <br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class=\"ajax_close\"></span></p>";

    if ($ErrNo == E_USER_ERROR) {
        die;
    }
}

set_error_handler('PHPErro');

//FUNCOES
function limpaString($string) {
    $string = str_replace(':', '', $string);
    $string = str_replace(';', '', $string);
    $string = str_replace('/', '', $string);
    $string = str_replace('\\', '', $string);
    $string = str_replace(']', '', $string);
    $string = str_replace('[', '', $string);
    $string = str_replace('}', '', $string);
    $string = str_replace('{', '', $string);
    $string = str_replace(')', '', $string);
    $string = str_replace('(', '', $string);
    $string = str_replace("'", '', $string);
    $string = str_replace('"', '', $string);
    $string = str_replace('ç', 'c', $string);
    $string = str_replace('Ç', 'C', $string);
    $string = str_replace('Ç', 'C', $string);
    $string = str_replace('Á', 'A', $string);
    $string = str_replace('À', 'A', $string);
    $string = str_replace('Â', 'A', $string);
    $string = str_replace('Ã', 'A', $string);
    $string = str_replace('Ä', 'A', $string);
    $string = str_replace('á', 'a', $string);
    $string = str_replace('à', 'a', $string);
    $string = str_replace('â', 'a', $string);
    $string = str_replace('ã', 'a', $string);
    $string = str_replace('ä', 'a', $string);
    $string = str_replace('É', 'E', $string);
    $string = str_replace('Ê', 'E', $string);
    $string = str_replace('é', 'e', $string);
    $string = str_replace('ê', 'e', $string);
    $string = str_replace('Í', 'I', $string);
    $string = str_replace('í', 'i', $string);
    $string = str_replace('Ó', 'O', $string);
    $string = str_replace('Ô', 'O', $string);
    $string = str_replace('Õ', 'O', $string);
    $string = str_replace('ó', 'o', $string);
    $string = str_replace('ô', 'o', $string);
    $string = str_replace('õ', 'o', $string);
    $string = str_replace('Ú', 'U', $string);
    $string = str_replace('Ü', 'U', $string);
    $string = str_replace('ú', 'u', $string);
    $string = str_replace('ü', 'u', $string);
    return $string;
}

function localizaTexto($string, $palavra) {
    $regex = '/' . $palavra . '/i';
    return preg_match($regex, $string);
}

function converteReal($valor) {
    $pos = strripos($valor, ',');
    $pos2 = strripos($valor, '.');
    if ($pos != '') {
        $v_exp = explode(',', $valor);
        $novo = "$v_exp[0],$v_exp[1]";
    } else if ($pos2 != '') {
        $v_exp = explode('.', $valor);
        $novo = "$v_exp[0],$v_exp[1]";
    } else if ($valor == NULL || $valor == '' | $valor == 0) {
        $novo = "0,00";
    } else {
        $novo = "$valor,00";
    }
    return $novo;
}

function dataExtenso($data = NULL) {
    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    if (isset($data) && $data != NULL) {
        return strftime('%d de %B de %Y', strtotime($data));
    } else {
        return strftime('%d de %B de %Y', strtotime('today'));
    }
}

function dataTela($data) {
    if (strripos($data, '/') != '') {
        $data_exp = explode('/', $data, 3);
        $data_tela = $data_exp[2] . '/' . $data_exp[1] . '/' . $data_exp[0];
        return $data_tela;
    } else if (strripos($data, '-') != '') {
        $data_exp = explode('-', $data, 3);
        $data_tela = $data_exp[2] . '/' . $data_exp[1] . '/' . $data_exp[0];
        return $data_tela;
    } else {
        return '-';
    }
}

function Redirecionar($url, $tempo = 0) {
    $ir = (string) $url;
    $tempo = (int) $tempo;
    echo '<script>window.setTimeout(\'location.href="' . $ir . '"\', ' . $tempo . ')</script>';
}

function geraSenha($size = 8, $maiusculas = true, $minusculas = true, $numeros = true) {
    $min = 'abcdefghijklmnopqrstuvwxyz';
    $mai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '0123456789';

    $retorno = '';
    $caracteres = '';

    if ($minusculas) {
        $caracteres .= $min;
    }
    if ($maiusculas) {
        $caracteres .= $mai;
    }
    if ($numeros) {
        $caracteres .= $num;
    }

    $len = strlen($caracteres);
    for ($n = 1; $n <= $size; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }

    return $retorno;
}


/*
MODELO  -   ACRESCIMO
V2801HW -   3
28HW    -   5
28HWV2  -   5
TPLINK  -   0
1GEZ    -   1
*/
function MacPlus($mac, $add = 3, $anterior = false) {
    $add = (int) $add;
    if (isset($mac) && ctype_xdigit($mac)) {
        $final = base_convert(base_convert(substr($mac, -4, 4), 16, 10) + base_convert("000$add", 16, 10), 10, 16);
        return strtoupper(substr($mac, 0, -4) . $final);
    } else {
        return false;
    }
}
