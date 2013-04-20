<?php
  $method = $_GET['method'];
  require_once(dirname(__FILE__) .'/../../../wp-blog-header.php');
  //file_put_contents("/tmp/log","method " . $method,FILE_APPEND);

  if (strcmp($method,'add') == 0) add_room();
  if (strcmp($method,'remove') == 0) remove_room();
  return;

  function add_room(){
    $id = $_GET['id'];
    header('Content-type: text/html');
    echo property_profile("",$id);
  }

  function remove_room(){
    $id = $_GET['id'];
    //$post = get_post($id );
    if ( empty($id) ) echo false;
    wp_delete_post($id, true);
    echo true;
  }
?>
