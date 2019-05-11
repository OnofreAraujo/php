<!DOCTYPE html>

<?php
	require_once("../connection.php");
	include("../protected.php");

	proteger();
	anti_adm();
	$pdo = conectar();

	if($id = isset($_GET['id']) ? $_GET['id'] : '' && is_numeric($id)){
		$conteudo = isset($_POST['conteudo']) ? $_POST['conteudo'] : ' ';
		$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : ' ';
		$materia = isset($_POST['materia']) ? $_POST['materia'] : ' ';
		if(isset($_POST['update'])){
			$salvar = $pdo->prepare("UPDATE conteudos SET conteudo=?, materia=?, titulo=? WHERE id = ?");
			$salvar->bindParam(1, $conteudo);
			$salvar->bindParam(2, $materia);
			$salvar->bindParam(3, $titulo);
			$salvar->bindParam(4, $id);
			if($salvar->execute()){
				header("Location: conteudo-editar.php");
			}

		}

		$editar = $pdo->prepare("SELECT * FROM conteudos WHERE id=? LIMIT 1");
		$editar->bindParam(1, $id);
		$editar->execute();
		$content = $editar->fetch(PDO::FETCH_OBJ);

	}

	if($ex = isset($_GET['excluir']) ? $_GET['excluir'] : '' && is_numeric($ex))
	{
		$excluir = $pdo->prepare("DELETE FROM conteudos WHERE ID = ?");
		$excluir->bindParam(1, $ex);
		if($excluir->execute()){
			echo "<script>location.href='conteudo-editar.php';</script>";
		}
	}
?>
<html>

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

	<!--FUNCOES DO JAVASCRIPT ?>"-->
	<script type="text/javascript">
		$(document).ready(function(){
			<?php if(isset($_GET['id'])) {
				echo "
					$('#editar').fadeIn(500);
					$('table').fadeOut(500); ";	
				}else{	
					echo "
					$('#editar').fadeOut(500);
					$('table').fadeIn(500); ";
				}
			?>
			
			$('#closing').click(function(){
				location.href="http://localhost/projeto/adm/conteudo-editar.php";
				$('#editar').fadeOut(500);
				$('table').fadeIn(500);
				
			});

			
		});

	</script>
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
												<li><a  href="http://localhost/projeto/adm/conteudo-adicionar.php">Adicionar</a></li>
												<li><a id="active" href="http://localhost/projeto/adm/conteudo-editar.php">Editar</a></li>
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
				<div id="editar">
					<form action="" method="POST">
						Titulo: <input type="text" name="titulo" placeholder="Digite o titulo" value=<?php echo $content->titulo; ?> autocomplete="off"><br>


						<span>Materia: <select name="materia">
							<?php 
								$query = $pdo->prepare("SELECT * FROM materias");
								$query->execute();
								while($result = $query->fetch(PDO::FETCH_OBJ)){
							?>
							<option value="<?php echo $result->materia; ?>" <?php echo ($result->materia == $content->materia) ? "selected" : null; ?> ><?php echo strtoupper($result->materia); ?></option>
							<?php }?>
						</select></span>

						<br>
						<textarea name="conteudo" placeholder="Digite o seu conteudo aqui"><?php echo $content->conteudo; ?></textarea>
						<input type="submit" class="botoes" name="update" value="Update">
						<input type="reset" class="botoes" value="Resetar">
					</form>
					<button id="closing">Fechar</button>
				</div>

				<?php 
					$ver = $pdo->prepare("SELECT * FROM conteudos");
					$ver->execute();
					if($ver->rowCount()){
				?>
				<table border="1">

					<tr>
						<td>ID</td>
						<td width="450px">Titulo</td>
						<td>Materia</td>
						<td width="200px">Data de Criação</td>
						<td width="100px">Ação</td>
					</tr>

				<?php 				
					while($pegar = $ver->fetch(PDO::FETCH_OBJ)){ ?>
						<tr>
							<td><?php echo $pegar->ID;?></td>
							<td><?php echo $pegar->titulo;?></td>
							<td><?php echo $pegar->materia; ?></td>
							<td><?php echo date("d/m/Y", strtotime($pegar->data));?></td>
							<td>
								<a class="openEdit" href="?id=<?php echo $pegar->ID; ?>"></a>
								<a href="?excluir=<?php echo $pegar->ID; ?>" onclick="return confirm('Tem certeza que deseja deletar esse conteúdo \nTitulo: <?php echo $pegar->titulo; ?>?');" ></a>

							</td>
						<tr>
				<?php }?>
				</table>
				<?php }else{?>
					<div class="nenhum">
						NÃO EXISTE NENHUM CONTEUDO CRIADO, DESEJA ADICIONAR ALGUM? <a href="http://localhost/projeto/adm/conteudo-adicionar.php">Clique aqui</a>
					</div>
				<?php }?>
			</main>
			<footer class="clearfix">ESCOLA VIRTUAL - &copy; 2019 - <?php echo date('Y'); ?></footer>
		</div>
		<?php } ?>
	</body>
</html>

