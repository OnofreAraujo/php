<?php

	if(!function_exists("proteger")){
		function proteger(){
			if(!isset($_SESSION)) session_start();
			if(!isset($_SESSION['logado'])){
				header("Location: /projeto/index.php");
			}
		}
	}

	if(!function_exists("anti_adm")){
		function anti_adm(){
			if(!isset($_SESSION['level']) && $_SESSION['level'] < 1){
				header("Location: index.php");
			}
		}
	}
?>