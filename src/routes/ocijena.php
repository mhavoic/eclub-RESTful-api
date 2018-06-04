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
$app->post('/api/ocijena', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 


    $sql="SELECT o.*, k.ime AS kime, ka.ime AS time FROM ocijena o
	 join korisnik k on k.korIme=o.korisnik
	 join korisnik ka on ka.korIme=o.trener
	 
	where 1=1 ";

	if(isset($json['id'])){	
$id=mysql_real_escape_string($json['id']);	
$sql.="AND o.id='$id'";
}

	if(isset($json['korisnik'])){	
$korisnik=mysql_real_escape_string($json['korisnik']);	
$sql.="AND o.korisnik='$korisnik'";
}

	if(isset($json['trener'])){	
$trener=mysql_real_escape_string($json['trener']);	
$sql.="AND o.trener='$trener'";
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

$app->post('/api/ocijena/add', function(Request $request, Response $response){



 
   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $komentar=mysql_real_escape_string($json['komentar']);
    $ocijena = mysql_real_escape_string($json['ocijena']);
	$korisnik=mysql_real_escape_string($json['korisnik']);
    $trener = mysql_real_escape_string($json['trener']);




    $sql = "INSERT INTO ocijena (komentar,ocijena,korisnik,trener) VALUES
    (:komentar,:ocijena,:korisnik,:trener)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":komentar" => $komentar,
		":ocijena"=> $ocijena,
		":korisnik" => $korisnik,
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
$app->put('/api/ocijena/update', function(Request $request, Response $response){


    
   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $komentar=mysql_real_escape_string($json['komentar']);
    $ocijena = mysql_real_escape_string($json['ocijena']);
	$korisnik=mysql_real_escape_string($json['korisnik']);
    $trener = mysql_real_escape_string($json['trener']);
	$id= mysql_real_escape_string($json['id']);
	

	



    $sql = "UPDATE ocijena SET
				komentar = :komentar,
				ocijena = :ocijena,
				korisnik = :korisnik,
				trener = :trener

			WHERE id = :id";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
         ":komentar" => $komentar,
		":ocijena"=> $ocijena,
		":korisnik" => $korisnik,
		":trener"=> $trener,
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

$app->delete('/api/ocijena', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$id=mysql_real_escape_string($json['id']);	


    $sql1="DELETE FROM ocijena where id='$id'";

	


    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql1);
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
	
