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
$app->post('/api/dolazak', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
+

    $sql="SELECT d.*,k.ime AS tIme, k.prezime AS tPrezime, s.ime AS sIme, s.prezime AS sPrezime,t.termin,t.lokacija FROM  dolazak d
	join korisnik k on k.korIme=d.trener
	join sportas s on s.oib=d.sportas
	join trenig t on t.id=d.trening
	 where 1=1 ";
	
if(isset($json['id'])){	
$id=mysql_real_escape_string($json['id']);	
$sql.="AND d.id='$id'";
}

if(isset($json['trening'])){	
$trening=mysql_real_escape_string($json['trening']);	
$sql.="AND d.trening='$trening'";
}
	if(isset($json['sportas'])){	
$sportas=mysql_real_escape_string($json['sportas']);	
$sql.="AND d.sportas='$sportas'";
}
	if(isset($json['trener'])){	
$trener=mysql_real_escape_string($json['trener']);	
$sql.="AND d.trener='$trener'";
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


	
$app->post('/api/dolazak/sportas', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$korIme=mysql_real_escape_string($json['korIme']);	

    $sql="SELECT d.id, t.termin,t.lokacija,dk.naziv,s.ime,s.prezime,k.ime AS time,k.prezime AS tprezime from DOLAZAK d
join rod r on r.SPORAS_oib = d.sportas
join trenig t on t.id = d.trening
join sportas s on s.oib =d.sportas
join korisnik k on k.korIme=d.trener
join dobna_kategorija dk on t.dobnaKategorija=dk.id
where r.KORISNIK_korIme ='$korIme'";
	




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

	
	
	
$app->post('/api/dolazak/sportasRoditelj', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$korIme=mysql_real_escape_string($json['korIme']);	

    $sql="select d.*,s.ime as s_ime,s.prezime as s_prezime,t.termin,t.lokacija from dolazak d
join sportas s on s.oib = d.sportas
join trenig t on t.id = d.trening
join rod r on r.SPORAS_oib = d.sportas
where r.KORISNIK_korIme ='$korIme'";

	




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

$app->post('/api/dolazak/add', function(Request $request, Response $response){



 
   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $trening=mysql_real_escape_string($json['trening']);
    $sportas = mysql_real_escape_string($json['sportas']);
	 $trener = mysql_real_escape_string($json['trener']);




    $sql = "INSERT INTO dolazak (trening,sportas,trener) VALUES
    (:trening,:sportas,:trener)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":trening" => $trening,
		":sportas" => $sportas,
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
$app->put('/api/dolazak/update', function(Request $request, Response $response){


    
   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


   $trening=mysql_real_escape_string($json['trening']);
    $sportas = mysql_real_escape_string($json['sportas']);
	 $trener = mysql_real_escape_string($json['trener']);
	$id= mysql_real_escape_string($json['id']);
	

	



    $sql = "UPDATE dolazak SET
				trening 	= :trening,
				sportas	= :sportas,
				trener= :trener

			WHERE id = :id";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
       ":trening" => $trening,
		":sportas" => $sportas,
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


$app->delete('/api/dolazak', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$id=mysql_real_escape_string($json['id']);	


    $sql1="DELETE FROM dolazak where id='$id'";

	


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
	
