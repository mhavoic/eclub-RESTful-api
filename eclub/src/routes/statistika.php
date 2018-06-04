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




// pogledaj  sve utakmice
$app->post('/api/statistika', function(Request $request, Response $response){


	$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 


    $sql="SELECT s.*,sp.ime,sp.prezime,u.domacin,u.gost,u.rezultat FROM statistika s
          join utakmica u on u.id=s.utakmica
		  join sportas sp on sp.oib=s.sportas
	where 1=1 ";

	if(isset($json['id'])){	
$id=mysql_real_escape_string($json['id']);	
$sql.="AND s.id='$id'";
}

	if(isset($json['utakmica'])){	
$utakmica=mysql_real_escape_string($json['utakmica']);	
$sql.="AND s.utakmica='$utakmica'";
}
	if(isset($json['sportas'])){	
$sportas=mysql_real_escape_string($json['sportas']);	
$sql.="AND s.sportas='$sportas'";
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

	
	
$app->post('/api/statistika/korisnik', function(Request $request, Response $response){


	$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 $korIme=mysql_real_escape_string($json['korIme']);	



    $sql="select s.*,sportas.ime,sportas.prezime,u.domacin,u.gost,u.rezultat from statistika s
join rod r on r.SPORAS_oib = s.sportas
join sportas sportas on sportas.oib = s.sportas
join utakmica u on u.id = s.utakmica
where r.KORISNIK_korIme = '$korIme'";




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
	

	
// dodaj statistiku

$app->post('/api/statistika/add', function(Request $request, Response $response){



   $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	

    $skokoviNapad=mysql_real_escape_string($json['skokoviNapad']);
    $skokoviObrana= mysql_real_escape_string($json['skokoviObrana']);
    $asistencije = mysql_real_escape_string($json['asistencije']);
    $osobnePogreske= mysql_real_escape_string($json['osobnePogreske']);
	$poeni= mysql_real_escape_string($json['poeni']);
	$utakmica= mysql_real_escape_string($json['utakmica']);
	$sportas= mysql_real_escape_string($json['sportas']);
	$minute= mysql_real_escape_string($json['minute']);



    $sql = "INSERT INTO statistika (minute,skokoviNapad,skokoviObrana,asistencije,osobnePogreske,poeni,utakmica,sportas) VALUES
    (:minute,:skokoviNapad,:skokoviObrana,:asistencije,:osobnePogreske,:poeni,:utakmica,:sportas)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":minute" => $minute,
        ":skokoviNapad" => $skokoviNapad,
        ":skokoviObrana" => $skokoviObrana,
		":asistencije"=> $asistencije,
		":osobnePogreske"=> $osobnePogreske,
		":poeni"=> $poeni,
		":utakmica"=> $utakmica,
		"sportas"=>$sportas

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
$app->put('/api/statistika/update', function(Request $request, Response $response){


    $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    
    $skokoviNapad=mysql_real_escape_string($json['skokoviNapad']);
    $skokoviObrana= mysql_real_escape_string($json['skokoviObrana']);
    $asistencije = mysql_real_escape_string($json['asistencije']);
    $osobnePogreske= mysql_real_escape_string($json['osobnePogreske']);
	$poeni= mysql_real_escape_string($json['poeni']);
	$utakmica= mysql_real_escape_string($json['utakmica']);
	$sportas= mysql_real_escape_string($json['sportas']);
	$minute= mysql_real_escape_string($json['minute']);
	
	$id= mysql_real_escape_string($json['id']);

	



    $sql = "UPDATE statistika SET
				minute 	= :minute,
				skokoviNapad	= :skokoviNapad,
                skokoviObrana = :skokoviObrana,
				asistencije	= :asistencije,
				osobnePogreske	= :osobnePogreske,
				poeni	= :poeni,
				utakmica	= :utakmica,
                sportas		= :sportas

			WHERE id = :id";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
        ":minute" => $minute,
        ":skokoviNapad" => $skokoviNapad,
		":skokoviObrana"=> $skokoviObrana,
		":asistencije"=>$asistencije,
		":osobnePogreske"=>$osobnePogreske,
		":poeni"=>$poeni,
		":utakmica"=>$utakmica,
		":sportas"=>$sportas,
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

$app->delete('/api/statistika', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$id=mysql_real_escape_string($json['id']);	



	$sql2="DELETE FROM statistika where id='$id'";



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
	
