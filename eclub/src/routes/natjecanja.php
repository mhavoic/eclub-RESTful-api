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
$app->post('/api/natjecanja', function(Request $request, Response $response){
	
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

    $sql="SELECT n.*, dk.naziv as nazivKategorije FROM natjecanje n
	join dobna_kategorija dk on dk.id=n.dobnaKategorija
    where 1=1 " ;

	
	
if(isset($json['dobnaKategorija'])){	
$dobnaKategorija=mysql_real_escape_string($json['dobnaKategorija']);	
$sql.="AND n.dobnaKategorija='$dobnaKategorija'";
}
if(isset($json['id'])){	
$id=mysql_real_escape_string($json['id']);	
$sql.="AND n.id='$id'";
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


	
// dodaj natjecanje

$app->post('/api/natjecanja/add', function(Request $request, Response $response){



   $json =$request->getParams();
   
   $provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $naziv=mysql_real_escape_string($json['naziv']);
    $opis = mysql_real_escape_string($json['opis']);
    $brojEkipa = mysql_real_escape_string($json['brojEkipa']);
    $pocetak= mysql_real_escape_string($json['pocetak']);
	$kraj= mysql_real_escape_string($json['kraj']);
	$dobnaKategorija= mysql_real_escape_string($json['dobnaKategorija']);



    $sql = "INSERT INTO natjecanje (naziv,opis,brojEkipa,pocetak,kraj,dobnaKategorija) VALUES
    (:naziv,:opis,:brojEkipa,:pocetak,:kraj,:dobnaKategorija)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":naziv" => $naziv,
        ":opis" => $opis,
        ":brojEkipa" => $brojEkipa,
		":pocetak"=> $pocetak,
		":kraj"=> $kraj,
		":dobnaKategorija"=>$dobnaKategorija

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
$app->put('/api/natjecanja/update', function(Request $request, Response $response){


     $json =$request->getParams();
	 
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){		 


    $naziv=mysql_real_escape_string($json['naziv']);
    $opis = mysql_real_escape_string($json['opis']);
    $brojEkipa = mysql_real_escape_string($json['brojEkipa']);
    $pocetak= mysql_real_escape_string($json['pocetak']);
	$kraj= mysql_real_escape_string($json['kraj']);
	$dobnaKategorija= mysql_real_escape_string($json['dobnaKategorija']);
	$id= mysql_real_escape_string($json['id']);
	

	



    $sql = "UPDATE natjecanje SET
				naziv 	= :naziv,
				opis	= :opis,
                brojEkipa = :brojEkipa,
				pocetak	= :pocetak,
				kraj	= :kraj,
                dobnaKategorija		= :dobnaKategorija


			WHERE id = :id";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
        ":naziv" => $naziv,
        ":opis" => $opis,
		":brojEkipa"=> $brojEkipa,
		":pocetak"=>$pocetak,
		":kraj"=>$kraj,
		":dobnaKategorija"=>$dobnaKategorija,
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

$app->delete('/api/natjecanja', function(Request $request, Response $response){


$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
$id=mysql_real_escape_string($json['id']);	


    $sql1="DELETE FROM utakmica where natjecanje='$id'";
	$sql2="DELETE FROM natjecanje where id='$id'";



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
	



