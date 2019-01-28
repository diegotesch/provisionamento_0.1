<?php
$id_lote = filter_input(INPUT_GET, 'lote_id', FILTER_VALIDATE_INT);
$rma = filter_input(INPUT_GET, 'remover_rma', FILTER_VALIDATE_BOOLEAN);
$postAtivar = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once '_models/AdminOnu.class.php';

$list = new AdminOnu;
if($list->regerarClientes($id_lote)){
    $list->listOnuPorLote($id_lote);
    $onuList = $list->getResult();
    $tipoLote = $list->getTipoLote($id_lote);
    //var_dump($onuList);
}else{
    echo 'erro';
}

?>
<div class='row-fluid'>
    
    <div class="offset2 span8">
        <h3 class='titulo'>Relatorio de ONU's - Lote Nº.: <?= $id_lote ?></h3>
        <table class='table table-hover table-bordered listOnuLote'>
            <thead>
                <tr>
                    <th>MAC</th>
                    <th>Alocado Para</th>
                    <th>Status Onu</th>
                    <th colspan='<?= ($_SESSION['userlogin']['user_level'] > 1 ? 3 : 2)?>'>Ações</th>
                </tr>
            </thead>
            <tbody>
            <form name='etiquetas_onu' method='post' action='' id='formEtiquetasOnu'>
                <input type='hidden' name='control' value="true" />
                <?php
                $readmk = new ReadPG();
                $readmk->ExeRead('mk_conexoes', "WHERE conexao_bloqueada = :cn", "cn=N", "codconexao, mac_address, descricao, cadastrado");
                $conexoesmk = $readmk->getResult();
                
                foreach($onuList as $onu){
                    extract($onu);
                    
                    switch ($tipoLote){
                        case 'V2801HW':
                            foreach($conexoesmk as $con){
                                extract($con);
                                if(str_replace(':', '', $mac_address) == MacPlus($onu_mac, 3)){
                                    $onu_cliente = $con['descricao'].' - '.date('d/m/Y', strtotime($con['cadastrado']));
                                    $onu_status = 2;
                                    
                                    $upOnu = new Update();
                                    $upOnu->ExeUpdate('onu', array('onu_cliente' => $onu_cliente, 'onu_status' => $onu_status), "WHERE onu_id = :oid", "oid={$onu_id}");
                                    if($upOnu->getResult()){
                                        continue;
                                    }else{
                                        alert('erro ao cadastrar');
                                    }
                                    
                                }
                            }
                            break;
                        case '28HW':
                        case '28HWV2':
                            foreach($conexoesmk as $con){
                                extract($con);
                                
                                if(str_replace(':', '', $mac_address) == MacPlus($onu_mac, 5)){
                                    $onu_cliente = $con['descricao'].' - '.date('d/m/Y', strtotime($con['cadastrado']));
                                    $onu_status = 2;
                                    
                                    $upOnu = new Update();
                                    $upOnu->ExeUpdate('onu', array('onu_cliente' => $onu_cliente, 'onu_status' => $onu_status), "WHERE onu_id = :oid", "oid={$onu_id}");
                                    if($upOnu->getResult()){
                                        continue;
                                    }else{
                                        alert('erro ao cadastrar');
                                    }
                                    
                                }
                            }
                            break;
                        case '1GEZ':
                            foreach($conexoesmk as $con){
                                extract($con);
                                
                                if(str_replace(':', '', $mac_address) == MacPlus($onu_mac, 1)){
                                    //var_dump($con);
                                    $onu_cliente = $con['descricao'].' - '.date('d/m/Y', strtotime($con['cadastrado']));
                                    $onu_status = 2;
                                    
                                    $upOnu = new Update();
                                    $upOnu->ExeUpdate('onu', array('onu_cliente' => $onu_cliente, 'onu_status' => $onu_status), "WHERE onu_id = :oid", "oid={$onu_id}");
                                    if($upOnu->getResult()){
                                        continue;
                                    }else{
                                        alert('erro ao cadastrar');
                                    }
                                    
                                }
                            }
                            break;
                        default:
                            foreach($conexoesmk as $con){
                                extract($con);
                                
                                if(str_replace(':', '', $mac_address) == $onu_mac){
                                    //var_dump($con);
                                    $onu_cliente = $con['descricao'].' - '.date('d/m/Y', strtotime($con['cadastrado']));
                                    $onu_status = 2;
                                    
                                    $upOnu = new Update();
                                    $upOnu->ExeUpdate('onu', array('onu_cliente' => $onu_cliente, 'onu_status' => $onu_status), "WHERE onu_id = :oid", "oid={$onu_id}");
                                    if($upOnu->getResult()){
                                        continue;
                                    }else{
                                        alert('erro ao cadastrar');
                                    }
                                    
                                }
                            }
                    }
                    
                    
                    ?>
                <tr class='<?= ($onu_status == '0' ? ($onu_cliente == 'jovane' ? 'info' : 'error') : '') ?>'>
                    <td><?= $onu_mac; ?></td>
                    <td><?= (!is_null($onu_cliente) ? strtoupper($onu_cliente) : "-"); ?></td>
                    <td><?= ($onu_status == '1' ? 'ONU em estoque' : ($onu_status == '0' ? "Posse Técnica" : ($onu_status == '3' ? "DEFEITO / RMA" : "Alocada"))); ?></td>
                    <td><a href='<?= $onu_file_conf; ?>' target="_blank" download='<?php ($tipoLote == '1GEZ' ? $onu_mac : "ONU_MAC_{$onu_mac}.xml"); ?>' alt='Download Configuração ONU <?= $onu_mac ?>' title='Download Configuração ONU <?= $onu_mac ?>'><i class='icon-download-alt'></i></a></td>
                    <!--<td><a href='?exe=lote/view&lote_id=<?= $lote_id ?><?= ($onu_status == '1' ? "&ativar_onu=true" : "&desativar_onu=true") ?>&id_onu=<?= $onu_id ?>' alt='<?= ($onu_status == '1' ? "Alocar Onu" : "Retornar Onu para Estoque") ?>' title='<?= ($onu_status == '1' ? "Alocar Onu" : "Retornar Onu para Estoque") ?>'><i class='<?= ($onu_status == '1' ? 'icon-ok-sign' : "icon-remove-sign") ?>'></i></a></td>-->
                    <td style="display: <?= ($_SESSION['userlogin']['user_level'] > 1 ? 'block' : 'none' ) ?>"><a title="<?= ($onu_status == 3 ? 'Equipamento com defeito, opção indisponível' : '') ?>" href='<?=($onu_status != 3 ? "?exe=lote/rma&lote_id=$lote_id&id_onu=$onu_id" : "javascript:void(0);" )?>' alt='' title=''><i class="icon-warning-sign"></i></a></td>
                    <td title='Gerar Etiquetas'><input type="checkbox" name="onu[]" value='<?= $onu_id ?>'  /></td>
                </tr>
                <?php
                }
                ?>
            </form>
            </tbody>
        </table>
        
        <a class="botao" href='etiquetas.php?lote_id=<?= $id_lote ?>&control=true'>
            <span class="texto">Gerar Etiquetas <br>do Lote</span>
        </a>
        <a class="botao" id='generateSelEtiquetas'>
            <span class="texto">Gerar Etiquetas <br>Selecionadas</span>
        </a>
        <a class="botao" target="_blank" href="relatorio.php?lote_id=<?= $id_lote; ?>">
            <span class="texto">Relatório do <br>Lote</span>
        </a>
        <!--<a class="botao" id='alocarSelTecnico' >
            <span class="texto">Alocar Selecionadas <br>para Técnico</span>
        </a>-->
    </div>
</div>