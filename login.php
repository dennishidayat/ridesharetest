<?php
  // import setting
  include("config.php");

  // check apakan http method = post
  if($_SERVER["REQUEST_METHOD"] == "POST") {

    //mendapatkan request body dari client
    $username = mysqli_real_escape_string($db,$_POST['username']);
    $password = mysqli_real_escape_string($db,$_POST['password']);
    // hashing password yang didapat dari client, agar sama dengan database yang telah di encryp dengan md5
    $password = md5($password);

    // query ke db mencari user
    $sql = "SELECT * FROM `users` WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);

    $response;

    // jika data = 1, artinya ditemukan usernya
    if($count == 1){
      // kirim response ke client dengan data sbb :
      $response = array(
        'status'=>true,
        'username'=>$username,
        'token'=>API_TOKEN
      );

      // merubah dari php array menjadi json, agar dimengeri device lain
      echo json_encode($response);
    }else{
      // kalau tidak ditemukan atau password salah
      $response = array('status'=>false);
      echo json_encode($response);
    }

    mysqli_close($db);
  }
?>
