<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View waba
$lang['config_waba_edit'] = "Editar integración";

// View find 
$lang['config_channels_config'] = "Configuraciones de los canales";
$lang['config_btn_new'] = "Nuevo";
$lang['config_channel_list_to_config'] = "Lista de canales para configurar";
$lang['config_name'] = "Nombre";
$lang['config_id_talkall'] = "ID de Talkall";

$lang['config_notification_title'] = "¿Qué plataforma deseas conectar?";
$lang['config_notification_widget'] = "Widget";
$lang['config_notification_facebook'] = "Facebook";
$lang['config_notification_whatsapp'] = "Whatsapp";
$lang['config_notification_telegram'] = "Telegram";
$lang['config_notification_btn_close'] = "Cerrar";

// Generic edit and add
$lang['config_talkall_id'] = "ID de Talkall";
$lang['config_channel_name'] = "Nombre del canal";
$lang['config_current_value'] = "Crédito actual";
$lang['config_minimum_credit'] = "Crédito mínimo";
$lang['config_report'] = "Reporte";
$lang['config_minimum_credit_toolpip'] = "Recibirás una notificación cuando tu saldo actual sea menor que";
$lang['config_default_sector'] = "Sector por defecto";
$lang['config_time_zone'] = "Zona Horaria";
$lang['config_welcome_text'] = "Texto de bienvenida: (Hasta 1024 caracteres) | Caracteres restantes:";
$lang['config_welcome_text_placeholder'] = "Texto de bienvenida";
$lang['config_welcome_message'] = "Mensaje de bienvenida (opt-in): (Hasta 1024 caracteres) | Caracteres restantes:";
$lang['config_welcome_message_placeholder'] = "Mensaje de bienvenida";
$lang['config_automatic_message'] = "Mensaje automático";
$lang['config_automatic_message_placeholder'] = "Mensaje automático";
$lang['config_exit_label'] = "Configuraciones para Salir - (opt-out)";
$lang['config_opt_out_key'] = "Palabras clave para Opt Out";
$lang['config_opt_out_key_placeholder'] = "Palabras clave para Opt Out";
$lang['config_opt_out_message'] = "Mensaje de despedida: (Hasta 1024 caracteres) | Caracteres restantes:";
$lang['config_opt_out_message_placeholder'] = "Mensaje de despedida";
$lang['config_template_wa_business_contains_broadcast'] = "Mensaje de respuesta a la interacción:";
$lang['config_template_wa_business_contains_broadcast_placeholder'] = "Estas son nuestras ofertas";
$lang['config_template_wa_business_contains_broadcast_message_default'] = "Estas son nuestras ofertas";
$lang['config_template_wa_business_no_contains_broadcast'] = "Mensaje de respuesta a la interacción cuando no hay campañas:";
$lang['config_template_wa_business_no_contains_broadcast_placeholder'] = "En este momento, no tenemos ofertas disponibles. Por favor, confirma si has guardado nuestro número en tu agenda para que, en breve, recibas nuestras ofertas exclusivas.";
$lang['config_template_wa_business_no_contains_broadcast_message_default'] = "En este momento, no tenemos ofertas disponibles. Por favor, confirma si has guardado nuestro número en tu agenda para que, en breve, recibas nuestras ofertas exclusivas.";
$lang['config_return_to_channel_message'] = "Mensaje de retorno";
$lang['config_return_to_channel_message_placeholder'] = "Mensaje de retorno";
$lang['config_sector_change'] = "¿Transferir al sector?";
$lang['config_text_in_outside_office_hours'] = "Texto fuera del horario de atención: (Hasta 1024 caracteres) | Caracteres restantes:";
$lang['config_text_in_outside_office_hours_placeholder'] = "Texto fuera del horario de atención";
$lang['config_text_in_service_start'] = "Texto de inicio de la atención: (Hasta 1024 caracteres) | Caracteres restantes:";
$lang['config_text_in_service_start_placeholder'] = "Hola, ¿cómo estás? Mi nombre es *{USER_NAME}*, el protocolo de tu atención es *{PROTOCOL}*";
$lang['config_service_transfer_text'] = "Texto de transferencia de atención: (Hasta 1024 caracteres) | Caracteres restantes:";
$lang['config_service_trasnfer_text_placeholder'] = "Por favor, espere";
$lang['config_service_automatic_transfer_minute'] = "Tiempo para transferencia automática en minutos:";
$lang['config_service_automatic_transfer_minute_placeholder'] = "Introduce aquí el tiempo para transferencia automática en minutos";
$lang['config_profile_photo'] = "CAMBIAR FOTO DE PERFIL";
$lang['config_enable_service'] = "Activar atención";
$lang['config_enable_chatbot'] = "Activar Chatbot";
$lang['config_enable_automatic_transfer'] = "Activar Transferencia Automática";
$lang['config_enable_protocol'] = "Activar Protocolo";
$lang['config_enable_attendant_name'] = "Activar Envío del Nombre del Agente";
$lang['config_enable_attendant_name_tooltip'] = "Activa esta opción para incluir el nombre del agente en los mensajes enviados.";
$lang['config_btn_save'] = "Guardar";
$lang['config_btn_cancel'] = "Cancelar";
$lang['config_btn_return'] = "Volver";
$lang['config_btn_edit'] = "Editar";
$lang['config_lc_switch_active'] = "ACTIVO";
$lang['config_lc_switch_inactive'] = "INACTIVO";

// Profile labels
$lang['config_profile_info'] = "Información del Perfil";
$lang['config_service_info'] = "Configuraciones del canal";
$lang['config_commercial_name'] = "Nombre Comercial";
$lang['config_address'] = "Dirección";
$lang['config_address_placeholder'] = "Introduce la dirección";
$lang['config_email'] = "E-mail";
$lang['config_email_placeholder'] = "Introduce el e-mail";
$lang['config_website_social'] = "Sitio web o red social de la empresa";
$lang['config_website_social_placeholder'] = "Introduce el sitio web o red social de la empresa";
$lang['config_description'] = "Descripción: (Hasta 512 caracteres) | Caracteres restantes:";
$lang['config_description_maxlength_number'] = "512";
$lang['config_picture'] = "Foto de perfil: (500x500px)";
$lang['config_account_instagram'] = "Cuenta vinculada a Instagram";

// AI Evaluation
$lang['config_ai_evaluation_enable'] = "Habilitar Evaluación de Atención con IA";
$lang['config_ai_evaluation_alert_title_attention'] = "Atención";
$lang['config_ai_evaluation_alert_body_no_ai_registered'] = "Para habilitar esta funcionalidad, es necesario tener una integración con OpenAI registrada";
$lang['config_ai_evaluation_alert_body_additional_costs'] = "Al activar esta opción, se podrán generar costos adicionales";
$lang['config_ai_evaluation_alert_body_select_option'] = "Selecciona una opción para la evaluación de atención con IA";
$lang['config_ai_evaluation_alert_button_ok'] = "Ok";
$lang['config_ai_evaluation_alert_validation_select_option'] = "Evaluación de Atención con IA";
$lang['config_ai_evaluation_service_selection'] = "Selecciona una opción";
$lang['config_ai_evaluation_select'] = "Seleccionar";
$lang['config_ai_evaluation_all_services'] = "Evaluar todas las atenciones";
$lang['config_ai_evaluation_specific_quantity_services'] = "Evaluar %s atenciones diarias";

// View edit
$lang["config_edit_title"] = "Editar";
$lang["config_edit_information"] = "Información de las configuraciones";
$lang["config_input_select"] = "Seleccionar";

$lang['config_dropdown_add_new_work_time'] = "Añadir nueva tabla de horarios";
$lang['config_dropdown_add_new_work_time_title'] = "Haz clic aquí para añadir nuevo";

$lang['config_worktime_none'] = "Ninguno";
$lang['config_worktime_title'] = "Período de envío";
$lang['config_worktime_tooltip'] = "El período configurado se valida en los envíos. Cualquier campaña cuya entrega esté prevista fuera del período configurado será alertada en la programación.";
$lang['config_worktime_information'] = "Información de la tabla de horarios";
$lang['config_worktime_subtitle_label'] = "Nombre de la tabla de horarios";
$lang['config_worktime_subtitle_placeholder'] = "Introduce el nombre";
$lang['config_worktime_monday'] = "Lunes";
$lang['config_worktime_tuesday'] = "Martes";
$lang['config_worktime_wednesday'] = "Miércoles";
$lang['config_worktime_thursday'] = "Jueves";
$lang['config_worktime_friday'] = "Viernes";
$lang['config_worktime_saturday'] = "Sábado";
$lang['config_worktime_sunday'] = "Domingo";
$lang['config_worktime_start_placeholder'] = "Inicio";
$lang['config_worktime_end_placeholder'] = "Fin";
$lang['config_worktime_btn_return'] = "Volver";
$lang['config_worktime_btn_save'] = "Guardar";

$lang['config_validation_modal_name_required'] = "El campo Nombre es obligatorio.";
$lang['config_validation_modal_name_min_length'] = "El campo Nombre debe tener al menos {param} caracter(es).";
$lang['config_validation_modal_name_max_length'] = "El campo Nombre ha superado el límite de {param} caracter(es).";
$lang['config_validation_modal_start_end_required'] = "Rellena todos los campos";

// View advanced
$lang['config_adv_title'] = "Configuraciones avanzadas";
$lang['config_adv_company_name'] = "Nombre de la empresa";
$lang['config_adv_company_name_placeholder'] = "Introduce el nombre de la empresa, por favor";
$lang['config_adv_hide_profile_picture'] = "Ocultar imagen de perfil de los contactos";
$lang['config_adv_hide_number'] = "Ocultar números de los contactos";
$lang['config_adv_company_cnpj'] = "CIF/NIF de la empresa";
$lang['lanf']['config_adv_company_cnpj_placeholder'] = "Introduce el CIF/NIF de la empresa, por favor";
$lang['config_adv_btn_save_and_return'] = "Guardar y volver";

// alert dropzone
$lang['config_alert_dropzone_maxSize_title'] = "Atención";
$lang['config_alert_dropzone_maxSize_text'] = "Límite máximo de 10 MB por archivo";
$lang['config_alert_dropzone_maxSize_confirmButtonText'] = "¡Ok!";

$lang['config_alert_dropzone_arquive_title'] = "Atención";
$lang['config_alert_dropzone_arquive_text'] = "Archivo no permitido";
$lang['config_alert_dropzone_arquive_confirmButtonText'] = "¡Ok!";

// form_validation
$lang['config_validation_call_start_text'] = "Texto de inicio de la atención";
$lang['config_validation_welcome_text'] = "Texto de bienvenida";
$lang['config_validation_template_wa_business_contains_broadcast_text'] = "Mensaje para cuando haya ofertas actuales";
$lang['config_validation_template_wa_business_no_contains_broadcast_text'] = "Mensaje para cuando no haya ofertas actuales";
$lang['config_validation_have_current_offers'] = "tiene ofertas vigentes";
$lang['config_validation_no_have_current_offers'] = "no tiene ofertas vigentes";
$lang['config_validation_automatic_tranfer_time'] = "Tiempo para transferencia automática";
$lang['config_validation_name_channel'] = "¡Es obligatorio informar el Nombre del Canal!";
$lang['config_validation_of_the_channel_default_message_other_than_business'] = "Introduce el campo obligatorio para habilitar el";
$lang['config_validation_of_the_channel_default_message_for_business'] = "Introduce los datos correctamente para guardar el mensaje cuando";


$lang['config_edit_channel_validation_name'] = "El campo Nombre es obligatorio";
$lang['config_edit_channel_validation_name_min_length'] = "El campo Nombre debe tener al menos {param} caracter(es)";
$lang['config_edit_channel_validation_name_max_length'] = "El campo Nombre ha superado el límite de {param} caracter(es).";

$lang['config_edit_channel_validation_description'] = "El campo Descripción es obligatorio";
$lang['config_edit_channel_validation_description_min_length'] = "El campo Descripción debe tener al menos {param} caracter(es)";
$lang['config_edit_channel_validation_description_max_length'] = "El campo Descripción ha superado el límite de {param} caracter(es).";

$lang['config_edit_channel_validation_email'] = "El campo E-mail es obligatorio";
$lang['config_edit_channel_validation_email_min_length'] = "El campo E-mail debe tener al menos {param} caracter(es)";
$lang['config_edit_channel_validation_email_max_length'] = "El campo E-mail ha superado el límite de {param} caracter(es).";

$lang['config_edit_channel_validation_site'] = "El campo Sitio web es obligatorio";
$lang['config_edit_channel_validation_site_min_length'] = "El campo Sitio web debe tener al menos {param} caracter(es)";
$lang['config_edit_channel_validation_site_max_length'] = "El campo Sitio web ha superado el límite de {param} caracter(es).";

$lang['config_edit_channel_alert_dropzone_maxSize_title'] = "Atención";
$lang['config_edit_channel_alert_dropzone_maxSize_text'] = "Límite máximo de 5 MB por archivo";
$lang['config_edit_channel_alert_dropzone_maxSize_confirmButtonText'] = "¡Ok!";

$lang['config_edit_channel_alert_dropzone_format_title'] = "Atención";
$lang['config_edit_channel_alert_dropzone_format_text'] = "Archivo inválido. Por favor, introduce una imagen jpg";
$lang['config_edit_channel_alert_dropzone_format_confirmButtonText'] = "¡Ok!";

$lang['config_edit_channel_alert_loading_img_title'] = "Atención";
$lang['config_edit_channel_alert_loading_img_text'] = "¡No es posible eliminar la imagen mientras se está cargando!";
$lang['config_edit_channel_alert_loading_img_confirmButtonText'] = "¡Ok!";

// Informativo
$lang['informative_msg'] = "¡Informativo!";
$lang['tooltip_informative'] = "Nombre que se mostrará internamente en la plataforma";
$lang["tooltip_informative_template_wa_business_contains_broadcast"] = "Este mensaje se envía junto con la(s) campaña(s) cuando una o más están configuradas como respuesta rápida.";
$lang["tooltip_informative_template_wa_business_no_contains_broadcast"] = "Este mensaje se envía cuando ninguna campaña está configurada como respuesta rápida del canal.";

// View edit_channel
$lang["config_edit_channel_title"] = "Editar Canal";
$lang["config_edit_channel_name"] = "Nombre del canal";
$lang["config_edit_channel_name_placeholder"] = "Introduce el nombre del canal";
$lang["config_edit_channel_name_tooltip_informative"] = "Información interna, no se actualizará en WhatsApp";
$lang["config_edit_channel_number"] = "Número del canal";
$lang["config_edit_channel_number_placeholder"] = "Introduce el número del canal";
$lang["config_edit_channel_description"] = "Descripción del canal";
$lang["config_edit_channel_description_placeholder"] = "Introduce la descripción del canal";
$lang["config_edit_channel_email"] = "E-mail";
$lang["config_edit_channel_email_placeholder"] = "Introduce el E-mail";
$lang["config_edit_channel_site"] = "Sitio web";
$lang["config_edit_channel_site_placeholder"] = "Introduce el sitio web";
$lang["config_edit_channel_profile_tooltip_informative"] = "Añade una imagen en formato jpeg, cuadrada, con dimensiones de 640x640px y un tamaño máximo de 5mb.";
$lang["config_edit_channel_profile"] = "Foto de perfil";
$lang["config_edit_channel_profile_title"] = "Foto de Perfil";
$lang["config_edit_channel_change_profile"] = "Cambiar Foto";

// Mensagem de boas vindas 
$lang["config_edit_channel_welcome_message"] = "Mensaje de bienvenida";
$lang["config_edit_channel_welcome_message_description"] = "El mensaje de bienvenida o de saludo es la primera comunicación de tu empresa con el usuario";
$lang["config_edit_channel_welcome_message_modal_title"] = "Mensaje de bienvenida";
$lang["config_edit_channel_welcome_message_modal_description"] = "Escribe el texto de bienvenida (opt-in) del canal";
$lang["config_edit_channel_welcome_message_modal_description_placeholder"] = "¡Bienvenido! Para recibir nuestras ofertas diariamente, por favor añade este número a tu agenda, envía Quiero ofertas y mantente al día de nuestras novedades.";

$lang["config_edit_channel_welcome_message_modal_validation_description"] = "El campo descripción del mensaje de bienvenida es obligatorio";

// Política de privacidade
$lang["config_edit_channel_privacy_policy"] = "Política de privacidad";
$lang["config_edit_channel_privacy_policy_no"] = "No";
$lang["config_edit_channel_privacy_policy_yes"] = "Sí";
$lang["config_edit_channel_privacy_policy_description"] = "Para que el usuario se suscriba a tu canal, es necesario que acepte las políticas de privacidad, de acuerdo con la Ley de Protección de Datos.";
$lang["config_edit_channel_privacy_policy_message_one"] = "Bienvenido a Novedades de TalkAll 🎉";
$lang["config_edit_channel_privacy_policy_message_two"] = "Aquí está nuestra política de privacidad 👇";
$lang["config_edit_channel_privacy_policy_message_link"] = "Ir a las políticas de privacidad";
$lang["config_edit_channel_privacy_policy_agree"] = "¿Estás de acuerdo con nuestra política de privacidad?";
$lang["config_edit_channel_privacy_policy_btn_agree"] = "Acepto";
$lang["config_edit_channel_privacy_policy_btn_dont_agree"] = "No acepto";

$lang['config_edit_channel_privacy_policy_modal_title'] = "Política de privacidad";
$lang['config_edit_channel_privacy_policy_modal_welcome'] = "Da la bienvenida al cliente";
$lang['config_edit_channel_privacy_policy_modal_welcome_value'] = "Bienvenido a Novedades de TalkAll 🎉";
$lang['config_edit_channel_privacy_policy_modal_welcome_placeholder'] = "Escribe la bienvenida al cliente";
$lang['config_edit_channel_privacy_policy_modal_msg_info_privacy'] = "Informa sobre la política de privacidad";
$lang['config_edit_channel_privacy_policy_modal_msg_info_privacy_value'] = "Aquí está nuestra política de privacidad 👇";
$lang['config_edit_channel_privacy_policy_modal_msg_info_privacy_placeholder'] = "Informa sobre la política de privacidad";
$lang['config_edit_channel_privacy_policy_modal_link_policy'] = "Enlace de la política de privacidad";
$lang['config_edit_channel_privacy_policy_modal_link_policy_value'] = "https://talkall.com.br/politicas-de-privacidade/";
$lang['config_edit_channel_privacy_policy_modal_link_policy_placeholder'] = "Introduce el enlace de la política de privacidad";
$lang['config_edit_channel_privacy_policy_modal_toggle'] = "Añadir reacción";
$lang['config_edit_channel_privacy_policy_modal_toggle_term_agree'] = "en acepto la política de privacidad.";
$lang['config_edit_channel_privacy_policy_modal_toggle_term_dont_agree'] = "en no acepto la política de privacidad.";

$lang['config_edit_channel_privacy_policy_modal_validation_emoji'] = "No has seleccionado un emoji";

// Opt-in
$lang["config_edit_channel_opt_in"] = "Opt-in";
$lang["config_edit_channel_opt_in_description"] = "Después de aceptar la Política de Privacidad, el usuario debe recibir instrucciones para realizar el Opt-in, interactuando con el canal y añadiendo el número a su agenda de contactos.";
$lang["config_edit_channel_opt_in_message"] = "¡Finaliza tu registro para recibir novedades increíbles!";
$lang["config_edit_channel_opt_in_message_options"] = "1 - Haz clic en el número de WhatsApp, en la esquina superior de la pantalla
2 - Ahora haz clic en el botón Crear contacto;
3 - Por último, haz clic en guardar.

Si deseas salir, envía #salir.";

$lang['config_edit_channel_opt_in_modal_title'] = "Opt-in";
$lang['config_edit_channel_opt_in_modal_customer_invitation'] = "Invita al cliente a finalizar el Opt-in";
$lang['config_edit_channel_opt_in_modal_customer_invitation_value'] = "¡Finaliza tu registro para recibir novedades increíbles!";
$lang['config_edit_channel_opt_in_modal_customer_invitation_placeholder'] = "Escribe la invitación para que el cliente finalice el Opt-in";
$lang['config_edit_channel_opt_in_modal_complete_opt_in'] = "Enseña cómo completar el Opt-in";
$lang['config_edit_channel_opt_in_modal_complete_opt_in_placeholder'] = "Escribe aquí..";
$lang['config_edit_channel_opt_in_modal_complete_opt_in_value'] = "1 - Haz clic en el número de WhatsApp, en la esquina superior de la pantalla;
2 - Ahora haz clic en el botón Crear contacto;
3 - Por último, haz clic en guardar.

Si deseas salir, envía #salir.";

$lang['config_edit_channel_opt_in_modal_add_imagem'] = "Puedes añadir una imagen para ilustrar el proceso.";
$lang['config_edit_channel_opt_in_modal_add_imagem_buttom'] = "Añadir";
$lang['config_edit_channel_opt_in_modal_remove_imagem_buttom'] = "Eliminar";
$lang['config_edit_channel_opt_in_modal_add_imagem_description'] = "Recomendamos el formato jpg, 800x800 px y un máximo de 5mb.";

$lang['config_edit_channel_opt_in_modal_validation_customer_invitation'] = "El campo 'Invita al cliente a finalizar el Opt-in' es obligatorio";

// Opt-out
$lang["config_edit_channel_opt_out"] = "Opt-out";
$lang["config_edit_channel_opt_out_description"] = "Crea un mensaje de cierre para informar al usuario que no está suscrito al canal por no aceptar la Política de Privacidad o no completar el Opt-in. Aprovecha también este espacio para recordarle que este es solo un canal de comunicación.";
$lang["config_edit_channel_opt_out_message"] = "Lamentamos que desees salir. Por favor, infórmanos el motivo seleccionando una de las opciones a continuación.";
$lang["config_edit_channel_opt_out_list"] = "Seleccionar opciones";

$lang['config_edit_channel_opt_out_modal_title'] = "Opt-out";
$lang['config_edit_channel_opt_out_modal_leave_opt_out'] = "Informa sobre el Opt-out";
$lang['config_edit_channel_opt_out_modal_leave_opt_out_value'] = "Si deseas salir, envía #salir.";
$lang['config_edit_channel_opt_out_modal_leave_opt_out_value_placeholder'] = "Salir";
$lang['config_edit_channel_opt_out_modal_leave_opt_out_placeholder'] = "Informa sobre el Opt-out";
$lang['config_edit_channel_opt_out_modal_keyword'] = "Palabras clave del Opt-out (presiona la tecla enter para separarlas)";
$lang['config_edit_channel_opt_out_modal_keyword_value'] = "#salir,#SALIR,#Salir,#chau,#CHAU,#Chau";
$lang['config_edit_channel_opt_out_modal_question_reason'] = "Pregunta el motivo del opt-out";
$lang['config_edit_channel_opt_out_modal_question_reason_value'] = "Lamentamos que desees salir de nuestro canal. Por favor, informa el motivo seleccionando una de las opciones a continuación.";
$lang['config_edit_channel_opt_out_modal_question_reason_placeholder'] = "Introduce el motivo del opt-out";
$lang['config_edit_channel_opt_out_modal_reason_one'] = "Motivo del opt-out 01";
$lang['config_edit_channel_opt_out_modal_reason_one_value'] = "No tengo tiempo para ver las promociones.";
$lang['config_edit_channel_opt_out_modal_reason_one_placeholder'] = "Introduce el motivo del opt-out 01";
$lang['config_edit_channel_opt_out_modal_reason_two'] = "Motivo del opt-out 02";
$lang['config_edit_channel_opt_out_modal_reason_two_value'] = "Estoy recibiendo muchos mensajes.";
$lang['config_edit_channel_opt_out_modal_reason_two_placeholder'] = "Introduce el motivo del opt-out 02";
$lang['config_edit_channel_opt_out_modal_reason_three'] = "Motivo del opt-out 03";
$lang['config_edit_channel_opt_out_modal_reason_three_value'] = "Las ofertas no son ventajosas.";
$lang['config_edit_channel_opt_out_modal_reason_three_placeholder'] = "Introduce el motivo del opt-out 03";
$lang['config_edit_channel_opt_out_modal_reason_four'] = "Motivo del opt-out 04";
$lang['config_edit_channel_opt_out_modal_reason_four_value'] = "No tengo interés en los productos.";
$lang['config_edit_channel_opt_out_modal_reason_four_placeholder'] = "Introduce el motivo del opt-out 04";
$lang['config_edit_channel_opt_out_modal_reason_five'] = "Motivo del opt-out 05";
$lang['config_edit_channel_opt_out_modal_reason_five_value'] = "Me mudé y ya no compro en la tienda.";
$lang['config_edit_channel_opt_out_modal_reason_five_placeholder'] = "Introduce el motivo del opt-out 05";

$lang['config_edit_channel_opt_out_modal_validation_optout_leave'] = "El campo 'informa sobre el opt-out' es obligatorio";
$lang['config_edit_channel_opt_out_modal_validation_optout_keyword'] = "El campo 'informa las palabras clave del opt-out' es obligatorio";

// Mensagem de encerramento
$lang["config_edit_channel_closed_message"] = "Mensaje de cierre";
$lang["config_edit_channel_closed_message_description"] = "Si el usuario no acepta la política de privacidad o realiza el opt-out, se presentará un mensaje de cierre.";
$lang["config_edit_channel_closed_message_call_closed"] = "¡Qué pena! Vamos a cerrar tu registro.";
$lang["config_edit_channel_closed_message_contact_return"] = "Si cambias de opinión, envía Hola y nos pondremos en contacto. ¡Hasta luego!";

$lang['config_edit_channel_closed_message_modal_title'] = "Mensaje de cierre";
$lang['config_edit_channel_closed_message_modal_call_closed'] = "Escribe un mensaje informando que lamentas la salida del usuario.";
$lang['config_edit_channel_closed_message_modal_call_closed_value'] = "¡Qué pena! Vamos a cerrar tu registro.";
$lang['config_edit_channel_closed_message_modal_call_closed_placeholder'] = "Informa que lamentas el cierre";
$lang['config_edit_channel_closed_message_modal_contact_return'] = "Explica cómo el usuario puede reanudar el contacto.";
$lang['config_edit_channel_closed_message_modal_contact_return_value'] = "Si cambias de opinión, envía Hola y nos pondremos en contacto. ¡Hasta luego!";
$lang['config_edit_channel_closed_message_modal_contact_return_placeholder'] = "Informa cómo el usuario puede reanudar el contacto";

$lang['config_edit_channel_closed_message_modal_validation_call_closed'] = "El campo 'Mensaje de salida del usuario' es obligatorio";

// Mensagens automáticas
$lang["config_edit_channel_automatic_messages"] = "Mensajes automáticos";
$lang["config_edit_channel_automatic_messages_description"] = "Los mensajes automáticos se envían siempre que el usuario interactúa con el canal. Es importante recordarle que el canal es solo para envíos.";
$lang["config_edit_channel_automatic_messages_info_attendance"] = "⚠️ Este canal es para el envío de novedades de TalkAll.";
$lang["config_edit_channel_automatic_messages_about_attendance"] = "Para soporte y dudas, ve a atención al cliente 👇";
$lang["config_edit_channel_automatic_messages_phone_attendance"] = "Ir a atención al cliente";

$lang['config_edit_channel_automatic_messages_modal_title'] = "Mensajes automáticos";
$lang['config_edit_channel_automatic_messages_modal_info_attendance'] = "Deja claro que el canal es exclusivo para el envío de información.";
$lang['config_edit_channel_automatic_messages_modal_info_attendance_value'] = "⚠️ Este canal es para el envío de novedades de TalkAll. ";
$lang['config_edit_channel_automatic_messages_modal_info_attendance_placeholder'] = "Informa que el canal es exclusivo para el envío de información";
$lang['config_edit_channel_automatic_messages_modal_about_attendance'] = "Informa sobre el canal de atención al cliente.";
$lang['config_edit_channel_automatic_messages_modal_about_attendance_value'] = "Para soporte y dudas, ve a atención al cliente 👇";
$lang['config_edit_channel_automatic_messages_modal_about_attendance_placeholder'] = "Informa sobre el canal de atención al cliente.";
$lang['config_edit_channel_automatic_messages_modal_phone_attendance'] = "Número para atención al cliente.";
$lang['config_edit_channel_automatic_messages_modal_phone_attendance_value'] = "5543999999999";
$lang['config_edit_channel_automatic_messages_modal_phone_attendance_placeholder'] = "Introduce un número de WhatsApp";

$lang['config_edit_channel_automatic_messages_modal_validation_info_attendance'] = "Deja claro que el canal es exclusivo para el envío de información";