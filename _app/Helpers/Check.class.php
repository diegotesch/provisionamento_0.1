<?php

/**
 * <b>Classe Check</b>
 * [HELPER]
 * Classe responsavel por manipular e validar dados do sistema!
 * @copyright (c) 2016, Diego Tesch 
 */
class Check {

    private static $data;
    private static $format;
    private static $id;

    public static function Email($email) {
        self::$data = (string) $email;
        self::$format = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';

        if (preg_match(self::$format, self::$data)) {
            return true;
        } else {
            return false;
        }
    }

    public static function Name($name) {
        self::$format = array();
        self::$format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        self::$data = strtr(utf8_decode($name), utf8_decode(self::$format['a']), self::$format['b']);
        self::$data = strip_tags((trim(self::$data)));
        self::$data = str_replace(' ', '-', self::$data);
        self::$data = str_replace(array('-----', '----', '---', '--'), '-', self::$data);

        return strtolower(utf8_encode(self::$data));
    }
    
    public static function Usuario() {
        self::$id = $_SESSION['userlogin']['user_id'];
        $read = new Read;
        $read->ExeRead("crm_user", "WHERE user_id = :ud", "ud=".self::$id);
        if($read->getResult()){
            self::$data = $read->getResult()[0]['user_name'].' '.$read->getResult()[0]['user_lastname'];
        }else{
            self::$data = false;
        }
        return self::$data;
    }
    
    public static function TipoContrato($tipo){
        self::$data = strtolower(strip_tags((trim($tipo))));
        return self::$data;
    }

    public static function Date($date) {
        self::$format = explode(' ', $date);
        self::$data = explode('/', self::$format[0]);

        if (empty(self::$format[1])) {
            self::$format[1] = date('H:i:s');
        }

        self::$data = self::$data[2] . '-' . self::$data[1] . '-' . self::$data[0].' '.self::$format[1];
        return self::$data;
    }
    
    public static function Words($string, $limite, $pointer = null) {
        self::$data = strip_tags(trim($string));
        self::$format = (int) $limite;
        
        $ArrWords = explode(' ', self::$data);//separa as palavras
        $NumWords = count($ArrWords);
        $NewWords = implode(' ', array_slice($ArrWords, 0, self::$format));
        $pointer = (empty($pointer) ? '...' : ' '.$pointer); 
        
        $result = (self::$format < $NumWords ? $NewWords.$pointer : self::$data);
        return $result;
    }
    
    public static function CatByName($NomeCategoria) {
        $read = new Read;
        $read->ExeRead('ws_categories', "WHERE category_name = :name", "name={$NomeCategoria}");
        if($read->getRowCount()){
            return $read->getResult()[0]['category_id'];
        }else{
            echo "A categoria {$NomeCategoria} não foi encontrada!";
            die;
        }
    }
    
    //ws_siteviews_online
    public static function UserOnline() {
        $now = date('Y-m-d H:i:s');
        $deleteUserOffline = new Delete;
        $deleteUserOffline->ExeDelete('ws_siteviews_online', "WHERE online_endview < :now", "now={$now}");
        
        $readUserOnline = new Read;
        $readUserOnline->ExeRead('ws_siteviews_online');
        return $readUserOnline->getRowCount();
    }
    
    public static function Image($ImageUrl, $ImageDesc, $ImageW = null, $ImageH = null) {
        self::$data = $ImageUrl;
        if(file_exists(self::$data) && !is_dir(self::$data)){
            $path = HOME;
            $imagem = self::$data;
            return "<img src=\"{$path}/tim.php?src={$path}/{$imagem}&w={$ImageW}&h={$ImageH}\" alt=\"{$ImageDesc}\" title=\"{$ImageDesc}\" />";
        }else{
            return false;
        }
    }

}
