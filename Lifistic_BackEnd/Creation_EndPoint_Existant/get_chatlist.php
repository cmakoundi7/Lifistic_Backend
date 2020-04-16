<?php

if(isset($_POST['id'])){
    $id = $_POST['id'];
}else{
    $id = "";
};


$get_chatlist = array(
                    "chatlist" => array(
                                        "id" => "1",
                                        "name" => "marpine",
                                        "avatar" => "b64" ,
                                    ),
                );
    
    
$res = json_encode($get_chatlist);
    
echo $res;

?>