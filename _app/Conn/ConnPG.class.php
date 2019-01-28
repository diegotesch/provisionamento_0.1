<?php

/**
 * Conn.class [ CONEXÃO ]
 * Classe Abstrata de conexão. Padrão SingleTon.
 * Retorna um objeto PDO pelo método estático getConn();
 * 
 * @copyright (c) 2016, Diego Tesch
 */
class ConnPG {

    private static $host = VIAON_HOST;
    private static $user = VIAON_USER;
    private static $pass = VIAON_PASS;
    private static $db = VIAON_DB;   

    /** @var PDO  */
    private static $connect = null;

    /**
     * Conecta com o banco de dados com o padrão Singleton
     * retorna um objeto PDO
     */
    private static function Conectar() {
        try {
            if (self::$connect == null) {
                $dsn = 'pgsql:host=' . self::$host . ';port=5432;dbname=' . self::$db . ';user=' . self::$user . ';password=' . self::$pass;
                //$options = array(PDO::PG => 'SET NAMES UTF8');
                //self::$connect = new PDO($dsn, self::$user, self::$pass, $options);
                self::$connect = new PDO($dsn);
                self::$connect->exec("SET CLIENT_ENCODING TO 'utf8'");
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
