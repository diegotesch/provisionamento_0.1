<?php
/**
 * Classe SystemHistorico [ MODEL SYSTEM ]
 *  Responsavel por gerenciar os dados do Historico
 * 
 * @copyright (c) 2016, Diego Tesch
 */
class Historico {
    private $dados;
    private $tpmsg;
    private $msg;
    private $error;
    private $result;
    private $tipo;
    private $id_tipo;
    
    //atributos contrato
    private $id_tipo_contrato;
    private $contrato;
    
    //atributos do cliente
    private $tabela;
    private $index;
    private $prefixo;
    
    const Tbl = 'crm_historico';
    
    /*public function ExeCreate(array $dados) {
        $this->dados = $dados;
        $this->dados['op_user_id'] = $_SESSION['userlogin']['user_id'];
        $this->setDados();
        $this->Create();
    }*/
    
    
    public function AddHistorico($id, $tipo, $tipo_mensagem=1, $id_tipo_contrato = null) {
        $this->id_tipo = (int) $id;
        $this->tipo = (string) $tipo;
        $this->tpmsg = (int) $tipo_mensagem;
        $this->id_tipo_contrato = (!is_null($id_tipo_contrato)? (int) $id_tipo_contrato : NULL);
        
        $this->Formata();
        $this->getDadosAuxiliares();
        
        $this->getTipoContrato();
        
        $this->getMsg();
        $this->SetDados();
        
        $this->Create();
    }
    
    //PRIVATES
    private function Formata() {
        if(strlen($this->tipo) != 1){
            $this->result = false;
            $this->error = array('Tipo deve conter apenas um caractere!', CRM_INFO);
        }else{
            strtoupper($this->tipo);
        }
    }
    
    private function getDadosAuxiliares(){
        if($this->tipo == 'O'){
            $this->tabela = 'crm_oportunidade';
            $this->index = 'op_id';
            $this->prefixo = 'op_';
        }else if($this->tipo == 'C'){
            $this->tabela = 'crm_cliente';
            $this->index = 'cli_id';
            $this->prefixo = 'cli_';
        }
    }
    
    private function getTipoContrato() {
        $intervalo = array(8, 9, 10, 11);
        if(in_array($this->tpmsg, $intervalo)){
            $read = new Read();
            //busca o nome do tipo de contrato
            $read->ExeRead('crm_tipo_contrato', "WHERE tipo_contrato_id = :tpid", "tpid={$this->id_tipo_contrato}");
            if($read->getResult()){
                $this->contrato = $read->getResult()[0]['tipo_contrato_nome'];
            }
        }
    }
    
    private function getMsg(){
        $tipo = ($this->tipo == 'O'? "Oportunidade" : "Cliente");
        $read = new Read;
        $read->ExeRead($this->tabela, "WHERE {$this->index} = :id", "id={$this->id_tipo}");
        if(!$read->getResult()){
            $this->result = false;
            $this->error = array("{$tipo} não existe no sistema! Verifique e tente novamente", CRM_ERRO);
        }else{
            switch($this->tpmsg){
                case '1':
                    //ADICIONAR / CADASTRAR
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Cadastrada no sistema por ".Check::Usuario().".";
                    break;
                case '2':
                    //ATUALIZAR
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Atualizada no sistema por ".Check::Usuario().".";
                    break;
                case '3':
                    //OPORTUNIDADE ACEITA
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Foi aceita por ".Check::Usuario()." e agora é um cliente.";
                    break;
                case '4':
                    //CANCELADA
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Foi cancelada por ".Check::Usuario().".";
                    break;
                case '5':
                    //REABERTA
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Foi reaberta por ".Check::Usuario().".";
                    break;
                case '6':
                    //NOVA PROPOSTA
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Nova Proposta aberta no sistema por ".Check::Usuario().".";
                    break;
                case '7':
                    //EXCLUIDO POR COMPLETO
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Foi excluída completamente do sistema por ".Check::Usuario()."!.";
                    break;
                case '8':
                    //CONTRATO CRIADO
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Contrato {$this->contrato} criado com sucesso por ".Check::Usuario()."!";
                    break;
                case '9':
                    //CONTRATO ADICIONADO
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Contrato {$this->contrato} adicionado com sucesso por ".Check::Usuario()."!";
                    break;
                case '10':
                    //CONTRATO ADITADO
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Contrato {$this->contrato} foi aditado por ".Check::Usuario().". Alterações realizadas com sucesso!";
                    break;
                case '11':
                    //UPLOAD CONTRATO
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Contrato {$this->contrato}, Upload realizado com sucesso por ".Check::Usuario()."!";
                    break;
                case '12':
                    //ABERTURA DE OS
                    $this->msg = "{$tipo} nome:{$read->getResult()[0][$this->prefixo.'empresa']}. Nova ordem de serviço aberta por ".Check::Usuario()."!";
                    break;
            }
        }
    }
    
    private function SetDados(){
        $this->dados['hist_tipo'] = $this->tipo;
        $this->dados['hist_id_tipo'] = $this->id_tipo;
        $this->dados['hist_dados'] = $this->msg;
    }
    
    private function Create() {
        $create = new Create();
        $create->ExeCreate(self::Tbl, $this->dados);
        if ($create->getResult()) {
            $this->result = $create->getResult();
            $this->error = array("<b>Sucesso: </b><br>Dados armazenados corretamente no histórico!", CRM_OK);
        }
    }
    
    /*public function ExeStatus($opId, $opStatus) {
        $this->op_id = (int) $opId;
        $this->dados['op_status'] = (string) $opStatus;
        $update = new Update;
        $update->ExeUpdate(self::Tbl, $this->dados, "WHERE op_id = :id", "id={$this->op_id}");
    }
    
    public function ExeUpdate($opId, array $dados) {
        $this->op_id = (int) $opId;
        $this->dados = $dados;
        $this->setDados();
        $this->Update();
    }
    
    public function ExeDelete($opId) {
        $this->op_id = (int) $opId;
        $read = new Read();
        $read->ExeRead(self::Tbl, "WHERE op_id = :delid", "delid={$this->op_id}");
        if(!$read->getResult()){
            $this->result = false;
            $this->error = ["Oooopsss, você tentou remover uma oportunidade que não existe no sistema!", CRM_INFO];
        }else{
            $delete = new Delete();
            $delete->ExeDelete(self::Tbl, "WHERE op_id = :deletaid", "deletaid={$this->op_id}");
            $this->result = true;
            $this->error = ["A oportunidade <b>{$read->getResult()[0]['op_empresa']}</b> foi removida com sucesso do sistema!", CRM_OK];
        }
    }
    
    function getError() {
        return $this->error;
    }

    function getResult() {
        return $this->result;
    }
    
    //PRIVATES
    private function setDados() {
        $this->dados = array_map('strip_tags', $this->dados);
        $this->dados = array_map('trim', $this->dados);
        $this->dados['op_email'] = (Check::Email($this->dados['op_email']) ? $this->dados['op_email']: NULL);
    }
    
    private function Create() {
        $create = new Create();
        $create->ExeCreate(self::Tbl, $this->dados);
        if ($create->getResult()) {
            $this->result = $create->getResult();
            $this->error = ["<b>Sucesso: </b><br>Os dados da oportunidade {$this->dados['op_empresa']} foram cadastrados com sucesso!", CRM_OK];
        }
    }
    
    private function Update() {
        $update = new Update;
        $update->ExeUpdate(self::Tbl, $this->dados, "WHERE op_id = :opid", "opid={$this->op_id}");
        if ($update->getResult()) {
            $this->error = ["A oportunidade <b>{$this->dados['op_empresa']}</b> foi atualizada com sucesso no sistema!", CRM_OK];
            $this->result = true;
        }
    }*/
}
