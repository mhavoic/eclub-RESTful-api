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
$app->post('/api/utakmica', function(Request $request, Response $response){

		$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 


    $sql="SELECT u.*,n.naziv,k.ime,k.prezime FROM utakmica u
	join natjecanje n on n.id=u.natjecanje
	join korisnik k on k.korIme=u.trener

	where 1=1 ";
	
	
if(isset($json['id'])){	
$id=mysql_real_escape_string($json['id']);	
$sql.="AND u.id='$id'";
}
if(isset($json['natjecanje'])){	
$natjecanje=mysql_real_escape_string($json['natjecanje']);	
$sql.="AND u.natjecanje='$natjecanje'";
}	
if(isset($json['trener'])){	
$trener=mysql_real_escape_string($json['trener']);	
$sql.="AND u.trener='$trener'";
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

	
	
	
	
	
	
$app->post('/api/utakmica/sudionici', function(Request $request, Response $response){

		$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 $id_ut=mysql_real_escape_string($json['id_ut']);	

$sql="select s.oib,s.ime,s.prezime,s.datumRodjenja,s.imeOca,s.imeMajke,s.dobnaKategorija from sportas s 
join dobna_kategorija d on d.id = s.dobnaKategorija
join natjecanje n on n.dobnaKategorija = d.id
join utakmica u on u.natjecanje = n.id
where u.id = '$id_ut'";
	
  

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

	

	
// dodaj natjecanje

$app->post('/api/utakmica/add', function(Request $request, Response $response){

$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	

  


    $domacin=mysql_real_escape_string($json['domacin']);
    $gost= mysql_real_escape_string($json['gost']);
    $lokacija = mysql_real_escape_string($json['lokacija']);
    $rezultat= mysql_real_escape_string($json['rezultat']);
	$natjecanje= mysql_real_escape_string($json['natjecanje']);
	$trener= mysql_real_escape_string($json['trener']);



    $sql = "INSERT INTO utakmica (domacin,gost,lokacija,rezultat,natjecanje,trener) VALUES
    (:domacin,:gost,:lokacija,:rezultat,:natjecanje,:trener)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":domacin" => $domacin,
        ":gost" => $gost,
        ":lokacija" => $lokacija,
		":rezultat"=> $rezultat,
		":natjecanje"=> $natjecanje,
		":trener"=>$trener

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
$app->put('/api/utakmica/update', function(Request $request, Response $response){


    $json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $domacin=mysql_real_escape_string($json['domacin']);
    $gost= mysql_real_escape_string($json['gost']);
    $lokacija = mysql_real_escape_string($json['lokacija']);
    $rezultat= mysql_real_escape_string($json['rezultat']);
	$natjecanje= mysql_real_escape_string($json['natjecanje']);
	$trener= mysql_real_escape_string($json['trener']);
	
	$id= mysql_real_escape_string($json['id']);

	



    $sql = "UPDATE utakmica SET
				domacin 	= :domacin,
				gost	= :gost,
                lokacija = :lokacija,
				rezultat	= :rezultat,
				natjecanje	= :natjecanje,
                trener		= :trener

			WHERE id = :id";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
        ":domacin" => $domacin,
        ":gost" => $gost,
		":lokacija"=> $lokacija,
		":rezultat"=>$rezultat,
		":natjecanje"=>$natjecanje,
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


$app->delete('/api/utakmica', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$id=mysql_real_escape_string($json['id']);	


    $sql1="DELETE FROM statistika where utakmica='$id'";
	$sql2="DELETE FROM utakmica where id='$id'";



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
	


