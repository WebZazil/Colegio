/**
 * @author Giovanni Rodriguez
 */
$().ready(function(){
	var appname = "/colegio/encuesta";
	var url = window.location.origin + appname;
	
	$("#nombres,#apellidos").on('blur',function(){
		var valor = $(this).val();
		console.log("TextoEnNombre: "+valor);
		var tipoCampo = $(this).attr("name");
		//console.log(tipoCampo);
		var urlCond = "";
		if(valor != ""){
			switch (tipoCampo) {
				case "nombres":
					urlCond = "/nombres/"+valor;
					if($("#apellidos").val() != "") urlCond += "/apellidos/" + encodeURIComponent($("#apellidos").val());
					break;
				case "apellidos":
					urlCond = "/apellidos/"+valor;
					if($("#nombres").val() != "") urlCond += "/nombres/" + encodeURIComponent($("#nombres").val());
					break;
				default:
					//tipo = "No especificado";
					break;
			}
		}
		console.log(urlCond);
		var urlQueryEvaluador = url + "/json/evaluadores" + urlCond;
		console.log(urlQueryEvaluador);
		if(urlCond != ""){
			$.ajax({
				url: urlQueryEvaluador,
				dataType: "json",
				success: function(data){
					//console.dir(data);
					var tbody = $("#evaluadores").find('tbody'); 
					tbody.empty();
					
					$.each(data, function(i,item){//function(key,value)
						var tipo = null;
						switch (item.tipo) {
							case "ALUM":
								tipo = "Alumna";
								break;
							case "DOCE":
								tipo = "Docente";
								break;
							default:
								tipo = "No especificado";
								break;
						}
						var urlAsignaciones = url+'/evaluador/admin/ev/'+item.idEvaluador;
						//var htmlBtn = $("<i></i>").attr("class","fa fa-cogs").text();
						var btnAdmin = $("<a></a>").attr("class","btn btn-link").attr("href",urlAsignaciones).html('<i class="fa fa-cogs"></i> Administrar');
						
						tbody.append($('<tr>').
							append($('<td>').append(item.nombres)).
							append($('<td>').append(item.apellidos)).
							append($('<td>').append(btnAdmin)).
							append($('<td>').append(tipo))
						);
						
					});
				}
			});
		}
	});
	
	
});
