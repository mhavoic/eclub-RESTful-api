<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

// rute
$app = new \Slim\App;
require '../src/routes/korisnici.php';
require '../src/routes/natjecanja.php';
require '../src/routes/dobKategorija.php';
require '../src/routes/trening.php';
require '../src/routes/utakmica.php';
require '../src/routes/statistika.php';
require '../src/routes/prijava.php';
require '../src/routes/vijesti.php';
require '../src/routes/tipKorisnika.php';
require '../src/routes/ocijena.php';
require '../src/routes/rod.php';
require '../src/routes/sportas.php';
require '../src/routes/dolazak.php';

$app->run();