<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use web\Security;

require '../vendor/autoload.php';
require '../classes/Security.php';


function callAPI($method, $url, $data){
  $curl = curl_init();
  switch ($method){
     case "POST":
        curl_setopt($curl, CURLOPT_POST, 1);
        if ($data)
           curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;
     case "PUT":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        if ($data)
           curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
        break;
      case "DELETE":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        if ($data)
           curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
        break;
     default:
        if ($data)
           $url = sprintf("%s?%s", $url, http_build_query($data));
  }
  // OPTIONS:
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
     
     'Content-Type: application/json',
  ));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  // EXECUTE:
  $result = curl_exec($curl);
  if(!$result){die("Connection Failure");}
  curl_close($curl);
  return $result;
}



// Create container
$container = new Container();
AppFactory::setContainer($container);

// Add Twig helper into the container
$container->set('view', function () {
    return new Twig('../template');
});

// Add Medoo-based database into the container
$container->set('db', function () {
    include_once('../config/database.php');
    return new \Medoo\Medoo($database_config);
});

// Add session helper into the container
$container->set('session', function () {
    return new \SlimSession\Helper();
});

// Create the app
$app = AppFactory::create();

// Add the session middleware
$app->add(new \Slim\Middleware\Session([
  'autorefresh' => true,
  'lifetime' => '1 hour'
]));

// Add twig Twig-View middleware
$app->add(TwigMiddleware::createFromContainer($app));

// the homepage for user to provide his/her name


// here, even though the path pattern equals to the previous
// the method is different.
$app->get('/', function ($request, $response, $args) {
  // get the session object from the container
  $session = $this->get('session');

  // read the user's name.
  if (!$session->exists("user")) {
    $form_data = $request->getParsedBody();
    $user = $form_data["name"];
    // set a new session named 'user' with value of the user's name.
    $session->set('user', $user);
  }

  return $response
    ->withHeader('Location', '/home')
    ->withStatus(302);
});


$app->get('/home', function($request, $response, $args){
  $session = $this->get('session');


  return $this->get('view')->render($response, 'template.html', [
    'content' => 'home.html',
    'user' => $session->get('user', false),
  ]);
});





$app->post('/', function ($request, $response, $args) {
  // get the session object from the container
  $session = $this->get('session');

  // read the user's name.
  if (!$session->exists("user")) {
    $form_data = $request->getParsedBody();
    $user = $form_data["name"];
    // set a new session named 'user' with value of the user's name.
    $session->set('user', $user);
  }

  return $response
    ->withHeader('Location', '/pasien/all')
    ->withStatus(302);
});


// See all pasien
$app->get('/pasien/all', function($request, $response, $args) use($app){
  $session = $this->get('session');
  
  //api call get
  $get_data = callAPI('GET', 'http://localhost/api/pasiens', false);
  $response_data = json_decode($get_data, true);
  $data = $response_data['data'];
  
  $pasiens=$data;

  return $this->get('view')->render($response, 'template.html', [
    'content' => 'all_pasien.html',
    'pasiens' => $pasiens
  
  ]);

});



$app->get('/pasien/new', function ($request, $response, $args) {
  // get the session object from the container
  $session = $this->get('session');

  // remove the session named as 'user'
  $session->delete("user");

  // display the page by using template
  return $this->get('view')->render($response, 'template.html', [
    'content' => 'pasien.html',
    'user' => $session->get('user', false),
  ]);
});







//========= MEMBUAT PASIEN BARU ========//
$app->post('/pasien/new', function($request, $response, $args){
  $session = $this->get('session');
  $form_data = $request->getParsedBody();


  $pasien = array(
    
    'nama' => $form_data["nama"],
    'jenis_kelamin' => $form_data["jenis_kelamin"],
    'usia' => $form_data["usia"],
    'agama' => $form_data["agama"],
    'pekerjaan' =>$form_data["pekerjaan"],
    'alamat' =>$form_data["alamat"],
    'golongan_darah' =>$form_data["golongan_darah"],
    'no_telepon' =>$form_data["no_telepon"]
  );

  // api call post 
  $make_call = callAPI('POST', 'http://localhost/api/pasiens', json_encode($pasien));
  $response_data = json_decode($make_call, true);
  $status   = $response_data['status'];
  

  return $response
    ->withHeader('Location', '/pasien/all')
    ->withStatus(302);
});

//======>>DETAIL PASIEN<<========//
$app->get('/pasien/detail/{id}', function($request, $response, $args){
  $session = $this->get('session');
  
  $id = $args["id"];

  $get_data = callAPI('GET', 'http://localhost/api/pasiens/'.$id, false);
  $response_data = json_decode($get_data, true);
  $status_code   = $response_data['code'];
  $pasien = $response_data['data'];


  if($status_code === "404"){
    return $response
      ->withHeader('Location', '/pasien/all')
      ->withStatus(302);
  }

  


  return $this->get('view')->render($response, 'template.html', [
    'content' => 'pasien-detail.html',
    'pasien' => $pasien
  ]);

});
//=====>>DELETE PASIEN<<+++++++///
$app->get('/pasien/delete/{id}', function($request, $response, $args){
  $session = $this->get('session');
  $id = $args["id"];

  $delete_call = callAPI("DELETE", 'http://localhost/api/pasiens/'.$id, false);
  $response_data = json_decode($delete_call,true);

   
    return $response
    ->withHeader('Location', '/pasien/all')
    ->withStatus(302);

});
//====>>>UPDATE PASIEN<<<=====///
$app->get('/pasien/update/{id}', function($request, $response, $args){
  $session = $this->get('session');
  
  $id = $args["id"];

  

  $get_data = callAPI('GET', 'http://localhost/api/pasiens/'.$id, false);
  $response_data = json_decode($get_data, true);
  $status_code   = $response_data['code'];
  $pasien = $response_data['data'];


  if($status_code === "404"){
    return $response
      ->withHeader('Location', '/pasien/all')
      ->withStatus(302);
  }

  

  return $this->get('view')->render($response, 'template.html', [
    'content' => 'pasien-update.html',
    'pasien' => $pasien
  ]);

});

//update ga jalan karna template ga nge POST , gatau solving nya 
$app->post("/pasien/ubah", function ($request, $response, $args){
  $session = $this->get('session');
  
  $form_data = $request->getParsedBody();
  $pasien = array(
    
    'nama' => $form_data["nama"],
    'jenis_kelamin' => $form_data["jenis_kelamin"],
    'usia' => $form_data["usia"],
    'agama' => $form_data["agama"],
    'pekerjaan' =>$form_data["pekerjaan"],
    'alamat' =>$form_data["alamat"],
    'golongan_darah' =>$form_data["golongan_darah"],
    'no_telepon' =>$form_data["no_telepon"]
  );


  //api call put pasien
  $update_plan = callAPI('PUT', 'http://localhost/api/pasiens'.$form_data['no_rekam_medis'], json_encode($pasien));
  $response_data = json_decode($update_plan, true);
  $errors = $response_data['data'];

  return $response
    ->withHeader('Location', '/pasien/all')
    ->withStatus(302);
});


////////////=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=
//end of pasien
//===========>============>============>//
#DOKTER

//See All Dokter
$app->get('/dokter/all', function($request, $response, $args){
  $session = $this->get('session');
 
  //api call get
 $get_data = callAPI('GET', 'http://localhost/api/dokters', false);
 $response_data = json_decode($get_data, true);
 $dokters = $response_data['data'];
    

  return $this->get('view')->render($response, 'template.html', [
    'content' => 'all_dokter.html',
    'dokters' => $dokters
  
  ]);

});


//===Dokter Baru====//
$app->get('/dokter/new', function ($request, $response, $args) {
  // get the session object from the container
  $session = $this->get('session');
  // remove the session named as 'user'
  $session->delete("user");

  // display the page by using template
  return $this->get('view')->render($response, 'template.html', [
    'content' => 'dokter.html',
    'user' => $session->get('user', false),
  ]);
});


$app->post('/dokter/new', function($request, $response, $args){
  $session = $this->get('session');
  
  $form_data = $request->getParsedBody();


  $dokter = array(
    'nama' => $form_data["nama"],
    'spesialis' => $form_data["spesialis"],
    'jenis_kelamin' => $form_data["jenis_kelamin"],
    'no_telepon' =>$form_data["no_telepon"],
    'alamat' =>$form_data["alamat"]
  );

  // api call post 
  $make_call = callAPI('POST', 'http://localhost/api/dokters', json_encode($dokter));
  $response_data = json_decode($make_call, true);
  $status   = $response_data['status'];

  return $response
    ->withHeader('Location', '/dokter/all')
    ->withStatus(302);
});


//==DETAIL DOTER==//
$app->get('/dokter/detail/{id}', function($request, $response, $args){
  $session = $this->get('session');
  
  $id = $args["id"];

  $get_data = callAPI('GET', 'http://localhost/api/dokters/'.$id, false);
  $response_data = json_decode($get_data, true);
  $status_code   = $response_data['code'];
  $dokter = $response_data['data'];

  if($status_code === "404"){
    return $response
      ->withHeader('Location', '/dokter/all')
      ->withStatus(302);
  }

  


  return $this->get('view')->render($response, 'template.html', [
    'content' => 'dokter-detail.html',
    'dokter' => $dokter
  ]);

});

///////////==HAPUS DOKTER==////////////
$app->get('/dokter/delete/{id}', function($request, $response, $args){
  $session = $this->get('session');
  
  $id = $args["id"];

  $delete_call = callAPI("DELETE", 'http://localhost/api/dokters/'.$id, false);
  $response_data = json_decode($delete_call,true);
   
    return $response
    ->withHeader('Location', '/dokter/all')
    ->withStatus(302);

});





/////////////=========///////////////
///=====/=/=/=/=//==/=/=/=/=/=/=/=/
////////////END OF DOKTER///////////


//////////RUANGAN////////////////

//////////RUANGAN///////////////

/////////ALL RUANGAN///////////

$app->get('/ruangan/all', function($request, $response, $args){
  

  //api call get
  $get_data = callAPI('GET', 'http://localhost/api/ruangans', false);
  $response_data = json_decode($get_data, true);
  $ruangans = $response_data['data'];

  return $this->get('view')->render($response, 'template.html', [
    'content' => 'all_ruangan.html',
    'ruangans' => $ruangans
  ]);

});

////////DETAIL RUANGAN///////
$app->get('/ruangan/detail/{id}', function($request, $response, $args){
  $session = $this->get('session');
  
  $kode = $args["id"];

  $get_data = callAPI('GET', 'http://localhost/api/ruangans/'.$kode, false);
  $response_data = json_decode($get_data, true);
  $status_code   = $response_data['code'];
  $ruangan = $response_data['data'];

  if($status_code === "404"){
    return $response
      ->withHeader('Location', '/ruangan/all')
      ->withStatus(302);
  }

  


  return $this->get('view')->render($response, 'template.html', [
    'content' => 'ruangan-detail.html',
    'ruangan' => $ruangan
  ]);

});


/////// ADD NEW RUANGAN ////////
// Add ruangan

$app->get('/ruangan/new', function ($request, $response, $args) {
  // get the session object from the container
  $session = $this->get('session');
  // remove the session named as 'user'
  $session->delete("user");


  // display the page by using template
  return $this->get('view')->render($response, 'template.html', [
    'content' => 'ruangan.html',
    'user' => $session->get('user', false),
  ]);
});

$app->post('/ruangan/new', function($request, $response, $args){
  $session = $this->get('session');
  
  $form_data = $request->getParsedBody();

  $ruangan = array(
    'kode' => $form_data["kode"],
    'klasifikasi' => $form_data["klasifikasi"]
  );
  // api call post 
  $make_call = callAPI('POST', 'http://localhost/api/ruangans', json_encode($ruangan));
  $response_data = json_decode($make_call, true);
  $status   = $response_data['status'];


  return $response
    ->withHeader('Location', '/ruangan/all')
    ->withStatus(302);
});

///////////==HAPUS RUANGAN==////////////
$app->get('/ruangan/delete/{id}', function($request, $response, $args){
  $session = $this->get('session');
  
  $id = $args["id"];

  $delete_call = callAPI("DELETE", 'http://localhost/api/ruangans/'.$id, false);
  $response_data = json_decode($delete_call,true);
   
    return $response
    ->withHeader('Location', '/ruangan/all')
    ->withStatus(302);

});


///////////=======SUMUA DATA RAWAT JALAN========///////////
/////////////////////////////////////////////////////////////
$app->get('/rawat_jalan/all', function($request, $response, $args){
  

 //api call get
 $get_data = callAPI('GET', 'http://localhost/api/rawat-jalan', false);
 $response_data = json_decode($get_data, true);
 $rjs = $response_data['data'];


  return $this->get('view')->render($response, 'template.html', [
    'content' => 'all_rawat_jalan.html',
    'rjs' => $rjs

  ]);

});


$app->get('/rawat_jalan/new', function ($request, $response, $args) {

  $db = $this->get('db');

  $join= $db->select(
    "pasien",
    [
      "[><]dokter"
    ]
    );

  return $this->get('view')->render($response, 'template.html', [
    'content' => 'rawat_jalan.html'
  ]);
});

$app->post('/rawat_jalan/new', function($request, $response, $args)use($app){
  $session = $this->get('session');
  $db = $this->get('db');
  $form_data = $request->getParsedBody();


  $rawat_jalan = [
    'id_pasien' => $form_data["id_pasien"],
    'id_dokter' => $form_data["id_dokter"],
    'tgl_kunjungan' => date("Y-m-d H:i:s"),
    'poliklinik' =>$form_data["poliklinik"],
    'diagnosia' =>$form_data["diagnosia"],
    'penanganan' =>$form_data["penanganan"],
    'pembayaran' =>$form_data["pembayaran"]
  ];

  $db->insert('rawat_jalan', $rewat_jalan);

  return $response
    ->withHeader('Location', '/rawat_jalan/all')
    ->withStatus(302);
});

  
// Run the app
$app->run();
