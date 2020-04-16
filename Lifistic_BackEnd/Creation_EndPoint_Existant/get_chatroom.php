<?php
if(isset($_POST['id'])){
    $id = $_POST['id'];
}else{
    $id = "";
};


$chatroom = array(
            "message" => "str",
            "time" => "str",
            "fromUserName" => "str",
            "statut" => "str",

);

$res = json_encode($chatroom);
echo $res
?>