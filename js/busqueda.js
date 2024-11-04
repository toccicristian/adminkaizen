    //en ajax.php llamo esta funcion asi
    //<li onclick='fill("<?php echo $resultado['Nombre']; ?>")'>
    //y le paso el resultado de la consulta...
    function fill(Value) {
        $('#busquedaTareas').val(Value);
        $('#resultadoTareas').hide();
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
                    url: "ajax.php",
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
        });