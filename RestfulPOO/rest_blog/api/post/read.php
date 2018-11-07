<?php

	header('Acess-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Content-Type: text/html; charset=utf-8');
	
	require_once '../../config/Conexao.php';
	require_once '../../models/Post.php';

	$db = new Conexao();
	$con = $db->getConexao();

    $post = new Post($con);
    

    if(isset($_GET['id'])) {
        $resultado = $post->read($_GET['id'] ,null);
    } elseif(isset($_GET['idcategoria'])) {
        $resultado = $post->read(null, $_GET['idcategoria']);
    } else {
        $resultado = $post->read();
    }

    $qtde_cats=sizeof($resultado);
    if($qtde_cats>0){
        echo json_encode($resultado);
       
    }else{
        echo json_encode(['Mensagem' => 'Nenhum post encontrada']);
    }