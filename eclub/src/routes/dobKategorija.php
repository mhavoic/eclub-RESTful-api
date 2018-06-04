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




// pogledaj sva natjecanja
$app->post('/api/dobKategorija', function(Request $request, Response $response){


		$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
if(isset($json['id'])){	
$id=mysql_real_escape_string($json['id']);	
}else{
	$id='';
}

    $sql="SELECT * FROM dobna_kategorija where id='$id' OR '$id'=''";






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

$app->post('/api/dobKategorija/add', function(Request $request, Response $response){
	
		$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	



 


    $naziv=mysql_real_escape_string($json['naziv']);
    $donjag= mysql_real_escape_string($json['donjaDobnaGranica']);
    $gornjag = mysql_real_escape_string($json['gornjaDobnaGranica']);
  



    $sql = "INSERT INTO dobna_kategorija (naziv,donjaDobnaGranica,gornjaDobnaGranica) VALUES
    (:naziv,:donjaDobnaGranica,:gornjaDobnaGranica)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":naziv" => $naziv,
        ":donjaDobnaGranica" => $donjag,
        ":gornjaDobnaGranica" => $gornjag

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
$app->put('/api/dobKategorija/update', function(Request $request, Response $response){
	
		$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	




     $naziv=mysql_real_escape_string($json['naziv']);
    $donjag= mysql_real_escape_string($json['donjaDobnaGranica']);
    $gornjag = mysql_real_escape_string($json['gornjaDobnaGranica']);
	$id= mysql_real_escape_string($json['id']);
	

	



    $sql = "UPDATE dobna_kategorija SET
				naziv 	= :naziv,
				donjaDobnaGranica	= :donjaDobnaGranica,
                gornjaDobnaGranica = :gornjaDobnaGranica
			WHERE id = :id";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
        ":naziv" => $naziv,
        ":donjaDobnaGranica" => $donjag,
		":gornjaDobnaGranica"=> $gornjag,
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

$app->delete('/api/dobKategorija', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$id=mysql_real_escape_string($json['id']);	



	$sql2="DELETE FROM dobna_kategorija where id='$id'";



    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

		 $stmt = $db->prepare($sql2);
        $stmt->execute();
	
        $db = null;
		 echo '{"status":true, "notice": "Zapisi izbrisani"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 }else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
	});
	
