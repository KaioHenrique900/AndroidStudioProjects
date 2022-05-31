<?php
$response = array();

$con_string = "host=ec2-35-168-80-116.compute-1.amazonaws.com port=5432 dbname=d3cnre2oc9uli5 user=blodrftcfvyshh password=0516abc94ad85d3b4e126ff67eae2e73022401049d2862f853034cd2e5e37c61";
$con = pg_connect($con_string);

$piadasCurtidas=Array();
if (isset($_GET['id_usuario'])){
    $userId = $_GET['id_usuario'];
    $queryCurtidas = pg_query($con, "SELECT fk_id_piada from curte where fk_id_usuario='$userId'");

    if (pg_num_rows($queryCurtidas)>0){

        while ($row = pg_fetch_array($queryCurtidas)) {
            $piada=Array();

            $id_piada = $row['fk_id_piada'];
            $piada['id_piada']=$id_piada;
            $piadasCurtidas[]=$piada;
        }
        
    }
}

$queryPiadas = pg_query($con, "SELECT p.id_piada, p.titulo, p.descricao, p.data_publicacao, p.fk_id_usuario, u.nome FROM piada as p join usuario as u on p.fk_id_usuario = u.id_usuario");


if (pg_num_rows($queryPiadas)>0){
	$response["piadas"] = array();
 
    while ($row = pg_fetch_array($queryPiadas)) {
        $piada = array();
        $piada["id_piada"] = $row["id_piada"];
        $piada["id_usuario"] = $row["fk_id_usuario"];
        $piada["titulo"] = $row["titulo"];
        $piada["descricao"] = $row["descricao"];
        $piada["data_publicacao"] = $row["data_publicacao"];
	 	$piada["nome_usuario"] = $row['nome'];

        $piadaId = $row['id_piada'];
        $queryCount = pg_query($con, "SELECT COUNT(fk_id_piada) as count from curte where fk_id_piada='$piadaId'");
        $resultCount = pg_fetch_array($queryCount);

        $piada["likes"] = $resultCount["count"];
        $piada["curtida"] = 0;
        for($i=0;$i<count($piadasCurtidas);$i++){
            if($piada["id_piada"] == $piadasCurtidas[$i]['id_piada']){
                $piada["curtida"] = 1;
            }
        }

        array_push($response["piadas"], $piada);
    }
	$response["success"] = 1;
}

else{
	$response["success"] = 0;
	$response["error"] = "Algo está errado";
}

pg_close($con);
echo json_encode($response);

?>