/**
 * 
 * @returns
 */
$().ready(function(){
	var appname = "/colegio/encuesta";
    var url = window.location.origin + appname;

    $("button[name='btnQuerySecciones']").click(function() {
		var encuesta = $(this).attr('encuesta');
		console.log(encuesta);
		
		var urlQuery = url + '/json/secciones/en/'+encuesta;
		console.log(urlQuery);
		$.ajax({
            url: urlQuery,
            dataType: "json",
            success: function(data){
                console.dir(data);
                
                var tbody = $("#secciones").find('tbody');
                tbody.empty();

                $.each(data, function(index,value){

                	var urlGrupos = url + "/json/grupossec/sec/"+value.idSeccionEncuesta;
                    
                    var buttonGrupos = $("<button></button>")
                         .attr('class','btn btn-link')
                         .attr('name','btnQueryGrupos')
                         .attr('seccion',value.idSeccionEncuesta)
                         .html('<i class="fa fa-th" ></i> Grupos');
                    
                    tbody.append($('<tr>').
                        append($('<td>').append(value.nombre)).
                        append($('<td>').append(buttonGrupos)).
                        append($('<td>').append(value.orden)).
                        append($('<td>').append(value.elementos)).
                        append($('<td>').append(value.creacion))
                    );
                });
                //$("#grupo").empty();
                
            }
        });
		
    });
	
    $(document).on('click','button[name="btnQueryGrupos"]',function() {
    	var seccion = $(this).attr('seccion');
		console.log(seccion);

		var urlQuery = url + '/json/grupossec/se/'+seccion;
		console.log(urlQuery);
		$.ajax({
            url: urlQuery,
            dataType: "json",
            success: function(data){
                console.dir(data);
                
                var tbody = $("#grupos").find('tbody');
                tbody.empty();

                $.each(data, function(index,value){

                	var tipoGrupo = '';

                	switch (value.tipo) {
					case 'SS':
						tipoGrupo = 'Simple Selección';
						break;

					case 'AB':
						tipoGrupo = 'Abiertas';
						break;
					}
                    
                    var buttonPreguntas = $("<button></button>")
                         .attr('class','btn btn-link')
                         .attr('name','btnQueryPreguntas')
                         .attr('grupo',value.idGrupoSeccion)
                         .html('<i class="fa fa-th" ></i> Preguntas');
                    
                    tbody.append($('<tr>').
                        append($('<td>').append(value.nombre)).
                        append($('<td>').append(tipoGrupo)).
                        append($('<td>').append(value.valorMaximo)).
                        append($('<td>').append(value.valorMinimo)).
                        append($('<td>').append(buttonPreguntas)).
                        append($('<td>').append(value.orden)).
                        append($('<td>').append(value.elementos)).
                        append($('<td>').append(value.creacion))
                    );
                });
                //$("#grupo").empty();
                
            }
        });
    });

    $(document).on('click','button[name="btnQueryPreguntas"]',function() {
    	var grupo = $(this).attr('grupo');
		console.log(grupo);

		var urlQuery = url + '/json/preguntas/gpo/'+grupo;
		console.log(urlQuery);

		$.ajax({
            url: urlQuery,
            dataType: "json",
            success: function(data){
                console.dir(data);
                
                var tbody = $("#preguntas").find('tbody');
                tbody.empty();

                $.each(data, function(index,value){

                	//var urlGrupos = url + "/json/grupossec/sec/"+value.idSeccionEncuesta;
                    /*
                    var buttonGrupos = $("<button></button>")
                         .attr('class','btn btn-link')
                         .attr('name','btnQueryGrupos')
                         .attr('seccion',value.idSeccionEncuesta)
                         .html('<i class="fa fa-th" ></i> Grupos');
                    */
					var tipo = '';
                    switch(value.tipo){
                    	case 'SS': tipo = 'Simple seleccion';  break;
                    	case 'MS': tipo = 'Multiple seleccion';  break;
                    	case 'AB': tipo = 'Abierta';  break;
                    }
                    
                    tbody.append($('<tr>').
                        append($('<td>').append(value.nombre)).
                        append($('<td>').append(tipo)).
                        append($('<td>').append(value.orden)).
                        append($('<td>').append(value.fecha))
                    );
                });
            }
        });
    });
});