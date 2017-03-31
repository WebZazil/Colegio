/**
 * @author Giovanni Rodriguez
 */
$().ready(function(){
	var appname = "/colegio/encuesta";
	var url = window.location.origin + appname;
	
	Survey.Survey.cssType = "bootstrap";
	var idEncuesta = $("#evaluaciones").attr("evaluacion");
	var surveyJSON = null;
	
	switch (idEncuesta) {
        case "1" : 
             
            break;
        case "2" : 
            surveyJSON = {pages:[{name:"page1",questions:[{type:"matrix",columns:[{value:"5",text:"Siempre"},{value:"4",text:"Casi Siempre"},{value:"3",text:"En Ocaciones"},{value:"2",text:"Casi Nunca"},{value:"1",text:"Nunca"}],name:"pss",rows:[{value:"19",text:"¿Sabe quién soy?"},{value:"20",text:"¿Es congruente con lo que dice, pide y hace?"},{value:"21",text:"Cuando me acerco a ella recibo lo que necesito(ayuda, escucha, apoyo)"},{value:"22",text:"¿Promueve la integración del grupo?"},{value:"23",text:"¿Nos informa oportunamente de las actividades y eventos en los que vamos a participar? "},{value:"24",text:"¿Facilita la integración y diálogo entre maestros, directora y compañeras?"},{value:"25",text:"¿Comparte con nosotras actividades como: misas, proyectos de servicio social y encuentros?"},{value:"26",text:"¿Está al pendiente de mis calificaciones?"},{value:"27",text:"¿Me recomienda actividades y formas diferentes para mejorar mi desempeño académico?"},{value:"28",text:"Me ayuda a reflexionar sobre mi desempeño  académico?"},{value:"29",text:"¿Aplica y sigue el reglamento? "},{value:"30",text:"¿Me ayuda a reflexionar sobre mi comportamiento en el Colegio?"},{value:"31",text:"¿Me ayuda a identificar los límites y reglas que debo de seguir para bien común?"}],title:"Opción Múltiple"},{type:"comment",isRequired:true,name:"pab",rows:2,title:"¿Qué aprendiste de tu orientadora?"}]}]}; 
            break;
        case "3" : 
            surveyJSON = {"pages":[{"name":"faseUno","questions":[{"type":"matrix","columns":[{"value":"5","text":"Siempre"},{"value":"4","text":"Casi siempre"},{"value":"3","text":"En ocaciones"},{"value":"2","text":"Casi nunca"},{"value":"1","text":"Nunca"}],"name":"Fase1","rows":[{"value":"35","text":"¿Es importante lo que estás aprendiendo y comprendes cómo se relaciona con otras materias  y temas?"},{"value":"36","text":"¿En esta materia fue clara la forma de evaluación?"},{"value":"33","text":"¿Conoces las reglas de comportamiento dentro del salón de clases?"},{"value":"34","text":"¿Las reglas de comportamiento son aplicadas cuando es necesario?"},{"value":"37","text":"¿El maestro las motiva constantemente para tener un mejor desempeño? "},{"value":"38","text":"¿Las metas que pone el maestro son retadoras pero al mismo tiempo alcanzables? "},{"value":"39","text":"¿En el salón de clases se atienden las necesidades de cada una de las alumnas? "},{"value":"40","text":"¿Sientes que el maestro reconoce tus logros y tus dificultades? "},{"value":"41","text":"¿El maestro fomenta la participación y eres escuchada y tomada en cuenta? "},{"value":"42","text":"¿El maestro genera experiencias para colaborar y trabajar en equipo durante el curso? "}]}],"title":"Fase Uno"},{"name":"faseDos","questions":[{"type":"matrix","columns":[{"value":"5","text":"Siempre"},{"value":"4","text":"Casi siempre"},{"value":"3","text":"En ocaciones"},{"value":"2","text":"Casi nunca"},{"value":"1","text":"Nunca"}],"name":"Fase 2","rows":[{"value":"43","text":"¿Le tienes confianza al maestro para expresarle tus dudas, Preguntas y sugerencias?"},{"value":"44","text":"¿Cuándo tienes una dificultad el maestro te apoya? "},{"value":"45","text":"¿El salón de clases es un espacio seguro donde no son permitidas las agresiones de ningún tipo?"},{"value":"46","text":"¿En el salón de clases te sientes respetada por el maestro? "},{"value":"47","text":"¿Formas parte del grupo y te sientes integrada?"},{"value":"48","text":"¿El maestro consigue que esta materia te interese?"},{"value":"49","text":"¿El maestro es entusiasta y emprendedor con respecto a su materia?"},{"value":"50","text":"¿El maestro contribuye con la limpieza y orden del salón?"},{"value":"51","text":"¿El maestro optimiza los espacios - mueve las bancas, abre ventanas, ilumina, utiliza las paredes para pegar cosas- para favorecer el aprendizaje?"}]}],"title":"Fase Dos"}]};
            break;
        case "4" : 
            var surveyJSON = {pages:[{name:"page1",questions:[{type:"matrix",columns:[{value:"10",text:"Excelente"},{value:"9",text:"Superior"},{value:"8",text:"Adecuado"},{value:"7",text:"Insuficiente "},{value:"6",text:"Marginal"}],name:"evUno",rows:[{value:"52",text:"El maestro diseña su curso con base al momento de desarrollo de las alumnas, el mapa de contenidos, el programa de estudios y la transversalidad."},{value:"53",text:"El maestro planea e imparte las clases de manera estructurada, de acuerdo a la metodología clase sagrado corazón incluyendo un encuadre inicial y brindando instrucciones claras para cada actividad."},{value:"54",text:"El maestro es intencional en el desarrollo de los macro procesos de pensamiento en el diseño de sus clases."},{value:"55",text:"El maestro ajusta el programa y las lecciones al reto que cada grupo requiere para sentirse motivado."},{value:"56",text:"El maestro revisa conocimientos previos y ajusta el punto de partida de cada nueva lección de acuerdo al nivel del grupo. "},{value:"57",text:"El maestro utiliza una variedad de estrategias de enseñanza para favorecer la comprensión y mantener a las alumnas involucradas en la tarea."},{value:"58",text:"El maestro aplica la mediación para promover un aprendizaje significativo."},{value:"59",text:"El maestro maneja el error como fuente de aprendizaje."},{value:"60",text:"El maestro establece límites claros y consecuencias de comportamiento. "},{value:"61",text:"El maestro regula la conducta y resuelve los conflictos que se presentan en el grupo."},{value:"62",text:"El maestro reconoce y refuerza el buen comportamiento. "}],title:"Evaluacion Parte Uno"}]},{name:"page2",questions:[{type:"matrix",columns:[{value:"10",text:"Excelente"},{value:"9",text:"Superior"},{value:"8",text:"Adecuado"},{value:"7",text:"Insuficiente "},{value:"6",text:"Marginal"}],name:"evDos",rows:[{value:"63",text:"El maestro inicia y termina cada clase de manera puntual, utilizando el tiempo de manera efectiva."},{value:"64",text:"El maestro atiende de manera equitativa a todas las alumnas."},{value:"65",text:"El maestro utiliza efectivamente distintos recursos didácticos, incluyendo el uso de las TIC"},{value:"66",text:"El maestro aplica una variedad de estrategias de evaluación (incluyendo la autoevaluación y coevaluación)"},{value:"67",text:"El maestro brinda retroalimentación objetiva y oportuna al grupo y a las alumnas."},{value:"68",text:"El maestro solicita tareas pertinentes para el proceso de aprendizaje."},{value:"69",text:"El maestro revisa las tareas de manera consistente y oportuna para construir el aprendizaje. "},{value:"70",text:"El maestro aplica en cada clase la secuencia de entrada, elaboración y salida."},{value:"71",text:"El maestro consigue que la clase sea fluida y equilibrada en cuanto al tipo de actividades."},{value:"72",text:"El profesor propicia la elaboración de síntesis, conclusiones  y principios."},{value:"73",text:"El profesor verifica la comprensión de conceptos y transferencia a la vida real."},{value:"74",text:"El profesor revisa el proceso metacognitivo."}],title:"Evaluación Parte Dos"}]}]};
            break;
    }
	
	//var surveyJSON = {"pages":[{"name":"faseUno","questions":[{"type":"matrix","columns":[{"value":"5","text":"Siempre"},{"value":"4","text":"Casi siempre"},{"value":"3","text":"En ocaciones"},{"value":"2","text":"Casi nunca"},{"value":"1","text":"Nunca"}],"name":"Fase1","rows":[{"value":"35","text":"¿Es importante lo que estás aprendiendo y comprendes cómo se relaciona con otras materias  y temas?"},{"value":"36","text":"¿En esta materia fue clara la forma de evaluación?"},{"value":"33","text":"¿Conoces las reglas de comportamiento dentro del salón de clases?"},{"value":"34","text":"¿Las reglas de comportamiento son aplicadas cuando es necesario?"},{"value":"37","text":"¿El maestro las motiva constantemente para tener un mejor desempeño? "},{"value":"38","text":"¿Las metas que pone el maestro son retadoras pero al mismo tiempo alcanzables? "},{"value":"39","text":"¿En el salón de clases se atienden las necesidades de cada una de las alumnas? "},{"value":"40","text":"¿Sientes que el maestro reconoce tus logros y tus dificultades? "},{"value":"41","text":"¿El maestro fomenta la participación y eres escuchada y tomada en cuenta? "},{"value":"42","text":"¿El maestro genera experiencias para colaborar y trabajar en equipo durante el curso? "}]}],"title":"Fase Uno"},{"name":"faseDos","questions":[{"type":"matrix","columns":[{"value":"5","text":"Siempre"},{"value":"4","text":"Casi siempre"},{"value":"3","text":"En ocaciones"},{"value":"2","text":"Casi nunca"},{"value":"1","text":"Nunca"}],"name":"Fase 2","rows":[{"value":"43","text":"¿Le tienes confianza al maestro para expresarle tus dudas, Preguntas y sugerencias?"},{"value":"44","text":"¿Cuándo tienes una dificultad el maestro te apoya? "},{"value":"45","text":"¿El salón de clases es un espacio seguro donde no son permitidas las agresiones de ningún tipo?"},{"value":"46","text":"¿En el salón de clases te sientes respetada por el maestro? "},{"value":"47","text":"¿Formas parte del grupo y te sientes integrada?"},{"value":"48","text":"¿El maestro consigue que esta materia te interese?"},{"value":"49","text":"¿El maestro es entusiasta y emprendedor con respecto a su materia?"},{"value":"50","text":"¿El maestro contribuye con la limpieza y orden del salón?"},{"value":"51","text":"¿El maestro optimiza los espacios - mueve las bancas, abre ventanas, ilumina, utiliza las paredes para pegar cosas- para favorecer el aprendizaje?"}]}],"title":"Fase Dos"}]};
	
	function sendDataToServer(survey) {
	    //send Ajax request to your web server.
	    var data = JSON.stringify(survey.data);
	    //var data = survey.data;
	    console.log("The results are:" + JSON.stringify(survey.data));
	    //console.log("The results are:");
	    //console.dir(data);
	    var evaluador = $("#evaluaciones").attr("evaluador");
	    var asignacion = $("#evaluaciones").attr("asignacion");
	    var evaluacion = $("#evaluaciones").attr("evaluacion");
	    var conjunto = $("#evaluaciones").attr("conjunto");
	    
	    var urlComplement = "evaluador/"+evaluador+"/conjunto/"+conjunto+"/evaluacion/"+evaluacion+"/asignacion/"+asignacion;
	    
	    var urlProcessForm = url + "/json/processform/"+urlComplement;
	    console.log(urlProcessForm);
	    $.ajax({
	    	type: "POST",
	    	dataType: "json",
	    	url: urlProcessForm,
	    	data: {myData: data},
	    	success: function(datos){
	    		console.log('Datos recibidos por el server');
	    		console.dir(datos);
	    		var urlComp = "/conjunto/"+conjunto+"/evaluador/"+evaluador+"/evaluacion/"+evaluacion;
	    		var urlAsignaciones = url+"/index/asigns"+urlComp;
	    		var link = $("<a></a>").attr("class","btn btn-success").attr("href",urlAsignaciones).text("Continuar");
	    		$("#continueEvals").append(link);
	    	},
	    	error: function(e){
	    		console.log(e.message);
	    		//console.dir(e);
	    	}
	    });
	    
	}
	
	var survey = new Survey.Model(surveyJSON);
	$("#surveyContainer").Survey({
	    model: survey,
	    onComplete: sendDataToServer
	});
});