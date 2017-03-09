/**
 * homepage.js
 * Usado en /encuesta/index/index
 * @author Giovanni Rodriguez
 */
$().ready(function(){
	var appname = "/colegio/encuesta";
	var url = window.location.origin + appname;
	/**
	 * Actualiza el combo de niveles
	 * Borra la tabla de evaluaciones
	 */
	$("#ciclo").on('change', function(){
		console.log("combo ciclo ha cambiado");
	});
	
	/**
	 * Borra la tabla de evaluaciones
	 * Llena tabla con evaluaciones disponibles
	 */
	$("#nivel").on('change', function(){
		console.log("combo nivel ha cambiado");
		var idNivel = $(this).val();
		console.log("IdNivel:"+idNivel);
		var urlQueryEvaluaciones = url+"/json/grados/idNivel/"+idNivel;
		console.log("Url: " + urlQueryEvaluaciones);
		$.ajax({
			url: urlQueryEvaluaciones,
			dataType: "json",
			success: function(data){
				console.dir(data);
				$("#grado").empty();
				$("#grado").append($("<option></option>").attr("value","0").text("Seleccione Grado Educativo"));
				$.each(data, function(i,item){//function(key,value)
					$("#grado").append($("<option></option>").attr("value",data[i].idGradoEducativo).text(data[i].gradoEducativo));
				});
				$("#grupo").empty();
			}
		});
	});
	
	$("#grado").on('change', function(){
		console.log("combo grado ha cambiado");
		var idGrado = $(this).val();
		//La primera opcion no es valida para hacer consulta
		if(idGrado != "0"){
			console.log("IdGrado:"+idGrado);
			//Ciclo
			var idCiclo = $("#ciclo").val();
			console.log("IdCiclo:"+idCiclo);
			var urlQueryEvaluaciones = url+"/json/grupos/idCiclo/"+idCiclo+"/idGrado/"+idGrado;
			
			console.log("Url: " + urlQueryEvaluaciones);
			$.ajax({
				url: urlQueryEvaluaciones,
				dataType: "json",
				success: function(data){
					console.dir(data);
					$("#grupo").empty();
					$("#grupo").append($("<option></option>").attr("value","0").text("Seleccione Grupo"));
					$.each(data, function(i,item){//function(key,value)
						$("#grupo").append($("<option></option>").attr("value",data[i].idGrupo).text(data[i].grupo));
					});
					//$("#grupo").empty();
					
				}
			});
			
		}
	});
	
	$("#grupo").on('change', function(){
		console.log("combo grupo ha cambiado");
		var idGrupo = $(this).val();
		if(idGrupo != "0"){
			var urlEncuesta = url+"/index/evaluadores/grupo/"+idGrupo;
			//var urlEncuesta = url+"/index/conjuntos/idGrupo/"+idGrupo;
			//var urlEncuesta = url+"/evaluacion/grupo/idGrupo/"+idGrupo;
			var form = $("#form-eval");
			var link = $("<a></a>").attr("class","btn btn-success").attr("href",urlEncuesta).text("Buscar Evaluaciones");
			var linkContainer = $("<div></div>").attr("class","col-xs-offset-2 col-xs-10");
			linkContainer.append(link);
			var container = $("<div></div>").attr("class","form-group");
			container.append(linkContainer); 
			form.append(container);
			//form.append($("<option></option>").attr("value",data[i].idGrupo).text(data[i].grupo));
		}
	});
});
