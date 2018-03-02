/**
 * @author IngenieroRodriguez
 */
$().ready(function() {
		var appname = "/colegio/";
        var url = window.location.origin + appname;
		
        $("button[tipo='encuesta']").click(function() {
			console.log($(this).attr("claveEncuesta"));
			var claveEncuesta = $(this).attr("claveEncuesta");
			var claveContenedor = $("div#detalles").attr("claveContenedor");
			//$hashContainer = $("div#detalles").attr("hash");
			var tipo = "encuesta";
			var tipoContenedor = $("div#detalles").attr("tipo");
			
			if((tipo != tipoContenedor) || (claveEncuesta != claveContenedor) ){
				// Peticion AJAX para actualizar contenedor de detalles
				var urlQueryEncuesta = url + "encuesta/html/encuestadet/claveEncuesta/"+claveEncuesta; 
				console.log(urlQueryEncuesta);
				$.ajax({
					url: urlQueryEncuesta,
					dataType: "html",
					success: function(data){
						$("div#dynamicContent").html(data);
						$("div#detalles").html(data);
						$("div#detalles").attr("claveContenedor", claveEncuesta);
						$("div#detalles").attr("tipo", tipo);
					}
				});
				// Peticion AJAX para actualizar secciones
				var urlQuerySecciones = url + "encuesta/html/secciones/claveEncuesta/"+claveEncuesta;
				$.ajax({
					url: urlQuerySecciones,
					dataType: "html",
					success: function(data){
						$("div#contenedorSecciones").html(data);
						$("div#contenedorGrupos").html("");
						$("div#contenedorPreguntas").html("");
					}
				});
			}
		});
	
		$("div#contenedorSecciones").on("click", "button[tipo='seccion']", function(event) {
			console.log("En secciones");
			
			var claveSeccion = $(this).attr("claveSeccion");
			//console.log("ClaveSeccion: " + claveSeccion);
			var claveContenedor = $("div#detalles").attr("claveContenedor");
			//console.log("ClaveContenedor" + claveContenedor);
			
			var tipo = "seccion";
			//console.log("Tipo: " + tipo);
			var tipoContenedor = $("div#detalles").attr("tipo");
			//console.log("TipoContenedor: " + tipoContenedor);
			
			if((tipo != tipoContenedor) || (claveSeccion != claveContenedor) ){
				//=========================================================== Detalles de la Seccion
				var urlQuerySeccion = url + "encuesta/html/secciondet/claveSeccion/"+claveSeccion;
				$.ajax({
					url: urlQuerySeccion,
					dataType: "html",
					success: function(data){
						$("div#dynamicContent").html(data);
						$("div#detalles").html(data);
						//$("div#detalles").attr("tipo", "seccion");
						$("div#detalles").attr("claveContenedor", claveSeccion);
						$("div#detalles").attr("tipo", tipo);
					}
				});
				//=========================================================== Botones de Grupos
				//console.log("Lanzando peticion de grupos");
				var urlQueryGrupos = url + "encuesta/html/grupos/claveSeccion/"+claveSeccion;
				$.ajax({
					url: urlQueryGrupos,
					dataType: "html",
					success: function(data){
						$("div#contenedorGrupos").html(data);
					}
				});
				//=========================================================== Botones de Preguntas
				//console.log("Lanzando peticion de preguntas");
				var urlQueryPreguntas = url + "encuesta/html/preguntas/claveSeccion/"+claveSeccion;
				$.ajax({
					url: urlQueryPreguntas,
					dataType: "html",
					success: function(data){
						$("div#contenedorPreguntas").html(data);
					}
				});
			}
			
		});
	
		$("div#contenedorGrupos").on("click", "button[tipo='grupo']", function(event) {
			console.log("En grupos");
			var claveGrupo = $(this).attr("claveGrupo");
			var claveContenedor = $("div#detalles").attr("claveContenedor");
			
			var tipo = "grupo";
			var tipoContenedor = $("div#detalles").attr("tipo");
			//if((tipo != tipoContenedor) || (claveSeccion != claveContenedor) ){
			if((tipo != tipoContenedor) || (claveGrupo != claveContenedor)){
				//============================================================================================== Detalles Grupo
				//$urlDetallesGrupo = url + "html/grupodet/hash/" + $hash;
				var urlQueryGrupo = url + "encuesta/html/grupodet/claveGrupo/"+claveGrupo;
				$.ajax({
					url: urlQueryGrupo,
					dataType: "html",
					success: function(data){
						$("div#dynamicContent").html(data);
						$("div#detalles").html(data);
						$("div#detalles").attr("claveGrupo", claveGrupo);
						$("div#detalles").attr("tipo", tipo);
					}
				});
				//============================================================================================== Preguntas
				//console.log("Lanzando peticion de preguntas");
				//$urlPreguntas = $urlEncuesta + "html/preguntas/hashGrupo/" + $hash;
				var urlPreguntasGrupo = url + "encuesta/html/preguntas/claveGrupo/" + claveGrupo;
				$.ajax({
					url: urlPreguntasGrupo,
					dataType: "html",
					success: function(data){
						$("div#contenedorPreguntas").html(data);
					}
				});
			}
		});
	
		$("div#contenedorPreguntas").on("click", "button[tipo='pregunta']", function(event) {
			console.log("En preguntas");
			var clavePregunta = $(this).attr("clavePregunta");
			var clavePadre = $(this).attr("padre");
			//$tipo = $(this).attr("tipo");
			//console.log($hash);
			
			var claveContenedor = $("div#detalles").attr("claveContenedor");
			var tipo = "grupo";
			var tipoContenedor = $("div#detalles").attr("tipo");
			//if((tipo != tipoContenedor) || (claveSeccion != claveContenedor) ){
			if((tipo != tipoContenedor) || (clavePregunta != claveContenedor) ){
				//$urlDetallesPregunta = $urlEncuesta + "html/preguntadet/idPadre/"+ $hashPadre+"/tipo/"+$tipo+"/hash/"+$hash;
				var urlDetallesPregunta = url + "encuesta/html/preguntadet/idPadre/"+clavePadre+"/tipo/"+tipo+"/clavePregunta/"+clavePregunta;
				console.log(urlDetallesPregunta);
				$.ajax({
					url: urlDetallesPregunta,
					dataType: "html",
					success: function(data){
						$("div#dynamicContent").html(data);
						$("div#detalles").html(data);
						//$("div#detalles").attr("tipo", "pregunta");
						$("div#detalles").attr("claveContenedor", clavePregunta);
						$("div#detalles").attr("tipo", tipo);
					}
				});
				
				
			}
			
		});
	
	});