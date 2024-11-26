<?php
session_start();


if(!isset($_SESSION['role'])){
    header('location: usuario/index.php');
    exit;
}

if($_SESSION['role'] === "admin") {
    header('location: admin/index.php');
    exit;
}else if ($_SESSION['role'] === "cliente"){
    header('location: usuario/index.php');
    exit;
}else {
    echo "Erro: Tipo de usuario desconhecido";
    exit;
}
