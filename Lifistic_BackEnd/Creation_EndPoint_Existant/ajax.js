// On attend que la page hmtl soit chargée et prête 
// à être utilisée pour lancer le code jquery
$( document ).ready(function("") {

​
    // on appelle de manière "asynchrone"
    // le fichier php backend.php
    $.ajax({ url: 'get_chatroom.php',
        data: {id: 'michaeljackson'},
        type: 'post',
        success: function(data) {
​
            // on transforme le json (echo $variable) de backend.php
            // en un objet javascript manipulable facilement
            var objetJson = JSON.parse(data);
            
            // pour chaque valeur de i, on crée un block
            // html avec la clé "nom" concaténée
            // puis on append ce contenu à la div id #moncontenu
            for(i=0;i<=2;i++){
                console.log(objetJson[i].data);
                var contenuDiv = "<div style='width:100px;height:20px;background:red;margin-bottom:10px;'></div>";               
                $("#moncontenu").append(contenuDiv);       
            }
        
    
        }
        
    });
