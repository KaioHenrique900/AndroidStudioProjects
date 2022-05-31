<?php
$response = array();

$con_string = "host=ec2-35-168-80-116.compute-1.amazonaws.com port=5432 dbname=d3cnre2oc9uli5 user=blodrftcfvyshh password=0516abc94ad85d3b4e126ff67eae2e73022401049d2862f853034cd2e5e37c61";
$con = pg_connect($con_string);

$queryCategorias = pg_query($con, "SELECT * FROM categoria");

if (pg_num_rows($queryCategorias)>0){
	$response["categorias"] = array();
 
    while ($row = pg_fetch_array($queryCategorias)) {
        $categoria = array();
        $categoria["id_categoria"] = $row["id_categoria"];
        $categoria["descricao"] = $row["descricao"];

        array_push($response["categorias"], $categoria);
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