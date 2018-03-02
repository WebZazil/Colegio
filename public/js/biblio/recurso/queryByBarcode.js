
$().ready(function(){
	var appname = "/colegio/biblioteca";
	var url = window.location.origin + appname;
	
	$('#btnQueryBarcode').on('click',function(evt){
		console.log('Se ha hecho click en el boton');
		var form = $('#formBarcode');
		
		var barcode =  form.find('#barcode');
		var tBarcode = form.find('#tBarcode');
		
		console.log(barcode.val());
		console.log(tBarcode.val());
		
		if(barcode.val() != '' ){
			var piece = '/json/brecbybc/tb/' + tBarcode.val()+'/bc/'+barcode.val();
			var urlQuery = url + piece;
			console.log(urlQuery);
			
			$.ajax({
				url: urlQuery,
				dataType: "json",
				success: function(data){
					var tbody = $("#recursos").find('tbody');
            		tbody.empty();
            		
            		var copia = data['copia'];
            		var ejemplar = data['ejemplar'];
            		var recurso = data['recurso'];
            		
            	   var urlAdminRecurso = url+'/recurso/admin/rc/'+recurso.recurso.idRecurso;
            	   var urlEjemplares = url+'/ejemplar/ejemplares/rc/'+recurso.recurso.idRecurso;
					//var urlPrestamoTest = url+'/prestamo/prestamot/cp/'+copia.idInventario+'/us/'+idUsuario;
            		/*
            		var btnAdminRecurso = $("<a></a>")
	                    .attr("class","btn btn-link")
	                    .attr("href",urlAdminRecurso)
	                    .html('<i class="fa fa-cogs"></i>');}
					*/
            		//recurso.recurso.titulo
            		
            	    var btnAdminRecurso = ' <a class="btn btn-link" href="'+urlAdminRecurso+'" ><i class="fa fa-cogs"></i></<a>';
             	    var btnEjemplares = '<a class="btn btn-link" href="'+urlEjemplares+'"><i class="fa fa-book"></i><i class="fa fa-book"></i>'; 
            		var celda = $('<td></td>').html(recurso.recurso.titulo + btnAdminRecurso  );
            		//var ejemplar =('<td></td>').html('Ejemplares'+ btnEjemplares);
            		
				tbody.append( $('<tr>').
                       append(celda ).
                       append($('<td>').append('Ejemplares' + btnEjemplares)).
                       append($('<td>').append('Codigo Barras')).
                       append($('<td>').append(recurso.material.material)).
                       append($('<td>').append(recurso.coleccion.coleccion)).
                       append($('<td>').append(recurso.clasificacion.clasificacion)) );
					
				}
			});
		}
	});
	
});