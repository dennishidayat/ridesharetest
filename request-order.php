<?php
  include("config.php");

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    // mengambil header request
    $headers = apache_request_headers();
    //mengecek apakah tidak ada field token pada header
    if (!isset($headers['token'])){
      $response = array(
        'success'=>false,
        'message'=>"Token Authentication Failed"
      );
      // jika tidak ada kembalikan response gagal
      echo json_encode($response);
      return;
    }
    // jika ada lanjut

    //cek kebenaran token
    if($headers['token'] != API_TOKEN){

      // kembalikan response error jika token tidak bener
      $response = array(
        'success'=>false,
        'message'=>"Token Authentication Failed"
      );
      echo json_encode($response);
      return;
    }else{

      // kalau token benar

      //check apakan ada request body username;
      $username='';
      if(isset($_POST['username'])){
        $username = mysqli_real_escape_string($db,$_POST['username']);
      }else{
        $response = array(
          'success'=>false,
          'message'=>"username required"
        );
        echo json_encode($response);
        return;
      }

      // mendapatkan user id berdasarkan username
      $sql = "SELECT `id` FROM users WHERE username = '$username'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

      $user_id = $row['id'];

      //buat order dengan pengemudi default 1, harga defaul 15000
      $sql = "INSERT INTO `orders` VALUES (NULL,$user_id,1,NULL,15000,false)";
      $result = mysqli_query($db,$sql);

      // jika insert berhasil
      if($result){
        $response = array(
          'success'=>true,
          'order_id'=>mysqli_insert_id($db),
          'message'=>"Order berhasil"
        );
        echo json_encode($response);
      }else{
        // jika insert gagal
        $response = array(
          'success'=>false,
          'message'=>"Order Gagal"
        );
        echo json_encode($response);
      }

    }

  }

  mysqli_close($db);
 ?>
