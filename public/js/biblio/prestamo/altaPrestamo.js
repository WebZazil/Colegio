/**
 * 
 */
$().ready(function(){
	var appname = "/colegio/biblioteca";
	var url = window.location.origin + appname;
	//var idUsuario
	
	$("#codBarrOrigen").keypress(function(evt){
		//console.log(evt.which);
		//console.log('keypress');
		if(evt.which === 13){
			console.log('keypress + enter');
			var idUsuario = $(this).attr('user');
			var valor = $(this).val();
			var urlQRecursos = url+'/json/brecursos/cr/'+valor;
			console.log(urlQRecursos);
			
			$.ajax({
				url: urlQRecursos,
				dataType: "json",
				success: function(data){
					var tbody = $("#detalleRecurso").find('tbody');
            		tbody.empty();
            		var copia = data['copia'];
            		var ejemplar = data['ejemplar'];
            		var recurso = data['recurso'];
            		
            		var urlPrestamo = url+'/prestamo/prestamo/cp/'+copia.idInventario+'/us/'+ idUsuario;
					var urlPrestamoTest = url+'/prestamo/prestamot/cp/'+copia.idInventario+'/us/'+idUsuario;
					
					var link = $("<a></a>")
                        .attr("class","btn btn-link")
                        .attr("href",urlPrestamo)
                        .text("Prestar Recurso");
                
					var linkT = $("<a></a>")
                        .attr("class","btn btn-link")
                        .attr("href",urlPrestamoTest)
                        .text("Prestar Recurso");

					tbody.append( $('<tr>').
                       append($('<td>').append(recurso.recurso.titulo)).
                       append($('<td>').append(recurso.material.material)).
                       append($('<td>').append(recurso.coleccion.coleccion)).
                       append($('<td>').append(recurso.clasificacion.clasificacion)).
                       append($('<td>').append(link)).
                       append($('<td>').append(linkT)) ) ;
					// =====================================================================
					tbody = $("#detalleEjemplar").find('tbody');
            		tbody.empty();
            		
            		var dLargo = ejemplar.dimensiones.largo;
            		var dAlto = ejemplar.dimensiones.alto;
            		var dAncho = ejemplar.dimensiones.ancho;
            		var dimension = dLargo+'mmX'+dAlto+'mmX'+dAncho+'mm';
            		
            		tbody.append( $('<tr>').
                        append($('<td>').append(ejemplar.editorial.editorial)).
                        append($('<td>').append(ejemplar.idioma.idioma)).
                        append($('<td>').append(ejemplar.pais.nombre)).
                        append($('<td>').append(ejemplar.serieEjemplar.nombreSerie)).
                        append($('<td>').append(ejemplar.tipoLibro.tipoLibro)).
                        append($('<td>').append(dimension)) ) ;
				}
			});
			
			$(this).val('');
		}
	});
});