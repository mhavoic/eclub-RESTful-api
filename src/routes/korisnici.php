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




// pogledaj sve korisnike
$app->post('/api/korisnici', function(Request $request, Response $response){
	
$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
  $sql="SELECT k.korIme,k.zaporka,k.ime,k.prezime,k.tip,t.naziv,t.opis FROM korisnik k, tip_korisnika t
	        WHERE k.tip=t.id ";
 
if(isset($json['korIme'])){	
$id=mysql_real_escape_string($json['korIme']);	
$sql.="AND korIme='$id'";
}
if(isset($json['tip'])){	
$tip=mysql_real_escape_string($json['tip']);	
$sql.="AND tip='$tip'";
}
 


   


 //   $sql = "SELECT k.korIme,k.zaporka,k.ime,k.prezime,k.tip,t.naziv,t.opis FROM korisnik k, tip_korisnika t
	//          WHERE k.tip=t.id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $korisnici = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($korisnici,JSON_UNESCAPED_UNICODE);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 }else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
	});


	
	
$app->post('/api/korisnici/djeca', function(Request $request, Response $response){
	
$json =$request->getParams();
$provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	
 
 $korIme=mysql_real_escape_string($json['korIme']);	
 
  $sql="select s.*,dk.naziv from sportas s
join rod r on r.SPORAS_oib = s.OIB 
join dobna_kategorija dk on dk.id = s.dobnaKategorija 
WHERE r.KORISNIK_korIme='$korIme'";





   


 //   $sql = "SELECT k.korIme,k.zaporka,k.ime,k.prezime,k.tip,t.naziv,t.opis FROM korisnik k, tip_korisnika t
	//          WHERE k.tip=t.id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $korisnici = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        echo json_encode($korisnici,JSON_UNESCAPED_UNICODE);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 }else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
	});

	
	
	
	


// dodaj korisnika


$app->post('/api/korisnici/add', function(Request $request, Response $response){



   $json =$request->getParams();
   
  $provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	 


    $korIme=mysql_real_escape_string($json['korIme']);
    $zaporka = mysql_real_escape_string($json['zaporka']);
    $ime = mysql_real_escape_string($json['ime']);
    $prezime= mysql_real_escape_string($json['prezime']);
	$tip= mysql_real_escape_string($json['tip']);



    $sql = "INSERT INTO korisnik (korIme,zaporka,ime,prezime,tip) VALUES
    (:korIme,:zaporka,:ime,:prezime,:tip)";

    try{

        $db = new db();
        $db = $db->connect();


		 $stmt = $db->prepare($sql);
         $stmt->execute([
        ":korIme" => $korIme,
        ":zaporka" => $zaporka,
        ":ime" => $ime,
		":prezime"=> $prezime,
		":tip"=>$tip

		              ]);
 
		echo '{"status":true,"notice":"Zapis dodan"}';

    } catch(PDOException $e){
        echo '{"status":false, "notice": '.$e->getMessage().'}';
    }	
}else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}

});




// auriraj
$app->put('/api/korisnici/update', function(Request $request, Response $response){


   $json =$request->getParams();
   
   $provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	


    $korIme=mysql_real_escape_string($json['korIme']);
    $ime = mysql_real_escape_string($json['ime']);
    $prezime= mysql_real_escape_string($json['prezime']);
	$tip= mysql_real_escape_string($json['tip']);
	
	if(isset($json['zaporka'])){
		
		$zaporka = mysql_real_escape_string($json['zaporka']);
	



    $sql = "UPDATE korisnik SET
				zaporka 	= :zaporka,
				ime 	= :ime,
                prezime		= :prezime,
                tip		= :tip


			WHERE korime = :korIme";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
        ":zaporka" => $zaporka,
        ":ime" => $ime,
		":prezime"=> $prezime,
		":tip"=>$tip,
		":korIme"=>$korIme
		              ]);

 
		echo '{"status":true,"notice":"Zapis auriran"}';

    } catch(PDOException $e){
        echo '{"status":false, "notice": '.$e->getMessage().'}';
    }	
	}else{
		

    $sql = "UPDATE korisnik SET
				ime 	= :ime,
                prezime		= :prezime,
                tip		= :tip


			WHERE korime = :korIme";

    try{

        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);



        $stmt->execute([
        ":ime" => $ime,
		":prezime"=> $prezime,
		":tip"=>$tip,
		":korIme"=>$korIme
		              ]);

  
		echo '{"status":true,"notice":"Zapis auriran"}';

    } catch(PDOException $e){
        echo '{"status":false, "notice": '.$e->getMessage().'}';
    }	
	}
	}else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
});



// prijava


$app->post('/api/korisnici/login', function(Request $request, Response $response){






   $json =$request->getParams();
   
  $provjera=new token;
	
	if(isset($json['AppToken'])){
    $key=mysql_real_escape_string($json['AppToken']);	
	}else{
     $key=0;
	} 

	
 if($provjera->provjera($key)){	 


    $korIme=mysql_real_escape_string($json['korIme']);
    $zaporka = mysql_real_escape_string($json['zaporka']);


$sql = "SELECT * FROM korisnik WHERE korIme='$korIme' and zaporka='$zaporka'";


    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        $stmt = $db->query($sql);

		if ($stmt->rowCount()==1) {


		    $korisnik = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo json_encode($korisnik,JSON_UNESCAPED_UNICODE);

     } else {


		 echo json_encode('Krivi podaci',JSON_UNESCAPED_UNICODE);

    }

    } catch(PDOException $e){

        echo '{"error": {"text": '.$e->getMessage().'}';
    }
}else{
		 echo '{"status":false, "notice": "Kriva identifikacija"}';
	}
});




















/*
// Delete Customer
$app->delete('/api/customer/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM customers WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Customer Deleted"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});*/