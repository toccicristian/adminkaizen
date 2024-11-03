    //en ajax.php llamo esta funcion asi
    //<li onclick='fill("<?php echo $resultado['Nombre']; ?>")'>
    //y le paso el resultado de la consulta...
    function fill(Value) {
         $('#search').val(Value);
         $('#display').hide();
       }
       $(document).ready(function() {
         $("#search").keyup(function() {//llamo a esta funcion cuando se presiona una tecla en el elemento de id search
             var nombre = $('#search').val();
             if (nombre == "") {
                 $("#display").html(""); //si el input de id search esta vacio, se limpian los resultados
             }
             else {
                //AJAX is called.
                 $.ajax({ //armo con AJAX un array de post con llave search que contiene nombre, se la paso a ajax.php
                     type: "POST",
                     url: "ajax.php",
                     data: {
                         search: nombre
                     },
                    //y además llamo a esta funcion que altera el contenido del div de id display
                     success: function(html) {
                         $("#display").html(html).show();
                     }
                 });
             }
         });
       });