<?php
	require_once("../connection.php");
	include("../protected.php");
	$pdo = conectar();
	extract($_POST);
	proteger();
	anti_adm();

	$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
	$conteudo = isset($_POST['conteudo']) ? $_POST['conteudo'] : '';
	$materia = isset($_POST['materia']) ? strtolower($_POST['materia']) : '';

	if(isset($_POST['inserir'])){

		$data = date("y/m/d");
		if(strlen($titulo) && strlen($conteudo) && strlen($materia)){
			$query = $pdo->prepare("INSERT INTO conteudos(materia, titulo, conteudo, data) values(?, ?, ?, ?)");
			$query->bindParam(1, $materia);
			$query->bindParam(2, $titulo);
			$query->bindParam(3, $conteudo);
			$query->bindParam(4, $data);
			
			if($query->execute()){
				header("Location: http://localhost/projeto/index.php");
			}

		}else{
			echo "<script>alert('Preecha todos os campos');</script>";
		}
	}

?>
<!DOCTYPE html>

<html lang="pt-br">
<head>

	<title>Escola Virtual - Adicionar Conteudo</title>
	<style type="text/css"><?php include("../assets/css/ucp.css")?></style>
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
						<li><a>Materias</a>
							<ul class="sub-menu">
								<li><a href="http://localhost/projeto/materias/base_tecnica.php">Base Tecnica</a></li>
								<li><a>Base Comum</a></li>
							</ul>
						</li>
							<?php if(isset($_SESSION['level']) && $_SESSION['level'] >= 1) { ?> 
								<li><a id="active">UCP</a>
									<ul class="sub-menu">
										<li><a id="active">Conteúdos</a>
											<ul class="sub-menu-1">
												<li><a id="active" href="http://localhost/projeto/adm/conteudo-adicionar.php">Adicionar</a></li>
												<li><a href="http://localhost/projeto/adm/conteudo-editar.php">Editar</a></li>
											</ul>
										</li>
										<li><a>Usuários</a>
											<ul class="sub-menu-1">
												<li><a href="#">Editar</a></li>
											</ul>
										</li>
										<li><a href="#">Relatórios</a></li>
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
				<div id="adicionar">
					<form action="" method="POST">
						Titulo: <input type="text" name="titulo" placeholder="Digite o titulo" autocomplete="off" value="<?php echo $titulo; ?>"><br>
						Data: <?php echo date("d/m/Y");
							$query = $pdo->prepare("SELECT * FROM materias ORDER by materia");
							$query->execute();
						?>
						<span>Materia: <select name="materia">
							<option value="">Selecione a materia</option>
							<?php while($result = $query->fetch(PDO::FETCH_OBJ)){ ; ?>
								<option value="<?php echo $result->materia; ?>" <?php echo ($materia == $result->materia) ? "selected" : null; ?> ><?php echo strtoupper($result->materia); ?></option>
							<?php }?>
						</select></span>
						<br>
			
						<textarea name="conteudo" placeholder="Digite o seu conteudo aqui"><?php echo $conteudo; ?></textarea>
						<input type="submit" class="botoes" name="inserir" value="INSERIR">
						<input type="reset" class="botoes" value="LIMPAR">
					</form>
				</div>

			</main>
			<footer class="clearfix">ESCOLA VIRTUAL - &copy; 2019 - <?php echo date('Y'); ?></footer>
		</div>
		<?php } ?>
	</body>
</html>

