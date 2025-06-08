<?php
$serverName = "CALCI\\MSSQLSERVER01"; //lagay mo server name mo (makikita sa ms sql pag open)
$database = "water_delivery";

try {
    //Server connection first
    $dsn = "sqlsrv:Server=$serverName;Database=$database;TrustServerCertificate=true";
    
    $pdo = new PDO($dsn, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
} catch (PDOException $e) {
   
    die("Database connection failed: " . $e->getMessage() . "<br>Make sure SQL Server is running and accessible.");
}


function getConnection() {
    global $pdo;
    return $pdo;
}

function closeConnection($conn) {
    //handles connection closing
    return true;
}
?>