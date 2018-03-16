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

  if($headers['token'] != API_TOKEN){

    // kembalikan response error jika token tidak bener
    $response = array(
      'success'=>false,
      'message'=>"Token Authentication Failed"
    );
    echo json_encode($response);
    return;
  }

  $order_id = '';
  if(isset($_POST['order_id'])){
    $order_id = mysqli_real_escape_string($db,$_POST['order_id']);
  }else{
    $response = array(
      'success'=>false,
      'message'=>"order id required"
    );
    echo json_encode($response);
    return;
  }
  // query ke db
  $sql = "SELECT `is_complete` FROM orders WHERE id = '$order_id'";
  $result = mysqli_query($db,$sql);
  $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

  $isComplete = $row['is_complete'];

  if($isComplete){
    $response = array(
      'success'=>true,
      'message'=>"order telah complete"
    );
    echo json_encode($response);
  }else{
    $response = array(
      'success'=>true,
      'message'=>"order belum complete"
    );
    echo json_encode($response);
  }



}

 ?>
