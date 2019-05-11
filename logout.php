<?php
	if(!isset($_SESSION)) session_start();
	$token = md5(session_id());
	if(isset($_GET['token']) && $_GET['token'] === $token && isset($_SESSION['logado'])){
		session_destroy();
		header("Location: index.php");
		exit();
	}else{
		header("Location: paginaoff.php");
	}
?>