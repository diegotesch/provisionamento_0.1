<?php

/**
 * <b>Classe Session</b>
 * [HELPER]
 * Classe responsável pelas estatisticas, sessões e atualizações de trafego do sistema
 * @copyright (c) 2016, Diego Tesch 
 */
class Session {

    private $date;
    private $cache;
    private $traffic;
    private $browser;

    public function __construct($cache = null) {
        session_start();
        $this->CheckSession($cache);
    }

    //Verifica e executa todos os métodos da classe!
    private function CheckSession($cache = null) {
        $this->date = date('Y-m-d');
        $this->cache = ((int) $cache ? $cache : 20 );

        if (empty($_SESSION['useronline'])) {
            $this->setTraffic();
            $this->setSession();
            $this->CheckBrowser();
            $this->setUsuario();
            $this->BrowserUpdate();
        } else {
            $this->TrafficUpdate();
            $this->sessionUpdate();
            $this->CheckBrowser();
            $this->UsuarioUpdate();
        }

        $this->date = null;
    }

    //Inicia a sessao do usuario
    private function setSession() {
        $_SESSION['useronline'] = [
            "online_session" => session_id(),
            "online_startview" => date('Y-m-d H:i:s'),
            "online_endview" => date('Y-m-d H:i:s', strtotime("+{$this->cache}minutes")),
            "online_ip" => filter_input(INPUT_SERVER, "REMOTE_ADDR", FILTER_VALIDATE_IP),
            "online_url" => filter_input(INPUT_SERVER, "REQUEST_URI", FILTER_DEFAULT),
            "online_agent" => filter_input(INPUT_SERVER, "HTTP_USER_AGENT", FILTER_DEFAULT)
        ];
    }

    //Atualiza sessao do usuário
    private function sessionUpdate() {
        $_SESSION['useronline']['online_endview'] = date('Y-m-d H:i:s', strtotime("+{$this->cache}minutes"));
        $_SESSION['useronline']['online_url'] = filter_input(INPUT_SERVER, "REQUEST_URI", FILTER_DEFAULT);
    }

    //Verifica e insere o trafego na tabela
    private function setTraffic() {
        $this->getTraffic();
        if (!$this->traffic) {
            $ArrSiteViews = [
                'siteviews_date' => $this->date,
                'siteviews_users' => 1,
                'siteviews_views' => 1,
                'siteviews_pages' => 1
            ];
            $createSiteViews = new Create;
            $createSiteViews->ExeCreate('ws_siteviews', $ArrSiteViews);
        } else {
            if (!$this->getCookie()) {
                $ArrSiteViews = [
                    'siteviews_users' => $this->traffic['siteviews_users'] + 1,
                    'siteviews_views' => $this->traffic['siteviews_views'] + 1,
                    'siteviews_pages' => $this->traffic['siteviews_pages'] + 1
                ];
            } else {
                $ArrSiteViews = [
                    'siteviews_views' => $this->traffic['siteviews_views'] + 1,
                    'siteviews_pages' => $this->traffic['siteviews_pages'] + 1
                ];
            }

            $updateSiteViews = new Update;
            $updateSiteViews->ExeUpdate('ws_siteviews', $ArrSiteViews, " WHERE siteviews_date = :date", "date={$this->date}");
        }
    }

    //Verifica e atualiza os pageviews
    private function TrafficUpdate() {
        $this->getTraffic();
        $ArrSiteViews = [
            'siteviews_pages' => $this->traffic['siteviews_pages'] + 1
        ];
        $updatePageViews = new Update;
        $updatePageViews->ExeUpdate('ws_siteviews', $ArrSiteViews, " WHERE siteviews_date = :date", "date={$this->date}");
        
        $this->traffic = null;
    }

    //Obtem dados da tabela {HELPER TRAFFIC]
    //ws_siteviews
    private function getTraffic() {
        $readSiteViews = new Read;
        $readSiteViews->ExeRead('ws_siteviews', "WHERE siteviews_date = :date", "date={$this->date}");
        if ($readSiteViews->getRowCount()) {
            $this->traffic = $readSiteViews->getResult()[0];
        }
    }

    //Verifica, cria e atualiza o cookie do usuario [HELPER TRAFFIC]
    private function getCookie() {
        $cookie = filter_input(INPUT_COOKIE, 'useronline', FILTER_DEFAULT);
        setcookie("useronline", base64_encode("teste"), time() + 86400);
        if (!$cookie) {
            return false;
        } else {
            return true;
        }
    }

    //Identifica navegador do usuário
    private function CheckBrowser() {
        $this->browser = $_SESSION['useronline']['online_agent'];
        if(strpos($this->browser, 'Chrome')){
            $this->browser = 'Chrome';
        }elseif(strpos($this->browser, 'Firefox')){
            $this->browser = 'Firefox';
        }elseif(strpos($this->browser, 'MSIE') || strpos($this->browser, 'Trident/')){
            $this->browser = 'Internet Explorer';
        }else{
            $this->browser = 'Outros';
        }
    }
    
    //Atualiza tabela com dados de navegadores
    private function BrowserUpdate() {
        $readAgent = new Read;
        $readAgent->ExeRead('ws_siteviews_agent', "WHERE agent_name = :agent", "agent={$this->browser}");
        if(!$readAgent->getResult()){
            $arrAgent = [
                'agent_name' => $this->browser,
                'agent_views' => 1
            ];
            $createAgent = new Create;
            $createAgent->ExeCreate('ws_siteviews_agent', $arrAgent);
        }else{
            $arrAgent = [
                'agent_views' => $readAgent->getResult()[0]['agent_views'] + 1
            ];
            $updateAgent = new Update;
            $updateAgent->ExeUpdate('ws_siteviews_agent', $arrAgent, "WHERE agent_name = :name", "name={$this->browser}");
        }
    }
    
    //Cadastra usuario online na tabela
    private function setUsuario() {
        $sesOnline = $_SESSION['useronline'];
        $sesOnline['online_agent'] = $this->browser;
        $userCreate = new Create;
        $userCreate->ExeCreate('ws_siteviews_online', $sesOnline);
    }
    
    //Atualiza navegação do usuario online
    private function UsuarioUpdate() {
        $arrOnline = [
            'online_endview' => $_SESSION['useronline']['online_endview'],
            'online_url' => $_SESSION['useronline']['online_url']
        ];
        $userUpdate = new Update;
        $userUpdate->ExeUpdate('ws_siteviews_online', $arrOnline, "WHERE online_session = :session", "session={$_SESSION['useronline']['online_session']}");
        
        if(!$userUpdate->getRowCount()){
            $readSes = new Read;
            $readSes->ExeRead('ws_siteviews_online', "WHERE online_session = :session", "session={$_SESSION['useronline']['online_session']}");
            if(!$readSes->getRowCount()){
                $this->setUsuario();
            }
        }
        var_dump($arrOnline);
    }
    
}
