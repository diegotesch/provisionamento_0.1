<?php

/**
 * Conn.class [ CONEXÃO ]
 * Classe Abstrata de conexão. Padrão SingleTon.
 * Retorna um objeto PDO pelo método estático getConn();
 * 
 * @copyright (c) 2016, Diego Tesch
 */
class Conn {

    private static $host = HOST;
    private static $user = USER;
    private static $pass = PASS;
    private static $db = DB;   

    /** @var PDO  */
    private static $connect = null;

    /**
     * Conecta com o banco de dados com o padrão Singleton
     * retorna um objeto PDO
     */
    private static function Conectar() {
        try {
            if (self::$connect == null) {
                $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$db;
                $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
                self::$connect = new PDO($dsn, self::$user, self::$pass, $options);
            }
        } catch (PDOException $e) {
            PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getFile());
            die;
        }
        
        self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$connect;
    }

    /** Retorna um Objeto PDO com padrão Singleton */
    public static function getConn() {
        return self::Conectar();
    }

}
