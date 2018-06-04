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
$app->post('/api/rod', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 


    $sql="SELECT r.*,k.ime AS kIme, k.prezime AS kPrezime, s.ime AS sIme, s.prezime AS sPrezime   rod r
	join korisnik k on k.korIme=r.KORISNIK_korIme
	join sportas s on s.oib=r.SPORAS_oib

	where 1=1 ";
	
		if(isset($json['SPORAS_oib'])){	
$SPORAS_oib=mysql_real_escape_string($json['SPORAS_oib']);	
$sql.="AND r.SPORAS_oib='$SPORAS_oib'";
}

	if(isset($json['KORISNIK_korIme'])){	
$KORISNIK_korIme=mysql_real_escape_string($json['KORISNIK_korIme']);	
$sql.="AND r.KORISNIK_korIme='$KORISNIK_korIme'";
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

$app->post('/api/rod/add', function(Request $request, Response $response){



 
   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $SPORAS_oib=mysql_real_escape_string($json['SPORAS_oib']);
    $KORISNIK_korIme = mysql_real_escape_string($json['KORISNIK_korIme']);




    $sql = "INSERT INTO rod (SPORAS_oib,KORISNIK_korIme) VALUES
    (:SPORAS_oib,:KORISNIK_korIme)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":SPORAS_oib" => $SPORAS_oib,
		":KORISNIK_korIme"=> $KORISNIK_korIme

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
$app->put('/api/rod/update', function(Request $request, Response $response){


    
   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $SPORAS_oib=mysql_real_escape_string($json['SPORAS_oib']);
    $KORISNIK_korIme = mysql_real_escape_string($json['KORISNIK_korIme']);
	
	

	



    $sql = "UPDATE rod SET
				KORISNIK_korIme	= :KORISNIK_korIme

			WHERE SPORAS_oib = :SPORAS_oib";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
        ":SPORAS_oib" => $SPORAS_oib,
        ":KORISNIK_korIme" => $KORISNIK_korIme
		
		              ]);

 
		echo '{"status":true,"notice":"Zapis ažuriran"}';

    } catch(PDOException $e){
        echo '{"status":false, "notice": '.$e->getMessage().'}';
    }	
		
	 }else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
});


$app->delete('/api/rod', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
 
if(isset($json['SPORAS_oib'])){ 
$SPORAS_oib=mysql_real_escape_string($json['SPORAS_oib']);	
$sql1="DELETE FROM rod where SPORAS_oib='$SPORAS_oib'";

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
}
if(isset($json['KORISNIK_korIme'])){ 
$KORISNIK_korIme=mysql_real_escape_string($json['KORISNIK_korIme']);	
$sql1="DELETE FROM rod where KORISNIK_korIme='$KORISNIK_korIme'";
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
}


  
 }else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
	});
	

