<?php

$conn = new mysqli("localhost","root","","login");

if($conn->connect_error){
    die("Erro de conexão");
}

?>