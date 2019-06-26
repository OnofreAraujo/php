<?php

	function conectar(){
	 	try{
	 		$pdo = new PDO('mysql:host=127.0.0.1;dbname=projeto;charset=utf8;port=3307', 'root', 'usbw');
	 	}catch(PDOException $e){

	 	}
	 	return $pdo;
	 }
	 date_default_timezone_set('America/Sao_Paulo');

 ?>
