// On attend que la page hmtl soit chargée et prête 
// à être utilisée pour lancer le code jquery
$( document ).ready(function() {

	// on appelle de manière "asynchrone"
	// le fichier php backend.php
	$.ajax({ url: 'get_chatroom.php',
		data: {id: 'michaeljackson'},
		type: 'post',
		success: function(data) {


			var objetJson = JSON.parse(data);

			var chris=Object.entries(objetJson);


			for(i=0;i<chris.length;i++){
				var contenuDiv = "<div style='width:100px;height:20px;background:red;margin-bottom:10px;'>"+chris[i][1]+"</div>";				
				$("#moncontenu").append(contenuDiv);		
			} 
		
	
		}
	});

});


$( document ).ready(function() {


	$.ajax({ url: 'get_chatcontact.php',
		data: {id: 'michaeljackson'},
		type: 'post',
		success: function(data) {


			var objetJson = JSON.parse(data);

			var chris=Object.entries(objetJson);

			for(i=0;i<chris.length;i++){
				var contenuDiv = "<div style='width:100px;height:20px;background:red;margin-bottom:10px;'>"+chris[i][1]+"</div>";				
				$("#moncontenu").append(contenuDiv);		
			} 
		
	
		}
	});

});

