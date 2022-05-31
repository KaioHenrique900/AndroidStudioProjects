<?php
$response = array();

$con_string = "host=ec2-35-168-80-116.compute-1.amazonaws.com port=5432 dbname=d3cnre2oc9uli5 user=blodrftcfvyshh password=0516abc94ad85d3b4e126ff67eae2e73022401049d2862f853034cd2e5e37c61";
$con = pg_connect($con_string);

if (isset($_POST['categoria'])){
    $categoria = trim($_POST['categoria']);
}

$queryCategoria = pg_query($con, "SELECT id_categoria from categoria where descricao='$categoria'");

if (pg_num_rows($queryCategoria)>0){
    $queryCategoria = pg_fetch_array($queryCategoria);
    $id_categoria = $queryCategoria['id_categoria'];
    $queryPossui = pg_query($con, "SELECT fk_piada_id_piada from possui where fk_categoria_id_categoria=$id_categoria");

    if(pg_num_rows($queryPossui)>0){
        $response["piadas"] = array();

        while ($row = pg_fetch_array($queryPossui)) {
            $id_piada = $row['fk_piada_id_piada'];

            $queryPiadas = pg_query($con, "SELECT p.id_piada, p.titulo, p.descricao, p.data_publicacao, u.nome FROM piada as p join usuario as u on p.id_piada=$id_piada and p.fk_id_usuario = u.id_usuario");

            if (pg_num_rows($queryPiadas)>0){
                $rowPiadas = pg_fetch_array($queryPiadas);
                $piada = array();
                $piada["id_piada"] = $rowPiadas["id_piada"];
                $piada["titulo"] = $rowPiadas["titulo"];
                $piada["descricao"] = $rowPiadas["descricao"];
                $piada["data_publicacao"] = $rowPiadas["data_publicacao"];
                $piada["nome_usuario"] = $rowPiadas['nome'];

                array_push($response["piadas"], $piada);
                
            }

            $response["success"] = 1;
        }

        

    }

    else{
        $response["success"] = 0;
        $response["error"] = "Algo está errado";
    }
}

else{
    $response["success"] = 0;
    $response["error"] = "Algo está errado";
}

pg_close($con);
echo json_encode($response);

?>