<?php

/**
 * Función para quitar métodos dentro de ganchos de WordPress.
 * Esta función utilitaria permite quitar un método que se ejecute
 * dentro de un gancho de filtro o de acción de WordPress.
 * Referencia: https://wordpress.stackexchange.com/a/339046/170886
 */

/*
  MODO DE USO:
  
  Coincidencia exacta (Hook, Clase, Método, Prioridad):
    add_action('plugins_loaded', function() {
      desactivar_accion_desde_clase('hook', 'Nombre_Clase', 'metodo', 0);
    });
  
  Cualquier Proridad (Hook, Clase, Método):
    add_action('plugins_loaded', function() {
      desactivar_accion_desde_clase('hook', 'Nombre_Clase', 'metodo');
    });

  Cualquier clase y prioridad (Hook, Clase vacía, Método):
    add_action('plugins_loaded', function() {
      desactivar_accion_desde_clase('hook', '', 'metodo');
    });  
 */

function desactivar_accion_desde_clase($tag, $class = '', $method, $priority = null): bool
{
  global $wp_filter;

  if (isset($wp_filter[$tag])) {
    $len = strlen($method);

    foreach ($wp_filter[$tag] as $_priority => $actions) {

      if ($actions) {
        foreach ($actions as $function_key => $data) {

          if ($data) {
            if (substr($function_key, -$len) == $method) {

              if ($class !== '') {
                $_class = '';
                if (is_string($data['function'][0])) {
                  $_class = $data['function'][0];
                } elseif (is_object($data['function'][0])) {
                  $_class = get_class($data['function'][0]);
                } else {
                  return false;
                }

                if ($_class !== '' && $_class == $class) {
                  if (is_numeric($priority)) {
                    if ($_priority == $priority) {
                      //if (isset( $wp_filter->callbacks[$_priority][$function_key])) {}
                      return $wp_filter[$tag]->remove_filter($tag, $function_key, $_priority);
                    }
                  } else {
                    return $wp_filter[$tag]->remove_filter($tag, $function_key, $_priority);
                  }
                }
              } else {
                if (is_numeric($priority)) {
                  if ($_priority == $priority) {
                    return $wp_filter[$tag]->remove_filter($tag, $function_key, $_priority);
                  }
                } else {
                  return $wp_filter[$tag]->remove_filter($tag, $function_key, $_priority);
                }
              }
            }
          }
        }
      }
    }
  }
  return false;
}
