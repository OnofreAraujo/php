<?php
	require_once("../connection.php");
	include("../protected.php");
	$pdo = conectar();
	extract($_POST);
	proteger();
	anti_adm();
	if(isset($_POST['inserir'])){
		$titulo = $_POST['titulo'];
		$conteudo = $_POST['conteudo'];
		$materia = strtolower($_POST['materia']);

		$data = date("y/m/d");
		if(strlen($titulo) && strlen($conteudo)){
			$query = $pdo->prepare("INSERT INTO conteudos(materia, titulo, conteudo, data) values(?, ?, ?, ?)");
			$query->bindParam(1, $materia);
			$query->bindParam(2, $titulo);
			$query->bindParam(3, $conteudo);
			$query->bindParam(4, $data);
			
			if($query->execute()){
				header("Location: http://localhost/projeto/index.php");
			}

		}
	}
?>
<!DOCTYPE html>

<html lang="pt-br">
<head>

	<title>Escola Virtual - Base Tenica</title>
	<meta name="author" content="Onofre Araujo" />
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0" />

	<!-- CSS -->
	<link href="http://localhost/projeto/assets/css/style.css" rel="stylesheet" type="text/css"/>

	<!--JAVASCRIPT-->

	<script src="http://localhost/projeto/assets/js/jquery.js" type="text/javascript"></script>
	<script src="http://localhost/projeto/assets/js/functios.js" type="text/javascript"></script>
	<script src="http://localhost/projeto/assets/js/slider-bar.js"></script>
</head>
<body>

	<?php if(isset($_SESSION['logado'])) { ?>
	<div id="wrapper">
		<header>

			<section class="devsite-header-top">
				
				<nav class="nav-header-top clearfix">
					<a id="logo" href="http://localhost/projeto/">ESCOLA VIRTUAL</a>
					<ul id="menu">
						<li><a href="http://localhost/projeto"> Home</a></li>
						<li><a id="active">Materias</a>
							<ul class="sub-menu">
								<li><a id="active" href="http://localhost/projeto/materias/base_tecnica.php">Base Tecnica</a></li>
								<li><a>Base Comum</a></li>
							</ul>
						</li>
							<?php if(isset($_SESSION['level']) && $_SESSION['level'] >= 1) { ?> 
								<li><a>UCP</a>
									<ul class="sub-menu">
										<li><a>Conteúdos</a>
											<ul class="sub-menu-1">
												<li><a  href="http://localhost/projeto/adm/conteudo-adicionar.php">Adicionar</a></li>
												<li><a href="http://localhost/projeto/adm/conteudo-editar.php">Editar</a></li>
											</ul>
										</li>
										<li><a>Usuários</a>
											<ul class="sub-menu-1">
												<li><a href="#">Editar</a></li>
											</ul>
										</li>
										<li><a href="#ios">Relatórios</a></li>
									</ul>
								</li><?php } ?>
							<li><a href="#">Sobre</a></li>
							<li><a href="http://localhost/projeto/logout.php?token=<?php echo md5(session_id())?>" onclick="return confirm('Você realmente deseja sair?')">sair</a></li>
						</ul>

						<div class="header-bar-search clearfix">
							<input type="text" id="inputSearch" placeholder="Digite o que deseja pesquisar" />
							<button type="submit" id="btnSearch"></button>
						</div>
						<!--<div id="test"></div>-->

					</nav>
				</section>

			</header>

			<main>
				<?php 
					if(!isset($_GET['id'])) header('Location: http://localhost/projeto/materias/base_tecnica.php?id=1');
					
					$pagina = isset($_GET['id']) ? $_GET['id'] : null;
					
					$maximo = 5;

					$inicio = ($maximo * $pagina) - $maximo;
					$query = $pdo->prepare("SELECT count(*) FROM conteudos");
					$query->execute();
					$total = $query->fetchColumn();


					$sql = $pdo->prepare("SELECT * FROM conteudos LIMIT ?, ?");
					$sql->bindParam(1, $inicio, PDO::PARAM_INT);
					$sql->bindParam(2, $maximo, PDO::PARAM_INT);
					$sql->execute();

					if($total > 0){

					while($result = $sql->fetch(PDO::FETCH_OBJ)){
				?>
					<div class="main-row row-color">
						<h1><?php echo $result->titulo; ?></h1>
						<div class="content">
							<?php echo $result->conteudo; ?>
						</div>
					</div>

				<?php }

				 ?>
				<div class="main-row">
					<div class="paginacao">
						<?php 
							$anterior = $pagina - 1;
							$proximo = $pagina + 1;
							$pgs = ceil($total / $maximo);
							if($anterior > 0) echo "<a class='controle' href=". $_SERVER['PHP_SELF']."?id=".$anterior . "><</a>";
							for($i = 1; $i <= $pgs; $i++){
								if($i != $pagina){
									echo "<a href=". $_SERVER['PHP_SELF'] . "?id=".($i) . "> $i </a>";

								}else{echo "<strong> $i </strong>";}
							}
							if($proximo <= $pgs) echo "<a class='controle' href=". $_SERVER['PHP_SELF']."?id=".$proximo . ">></a>";
						?>
					</div>
				</div>
				<?php } else{ ?>
										<div class="main-row row-color">
							<h1>NÃO EXISTE NENHUM CONTEÚDO POR ENQUANTO...</h1>
						</div>
				<?php }?>				 
			</main>
			<footer class="clearfix"> ESCOLA VIRTUAL - &copy; 2019 - <?php echo date('Y'); ?></footer>
		</div>
		<?php } ?>
	</body>
</html>

