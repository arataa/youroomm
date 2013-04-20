<?php
$filename = basename($_FILES['upload']['name']);
require_once(dirname(__FILE__) .'/../../../wp-blog-header.php');
$uploads  = wp_upload_dir();
$dir      = $uploads['path']; 
$url      = $uploads['url']; 

if ( $_GET['type'] == 'propety_pic' ) {
  $dir = $dir . '/' . $post->ID ;
  $url = $url . '/' . $post->ID ;
}else{
  $filename = $current_user->ID.'_'.$filename;
}

//file_put_contents("/tmp/log","user " . $current_user->ID,FILE_APPEND);
//file_put_contents("/tmp/log","post " . $post->ID,FILE_APPEND);
//file_put_contents("/tmp/log","get " . $_GET['type'] ,FILE_APPEND);

exec('mkdir -p '. $dir);
if (move_uploaded_file($_FILES['upload']['tmp_name'], $dir.'/'.$filename)) {
    $data = array('filename' => $filename, 'url' => $url.'/'.$filename);
} else {
    $data = array('error' => 'Failed to save');
}
header('Content-type: text/html');
echo json_encode($data);
?>
