<?php
 class connectDB
 {
     private static $host = "localhost";
     private static $username = "root";
     private static $password = "";
     private static $dbname = "68pm34";
     private static $conn;

     public static function Connect()
     {
         // Tạo kết nối
         if (!self::$conn) {
            try {
               self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname, self::$username, self::$password);
               self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $e) {
               echo "loi ket noi: " . $e->getMessage();
               return null;
            }
         }
         return self::$conn;
     }

     public function __destruct()
     {
         // Đóng kết nối khi đối tượng bị hủy
         if (self::$conn) {
             self::$conn = null;
         }
     }
 }