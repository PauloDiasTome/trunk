<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Vista Waba
$lang['template_waba_header'] = "Plantillas";
$lang['template_waba_add'] = "Nueva plantilla";
$lang['template_waba_edit'] = "Editar plantilla";
$lang['template_waba_view'] = "Ver plantilla";

// Vista buscar
$lang['template_topnav'] = "Plantillas";
$lang['template_btn_new'] = "Nuevo";
$lang['template_btn_export'] = "Exportar";

$lang['template_column_creation'] = "Fecha de Creación";
$lang['template_column_name'] = "Nombre";
$lang['template_column_channel'] = "Canal";
$lang['template_column_category'] = "Categoría";
$lang['template_column_language'] = "Idioma";
$lang['template_column_status'] = "Estado";
$lang['template_column_actions'] = "Acciones";

$lang['template_export_title'] = "Exportación de datos";
$lang['template_export_email'] = "¿Desea enviar los datos al correo electrónico:";
$lang['template_export_email_placeholder'] = "Ingrese el correo electrónico para la exportación";
$lang['template_export_btn_confirm'] = "Confirmar";
$lang['template_export_btn_return'] = "Volver";

// Edición y adición genérica
$lang['template_select_option_none'] = "Ninguno";

$lang['template_creation'] = "Creación";
$lang['template_channel'] = "Canal";

$lang['template_name'] = "Nombre de la plantilla";
$lang['template_name_placeholder'] = "Ingrese el nombre de la plantilla";

$lang['template_lang'] = "Idioma";
$lang['template_port'] = "Portugués";
$lang['template_en'] = "Inglés";

$lang['template_header'] = "Encabezado";
$lang['template_header_placeholder'] = "Ingrese el texto del encabezado";
$lang['template_header_option_text'] = "Texto";
$lang['template_header_option_media'] = "Medios";
$lang['template_header_media_image'] = "Imagen";
$lang['template_header_media_video'] = "Vídeo";
$lang['template_header_media_document'] = "Documento";

$lang['template_body_label'] = "Cuerpo";
$lang['template_body_description'] = "Descripción: (Hasta 1024 caracteres) | Caracteres restantes:";
$lang['template_body_description_validation'] = "Descripción";
$lang['template_body_button_add_variable'] = "+ Agregar variable";
$lang['template_body_button_variable_first_last_error'] = "El texto del cuerpo contiene parámetros variables al principio o al final.";
$lang['template_body_button_variable_qtd_error'] = "El texto del cuerpo contiene demasiados parámetros variables en relación con la longitud del mensaje.";

$lang['template_footer_label'] = "Pie de página";
$lang['template_footer_placeholder'] = "Ingrese el texto del pie de página";

$lang['template_button_label'] = "Botones";
$lang['template_button_option_cta'] = "Llamada a la acción";
$lang['template_button_option_quick_answer'] = "Respuesta rápida";
$lang['template_button_cta_type'] = "Tipo de botón";
$lang['template_button_cta_type_call'] = "Llamar";
$lang['template_button_cta_type_url'] = "Acceder al sitio web";
$lang['template_button_text_placeholder'] = "Ingrese el texto del botón";
$lang['template_button_country_label'] = "País";
$lang['template_button_phone_label'] = "Teléfono";
$lang['template_button_url_type_label'] = "Tipo de URL";
$lang['template_button_url_type_option_static'] = "Estática";
$lang['template_button_url_type_option_dynamic'] = "Dinámica";
$lang['template_button_url_label'] = "URL del sitio";
$lang['template_button_text_label'] = "Texto del botón";
$lang['template_button_add_button'] = "+ Agregar otro botón";

$lang['template_api_validation'] = "Ocurrió un error en la creación de la plantilla. Intente nuevamente.";
$lang['template_api_validation_repeated_name'] = "El nombre no puede ser el mismo que una plantilla ya existente";
$lang['template_number'] = "1024";
$lang['template_baseboard'] = "Pie de página";

$lang['template_btn_cancel'] = "Cancelar";
$lang['template_btn_save'] = "Guardar";
$lang['template_btn_return'] = "Volver";

// Validación de adición

$lang['template_validation_name'] = "El campo nombre es obligatorio";
$lang['template_validation_same_name'] = "Ya se ha registrado una plantilla con el mismo nombre";
$lang['template_validation_same_name_deleted'] = "Una plantilla con este nombre está siendo eliminada";
$lang['template_validation_invalid_parameter_header'] = "El encabezado del mensaje no puede tener nuevas líneas, caracteres de formato, emojis o asteriscos.";
$lang['template_validation_invalid_parameter_button'] = "Los botones no pueden contener enlaces directos a WhatsApp.";
$lang['template_validation_header_text'] = "El texto del encabezado es obligatorio";
$lang['template_validation_header_media'] = "Seleccione un tipo de medios para el encabezado";
$lang['template_validation_body'] = "El campo cuerpo es obligatorio";
$lang['template_validation_button'] = "Complete todos los campos de los botones";
$lang['template_validation_url_button'] = "Ingrese una URL válida";
$lang['template_validation_url_button_phone'] = "Ingrese un número válido";
$lang['template_validation_general_error'] = "Ocurrió un error en la solicitud. Intente nuevamente más tarde";

// Vista agregar
$lang["template_add_title"] = "Nuevo";
$lang["template_add_information"] = "Agregar plantilla de mensaje";

// Vista ver
$lang["template_view_title"] = "Vista previa de la plantilla de mensaje";
$lang["template_view_information"] = "Información de la plantilla";
$lang['template_view_name'] = "Título";
$lang['template_view_channel'] = "Canal";
$lang['template_view_category'] = "Categoría";
$lang['template_view_language'] = "Idioma";
$lang['template_view_status'] = "Estado";
$lang['template_view_rejected_reason'] = "Razón del rechazo";

// Vista editar
$lang["template_edit_title"] = "Editar";
$lang["template_edit_information"] = "Editar plantilla de mensaje";

// Alerta eliminar
$lang['template_alert_delete_title'] = "¿Estás seguro?";
$lang['template_alert_delete_text'] = "¿Realmente deseas eliminar esta plantilla?";
$lang['template_alert_delete_confirmButtonText'] = "Sí";
$lang['template_alert_delete_cancelButtonText'] = "No";

$lang['template_alert_delete_two_title'] = "¡Eliminado!";
$lang['template_alert_delete_two_text'] = "¡Plantilla eliminada con éxito!";

$lang['template_alert_delete_two_title_error'] = "¡Error!";
$lang['template_alert_delete_two_text_error'] = "Ocurrió un error al intentar eliminar la plantilla";

// Alerta exportar
$lang['template_alert_export_title'] = "Atención";
$lang['template_alert_export_text'] = "El correo electrónico puede tardar hasta una hora en llegar.";
$lang['template_alert_export_confirmButtonText'] = "¡Ok!";

$lang['template_alert_export_two_title'] = "¡Lista enviada!";
$lang['template_alert_export_two_text'] = "El correo electrónico puede tardar hasta una hora en llegar.";
$lang['template_alert_export_two_confirmButtonText'] = "¡Ok!";

// JS
$lang['template_datatable_column_status_title_review'] = "En revisión";
$lang['template_datatable_column_status_title_approved'] = "Aprobado";
$lang['template_datatable_column_status_title_rejected'] = "Rechazado";
$lang['template_datatable_column_status_title_called'] = "Enviado";
$lang['template_datatable_column_status_title_delete'] = "Eliminar";
$lang['template_datatable_column_status_title_view'] = "Ver";
$lang['template_datatable_column_status_title_edit'] = "Editar";
