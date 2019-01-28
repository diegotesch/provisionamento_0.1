<?php

/**
 * <b>Classe View</b>
 * [HELPER MVC]
 * Classe responsavel por carregar o template, povoar e exibir a view, povoar e incluir arquivos PHP no sistema
 * Arquitetura MVC
 * @copyright (c) 2016, Diego Tesch 
 */
class View {
    private static $data;
    private static $keys;
    private static $values;
    private static $template;
    
    public static function Load($template) {
        self::$template = (string) $template;
        self::$template = file_get_contents(self::$template . '.tpl.html');
    }
    
    public static function Show(array $data) {
        self::setKeys($data);
        self::setValues();
        self::ShowView();
    }
    
    public static function Request($file, array $data) {
        extract($data);
        require("{$file}.inc.php");
    }
    
    private static function setKeys($data) {
        self::$data = $data;
        self::$keys = explode('&', '#'.implode('#&#', array_keys(self::$data)).'#');
    }
    
    private static function setValues() {
        self::$values = array_values(self::$data);
    }
    
    private static function ShowView() {
        echo str_replace(self::$keys, self::$values, self::$template);
    }
}
