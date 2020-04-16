<?php 

if(isset($_POST['id'])){
    $id = $_POST['id'];
}else{
    $id = "";
};

$friends = array(
    
                 "id" => "clement",
                 "hasRoom" => "boolean",
                 "name" => "str",
                  "picture" => "b64",
    
);

$res = json_encode($friends);
    
echo $res;
?>