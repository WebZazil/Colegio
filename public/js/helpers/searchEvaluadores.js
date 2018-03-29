/**
 * @author EnginnerRodriguez
 */
$().ready(function(){
    var appname = "/colegio/encuesta";
    var url = window.location.origin + appname;
    
    $("#evaluador").on('blur',function(){
        console.log("Saliendo del input!!");
        var valor = $(this).val();
        console.log(valor);
        
        var conjunto = $("#searchPanel").attr("conjunto");
        
        if(valor != ""){
            var urlCond = "/eval/" + valor; 
            var urlQueryEvaluador = url + "/json/evals"+urlCond;
            //console.log(urlQueryEvaluador);
            
            $.ajax({
                url: urlQueryEvaluador,
                dataType: "json",
                success: function(data){
                    
                    console.dir(data);
                    var tbody = $("#evaluadores").find('tbody'); 
                    tbody.empty();
                    
                    $.each(data, function(i,item){
                        var tipo = "";
                        switch (item.tipo) {
                        	case "ALUM" : tipo = "Alumna"; break;
                        	case "DOCE" : tipo = "Docente"; break;
                        }
                        
                        var link = $("<a />").attr("class", "btn btn-success");
                        var urlLink = url + "/evaluador/asociar/co/"+conjunto+"/ev/"+item.idEvaluador;
                        console.log(urlLink);
                        link.attr("href", urlLink).text("Asociar");
                        
                        tbody.append($('<tr>').
                            append($('<td>').append(item.nombres)).
                            append($('<td>').append(item.apellidos)).
                            append($('<td>').append(tipo)).
                            append($('<td>').append(link)) );
                    });
                }
            });
        }
    });
});