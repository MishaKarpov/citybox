<?php
header('Access-Control-Allow-Origin: *');




//$json= file_get_contents("https://www.instagram.com/".$_GET[user]."/?__a=1");

$data=json_decode(file_get_contents("https://www.instagram.com/".$_GET[user]."/?__a=1"));

$res->is_private=$data->user->is_private;

$res->full_name=$data->user->full_name;
$res->profile_pic_url=$data->user->profile_pic_url;
$res->is_empty=false;
if($data->user->media->count==0) $res->is_empty=true;
else
foreach ($data->user->media->nodes as $key => $value) {
  $res->media[$key]->thumbnail_src=$value->thumbnail_resources[1]->src;
  $res->media[$key]->code=$value->code;
if($key>4) break;
}

echo json_encode($res);
?>
