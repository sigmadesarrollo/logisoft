
function loadRss(mostrar){    
    $.getJSON("rss_manager/rss_FrontController.php?action=leer",function (data){
        escribirRss(data,mostrar);
    });

}

function escribirRss(feeds,mostrar){
    var escribir='';
   var  numfeeds=feeds.length;
   var  numleidos='xxx';
    escribir+="<div id='encabezado'>"+
             '<a  href="#" >mensajes(' + numfeeds +')</a>\n\
              <a  href="#" onClick="loadRss(true)">  REFRESCAR</a>  \n\
              </div>';
    escribir+="<div id='feeds'>";
    escribir+="<div id='cerrar' onclick='ocultar();'>X</div> ";
    for(var i=0;i<numfeeds;i++){
        escribir+='<div class="elemento_rss">';
        escribir+='<div class="titulo">';

        escribir+='<a  href="#" onClick="window.open('+"'"+ feeds[i].link+ "'"  + ');" > ' ;
        //escribir+=' <a  href="#" onClick="window.open('+"'"+
            //"'+ feeds[i].link+'");>';
            

        escribir+= feeds[i].titulo+'</a></div>';;
          // escribir+='<div class="titulo">'+ feeds[i].titulo+'</div>';;
           escribir+='<div class="fecha">'+ feeds[i].fecha+'</div>';;
           //escribir+='<div class="contenido">'+ feeds[i].contenido+'</div>';
       escribir+='</div>';        
    }
    escribir+="</div>";
    $('#rss').html(escribir);

    if (mostrar==false){
        $('#feeds').hide();
    }

    $('#encabezado').toggle(function() {
        $('#feeds').show('slow');                     
    }, function() {
        $('#feeds').hide('slow');
        //alert('Second handler for .toggle() called.');
    });
}
/*
function marcar(url){
    $.ajax({
        url: 'php/rss/rss_FrontController.php?action=marcar&feed_url='+url,
        success: function(data) {
            //$('#rss').html(data);
            //loadRss(true);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert ("ERROR");
        }
    });
    
}*/
function ocultar() {  
   $('#encabezado').click();
} //checkHover


