<?php

if(isset($_POST['id'])){
    $id = $_POST['id'];
}else{
    $id = "";
};

$results = array(
            "user" => array(
                        "islive" => "boolean",
                        "id" => "str",
                        "avatar" => "str.jpg",
                        "name" => "str"
            )
             "song" => array(
                        "duration" => "int",
                        "position" => "int",
                        "title" => "str",
                        "user" => array(
                                "name" => "str",

                         )
                        "url" => "http://str.mp3",
                        "id" => "str",
                        "playing" => "boolean",
                        "img" => "str64.jpg",
             )
             "likeCount" => "int",
             "commentCount" => "int"
             "comment" => array(
                        "item" => array(
                                "commentId" => "int",
                                "userId" => "int",
                                "text" => "str"
                        )
                        "next" => "ws.lifistic.com/api/post/2345/comments?page=2&count=3",
                       

             )
             "date" => "2018-02-07T18:38:21.994Z",
             "location" => "paris france",
             "liked" => "boolean",
             "image" => "str.jpg",
             "isLifistic" => "boolean",
             "image" => "str.jpg",
             "text": "audio",
               
)
?>