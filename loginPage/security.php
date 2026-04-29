<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['permissao'] !== 'admin') {
    header("Location: index.html");
    exit;
	//die ("Acesso não autorizado");
}
?>