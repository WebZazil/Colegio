/**
 * @author Giovanni Rodriguez
 */
$().ready(function(){
	var appname = "/colegio/encuesta";
	var url = window.location.origin + appname;
	
	Survey.Survey.cssType = "bootstrap";
	
	var surveyJSON = {"pages":[{"name":"faseUno","questions":[{"type":"matrix","columns":[{"value":"5","text":"Siempre"},{"value":"4","text":"Casi siempre"},{"value":"3","text":"En ocaciones"},{"value":"2","text":"Casi nunca"},{"value":"1","text":"Nunca"}],"name":"Fase1","rows":[{"value":"35","text":"¿Es importante lo que estás aprendiendo y comprendes cómo se relaciona con otras materias  y temas?"},{"value":"36","text":"¿En esta materia fue clara la forma de evaluación?"},{"value":"33","text":"¿Conoces las reglas de comportamiento dentro del salón de clases?"},{"value":"34","text":"¿Las reglas de comportamiento son aplicadas cuando es necesario?"},{"value":"37","text":"¿El maestro las motiva constantemente para tener un mejor desempeño? "},{"value":"38","text":"¿Las metas que pone el maestro son retadoras pero al mismo tiempo alcanzables? "},{"value":"39","text":"¿En el salón de clases se atienden las necesidades de cada una de las alumnas? "},{"value":"40","text":"¿Sientes que el maestro reconoce tus logros y tus dificultades? "},{"value":"41","text":"¿El maestro fomenta la participación y eres escuchada y tomada en cuenta? "},{"value":"42","text":"¿El maestro genera experiencias para colaborar y trabajar en equipo durante el curso? "}]}],"title":"Fase Uno"},{"name":"faseDos","questions":[{"type":"matrix","columns":[{"value":"5","text":"Siempre"},{"value":"4","text":"Casi siempre"},{"value":"3","text":"En ocaciones"},{"value":"2","text":"Casi nunca"},{"value":"1","text":"Nunca"}],"name":"Fase 2","rows":[{"value":"43","text":"¿Le tienes confianza al maestro para expresarle tus dudas, Preguntas y sugerencias?"},{"value":"44","text":"¿Cuándo tienes una dificultad el maestro te apoya? "},{"value":"45","text":"¿El salón de clases es un espacio seguro donde no son permitidas las agresiones de ningún tipo?"},{"value":"46","text":"¿En el salón de clases te sientes respetada por el maestro? "},{"value":"47","text":"¿Formas parte del grupo y te sientes integrada?"},{"value":"48","text":"¿El maestro consigue que esta materia te interese?"},{"value":"49","text":"¿El maestro es entusiasta y emprendedor con respecto a su materia?"},{"value":"50","text":"¿El maestro contribuye con la limpieza y orden del salón?"},{"value":"51","text":"¿El maestro optimiza los espacios - mueve las bancas, abre ventanas, ilumina, utiliza las paredes para pegar cosas- para favorecer el aprendizaje?"}]}],"title":"Fase Dos"}]};
	
	function sendDataToServer(survey) {
	    //send Ajax request to your web server.
	    var data = JSON.stringify(survey.data);
	    //var data = survey.data;
	    //console.log("The results are:" + JSON.stringify(survey.data));
	    console.log("The results are:");
	    console.dir(data);
	    var evaluador = $("#evaluaciones").attr("evaluador");
	    var asignacion = $("#evaluaciones").attr("asignacion");
	    
	    var urlProcessForm = url + "/json/processform/evaluador/"+evaluador+"/asignacion/"+asignacion;
	    console.log(urlProcessForm);
	    $.ajax({
	    	type: "POST",
	    	dataType: "json",
	    	url: urlProcessForm,
	    	data: {myData: data},
	    	success: function(datos){
	    		console.log('Datos recibidos por el server');
	    		console.dir(datos);
	    	},
	    	error: function(e){
	    		console.log(e.message);
	    	}
	    });
	    
	}
	
	var survey = new Survey.Model(surveyJSON);
	$("#surveyContainer").Survey({
	    model: survey,
	    onComplete: sendDataToServer
	});
});