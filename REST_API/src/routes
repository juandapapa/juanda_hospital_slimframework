<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();
    

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");
       
        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

        
    //Dari sini mulai 
    //dokter as obeject

    $app->get("/api/dokters", function (Request $request, Response $response){
        $sql = "SELECT * FROM dokter";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if($result){
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }
        return $response->withJson(["status" => "not found", "code" => "404" ], 404);
    
    });
    //mencari dokter sesuai id permintaan
    $app->get("/api/dokters/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM dokter WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if($result){
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }
        return $response->withJson(["status" => "not found", "code" => "404"], 404);
    });
    
    //mencari dokter berdasarkan kata kunci
    //nama dokter
    //spesialis dokter
    $app->get("/api/dokters/search/{keyword}", function (Request $request, Response $response, $args){
        $keyword = $args["keyword"];
        $sql = "SELECT * FROM dokter WHERE nama LIKE '%$keyword%' OR spesialis LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result){
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }
        return $response->withJson(["status" => "not found", "code" => "404"], 404);
    });
    
    //create dokter
    $app->post("/api/dokters", function (Request $request, Response $response){
        $new_dokter = $request->getParsedBody();
    
        if(!isset($new_dokter['nama']) || !isset($new_dokter['jenis_kelamin']) || !isset($new_dokter['spesialis']) || !isset($new_dokter['alamat'])  || !isset($new_dokter['no_telepon']) ){
            return $response->withJson(["status" => "incorrect request", "code" => "400"], 400);
    
        }else{
    
        $sql = "INSERT INTO dokter (nama, spesialis, jenis_kelamin, no_telepon, alamat) VALUE(:nama, :spesialis, :jenis_kelamin, :no_telepon, :alamat)";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":nama" => $new_dokter["nama"],
            ":spesialis" => $new_dokter["spesialis"],
            ":jenis_kelamin" => $new_dokter["jenis_kelamin"],
            ":no_telepon" => $new_dokter["no_telepon"],
            ":alamat" => $new_dokter["alamat"]
        ];
    
        if ($stmt->execute($data)){
            return $response->withJson(["status" => "success", "code" => "200"], 200);
        }
        return $response->withJson(["status" => "failed", "code" => "200"], 200);
        }
    });
    //delete dokters
    $app->delete("/api/dokters/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM dokter WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if($result){
            $sql = "DELETE FROM dokter WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            
            $data = [
                ":id" => $id
            ];
        
            if($stmt->execute($data)){
                return $response->withJson(["status" => "success", "code" => "200"], 200);
            }
            return $response->withJson(["status" => "failed", "code" => "500"], 500);
            
        }
        
        return $response->withJson(["status" => "failed", "code" => "400", "message" => "id not found"], 400);
    });
    
    //update dokters
    $app->put("/api/dokters/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $new_dokter = $request->getParsedBody();
    
        //validasi data sebelum update
    
        if(isset($new_dokter['nama']) || isset($new_dokter['jenis_kelamin']) || isset($new_dokter['spesialis']) || isset($new_dokter['alamat'])  || isset($new_dokter['no_telepon']) ){
            $data = [
                ":id" => $id];
            $sql = "SELECT * FROM dokter WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $result = $stmt->fetch();
            if($result){
    
            if(isset($new_dokter['spesialis'])) {
                $sql2 = "UPDATE dokter SET  spesialis=:spesialis WHERE id=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":spesialis" => $new_dokter["spesialis"]]);
            }
            if(isset($new_dokter['jenis_kelamin'])) {
                
                $sql2 = "UPDATE dokter SET jenis_kelamin=:jenis_kelamin WHERE id=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":jenis_kelamin" => $new_dokter["jenis_kelamin"]]);
            }
            if(isset($new_dokter['no_telepon'])) {
                
                $sql2 = "UPDATE dokter SET  no_telepon=:no_telepon WHERE id=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":no_telepon" => $new_dokter["no_telepon"]]);
            }
            if(isset($new_dokter['nama'])) {
                
                $sql2 = "UPDATE dokter SET nama=:nama WHERE id=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":nama" => $new_dokter["nama"]]);
    
            }
            if(isset($new_dokter['alamat'])) {
                
                $sql2 = "UPDATE dokter SET alamat=:alamat WHERE id=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":alamat" => $new_dokter["alamat"]]);
    
            }
            
            
            
                
                
                
    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $result = $stmt->fetch();
            unset($result["id"]);
            return $response->withJson(["status" => "success", "data" => $result], 200);
                
            
    
            }
            return $response->withJson(["status" => "failed", "code" => "400","message" => "id not found"], 400);
            
        }else{
    
            return $response->withJson(["status" => "incorrect request", "code" => "400"], 400);   
        }
        
    });
    
    //berakhir di sini ya 
    //pasien
    //pasien as obeject
    
    
    //Menampilakn semua pasien
    $app->get("/api/pasiens", function (Request $request, Response $response){
        $sql = "SELECT * FROM pasien";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if($result){
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }
        return $response->withJson(["status" => "not found", "code" => "404" ], 404);
    
    });
    //mencari dokter sesuai id permintaan
    $app->get("/api/pasiens/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM pasien WHERE no_rekam_medis=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if($result){
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }
        return $response->withJson(["status" => "not found", "code" => "404"], 404);
    });
    
    //mencari pasien berdasarkan kata kunci
    //nama pasien
    //spesialis pasiens
    $app->get("/api/pasiens/search/{keyword}", function (Request $request, Response $response, $args){
        $keyword = $args["keyword"];
        $sql = "SELECT * FROM pasien WHERE nama LIKE '%$keyword%' OR pekerjaan LIKE '%$keyword%' OR alamat LIKE '%$keyword%' OR agama LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result){
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }
        return $response->withJson(["status" => "not found", "code" => "404"], 404);
    });
    
    //create pasien
    $app->post("/api/pasiens", function (Request $request, Response $response){
        $new_pasien = $request->getParsedBody();
    
        if(!isset($new_pasien['nama']) || !isset($new_pasien['jenis_kelamin']) || !isset($new_pasien['usia']) || !isset($new_pasien['agama'])  || !isset($new_pasien['pekerjaan']) || !isset($new_pasien['alamat']) || !isset($new_pasien['golongan_darah']) || !isset($new_pasien['no_telepon']) ){
            return $response->withJson(["status" => "incorrect request", "code" => "400"], 400);
    
        }else{
    
        $sql = "INSERT INTO pasien (nama, jenis_kelamin, usia, agama, pekerjaan, alamat, golongan_darah, no_telepon ) VALUE(:nama, :jenis_kelamin, :usia, :agama, :pekerjaan, :alamat, :golongan_darah, :no_telepon)";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":nama" => $new_pasien["nama"],
            ":jenis_kelamin" => $new_pasien["jenis_kelamin"],
            ":usia" => $new_pasien["usia"],
            ":agama" => $new_pasien["agama"],
            ":pekerjaan" => $new_pasien["pekerjaan"],
            ":alamat" => $new_pasien["alamat"],
            ":golongan_darah" => $new_pasien["golongan_darah"],
            ":no_telepon" => $new_pasien["no_telepon"]
        ];
    
        if ($stmt->execute($data)){
            return $response->withJson(["status" => "success", "code" => "200"], 200);
        }
        return $response->withJson(["status" => "failed", "code" => "200"], 200);
        }
    });
    //delete pasiens
    $app->delete("/api/pasiens/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM pasien WHERE no_rekam_medis=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if($result){
            $sql = "DELETE FROM pasien WHERE no_rekam_medis=:id";
            $stmt = $this->db->prepare($sql);
            
            $data = [
                ":id" => $id
            ];
        
            if($stmt->execute($data)){
                return $response->withJson(["status" => "success", "code" => "200"], 200);
            }
            return $response->withJson(["status" => "failed", "code" => "500"], 500);
            
        }
        
        return $response->withJson(["status" => "failed", "code" => "400", "message" => "id not found"], 400);
    });
    //alternatif API for delete with curl(curl only support get)
    $app->get("/api/pasiens/delete/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM pasien WHERE no_rekam_medis=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if($result){
            $sql = "DELETE FROM pasien WHERE no_rekam_medis=:id";
            $stmt = $this->db->prepare($sql);
            
            $data = [
                ":id" => $id
            ];
        
            if($stmt->execute($data)){
                return $response->withJson(["status" => "success", "code" => "200"], 200);
            }
            return $response->withJson(["status" => "failed", "code" => "500"], 500);
            
        }
        
        return $response->withJson(["status" => "failed", "code" => "400", "message" => "id not found"], 400);
    });
    
    //update pasien
    $app->put("/api/pasiens/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $new_pasien = $request->getParsedBody();
    
        //validasi data sebelum update
    
        if(isset($new_pasien['nama']) || isset($new_pasien['jenis_kelamin']) || isset($new_pasien['usia']) || isset($new_pasien['agama']) || isset($new_pasien['pekerjaan']) || isset($new_pasien['alamat'])  || isset($new_pasien['golongan_darah'])  || isset($new_pasien['no_telepon'])  ){
            $data = [
                ":id" => $id];
            $sql = "SELECT * FROM pasien WHERE no_rekam_medis=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $result = $stmt->fetch();
            if($result){
    
            if(isset($new_pasien['nama'])) {
                $sql2 = "UPDATE pasien SET  nama=:nama WHERE no_rekam_medis=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":nama" => $new_pasien["nama"]]);
            }
            
            if(isset($new_pasien['jenis_kelamin'])) {
                $sql2 = "UPDATE pasien SET  jenis_kelamin=:jenis_kelamin WHERE no_rekam_medis=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":jenis_kelamin" => $new_pasien["jenis_kelamin"]]);
            }
            
            if(isset($new_pasien['usia'])) {
                $sql2 = "UPDATE pasien SET  usia=:usia WHERE no_rekam_medis=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":usia" => $new_pasien["usia"]]);
            }
    
            if(isset($new_pasien['agama'])) {
                $sql2 = "UPDATE pasien SET  agama=:agama WHERE no_rekam_medis=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":agama" => $new_pasien["agama"]]);
            }
    
            if(isset($new_pasien['pekerjaan'])) {
                $sql2 = "UPDATE pasien SET  pekerjaan=:pekerjaan WHERE no_rekam_medis=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":pekerjaan" => $new_pasien["pekerjaan"]]);
            }
    
            if(isset($new_pasien['alamat'])) {
                $sql2 = "UPDATE pasien SET  alamat=:alamat WHERE no_rekam_medis=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":alamat" => $new_pasien["alamat"]]);
            }
    
            if(isset($new_pasien['golongan_darah'])) {
                $sql2 = "UPDATE pasien SET  golongan_darah=:golongan_darah WHERE no_rekam_medis=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":golongan_darah" => $new_pasien["golongan_darah"]]);
            }
    
            if(isset($new_pasien['no_telepon'])) {
                $sql2 = "UPDATE pasien SET  no_telepon=:no_telepon WHERE no_rekam_medis=:id";
                $stmt = $this->db->prepare($sql2);
                $stmt->execute([":id" => $id , ":no_telepon" => $new_pasien["no_telepon"]]);
            }
            
    
    
                
                
                
    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $result = $stmt->fetch();
            unset($result["id"]);
            return $response->withJson(["status" => "success", "data" => $result], 200);
                
            
    
            }
            return $response->withJson(["status" => "failed","code" => "400" ,"message" => "id not found"], 400);
            
        }else{
    
            return $response->withJson(["status" => "incorrect request", "code" => "400"], 400);   
        }
        
    });
    
    
    
    
    //end of pasien
    
    
    
    //ruangan as object
    
    $app->get("/api/ruangans", function (Request $request, Response $response){
        $sql = "SELECT * FROM ruangan";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if($result){
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }
        return $response->withJson(["status" => "not found", "code" => "404"], 404);
    
    });
    
    //get ruangan with id
    $app->get("/api/ruangans/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM ruangan WHERE kode=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if($result){
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }
        return $response->withJson(["status" => "not found", "code" => "404"], 404);
    });
    
    //get ruangan besed on keyword
    $app->get("/api/ruangans/search/{keyword}", function (Request $request, Response $response, $args){
        $keyword = $args["keyword"];
        $sql = "SELECT * FROM ruangan WHERE kode LIKE '%$keyword%' OR klasifikasi LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if($result){
            return $response->withJson(["status" => "found", "data" => $result], 200);
        }else{
            return $response->withJson(["status" => "not found","code" => "404"], 404);
        }
        
    });
    
    
    //create Ruangan
    $app->post("/api/ruangans", function (Request $request, Response $response){
        $new_klasifikasi = $request->getParsedBody();
    
        //validasi input bila kurang send 400 bad request 
    
        if(!isset($new_klasifikasi['kode']) || !isset($new_klasifikasi['klasifikasi']) ){
            return $response->withJson(["status" => "incorrect request", "code" => "400","data"=>$new_klasifikasi], 400 );
    
        }
    
        $sql = "INSERT INTO ruangan (kode, klasifikasi ) VALUE(:kode, :klasifikasi)";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":kode" => $new_klasifikasi["kode"],
            ":klasifikasi" => $new_klasifikasi["klasifikasi"]
        ];
    
        if ($stmt->execute($data)){
            return $response->withJson(["status" => "success", "code" => "200"], 200);
        }
        return $response->withJson(["status" => "failed","code" => "200"], 200);
            
        
    });
    
    
    $app->delete("/api/ruangans/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "DELETE FROM ruangan WHERE kode=:id";
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ":id" => $id
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success","code" => "200"], 200);
        
        return $response->withJson(["status" => "failed","code" => "200"], 200);
    });
    
    //rawat inap dan rawat jalan , bebas mau dikerjain apa enggak 
    //ditempatku takbisa
    
    $app->get("/api/rawat-inap/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        
        
        $sql = "SELECT * FROM rawat_inap WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        if($result){
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }else{
            return $response->withJson(["status" => "not found", "data" => $result], 404);
        }
        
    });
    
    
    $app->get("/api/rawat-inap", function (Request $request, Response $response, $args){
        
        
        $sql = "SELECT * FROM rawat_inap";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if($result){
            return $response->withJson(["status" => "success", "data" => $result], 200);
        }else{
            return $response->withJson(["status" => "not found", "data" => $result], 404);
        }
    
        
    });
    
    //ini gabisa jalan ditempatku ju kalo ditempatmu bisa, buat aja yg kemarin kau siapin itu 
    //yg di whatsaapp kautunjukin bisa
    $app->post("/api/rawat-inap", function (Request $request, Response $response, $args) use($app){

        $new_entry = $request->getParsedBody();
        return $response->withJson(["status" => "incorrect request", "code" => $new_entry], 400);
        
        //ini buat ngecek dan nge entry ke db , belum siap 
        if(!isset($new_entry['id_pasien']) || !isset($new_entry['id_dokter']) || !isset($new_entry['tanggal_masuk']) || !isset($new_entry['agama'])  || !isset($new_entry['pekerjaan']) || !isset($new_entry['alamat']) || !isset($new_entry['golongan_darah']) || !isset($new_pasien['no_telepon']) ){
            return $response->withJson(["status" => "incorrect request", "code" => $new_entry], 400);

        }else{

            $dokter = $app->subRequest('GET', '/dokters/'.$new_entry['id_dokter']) ;
            $pasien = $app->subRequest('GET', '/pasiens/'.$new_entry['id_pasien']) ;

            if($dokter->getStatusCode() === 404 ){
                return $response->withJson(["status" => "incorrect request", "message" => "id dokter invalid"], 400);
            }

            if($pasien->getStatusCode() === 404 ){
                return $response->withJson(["status" => "incorrect request", "message" => "id pasien invalid"], 400);
            }

            return $response->withJson(["status" => "checkpoint", "code" => $new_entry], 400);
            //lengkapi juu dari sini

            $sql = "";
            $stmt = $this->db->prepare($sql);
            //inni juga blm pas masih copas
            $data = [
                ":nama" => $new_entry["nama"],
                ":jenis_kelamin" => $new_entry["jenis_kelamin"],
                ":usia" => $new_entry["usia"],
                ":agama" => $new_entry["agama"],
                ":pekerjaan" => $new_entry["pekerjaan"],
                ":alamat" => $new_entry["alamat"],
                ":golongan_darah" => $new_entry["golongan_darah"],
                ":no_telepon" => $new_entry["no_telepon"]
            ];
            //sampe sini ajaa
            if ($stmt->execute($data)){
                return $response->withJson(["status" => "success", "data" => "1"], 200);
            }
            return $response->withJson(["status" => "failed", "data" => "0"], 200);
            }
    });
    
    
    
        //rawat jalan juga gaada postnya 
    
        $app->get("/api/rawat-jalan/{id}", function (Request $request, Response $response, $args){
            $id = $args["id"];
            
            
            $sql = "SELECT * FROM rawat_jalan WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            if($result){
                return $response->withJson(["status" => "success", "data" => $result], 200);
            }else{
                return $response->withJson(["status" => "not found", "data" => $result], 404);
            }
            
            
            
    
    
            
        });
        
    
        $app->get("/api/rawat-jalan", function (Request $request, Response $response, $args){
            
            
            $sql = "SELECT * FROM rawat-jalan";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            
            if($result){
                return $response->withJson(["status" => "success", "data" => $result], 200);
            }
            return $response->withJson(["status" => "not found", "code" => "404"], 404);
    
    
            
        });
    

};
