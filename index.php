<!DOCTYPE html>

<?php
	require_once("connection.php");
	
	include("security.php");
	include("protected.php");
	$pdo=conectar();


	if(!isset($_SESSION)) session_start();
	if(!isset($_SESSION['logado'])){
		$user = anti(isset($_POST['user']) ? $_POST['user'] : '');
		$pass = anti(isset($_POST['pass']) ? $_POST['pass'] : '');
		if(isset($_POST['login'])){
			if(empty($user)) $errorEmail = 'Digite um email ou usuario';
			if(empty($pass)) $errorPass = "Digite a sua senha";

			if(!empty($user) && !empty($pass)){
				$verificar = $pdo->prepare("SELECT * FROM usuarios WHERE BINARY nome=? AND BINARY senha=md5(?) LIMIT 1");
				$verificar->bindParam(1, $user);
				$verificar->bindParam(2, $pass);
				$verificar->execute();

				if($verificar->rowCount()){
				
					$resultado = $verificar->fetch(PDO::FETCH_OBJ);
					$_SESSION['level'] = $resultado->privilegios;
				
					$_SESSION['logado'] = true;
					header("Location: index.php");
				}else{
					
					$errorNot = "Email ou senha incorreto";

				}

			}

		}
	}

?>
<html lang="pt-br">
<head>
	<title>Escola Virtual - Home</title>
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
	<?php if(!isset($_SESSION['logado'])) {?>
		<div id="modal">

			<div class="telaLogin">
				<div class="imagem"></div>
				<form action="" method="POST">
					<span>Faça seu login</span><br>
					<input type="text" name="user" placeholder="Email ou usuario" autocomplete="off" value="<?php if(isset($user)) echo $user?>"><br>

					<?php if(!empty($errorEmail)) {?><div class="error"> <?php echo $errorEmail ?></div><?php } ?>
					<input type="password" name="pass" placeholder="Senha"><br>

					<?php if(!empty($errorPass)) { ?><div class="error"><?php echo $errorPass ?></div><?php } ?>
					<input type="submit" name="login" value="Fazer Login" /><br>
					<?php if(!empty($errorNot)) {?><div class="error" style="left: 28%"> <?php echo $errorNot ?></div><?php } ?>
				</form>
				<!--<span class="notCadas"><a href="?cadastro.php">Não tem uma conta? Clique aqui para criar uma</a></span> -->
			</div>
		</div>
	<?php } ?>

	<?php if(isset($_SESSION['logado'])) { ?>
	<div id="wrapper">
		<header>

			<section class="devsite-header-top">
				<nav class="nav-header-top clearfix">
				<!--<a href=""><img src="assets/imagens/imagem-login.png" higth="100%" alt="coffe"/> </a> -->
					<a id="logo" href="http://localhost/projeto/">ESCOLA VIRTUAL</a>
					<ul id="menu">
						<li><a href="http://localhost/projeto" id="active"> Home</a></li>
						<li><a>Materias</a>
							<ul class="sub-menu">
								<li><a href="http://localhost/projeto/materias/base_tecnica.php">Base Tecnica</a></li>
								<li><a>Base Comum</a></li>
							</ul>
						</li>
							<?php if(isset($_SESSION['level']) && $_SESSION['level'] >= 1) { ?> 
								<li><a>UCP</a>
									<ul class="sub-menu">
										<li><a>Conteúdos</a>
											<ul class="sub-menu-1">
												<li><a href="http://localhost/projeto/adm/conteudo-adicionar.php">Adicionar</a></li>
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
				<div id="banner">
					
					<img width="100%" height="100%">
					
					<div class="right control">
						<button class="next"></button>
					</div>
					
					<div class="left control">
						<button class="previous"></button>
					</div>
				</div>
			</header>

			<main>
				<article class="main-top">

					<?php 
						$query = $pdo->prepare("SELECT * FROM conteudos ORDER by id DESC LIMIT 4");
						$query->execute();

					if($query->rowCount()){
					?>
					<div class="main-row row-color">
						<h1>CONTEUDOS NOVOS</h1>

						<div class="main-description">
							<div class="conteudo-galery">
								<?php 
									while($r = $query->fetch(PDO::FETCH_OBJ)){ ?>
										<a href=""><div class="box">
											<img src="<?php echo "assets/imagens/".strtolower(str_replace('/', '', $r->materia)).".png";?>">
											<scan class="title"><?php echo $r->titulo;?></scan>
											</div>
										</a>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php }else{?>
						<div class="main-row">
							<h1>NÃO EXISTE NENHUMA NOVIDADE POR ENQUANTO...</h1>
						</div>
					<?php }?>
				</article>

			</main>
			<footer class="clearfix">ESCOLA VIRTUAL - &copy; 2019 - <?php echo date('Y'); ?></footer>
		</div>
		<?php } ?>
	</body>
</html>
