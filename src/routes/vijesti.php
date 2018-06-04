<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;





$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});




// pogledaj sve prijave
$app->post('/api/vijesti', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 


    $sql="SELECT v.*,k.ime,k.prezime FROM vijesti v
           join korisnik k on k.korIme=v.voditelj
	where 1=1 ";
	
	if(isset($json['id'])){	
$id=mysql_real_escape_string($json['id']);	
$sql.="AND v.id='$id'";
}

	if(isset($json['voditelj'])){	
$voditelj=mysql_real_escape_string($json['voditelj']);	
$sql.="AND v.voditelj='$voditelj'";
}



    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $korisnici = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($korisnici,JSON_UNESCAPED_UNICODE);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 }else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
	});


	
// dodaj prijavu

$app->post('/api/vijesti/add', function(Request $request, Response $response){



 
   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $naslov=mysql_real_escape_string($json['naslov']);
    $sadrzaj = mysql_real_escape_string($json['sadrzaj']);
	 $voditelj = mysql_real_escape_string($json['voditelj']);




    $sql = "INSERT INTO vijesti (naslov,sadrzaj,voditelj) VALUES
    (:naslov,:sadrzaj,:voditelj)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":naslov" => $naslov,
		":sadrzaj" => $sadrzaj,
		":voditelj"=> $voditelj

		              ]);
 
		echo '{"status":true,"notice":"Zapis dodan"}';

    } catch(PDOException $e){
        echo '{"status":false, "notice": '.$e->getMessage().'}';
    }	

 }else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
});

// ažuriraj
$app->put('/api/vijesti/update', function(Request $request, Response $response){


    
   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $naslov=mysql_real_escape_string($json['naslov']);
    $sadrzaj = mysql_real_escape_string($json['sadrzaj']);
	$voditelj = mysql_real_escape_string($json['voditelj']);
	$id= mysql_real_escape_string($json['id']);
	

	



    $sql = "UPDATE vijesti SET
				naslov 	= :naslov,
				sadrzaj	= :sadrzaj,
				voditelj= :voditelj

			WHERE id = :id";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
         ":naslov" => $naslov,
		":sadrzaj" => $sadrzaj,
		":voditelj"=> $voditelj,
		":id"=>$id
		
		              ]);

 
		echo '{"status":true,"notice":"Zapis ažuriran"}';

    } catch(PDOException $e){
        echo '{"status":false, "notice": '.$e->getMessage().'}';
    }	
		
	 }else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
});


// pogledaj sve prijave
$app->delete('/api/vijesti', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$id=mysql_real_escape_string($json['id']);	

    $sql="DELETE FROM vijesti where id='$id'";
	


    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
		 echo '{"status":true, "notice": "Zapis izbrisan"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 }else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
	});
	
	