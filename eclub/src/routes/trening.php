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




// pogledaj sve treninge
$app->post('/api/trening', function(Request $request, Response $response){
	
		$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 


    $sql="SELECT t.*, dk.naziv FROM trenig t 
	       join dobna_kategorija dk on dk.id=t.dobnaKategorija
		   where 1=1 ";
	

	
if(isset($json['dobnaKategorija'])){	
$dobnaKategorija=mysql_real_escape_string($json['dobnaKategorija']);	
$sql.="AND t.dobnaKategorija='$dobnaKategorija'";
}
if(isset($json['id'])){	
$id=mysql_real_escape_string($json['id']);	
$sql.="AND t.id='$id'";
}
if(isset($json['trener'])){	
$trener=mysql_real_escape_string($json['trener']);	
$sql.="AND t.trener='$trener'";
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


	
// dodaj trening

$app->post('/api/trening/add', function(Request $request, Response $response){



   $json =$request->getParams();

$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	

    $termin=mysql_real_escape_string($json['termin']);
    $lokacija = mysql_real_escape_string($json['lokacija']);
    $dobnaKategorija = mysql_real_escape_string($json['dobnaKategorija']);
    $trener= mysql_real_escape_string($json['trener']);



    $sql = "INSERT INTO trenig (termin,lokacija,dobnaKategorija,trener) VALUES
    (:termin,:lokacija,:dobnaKategorija,:trener)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":termin" => $termin,
        ":lokacija" => $lokacija,
        ":dobnaKategorija" => $dobnaKategorija,
		":trener"=> $trener

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
$app->put('/api/trening/update', function(Request $request, Response $response){


     	$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $termin=mysql_real_escape_string($json['termin']);
    $lokacija = mysql_real_escape_string($json['lokacija']);
    $dobnaKategorija = mysql_real_escape_string($json['dobnaKategorija']);
    $trener= mysql_real_escape_string($json['trener']);
	$id= mysql_real_escape_string($json['id']);
	

	



    $sql = "UPDATE trenig SET
				termin 	= :termin,
				lokacija	= :lokacija,
                dobnaKategorija = :dobnaKategorija,
				trener	= :trener

			WHERE id = :id";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
        ":termin" => $termin,
        ":lokacija" => $lokacija,
		":dobnaKategorija"=> $dobnaKategorija,
		":trener"=>$trener,
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

$app->delete('/api/trening', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$id=mysql_real_escape_string($json['id']);	


    $sql1="DELETE FROM dolazak where trening='$id'";
	$sql2="DELETE FROM trenig where id='$id'";



    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql1);
        $stmt->execute();
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
	


