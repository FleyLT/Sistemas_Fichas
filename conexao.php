<?php 

$db = "ficha_sistema";
$host = "localhost";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);

if ($conne->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
};


?>