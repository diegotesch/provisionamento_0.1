<?php
require_once '../_app/Config.inc.php';

header('Access-Control-Allow-Origin: *');

$url = explode('/', $_SERVER['PATH_INFO']);

switch($url[1]){
    case 'mac':
        $read = new Read();
        $read->ExeRead('onu', "WHERE onu_status = 1", NULL, 'onu_id, onu_mac, onu_status, lote_id');
        echo json_encode($read->getResult());
        break;
    case 'alocar':
        $post = json_decode(file_get_contents("php://input"));
        $responsavel = $post[0]->responsavel;
        $equipamentos = $post[1]->equipamentos;
        
        foreach ($equipamentos as $equipamento){
            $equipamento->onu_status = 0;
            $equipamento->onu_cliente = $responsavel;
            unset($equipamento->selecionado);
            $up = new Update();
            $up->ExeUpdate('onu', (array) $equipamento, "WHERE onu_id = :oid", "oid={$equipamento->onu_id}");
            if($up->getResult()){
                echo 1;
            }else{
                echo 0;
            }
        }
        break;
    case 'defeito':
        $post = json_decode(file_get_contents("php://input"));
        $data = date('d/m/Y H:i:s');
        $up = new Update();
        $up->ExeUpdate('onu', array('onu_cliente' => "{$post->titulo} - {$post->descricao} - data: {$data}", 'onu_status' => 3), "WHERE onu_id = :oid", "oid={$post->onu_id}");
        if($up->getResult()){
            echo 1;
        }else{
            echo 0;
        }
        break;
    case 'find':
        $read = new Read();
        $where = "WHERE ";
        $plus = 0;
        if($url[2] != "null"){
            $plus++;
            $where .= "onu_mac LIKE '%$url[2]%' ";
        }
        if($url[3] != "null"){
            $where .= ($plus > 0 ? "AND " : '');
            $plus++;
            $where .= "onu_cliente LIKE '%$url[3]%' ";
        }
        
        $read->ExeRead('onu', $where);
        echo json_encode($read->getResult());
        break;
    default :
        echo 'Opção Inválida';
}