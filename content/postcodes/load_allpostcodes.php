<?php
    if (isset($_GET['tbl'])) {
        $table = $_GET['tbl'];

    if(isset($_GET['flds'])){
        $fields = $_GET['flds'];
    }else{
        $fields = "*";
    }

    if(isset($_GET['where'])){
        $where = " WHERE ".$_GET['where'];
    }else{
        $where = "";
    }




        $dsn = "pgsql:host=localhost;dbname=webmap303;port=5432";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        $pdo = new PDO($dsn, 'postgres', 'cheppar#11947', $opt);

        $result = $pdo->query("SELECT *, ST_AsGeoJSON(geom, 5) AS geojson FROM {$table}{$where} LIMIT 100 OFFSET 50");
        $features=[];
        foreach($result AS $row) {
            unset($row['geom']);
            $geometry=$row['geojson']=json_decode($row['geojson']);
            unset($row['geojson']);
            $feature=["type"=>"Feature", "geometry"=>$geometry, "properties"=>$row];
            array_push($features, $feature);
        }
        $featureCollection=["type"=>"FeatureCollection", "features"=>$features];
        echo json_encode($featureCollection);
    } else {
        echo "ERROR: No table parameter included with request";
    }

?>
