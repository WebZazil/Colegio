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
        if(valor != ""){
            
            var urlCond = "/eval/" + valor; 
            
            var urlQueryEvaluador = url + "/json/evals"+urlCond;
            console.log(urlQueryEvaluador);
            
            $.ajax({
                url: urlQueryEvaluador,
                dataType: "json",
                success: function(data){
                    
                    console.dir(data);
                    var tbody = $("#evaluadores").find('tbody'); 
                    tbody.empty();
                    
                    $.each(data, function(i,item){
                        var opts = {
                            id: "eval_"+item.idEvaluador,
                            "type": "checkbox",
                        };
                        var checkbox = $("<input />", opts);
                        
                        var div = $("<div />").attr("class", "checkbox");
                        div.append($("<label />").append(checkbox));
                        
                        
                        tbody.append($('<tr>').
                            append($('<td>').append(div)).
                            append($('<td>').append(item.nombres)).
                            append($('<td>').append(item.apellidos))
                        );
                    });
                    /**/
                }
            });
            /**/
        }
    });
});