<?php

session_start();
// connecting to db
$con_string = "host=ec2-35-168-80-116.compute-1.amazonaws.com port=5432 dbname=d3cnre2oc9uli5 user=blodrftcfvyshh password=0516abc94ad85d3b4e126ff67eae2e73022401049d2862f853034cd2e5e37c61";
$con = pg_connect($con_string);

// array for JSON response
$response = array();

if (isset($_POST['tituloPiada']) && isset($_POST['categoria']) && isset($_POST['conteudoPiada']) && isset($_POST['email'])){
	$tituloPiada = trim($_POST['tituloPiada']);
	$categoria = trim($_POST['categoria']);
	$conteudoPiada = trim($_POST['conteudoPiada']);
	$email = trim($_POST['email']);

	$queryUser = pg_query($con, "SELECT id_usuario from usuario where email='$email'");
	$rowUser = pg_fetch_array($queryUser);
	$id_usuario = $rowUser['id_usuario'];

	$dataAtual = new DateTime('now');
	$dataAtual = $dataAtual->format('Y-m-d H:i:s');

	$result = pg_query($con, "INSERT INTO piada(titulo, descricao, data_publicacao, fk_id_usuario) VALUES('$tituloPiada', '$conteudoPiada', '$dataAtual', $id_usuario)");
	 
	if ($result) {

		$query_idPiada = pg_query($con, "SELECT id_piada FROM piada WHERE titulo = '$tituloPiada'");
		$rowPiada = pg_fetch_array($query_idPiada);
		$idPiada = $rowPiada['id_piada'];

		$query_idCategoria = pg_query($con, "SELECT id_categoria FROM categoria WHERE descricao = '$categoria'");
		$rowCategoria = pg_fetch_array($query_idCategoria);
		$idCategoria = $rowCategoria['id_categoria'];

		$query_possui = pg_query($con, "INSERT INTO possui(fk_piada_id_piada, fk_categoria_id_categoria) VALUES($idPiada, $idCategoria)");

		if($query_possui){
			$response["success"] = 1;
		}

		else {
			$response["success"] = 0;
			$response["error"] = "Error BD: ".pg_last_error($con);
		}
	}
	else {
		$response["success"] = 0;
		$response["error"] = "Error BD: ".pg_last_error($con);
	}
}

else{
	$response["success"] = 0;
	$response["error"] = "faltam parametros";
}

pg_close($con);
echo json_encode($response);

?>
