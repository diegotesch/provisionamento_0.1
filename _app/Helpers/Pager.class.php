<?php

/**
 * <b>Classe Pager</b>
 * [HELPER]
 * Classe responsavel por gerir e paginar os resultados do sistema!
 * @copyright (c) 2016, Diego Tesch 
 */
class Pager {

    /** DEFINE O PAGER  */
    private $page;
    private $limite;
    private $offset;

    /** REALIZA A LEITURA  */
    private $tabela;
    private $termos;
    private $places;

    /** DEFINE O PAGINADOR  */
    private $rows;
    private $link;
    private $MaxLinks;
    private $First;
    private $Last;

    /** RENDERIZA O PAGINADOR  */
    private $paginator;

    public function __construct($link, $First = null, $Last = null, $MaxLinks = null) {
        $this->link = (string) $link;
        $this->MaxLinks = ((int) $MaxLinks ? $MaxLinks : 5);
        $this->First = ((string) $First ? $First : "Primeira Página");
        $this->Last = ((string) $Last ? $Last : "Última Página");
    }

    public function ExePager($page, $limite) {
        $this->page = ((int) $page ? $page : 1);
        $this->limite = (int) $limite;
        $this->offset = ($this->page * $this->limite) - $this->limite;
    }

    public function ReturnPage() {
        if ($this->page > 1) {
            $nPage = $this->page - 1;
            header("Location: {$this->link}{$nPage}");
        }
    }

    public function getPage() {
        return $this->page;
    }

    public function getLimite() {
        return $this->limite;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function ExePaginator($tabela, $termos = null, $ParseString = null) {
        $this->tabela = (string) $tabela;
        $this->termos = (string) $termos;
        $this->places = (string) $ParseString;
        $this->getSyntax();
    }

    public function getPaginator() {
        return $this->paginator;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    private function getSyntax() {
        $read = new Read;
        $read->ExeRead($this->tabela, $this->termos, $this->places);
        $this->rows = $read->getRowCount();

        if ($this->rows > $this->limite) {
            $paginas = ceil($this->rows / $this->limite);
            $MaxLinks = $this->MaxLinks;

            $this->paginator = "<ul class=\"paginator\">";
            $this->paginator .= "<li><a title=\"{$this->First}\" href=\"{$this->link}1\">{$this->First}</a></li>";
            
            for ($iPag = $this->page - $MaxLinks; $iPag <= $this->page - 1; $iPag++) {
                if($iPag >= 1){
                    $this->paginator .= "<li><a title=\"Página {$iPag}\" href=\"{$this->link}{$iPag}\">{$iPag}</a></li>";
                }
            }
            
            $this->paginator .= "<li><span class=\"active\">{$this->page}</span></li>";
            
            for ($dPag = $this->page + 1; $dPag <= $this->page + $MaxLinks; $dPag++) {
                if($dPag <= $paginas){
                    $this->paginator .= "<li><a title=\"Página {$dPag}\" href=\"{$this->link}{$dPag}\">{$dPag}</a></li>";
                }
            }
            
            $this->paginator .= "<li><a title=\"{$this->Last}\" href=\"{$this->link}{$paginas}\">{$this->Last}</a></li>";
            $this->paginator .= "</ul>";
        }
    }

}
