/**
 * @author EnginnerRodriguez
 */
$().ready(function(){
    var appname = "/colegio/encuesta";
    var url = window.location.origin + appname;
    
    $("#nivelTwo").on('change', function(){
        var idNivel = $(this).val();
        var urlQueryGrados = url+"/json/grados/idNivel/"+idNivel;
        
        $.ajax({
            url: urlQueryGrados,
            dataType: "json",
            success: function(data){
                console.dir(data);
                $("#gradoTwo").empty();
                $("#gradoTwo").append($("<option></option>").attr("value","0").text("Seleccione Grado Educativo"));
                $.each(data, function(i,item){//function(key,value)
                    $("#gradoTwo").append($("<option></option>").attr("value",data[i].idGradoEducativo).text(data[i].gradoEducativo));
                });
                //$("#grupo").empty();
            }
        });
        
    });
    
    $("#gradoTwo").on('change', function(){
            //console.log("combo grado ha cambiado");
            var idGrado = $(this).val();
            //La primera opcion no es valida para hacer consulta
            if(idGrado != "0"){
                console.log("IdGrado:"+idGrado);
                //Ciclo
                var idCiclo = $("#cicloTwo").val();
                console.log("IdCiclo:"+idCiclo);
                var urlQueryReposGrals = url+"/json/reportesgrals/gr/"+idGrado;
                
                console.log("Url: " + urlQueryReposGrals);
                $.ajax({
                    url: urlQueryReposGrals,
                    dataType: "json",
                    success: function(data){
                        console.dir(data);
                        
                        var tbody = $("#reports").find('tbody'); 
                        tbody.empty();
                       $.each(data, function(i,item){
                           var tipoReporte = "";
                           
                           switch (item.tipoReporte) {
                               case "GRAL":
                                   tipoReporte = "Reporte General";
                                   break;
                               case "AUTO":
                                   tipoReporte = "Reporte Autoevaluación";
                                   break;
                               case "ORED":
                                   tipoReporte = "Reporte Orientadora";
                                   break;
                           }
                           
                           var urlReporte = url + "/reporte/desrg/idReporte/"+item.idReportesGenerales;
                           var link = $("<a></a>")
                            .attr("class","btn btn-link")
                            .attr("href",urlReporte)
                            .attr("target","_blank")
                            .text("Reporte");
                           
                           tbody.append($('<tr>').
                               append($('<td>').append(item.nombreReporte)).
                               append($('<td>').append(tipoReporte)).
                               append($('<td>').append(link))
                           );
                       });
                    }
                });
                
            }
        });
});
