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
	//$("#ciclo").on('change', function(){
		//console.log("combo ciclo ha cambiado");
	//});
	
	/**
	 * Borra la tabla de evaluaciones
	 * Llena tabla con evaluaciones disponibles
	 */
	$("#nivel").on('change', function(){
		//console.log("combo nivel ha cambiado");
		var idNivel = $(this).val();
		//console.log("IdNivel:"+idNivel);
		var urlQueryEvaluaciones = url+"/json/grados/idNivel/"+idNivel;
		//console.log("Url: " + urlQueryEvaluaciones);
		$.ajax({
			url: urlQueryEvaluaciones,
			dataType: "json",
			success: function(data){
				//console.dir(data);
				$("#grado").empty();
				$("#grado").append($("<option></option>").attr("value","0").text("Seleccione Grado Educativo"));
				$.each(data, function(index,value){//function(key,value)
					$("#grado").append($("<option></option>").attr("value",value.idGradoEducativo).text(value.gradoEducativo));
				});
				$("#grupo").empty();
			}
		});
	});
	
	$("#grado").on('change', function(){
		//console.log("combo grado ha cambiado");
		var idGrado = $(this).val();
		//La primera opcion no es valida para hacer consulta
		if(idGrado != "0"){
			//console.log("IdGrado:"+idGrado);
			var idCiclo = $("#ciclo").val();
			//console.log("IdCiclo:"+idCiclo);
			var urlQueryEvaluaciones = url+"/json/grupos/idCiclo/"+idCiclo+"/idGrado/"+idGrado;
			//console.log("Url: " + urlQueryEvaluaciones);
			$.ajax({
				url: urlQueryEvaluaciones,
				dataType: "json",
				success: function(data){
					//console.dir(data);
					$("#grupo").empty();
					$("#grupo").append($("<option></option>").attr("value","0").text("Seleccione Grupo"));
					$.each(data, function(index,value){//function(key,value)
						$("#grupo").append($("<option></option>").attr("value",value.idGrupo).text(value.grupo));
					});
					//$("#grupo").empty();
					
				}
			});
			
		}
	});
	
	$("#grupo").on('change', function(){
		//console.log("combo grupo ha cambiado");
		var idGrupo = $(this).val();
		if(idGrupo != "0"){
			var urlEncuesta = url+"/evaluacion/evaluadores/gpo/"+idGrupo;
			//var urlEncuesta = url+"/index/conjuntos/idGrupo/"+idGrupo;
			//var urlEncuesta = url+"/evaluacion/grupo/idGrupo/"+idGrupo;
			var link = $('#btnQueryEncuestas');
			link.attr("class","btn btn-success").attr("href",urlEncuesta);
		}
	});
});
