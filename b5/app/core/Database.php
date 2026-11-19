<?php
class Database
{
    private static ?PDO $instance = null;
    public static int $queryCount = 0;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                error_log('Loi ket noi CSDL: ' . $e->getMessage());
                http_response_code(500);
                die('Không thể kết nối cơ sở dữ liệu. Hãy kiểm tra config/config.php và database.');
            }
        }

        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
}
