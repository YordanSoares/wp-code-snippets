<?php

/**
 * MOSTRAR TODOS LOS GANCHOS
 * Muestra todos los ganchos que se están ejecutando en la vista actual,
 * incluso los ganchos en la vista pública a usuarios desconectados,
 * por lo tanto, solo se debe ejecutar en modo de desarrollo.
 * 
 * Referencia: https://www.barrykooij.com/display-hooks-run-page/
 */


$debug_tags = array();
add_action( 'all', function ( $tag ) {
    global $debug_tags;
    if ( in_array( $tag, $debug_tags ) ) {
        return;
    }
    echo "<pre>" . $tag . "</pre>";
    $debug_tags[] = $tag;
} );