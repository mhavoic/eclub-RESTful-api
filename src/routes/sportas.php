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





$app->post('/api/sportas', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 

    $sql="SELECT s.*, dk.naziv FROM sportas s
 join dobna_kategorija dk on dk.id=s.dobnaKategorija
 where 1=1 ";
	
		if(isset($json['oib'])){	
$oib=mysql_real_escape_string($json['oib']);	
$sql.="AND oib='$oib'";
}

	if(isset($json['dobnaKategorija'])){	
$dobnaKategorija=mysql_real_escape_string($json['dobnaKategorija']);	
$sql.="AND dobnaKategorija='$dobnaKategorija'";
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

	
	
$app->post('/api/sportas/trening', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
 $trening=mysql_real_escape_string($json['trening']);	

    $sql="select * from sportas s
join dobna_kategorija dk on dk.id = s.dobnaKategorija
join trenig t on t.dobnaKategorija = dk.id
left join dolazak d on d.sportas = s.oib
where d.id is null and t.id ='$trening'";
	






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

$app->post('/api/sportas/add', function(Request $request, Response $response){



 
   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $oib=mysql_real_escape_string($json['oib']);
    $ime = mysql_real_escape_string($json['ime']);
	$prezime=mysql_real_escape_string($json['prezime']);
    $datumRodjenja = mysql_real_escape_string($json['datumRodjenja']);
	$imeOca=mysql_real_escape_string($json['imeOca']);
    $imeMajke = mysql_real_escape_string($json['imeMajke']);
	$dobnaKategorija = mysql_real_escape_string($json['dobnaKategorija']);




    $sql = "INSERT INTO sportas (oib,ime,prezime,datumRodjenja,imeOca,imeMajke,dobnaKategorija) VALUES
    (:oib,:ime,:prezime,:datumRodjenja,:imeOca,:imeMajke,:dobnaKategorija)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":oib" => $oib,
		":ime"=> $ime,
		":prezime" => $prezime,
		":datumRodjenja"=> $datumRodjenja,
		":imeOca" => $imeOca,
		":imeMajke"=> $imeMajke,
		":dobnaKategorija" => $dobnaKategorija

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
$app->put('/api/sportas/update', function(Request $request, Response $response){


    
   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $oib=mysql_real_escape_string($json['oib']);
    $ime = mysql_real_escape_string($json['ime']);
	$prezime=mysql_real_escape_string($json['prezime']);
    $datumRodjenja = mysql_real_escape_string($json['datumRodjenja']);
	$imeOca=mysql_real_escape_string($json['imeOca']);
    $imeMajke = mysql_real_escape_string($json['imeMajke']);
	$dobnaKategorija = mysql_real_escape_string($json['dobnaKategorija']);
	

	



    $sql = "UPDATE sportas SET
				ime= :ime,
				prezime= :prezime,
				datumRodjenja= :datumRodjenja,
				imeOca= :imeOca,
				imeMajke= :imeMajke,
				dobnaKategorija= :dobnaKategorija
				WHERE oib = :oib";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
         ":oib" => $oib,
		":ime"=> $ime,
		":prezime" => $prezime,
		":datumRodjenja"=> $datumRodjenja,
		":imeOca" => $imeOca,
		":imeMajke"=> $imeMajke,
		":dobnaKategorija" => $dobnaKategorija
		
		              ]);

 
		echo '{"status":true,"notice":"Zapis ažuriran"}';

    } catch(PDOException $e){
        echo '{"status":false, "notice": '.$e->getMessage().'}';
    }	
		
	 }else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
});

$app->delete('/api/sportas', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$id=mysql_real_escape_string($json['id']);	


    $sql1="DELETE FROM statistika where sportas='$id'";
	$sql2="DELETE FROM rod where SPORAS_oib='$id'";
	$sql3="DELETE FROM dolazak where sportas='$id'";
    $sql4="DELETE FROM sportas where oib='$id'";
	


    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql1);
        $stmt->execute();
		 $stmt = $db->prepare($sql2);
        $stmt->execute();
		 $stmt = $db->prepare($sql3);
        $stmt->execute();
		 $stmt = $db->prepare($sql4);
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
	