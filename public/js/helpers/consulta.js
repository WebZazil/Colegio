/**
 * @author EnginnerRodriguez
 */
$().ready(function(){
        var appname = "/colegio/encuesta";
        var url = window.location.origin + appname;
        
        $("#cicloR").on('change', function(){
            console.log("combo ciclo-reporte ha cambiado");
        });
        
        $("#nivelR").on('change', function(){
            var idNivel = $(this).val();
            var urlQueryEvaluaciones = url+"/json/grados/idNivel/"+idNivel;
            
            $.ajax({
                url: urlQueryEvaluaciones,
                dataType: "json",
                success: function(data){
                    console.dir(data);
                    $("#gradoR").empty();
                    $("#gradoR").append($("<option></option>").attr("value","0").text("Seleccione Grado Educativo"));
                    $.each(data, function(i,item){//function(key,value)
                        $("#gradoR").append($("<option></option>").attr("value",data[i].idGradoEducativo).text(data[i].gradoEducativo));
                    });
                    $("#grupo").empty();
                }
            });
        });
        
        $("#gradoR").on('change', function(){
            //console.log("combo grado ha cambiado");
            var idGrado = $(this).val();
            //La primera opcion no es valida para hacer consulta
            if(idGrado != "0"){
                console.log("IdGrado:"+idGrado);
                //Ciclo
                var idCiclo = $("#cicloR").val();
                console.log("IdCiclo:"+idCiclo);
                var urlQueryEvaluaciones = url+"/json/grupos/idCiclo/"+idCiclo+"/idGrado/"+idGrado;
                
                console.log("Url: " + urlQueryEvaluaciones);
                $.ajax({
                    url: urlQueryEvaluaciones,
                    dataType: "json",
                    success: function(data){
                        console.dir(data);
                        $("#grupoR").empty();
                        $("#grupoR").append($("<option></option>").attr("value","0").text("Seleccione Grupo"));
                        $.each(data, function(i,item){//function(key,value)
                            $("#grupoR").append($("<option></option>").attr("value",data[i].idGrupo).text(data[i].grupo));
                        });
                        //$("#grupo").empty();
                        
                    }
                });
                
            }
        });
        
        $("#grupoR").on('change', function(){
            console.log("combo grupo ha cambiado");
            var idGrupo = $(this).val();
            if(idGrupo != "0"){
                //var urlEncuesta = url+"/index/evaluadores/grupo/"+idGrupo;
                //var urlEncuesta = url+"/index/conjuntos/idGrupo/"+idGrupo;
                //var urlEncuesta = url+"/evaluacion/grupo/idGrupo/"+idGrupo;
                
                var idCiclo = $("#cicloR").val();
                var idNivel = $("#nivelR").val();
                var idGrado = $("#gradoR").val();
                var idGrupo = $("#grupoR").val();
                
                var urlReportes = url+"/json/reportes/ge/"+idGrupo+"/ce/"+idCiclo;
                
                console.log(urlReportes);
                
                $("#btnQueryReports").attr("idCiclo", idCiclo);
                $("#btnQueryReports").attr("idNivel", idNivel);
                $("#btnQueryReports").attr("idGrado", idGrado);
                $("#btnQueryReports").attr("idGrupo", idGrupo);
                
                $("#btnQueryReports").removeClass("hidden");
                
                
                
                /*
                var form = $("#form-eval");
                var link = $("<a></a>").attr("class","btn btn-success").attr("href",urlEncuesta).text("Buscar Evaluaciones");
                var linkContainer = $("<div></div>").attr("class","col-xs-offset-2 col-xs-10");
                linkContainer.append(link);
                var container = $("<div></div>").attr("class","form-group");
                container.append(linkContainer); 
                form.append(container);
                */
                //form.append($("<option></option>").attr("value",data[i].idGrupo).text(data[i].grupo));
            }
        });
        
        
        $("#btnQueryReports").on('click',function() {
            var idCiclo = $(this).attr("idCiclo");
            var idNivel = $(this).attr("idNivel");
            var idGrado = $(this).attr("idGrado");
            var idGrupo = $(this).attr("idGrupo");
            
            var urlReportes = url+"/json/reportes/ge/"+idGrupo+"/ce/"+idCiclo;
            
            console.log(urlReportes);
            
            $.ajax({
                url: urlReportes,
                dataType: "json",
                success: function(data){
                   //console.dir(data);
                    
                   var tbody = $("#reports").find('tbody'); 
                   tbody.empty();
                   $.each(data, function(i,item){
                       var tipoReporte = "";
                       
                       switch (item.tipoReporte) {
                           case "RGRU":
                               tipoReporte = "Reporte Grupal";
                               break;
                           case "RPAB":
                               tipoReporte = "Reporte Orientadora";
                               break;
                           case "RAUT":
                               tipoReporte = "Reporte Autoevaluación";
                               break;
                       }
                       var urlReporte = "";
                       
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
        });
        
    });