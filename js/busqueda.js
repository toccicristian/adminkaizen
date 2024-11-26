    function fill(Value) {
        $('#busquedaTareas').val(Value);
       }
    $(document).ready(function() {
         $("#busquedaTareas").keyup(function() {//llamo a esta funcion cuando se presiona una tecla en el elemento de id search
            var mostrarborradas =$('#mostrareliminadas').is(':checked');
            var nombre = $('#busquedaTareas').val();
            if (nombre == "") {
                $("#resultadoTareas").html(""); //si el input de id search esta vacio, se limpian los resultados
            }
            else {
                $.ajax({ //armo con AJAX un array de post con llave search que contiene nombre, se la paso a ajax.php
                    type: "POST",
                    url: "ajax/buscaTareas.php",
                    data: {
                        search: nombre,
                        mostrareliminadas: mostrarborradas
                    },
                    //y además llamo a esta funcion que altera el contenido del div de id display
                    success: function(html) {
                        $("#resultadoTareas").html(html).show();
                    }
                });
            }
        });

        $(document).ready(function() {
            $("#mostrareliminadas").change(function() { //esta funcion es para cuando tildo o destilo el "Mostrar eliminadas"
               var mostrarborradas =$('#mostrareliminadas').is(':checked');
               var nombre = $('#busquedaTareas').val();
               if (nombre == "") {
                   $("#resultadoTareas").html(""); //si el input de id search esta vacio, se limpian los resultados
               }
               else {
                   $.ajax({ //armo con AJAX un array de post con llave search que contiene nombre, se la paso a ajax.php
                       type: "POST",
                       url: "ajax/buscaTareas.php",
                       data: {
                           search: nombre,
                           mostrareliminadas: mostrarborradas
                       },
                       //y además llamo a esta funcion que altera el contenido del div de id display
                       success: function(html) {
                           $("#resultadoTareas").html(html).show();
                       }
                   });
               }
           })});







    $(document).ready(function() {
        $("#busquedaUsuarios").keyup(function() {
            var mostrarborrados = $('#mostrareliminados').is(':checked');
            var nombre = $('#busquedaUsuarios').val();
            if (nombre == "") {
                $("#resultadoUsuarios").html(""); 
            } else {
                $.ajax({
                    type: "POST",
                    url: "ajax/buscaUsuarios.php", 
                    data: {
                        search: nombre,
                        mostrareliminados: mostrarborrados
                    },
                    success: function(html) {
                        $("#resultadoUsuarios").html(html).show();
                    },
                    error: function() {
                        $("#resultadoUsuarios").html("Error al cargar los usuarios."); 
                    }
                });
            }
        });
    });

});