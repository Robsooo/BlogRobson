<?php

class Post {
	public $id;
	public $titulo;
    public $texto;
    public $id_categoria;
    public $autor;
    public $dt_criacao;
    

	private $conexao;

	// Ao instanciar um objeto, passaremos a conexão 
	// A conexão será armazenada em $this->conexao para uso aqui na Class

	public function __construct($con){
		$this->conexao = $con;
	}

	/* O método read() deverá efetuar uma consulta SQL na tabela categoria, e retornar o resultado */

	public function read($id=null, $id_categoria=null) {
		if(isset($id_categoria)) {
			$consulta = "SELECT * FROM post p WHERE id_categoria = :id_categoria";
			$stmt = $this->conexao->prepare($consulta);
			$stmt->bindParam('id_categoria', $id_categoria);
		}else if(isset($id)) {
			$consulta = "SELECT * FROM post WHERE id = :id";
			$stmt = $this->conexao->prepare($consulta);
			$stmt->bindParam('id', $id);
		}else{
			$consulta = "SELECT * FROM post ORDER BY titulo";
			$stmt = $this->conexao->prepare($consulta);
		}
		try{
			$stmt->execute();
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $resultado;
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function create() {
		$consulta = "INSERT INTO post(titulo, texto, id_categoria, autor) VALUES (:titulo, :texto, :id_categoria, :autor)";
		$stmt = $this->conexao->prepare($consulta);
		$stmt->bindParam('titulo', $this->titulo, PDO::PARAM_STR);
        $stmt->bindParam('texto', $this->texto, PDO::PARAM_STR);
        $stmt->bindParam('id_categoria', $this->id_categoria, PDO::PARAM_STR);
        $stmt->bindParam('autor', $this->autor, PDO::PARAM_STR);
		if ($stmt->execute()) {
			return "true";
		} else {
			return "false";
		}
	}

	public function update(){
		$consulta = "UPDATE post SET titulo = :titulo, texto = :texto, id_categoria = :id_categoria, autor = :autor where id = :id ";
		$stmt = $this->conexao->prepare($consulta);
		$stmt->bindParam('id', $this->id,PDO::PARAM_INT);
		$stmt->bindParam('titulo', $this->titulo, PDO::PARAM_STR);
		$stmt->bindParam('texto', $this->texto, PDO::PARAM_STR);
		$stmt->bindParam('id_categoria', $this->id_categoria, PDO::PARAM_INT);
		$stmt->bindParam('autor', $this->autor, PDO::PARAM_STR);
		if ($stmt->execute()) {
			return "true";
		} else {	
			return "false";
		}	
	}
	public function delete(){
		$consulta = "DELETE FROM post where id = :id";
		$stmt = $this->conexao->prepare($consulta);
		$stmt->bindParam('id', $this->id, PDO::PARAM_STR);	
		if ($stmt->execute()) {
			return "true";
		} else {
			return "false";
		}		
	}
	
}

