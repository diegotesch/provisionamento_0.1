<?php

class AdminOnu {

    private $dados;
    private $lote;
    private $loteId;
    private $loteTipo;
    private $onuId;
    private $modeloConf;
    private $confDest;
    private $modeloEtiq;
    private $etqDest;
    private $dadosOnu;
    private $dadosReg;
    private $result;
    private $error;

    const Lote = 'lote';
    const Onu = 'onu';

    /**
     * checkNumLote
     * Verifica o número do próximo Lote
     * @return int
     */
    public function checkNumLote() {
        $readLote = new Read();
        $readLote->ExeRead(self::Lote);
        if ($readLote->getResult()) {
            $retorno = $readLote->getResult();
            return $retorno[count($retorno) - 1]['lote_id'] + 1;
        } else {
            return 1;
        }
    }
    
    public function regerarClientes($lote_id){
        $upLote = new Update();
        $upLote->ExeUpdate('onu', array("onu_cliente" => null, "onu_status" => 1), "WHERE onu_status <> :ost AND onu_status <> :os AND lote_id = :lid", "ost=0&os=3&lid={$lote_id}");
        if($upLote->getResult()){
            $this->result = true;
            return true;
        }else{
            return false;
        }
    }

    /**
     * cadLote
     * Adiciona uma Onu a um lote em cadastro
     * @param array $dados
     */
    public function addOnuToLote(array $dados, $loteTipo) {
        $this->dados = $dados;
        $this->loteTipo = (string) $loteTipo;
        $this->setDadosOnu();

        if ($this->checkOnuExists()) {
            $this->geraCamposOnu();
            $_SESSION['lote'][count($_SESSION['lote'])] = $this->dados;
            $this->dados = null;
            Redirecionar("?exe=onu/create");
        }
    }
    
    

//OK

    /**
     * cadLote
     * Inicia um novo Lote de Onu's e cadastra cada uma no banco 
     * de dados
     * @param array $lote
     */
    public function cadLote(array $lote, $loteTipo) {
        $this->loteTipo = (string) $loteTipo;
        //echo $this->loteTipo."<br>";
        $this->lote = $lote;
        unset($_SESSION['lote'], $_SESSION['lote_tipo']);
        if ($this->createLote()) {
            if ($this->loteTipo == 'V2801HW') {
                //recupera arquivo de configuração
                $this->getModel(1);
            } else if($this->loteTipo == '28HW'){
                $this->getModel(2);
            } else if($this->loteTipo == "28HWV2"){
                $this->getModel(3);
            }
            
            foreach ($this->lote as $onu) {
                $this->dadosOnu = $onu;
                $this->dadosOnu['lote_id'] = $this->loteId;
                if ($this->loteTipo == 'TPLINK') {
                    $this->dadosOnu['onu_file_conf'] = "modelos/rom-0";
                }else if($this->loteTipo == '1GEZ'){
                    $this->dadosOnu['onu_file_conf'] = "modelos/conf_file_onu_1ge-z.bin";
                }else{
                    $this->dadosOnu['onu_file_conf'] = "conf_files_gerados/ONUMAC" . $this->dadosOnu['onu_mac'] . ".xml";
                }
                
                                
                if ($this->CheckOnuBanco()) {
                    $this->cadOnu();
                }
            }
        } else {
            $error = $this->getError();
            PROVErro($error[0], $error[1]);
        }
    }
    
    public function RegerarConf(array $dados_onu) {
        $this->getModel(1);
        $this->dadosReg = $dados_onu;
        //var_dump($this);
        if(file_exists($this->dadosReg['onu_file_conf'])){
            unlink($this->dadosReg['onu_file_conf']);
            $this->geraCamposOnu($this->dadosReg['onu_mac']);
            $this->geraConf();
            
        }else{
            $this->geraCamposOnu($this->dadosReg['onu_mac']);
            $this->geraConf();
        }
    }

//OK

    /**
     * listLote
     * método responsavel por listar os lotes cadastrados
     * ou listar um lote especificado por seu ID
     * @param type $id_lote
     */
    public function listLote($id_lote = null) {
        $this->loteId = (!is_null($id_lote) ? (int) $id_lote : null);
        $readLote = new Read;
        $readLote->ExeRead(self::Lote, (!is_null($this->loteId) ? "WHERE lote_id = :lote" : null), (!is_null($this->loteId) ? "lote={$this->loteId}" : null));
        if ($readLote->getResult()) {
            $this->result = $readLote->getResult();
        } else {
            if (!is_null($this->loteId)) {
                PROVErro("Lote não esta cadastrado no banco de dados!", MSG_ERRO);
                $this->result = false;
            } else {
                PROVErro("Não existem lotes cadastrados no sistema!", MSG_INFO);
                $this->result = false;
            }
        }
    }

//OK

    /**
     * listOnuPorLote
     * método que lista todas as ONU's
     * cadastradas em um determinado Lote
     * @param type $id_lote
     */
    public function listOnuPorLote($id_lote) {
        $this->loteId = (int) $id_lote;
        $read = new Read;
        $read->ExeRead(self::Onu, "WHERE lote_id = :lote ORDER BY onu_mac ASC", "lote={$this->loteId}");
        if ($read->getResult()) {
            $this->result = $read->getResult();
        } else {
            PROVErro("Não existem ONU's cadastradas para este Lote!", MSG_ERRO);
            $this->result = false;
        }
    }

//OK

    public function listDadosOnu($id_onu) {
        $this->onuId = (int) $id_onu;
        $read = new Read;
        $read->ExeRead(self::Onu, "WHERE onu_id = :onu", "onu={$this->onuId}");
        if ($read->getResult()) {
            $this->result = $read->getResult();
        } else {
            PROVErro("Onu não cadastrada no sistema!", MSG_ERRO);
            $this->result = false;
        }
    }

    public function ativarOnu(array $dados) {
        $this->dados = $dados;
        $this->setDados();
        if ($this->ativar()) {
            $error = $this->getError();
            PROVErro($error[0], $error[1]);
        } else {
            $error = $this->getError();
            PROVErro($error[0], $error[1]);
        }
    }

//OK

    public function desativarOnu($id_onu) {
        $this->onuId = (int) $id_onu;
        $this->dados['onu_cliente'] = null;
        $this->dados['onu_status'] = 1;
        if ($this->desativar()) {
            $error = $this->getError();
            PROVErro($error[0], $error[1]);
        } else {
            $error = $this->getError();
            PROVErro($error[0], $error[1]);
        }
    }

//OK

    /**
     * getResult()
     * retorna os resultados do objeto
     * @return mixed
     */
    public function getResult() {
        return $this->result;
    }

//OK

    /**
     * getError()
     * retorna as mensagens de erro e validações geradas
     * pelo sistema
     * @return type
     */
    public function getError() {
        return $this->error;
    }
    
    public function getTipoLote($lote_id) {
        $this->loteId = (int) $lote_id;
        $this->listLote($this->loteId);
        $res = $this->getResult();
        return $res[0]['lote_tipo'];
    }

//OK
    //PRIVATES
    /**
     * setDadosOnu
     * verifica se os dados estao digitados corretamente
     * gera os campos: SSID, Senha WIFI, usuario e senha PPPoE
     */
    private function setDadosOnu() {
        $this->dados = array_map('strip_tags', $this->dados);
        $this->dados = array_map('strtoupper', $this->dados);
        $this->dados['onu_mac'] = str_replace(':', '', $this->dados['onu_mac']);
    }

//OK

    /**
     * setDados
     * verifica se os campos foram preenchidos corretamente.
     */
    private function setDados() {
        $this->dados = array_map('strip_tags', $this->dados);
        $this->dados = array_map('trim', $this->dados);
        $this->dados = array_map('strtoupper', $this->dados);
    }

//OK

    /**
     * geraCamposOnu()
     * gera os campos a partir do MAC da ONU
     */
    private function geraCamposOnu($mac = null) {
               
        if(!is_null($mac)){
            $this->dados['onu_mac'] = $mac;
        }
        
        $this->dados['onu_ssid'] = "VIAON-" . substr($this->dados['onu_mac'], -4);
        $this->dados['onu_wifi'] = geraSenha(8, true, false, true);
        $this->dados['onu_user_pppoe'] = strtolower("viaonuser" . substr($this->dados['onu_mac'], -6) . date('H') . date('i'));
        $this->dados['onu_senha_pppoe'] = geraSenha(8, false, true, true);
        $this->dados['mac1'] = strtolower(MacPlus($this->dados['onu_mac'], ($this->loteTipo == '28hw' ? 5 : 3)));
        $this->dados['mac2'] = substr(strtolower($this->dados['onu_mac']), 0, 4).':'
                .substr(strtolower($this->dados['onu_mac']), 4, 4).':'
                .substr(strtolower($this->dados['onu_mac']), 8, 4);
           
        //var_dump($this);
    }
    
//OK

    /**
     * checkOnuExists()
     * verifica se a ONU ja esta registrada no lote atual ou
     * no banco de dados. retorna true caso não haja registro 
     * localizado
     * @return boolean
     */
    private function checkOnuExists() {
        if (!$this->CheckOnuLote() || !$this->CheckOnuBanco()) {
            $error = $this->getError();
            PROVErro($error[0], $error[1]);
            Redirecionar("?exe=onu/create&red=false", 4000);
            return false;
        } else {
            return true;
        }
    }

//OK

    /**
     * CheckOnuLote
     * verifica se a ONU esta registrada no Lote
     * atual.
     * retorna true caso não esteja
     * @return boolean
     */
    private function CheckOnuLote() {
        if (!empty($_SESSION['lote'])) {
            foreach ($_SESSION['lote'] as $lote) {
                if ($lote['onu_mac'] == $this->dados['onu_mac']) {
                    $this->error = array("<b>ERRO:</b><br>ONU MAC:{$this->dados['onu_mac']} ja foi adicionada ao lote atual.", MSG_ERRO);
                    $this->result = false;
                    return false;
                } else {
                    $this->result = true;
                    return true;
                }
            }
        } else {
            $this->result = true;
            return true;
        }
    }

//OK

    /**
     * CheckOnuBanco()
     * verifica se a ONU esta cadastrada em outro lote
     * caso não esteja retorna true
     * @return boolean
     */
    private function CheckOnuBanco() {
        $mac = (isset($this->dadosOnu) ? $this->dadosOnu['onu_mac'] : $this->dados['onu_mac']);
        $findOnu = new Read();
        $findOnu->ExeRead(self::Onu, "WHERE onu_mac = :mac", "mac={$mac}");
        if ($findOnu->getResult()) {
            $this->error = array("<b>ERRO:</b><br>ONU MAC:{$mac} ja esta cadastrada em outro lote.<br>MAC clonado", MSG_ERRO);
            $this->result = false;
            return false;
        } else {
            $this->result = true;
            return true;
        }
    }

//OK

    /**
     * createLote()
     * função responsável por cadastrar o "LOTE"
     * no banco de dados
     * @return boolean
     */
    private function createLote() {
        $createLote = new Create();
        $createLote->ExeCreate(self::Lote, array('lote_user' => 'viaon', 'lote_tipo' => $this->loteTipo));
        if ($createLote->getResult()) {
            $this->loteId = $createLote->getResult();
            return true;
        } else {
            $this->error = array("Erro ao criar lote. Verifique e tente novamente!", MSG_ERRO);
            $this->result = false;
            return false;
        }
    }

//OK

    /**
     * getModel
     * Função responsável por obter o modelo dos arquivos
     * 1 - Modelo de Arquivo de Configuração
     * 2 - Modelo de Arquivo de Etiqueta
     * @param int $modelo
     */
    private function getModel($modelo) {
        $id = (int) $modelo;
        //echo $modelo." - ".$id."<br>";
        
        switch($id){
            case 1:
                if (file_exists('modelos/prov.xml')) {
                    $fp = fopen('modelos/prov.xml', 'r');
                    $this->modeloConf = fread($fp, filesize('modelos/prov.xml'));
                    fclose($fp);
                } else {
                    PROVErro("arquivo de Modelo modelos/prov.xml não existe", MSG_ALERTA, true);
                }
                break;
            case 2:
                if (file_exists('modelos/prov2.xml')) {
                    $fp = fopen('modelos/prov2.xml', 'r');
                    $this->modeloConf = fread($fp, filesize('modelos/prov2.xml'));
                    fclose($fp);
                } else {
                    PROVErro("arquivo de Modelo modelos/prov2.xml não existe", MSG_ALERTA, true);
                }
                break;
            case 3:
                if (file_exists('modelos/prov3.xml')) {
                    $fp = fopen('modelos/prov3.xml', 'r');
                    $this->modeloConf = fread($fp, filesize('modelos/prov3.xml'));
                    fclose($fp);
                } else {
                    PROVErro("arquivo de Modelo modelos/prov3.xml não existe", MSG_ALERTA, true);
                }
                break;
            default :
                //PROVErro("Digite um número de modelo válido! Modelo selecionado = {$id}", MSG_ERRO, true);
                break;
        }
        
    }
    
    private function getPrn($modelo) {
        $idPrn = (int) $modelo;
        if ($idPrn == 1) {
            if (file_exists('modelos/etq_modelo.prn')) {
                $fp = fopen('modelos/etq_modelo.prn', 'r');
                $this->modeloEtiq = fread($fp, filesize('modelos/etq_modelo.prn'));
                fclose($fp);
            } else {
                PROVErro("arquivo de Modelo modelos/etiqueta.prn não existe", MSG_ALERTA, true);
            }   
        }else if($idPrn == 2){
            if (file_exists('modelos/etq_modelo2.prn')) {
                $fp = fopen('modelos/etq_modelo2.prn', 'r');
                $this->modeloEtiq = fread($fp, filesize('modelos/etq_modelo2.prn'));
                fclose($fp);
            } else {
                PROVErro("arquivo de Modelo modelos/etiqueta.prn não existe", MSG_ALERTA, true);
            }   
        }
    }
    
    public function getModeloOnu($id_lote) {
        $this->loteId = (int) $id_lote;
        $this->listLote($id_lote);
        $res = $this->getResult();
        if($res[0]['lote_tipo'] == 'V2801HW'){
            return 1;
        }else if($res[0]['lote_tipo'] == '28HW'){
            return 2;
        }else if($res[0]['lote_tipo'] == '28HWV2'){
            return 3;
        }else if($res[0]['lote_tipo'] == '1GEZ'){
            return 4;
        }else{
            return false;
        }
        
    }
    
    public function geraEtiquetaPrn(array $dados) {
        $this->dados = $dados;
        foreach($this->dados as $c => $v){
            $this->listDadosOnu($v);
            $res = $this->getResult();
            $this->dadosOnu[$c] = $res[0];
        }
        $this->dados = null;
                
        if(file_exists('etiquetas_geradas/print_etiquetas.prn')){
            unlink('etiquetas_geradas/print_etiquetas.prn');
        }
        
        if($this->getModeloOnu($res[0]['lote_id']) == 4 ){
            $this->getPrn(2);
        }else{
            $this->getPrn(1);
        }
        /*if($this->getResult() == 'TPLINK'){
            $this->getPrn(2);
            if($fp = fopen('etiquetas_geradas/print_etiquetas.prn', 'w')){
                $model = '';
                $main = '';
                foreach ($this->dadosOnu as $indice => $onu){
                    $model = $this->modeloEtiq;
                    $main .= $model;
                }
                fwrite($fp, $main);
                fclose($fp);
                if(shell_exec('copy etiquetas_geradas\print_etiquetas.prn lpt1')){
                    //imprime e volta para a pagina principal
                    unlink('etiquetas_geradas/print_etiquetas.prn');
                    Redirecionar('index.php');
                }
            }
        }else */
        //if($this->getResult() == 'V2801HW' || $this->getResult() == 'TPLINK'){
            
            if($fp = fopen('etiquetas_geradas/print_etiquetas.prn', 'w')){
                $model = '';
                $main = '';
                foreach ($this->dadosOnu as $indice => $onu){
                    $model = str_replace('{REDEWIFI}', $onu['onu_ssid'], $this->modeloEtiq);
                    $model = str_replace('{SENHAWIFI}', $onu['onu_wifi'], $model);
                    $main .= $model;
                }
                fwrite($fp, $main);
                fclose($fp);
                if(shell_exec('copy etiquetas_geradas\print_etiquetas.prn lpt1')){
                    //imprime e volta para a pagina principal
                    unlink('etiquetas_geradas/print_etiquetas.prn');
                    Redirecionar('index.php');
                }
            }
        //}
        
        
        
        
        //$this->modeloEtiq = str_replace('{REDEWIFI}', $dados, $subject)*/
    }
    
    public function gerarArquivosDeConfiguracao(array $onu) {
        $this->dados = $onu;
        $this->loteTipo = $this->getModeloOnu($this->dados['lote_id']);
        $this->getModel($this->loteTipo);
        
        
        $this->dados['mac1'] = strtolower(MacPlus($this->dados['onu_mac'], ($this->loteTipo == 2 ? 5 : 3)));
        $this->dados['mac2'] = substr(strtolower($this->dados['onu_mac']), 0, 4).':'
                .substr(strtolower($this->dados['onu_mac']), 4, 4).':'
                .substr(strtolower($this->dados['onu_mac']), 8, 4);
        
        $this->geraConf();
        //var_dump($this);
    }
    
    public function ajustarConf(array $onu){
        foreach($onu as $c=>$v){
            $this->dados[$c] = $v;
        }
        
        $this->loteTipo = $this->getModeloOnu($this->dados['lote_id']);
        $this->getModel($this->loteTipo);
        
        $this->dados['mac1'] = strtolower(MacPlus($this->dados['onu_mac'], ($this->loteTipo == 2 ? 5 : 3)));
        $this->dados['mac2'] = substr(strtolower($this->dados['onu_mac']), 0, 4).':'
                .substr(strtolower($this->dados['onu_mac']), 4, 4).':'
                .substr(strtolower($this->dados['onu_mac']), 8, 4);
        
        if($this->geraConf()){
            return true;
        }else{
            echo "erro<br>";
        }
        
        //var_dump($this);
    }


    /**
     * cadOnu()
     * método responsável por cadastrar cada ONU
     * no banco de dados
     */
    private function cadOnu() {
        //var_dump($this);
        unset($this->dadosOnu['mac1']);
        unset($this->dadosOnu['mac2']);
        $cadOnu = new Create();
        $cadOnu->ExeCreate(self::Onu, $this->dadosOnu);
        if ($cadOnu->getResult()) {
            $this->result = $this->loteId;
            return true;
        } else {
            PROVErro("ERRO AO CADASTRAR ONU", MSG_ERRO, true);
        }
    }

    /**
     * geraConf()
     * método responsável por gerar o Arquivo de Configuração
     * para cada ONU. Realizando a substituição de cada campo
     * necessário
     * @return boolean
     */
    private function geraConf() {
        

        $this->modeloConf = str_replace("ALTERSSID", $this->dados['onu_ssid'], $this->modeloConf);
        $this->modeloConf = str_replace("ALTERSENHA", $this->dados['onu_wifi'], $this->modeloConf);
        $this->modeloConf = str_replace("ALTERPPPOEUSER", $this->dados['onu_user_pppoe'], $this->modeloConf);
        $this->modeloConf = str_replace("ALTERPPPOESENHA", $this->dados['onu_senha_pppoe'], $this->modeloConf);
        $this->modeloConf = str_replace("ONUMAC", $this->dados['mac1'], $this->modeloConf);
        $this->modeloConf = str_replace("ONU_MAC", $this->dados['mac2'], $this->modeloConf);
        
        //verifica se o arquivo de configuração existe.
        //se positivo exclui e cria um novo
        if (file_exists($this->dados['onu_file_conf'])) {
            unlink($this->dados['onu_file_conf']);
            //var_dump($this);
        }
        
        if ($fp = fopen($this->dados['onu_file_conf'], 'w')) {
            fwrite($fp, $this->modeloConf);
            fclose($fp);
            return true;
        }else{
            return false;
        }
    }

//OK

    private function ativar() {
        $update = new Update;
        $update->ExeUpdate(self::Onu, $this->dados, "WHERE onu_id = :onu", "onu={$this->dados['onu_id']}");
        if ($update->getResult()) {
            $this->error = array("Onu alocada com sucesso para o cliente {$this->dados['onu_cliente']}", MSG_OK);
            $this->result = $update->getResult();
            return true;
        } else {
            $this->error = array("Erro ao alocar ONU!", MSG_INFO);
            $this->result = false;
            return false;
        }
    }

//OK

    private function desativar() {
        $des = new Update();
        $des->ExeUpdate(self::Onu, $this->dados, "WHERE onu_id = :onu", "onu={$this->onuId}");
        if ($des->getResult()) {
            $this->error = array("Onu retornou para o estoque", MSG_OK);
            $this->result = $des->getResult();
            return true;
        } else {
            $this->error = array("Erro ao desalocar ONU!", MSG_ALERTA);
            $this->result = false;
            return false;
        }
    }
    
    

}
