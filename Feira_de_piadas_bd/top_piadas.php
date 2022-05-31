<?php
session_start();
// connecting to db
$con_string = "host=ec2-35-168-80-116.compute-1.amazonaws.com port=5432 dbname=d3cnre2oc9uli5 user=blodrftcfvyshh password=0516abc94ad85d3b4e126ff67eae2e73022401049d2862f853034cd2e5e37c61";
$con = pg_connect($con_string);

// array for JSON response
$response = array();


$piadasCurtidas=Array();
if (isset($_GET['email'])){
	$email = trim($_GET['email']);
	$query = pg_query($con, "SELECT id_usuario FROM usuario WHERE email='$email'");

	$row = pg_fetch_array($query);

    $userId = $row['id_usuario'];
    $queryCurtidas = pg_query($con, "SELECT * from curte where fk_id_usuario='$userId'");

    if (pg_num_rows($queryCurtidas)>0){
        while ($row = pg_fetch_array($queryCurtidas)) {
            $id_piada = $row['fk_id_piada'];
            $piadasCurtidas[]=$id_piada;
        }
        
    }
}

$queryTopPiadasIds = "SELECT fk_id_piada, COUNT(fk_id_piada) as count FROM curte GROUP BY fk_id_piada HAVING COUNT(fk_id_piada) > 0 ORDER BY COUNT(fk_id_piada) DESC LIMIT 11";

$resultPiadasCurtidasIds = pg_query($con, $queryTopPiadasIds);

if (pg_num_rows($resultPiadasCurtidasIds)>0){

	$response["topPiadas"] = Array();

	while ($row = pg_fetch_array($resultPiadasCurtidasIds)){
		$idPiada = $row['fk_id_piada'];
		$queryTopPiadas = pg_query($con, "SELECT p.id_piada, p.titulo, p.descricao, p.data_publicacao, p.fk_id_usuario, u.nome FROM piada as p join usuario as u on p.id_piada = $idPiada and p.fk_id_usuario = u.id_usuario");
		$result = pg_fetch_array($queryTopPiadas);

        $piada = array();
        $piada["id_piada"] = $result["id_piada"];
        $piada["titulo"] = $result["titulo"];
        $piada["descricao"] = $result["descricao"];
        $piada["data_publicacao"] = $result["data_publicacao"];
        $piada["id_usuario"] = $result['fk_id_usuario'];
        $piada["nome_usuario"] = $result['nome'];
        $piada["likes"] = $row["count"];
        $piada["curtida"] = 0;
        for($i=0;$i<count($piadasCurtidas);$i++){
            if($piada["id_piada"] == $piadasCurtidas[$i]){
                $piada["curtida"] = 1;
            }
        }

        array_push($response["topPiadas"], $piada);
	}
	$response["success"]=1;
}

else{
	$response["success"]=0;
	$response["error"]="Algo deu errado";
}


pg_close($con);
echo json_encode($response);

?>
