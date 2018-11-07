<?php
	header('Content-Type: application/json');
	
	if(!isset($_SERVER['PHP_AUTH_USER'])){
		header('WWW-Authenticate: Basic realm="Página Restrita"');
		header('HTTP/1.0 401 Unauthorized');
		die(json_encode(["mensagem" => "autenticação necessária"]));
	}
	if($_SERVER['PHP_AUTH_USER'] == 'admin' && $_SERVER['PHP_AUTH_PW'] == 'admin'){
		echo json_encode(["mensagem" => "Bem-vindo"]);
		exit;
	}elseif(!($_SERVER['PHP_AUTH_USER'] == 'admin' && $_SERVER['PHP_AUTH_PW'] == 'admin')){
		header('HTTP/1.0 401 Unauthorized');
		die(json_encode(["mensagem" => "dados incorretos"]));
	}
	
	require_once '../../config/Conexao.php';
	require_once '../../models/Post.php';
	if($_SERVER['REQUEST_METHOD']=='POST'){

		$db = new Conexao();
		$con = $db->getConexao();

		$post = new Post($con);
		
		$dados = json_decode(file_get_contents("php://input"));	

		$post->titulo = $dados->titulo;
        $post->texto = $dados->texto;
        $post->id_categoria = $dados->id_categoria;
        $post->autor = $dados->autor;

		if($post->create()) {
			$res = array('mensagem','post criado');
		} else {
			$res = array('mensagem','Erro na criação do post');
		}
		echo json_encode($res);
	}else{
		echo json_encode(['mensagem'=> 'método não suportado']);
	}
