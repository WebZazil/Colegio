/**
 * 
 * @returns
 */
$().ready(function(){
	var module = '/colegio/encuesta';
	var url = window.location.origin + module;

	$('#idNivel').on('change',function(){
		var idNivel = $(this).val();

		if(idNivel != 0){
			var urlQueryRecurso = url + '/json/grados/idNivel/'+idNivel;
			console.log(urlQueryRecurso);
			$.ajax({
				url: urlQueryRecurso,
				dataType: "json",
				success: function(data){
					//console.dir(data);
					var selectGrado = $("#idGrado");
            		selectGrado.empty();
            		selectGrado.append($("<option></option>").attr("value","0").text("Seleccione grado..."));
            		$.each(data, function(index,value){
    					selectGrado.append($("<option></option>").attr("value",value.idGradoEducativo).text(value.gradoEducativo));
    				});

            		var selectGrupo = $("#idGrupo");
            		selectGrupo.empty();
				}
			});
		}
	});

	$('#idGrado').on('change',function(){
		var idGrado = $(this).val();
		if(idGrado!= 0){
			var urlQueryRecurso = url + '/json/grupos/idGrado/'+idGrado;
			console.log(urlQueryRecurso);
			$.ajax({
				url: urlQueryRecurso,
				dataType: "json",
				success: function(data){
					var selectGrupo = $("#idGrupo");
            		selectGrupo.empty();
            		selectGrupo.append($("<option></option>").attr("value","0").text("Seleccione grupo..."));
            		$.each(data, function(index,value){
    					selectGrupo.append($("<option></option>").attr("value",value.idGrupoEscolar).text(value.grupoEscolar));
    				});
				}
			});
		}
	});

	$('#idGrupo').on('change',function(){
		var idGrupo = $(this).val();
		if(idGrupo != 0){
			var urlQueryRecurso = url + '/json/asignacionesgpo/gpo/'+idGrupo;
			console.log(urlQueryRecurso);
			$.ajax({
				url: urlQueryRecurso,
				dataType: "json",
				success: function(data){
					console.dir(data);
					
					var numEvals = data.length;
					console.log(numEvals);
					
					tbody = $("#evaluaciones").find('tbody');
            		tbody.empty();
            		
            		$.each(data, function(index, value){
            			var asignacion = value.asignacion;
            			//var encuesta = value.encuesta;
                		var docente = value.docente;
                		var materiae = value.materiae;
                		var grupoe = value.grupoe;

                		var urlDetails = url + '/resultado/resgras/as/'+asignacion.idAsignacionGrupo+'/ev/'+0;
                		
                		var nombreDocente = docente.apellidos + ', '+docente.nombres;
                		var boton = $('<a></a>').
                			attr('href',urlDetails).
                			attr('target','_blank').
                			html('Ver Reporte');
                		
                		tbody.append( $('<tr>').
                            //append($('<td>').append(encuesta.nombre)).
                            append($('<td>').append(nombreDocente)).
                            append($('<td>').append(materiae.materiaEscolar)).
                            append($('<td>').append(boton)) ) ;
                    });
				}
			});
		}
	});

});