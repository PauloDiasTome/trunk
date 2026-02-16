<?php

/**
 * Traducción de mensajes del sistema para CodeIgniter(tm)
 *
 * @author Comunidad de CodeIgniter
 * @copyright Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license http://opensource.org/licenses/MIT Licencia MIT
 * @link http://codeigniter.com
 */
defined('BASEPATH') or exit('No se permite el acceso directo al script');

$lang['db_invalid_connection_str'] = 'No se pudo determinar la configuración de la base de datos basada en la cadena de conexión que enviaste.';
$lang['db_unable_to_connect'] = 'No se pudo conectar a tu base de datos utilizando la configuración proporcionada.';
$lang['db_unable_to_select'] = 'No se pudo seleccionar la base de datos especificada: %s';
$lang['db_unable_to_create'] = 'No se pudo crear la base de datos especificada: %s';
$lang['db_invalid_query'] = 'La consulta que enviaste no es válida.';
$lang['db_must_set_table'] = 'Debes configurar la tabla en tu base de datos para usarla con tu consulta.';
$lang['db_must_use_set'] = 'Debes usar el método "set" para actualizar un registro.';
$lang['db_must_use_index'] = 'Debes especificar un índice para que coincida con tus actualizaciones en lote.';
$lang['db_batch_missing_index'] = 'Una o más filas enviadas para actualización en lote están faltando el índice especificado.';
$lang['db_must_use_where'] = 'Las actualizaciones no están permitidas a menos que exista la cláusula "where".';
$lang['db_del_must_use_where'] = 'Las eliminaciones no están permitidas a menos que exista la cláusula "where" o "like".';
$lang['db_field_param_missing'] = 'Para buscar campos se requiere el nombre de la tabla como parámetro.';
$lang['db_unsupported_function'] = 'Esta funcionalidad no está disponible para la base de datos que estás usando.';
$lang['db_transaction_failure'] = 'Fallo en la transacción: Rollback ejecutado.';
$lang['db_unable_to_drop'] = 'No se pudo eliminar la base de datos especificada.';
$lang['db_unsupported_feature'] = 'Funcionalidad no soportada en la base de datos que estás usando.';
$lang['db_unsupported_compression'] = 'El formato de compresión de archivos que elegiste no es compatible con tu servidor.';
$lang['db_filepath_error'] = 'No se pudo escribir los datos en el archivo que enviaste.';
$lang['db_invalid_cache_path'] = 'El camino del cache que enviaste no es válido o escribible.';
$lang['db_table_name_required'] = 'El nombre de la tabla es obligatorio para esta operación.';
$lang['db_column_name_required'] = 'El nombre de la columna es obligatorio para esta operación.';
$lang['db_column_definition_required'] = 'La definición de la columna es obligatoria para esta operación.';
$lang['db_unable_to_set_charset'] = 'No se puede configurar el conjunto de caracteres de la conexión cliente: %s';
$lang['db_error_heading'] = 'Ocurrió un error en la base de datos';
