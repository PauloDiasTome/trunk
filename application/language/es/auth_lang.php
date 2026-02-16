<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name:  Auth Lang - Spanish
 *
 * Author: Alfredo Braga
 *         alphabraga@hotmail.com
 *         @alphabraga
 *
 * Location: http://github.com/benedmunds/ion_auth/
 *
 * Created:  03.09.2013
 *
 * Description:  Spanish language file
 *
 */

// Errors
$lang['error_csrf'] = 'El envío de este formulario no cumplió con los requisitos de seguridad.';

// Login
$lang['login_heading']         = 'Iniciar Sesión';
$lang['login_subheading']      = 'Por favor, introduzca su usuario/email y contraseña a continuación.';
$lang['login_identity_label']  = 'Usuario/Email:';
$lang['login_password_label']  = 'Contraseña:';
$lang['login_remember_label']  = 'Recuérdame:';
$lang['login_submit_btn']      = 'Iniciar Sesión';
$lang['login_forgot_password'] = '¿Olvidó su contraseña?';

// Index
$lang['index_heading']           = 'Usuarios';
$lang['index_subheading']        = 'A continuación, una lista con los usuarios.';
$lang['index_fname_th']          = 'Nombre';
$lang['index_lname_th']          = 'Apellido';
$lang['index_email_th']          = 'Email';
$lang['index_groups_th']         = 'Grupos';
$lang['index_status_th']         = 'Estado';
$lang['index_action_th']         = 'Acción';
$lang['index_active_link']       = 'Activo';
$lang['index_inactive_link']     = 'Inactivo';
$lang['index_create_user_link']  = 'Crear nuevo usuario';
$lang['index_create_group_link'] = 'Crear nuevo grupo';

// Deactivate User
$lang['deactivate_heading']                  = 'Desactivar Usuario';
$lang['deactivate_subheading']               = '¿Está seguro de que desea desactivar a este usuario \'%s\'?';
$lang['deactivate_confirm_y_label']          = 'Sí:';
$lang['deactivate_confirm_n_label']          = 'No:';
$lang['deactivate_submit_btn']               = 'Enviar';
$lang['deactivate_validation_confirm_label'] = 'confirmación';
$lang['deactivate_validation_user_id_label'] = 'ID del usuario';

// Create User
$lang['create_user_heading']                           = 'Crear Usuario';
$lang['create_user_subheading']                        = 'Por favor, introduzca los datos del usuario.';
$lang['create_user_fname_label']                       = 'Nombre:';
$lang['create_user_lname_label']                       = 'Apellido:';
$lang['create_user_company_label']                     = 'Empresa:';
$lang['create_user_email_label']                       = 'Email:';
$lang['create_user_phone_label']                       = 'Teléfono:';
$lang['create_user_password_label']                    = 'Contraseña:';
$lang['create_user_password_confirm_label']            = 'Confirmar Contraseña:';
$lang['create_user_submit_btn']                        = 'Crear Usuario';
$lang['create_user_validation_fname_label']            = 'Nombre';
$lang['create_user_validation_lname_label']            = 'Apellido';
$lang['create_user_validation_email_label']            = 'Email';
$lang['create_user_validation_phone1_label']           = 'Primera parte del teléfono';
$lang['create_user_validation_phone2_label']           = 'Segunda parte del teléfono';
$lang['create_user_validation_phone3_label']           = 'Tercera parte del teléfono';
$lang['create_user_validation_company_label']          = 'Empresa';
$lang['create_user_validation_password_label']         = 'Contraseña';
$lang['create_user_validation_password_confirm_label'] = 'Confirmación de Contraseña';

// Edit User
$lang['edit_user_heading']                           = 'Editar Usuario';
$lang['edit_user_subheading']                        = 'Por favor, introduzca los datos del usuario a continuación.';
$lang['edit_user_fname_label']                       = 'Nombre:';
$lang['edit_user_lname_label']                       = 'Apellido:';
$lang['edit_user_company_label']                     = 'Empresa:';
$lang['edit_user_email_label']                       = 'Email:';
$lang['edit_user_phone_label']                       = 'Teléfono:';
$lang['edit_user_password_label']                    = 'Contraseña: (si desea cambiarla)';
$lang['edit_user_password_confirm_label']            = 'Confirmar Contraseña: (si desea cambiarla)';
$lang['edit_user_groups_heading']                    = 'Miembro de grupos';
$lang['edit_user_submit_btn']                        = 'Guardar Usuario';
$lang['edit_user_validation_fname_label']            = 'Nombre';
$lang['edit_user_validation_lname_label']            = 'Apellido';
$lang['edit_user_validation_email_label']            = 'Email';
$lang['edit_user_validation_phone1_label']           = 'Primera parte del teléfono';
$lang['edit_user_validation_phone2_label']           = 'Segunda parte del teléfono';
$lang['edit_user_validation_phone3_label']           = 'Tercera parte del teléfono';
$lang['edit_user_validation_company_label']          = 'Empresa';
$lang['edit_user_validation_groups_label']           = 'Grupos';
$lang['edit_user_validation_password_label']         = 'Contraseña';
$lang['edit_user_validation_password_confirm_label'] = 'Confirmar Contraseña';

// Create Group
$lang['create_group_title']                  = 'Crear Grupo';
$lang['create_group_heading']                = 'Crear Grupo';
$lang['create_group_subheading']             = 'Por favor, introduzca los datos del grupo a continuación.';
$lang['create_group_name_label']             = 'Nombre:';
$lang['create_group_desc_label']             = 'Descripción:';
$lang['create_group_submit_btn']             = 'Crear Grupo';
$lang['create_group_validation_name_label']  = 'Nombre';
$lang['create_group_validation_desc_label']  = 'Descripción';

// Edit Group
$lang['edit_group_title']                  = 'Editar Grupo';
$lang['edit_group_saved']                  = 'Grupo Guardado';
$lang['edit_group_heading']                = 'Editar Grupo';
$lang['edit_group_subheading']             = 'Por favor, introduzca los datos del grupo a continuación.';
$lang['edit_group_name_label']             = 'Nombre:';
$lang['edit_group_desc_label']             = 'Descripción:';
$lang['edit_group_submit_btn']             = 'Guardar Grupo';
$lang['edit_group_validation_name_label']  = 'Nombre';
$lang['edit_group_validation_desc_label']  = 'Descripción';

// Change Password
$lang['change_password_heading']                               = 'Cambiar Contraseña';
$lang['change_password_old_password_label']                    = 'Contraseña Antigua:';
$lang['change_password_new_password_label']                    = 'Nueva Contraseña: (mínimo %s caracteres)';
$lang['change_password_new_password_confirm_label']            = 'Confirmar Nueva Contraseña:';
$lang['change_password_submit_btn']                            = 'Cambiar';
$lang['change_password_validation_old_password_label']         = 'Contraseña Antigua';
$lang['change_password_validation_new_password_label']         = 'Nueva Contraseña';
$lang['change_password_validation_new_password_confirm_label'] = 'Confirmar Nueva Contraseña';

// Forgot Password
$lang['forgot_password_heading']                 = 'Olvidó su Contraseña';
$lang['forgot_password_subheading']              = 'Por favor, introduzca su %s para que podamos enviarle un mensaje para restablecer su contraseña.';
$lang['forgot_password_email_label']             = '%s:';
$lang['forgot_password_submit_btn']              = 'Enviar';
$lang['forgot_password_validation_email_label']  = 'Email';
$lang['forgot_password_username_identity_label'] = 'Usuario';
$lang['forgot_password_email_identity_label']    = 'Email';
$lang['forgot_password_email_not_found']         = 'Este email no fue encontrado.';

// Reset Password
$lang['reset_password_heading']                               = 'Cambiar Contraseña';
$lang['reset_password_new_password_label']                    = 'Nueva Contraseña: (mínimo %s caracteres)';
$lang['reset_password_new_password_confirm_label']            = 'Confirmar Nueva Contraseña:';
$lang['reset_password_submit_btn']                            = 'Cambiar';
$lang['reset_password_validation_new_password_label']         = 'Nueva Contraseña';
$lang['reset_password_validation_new_password_confirm_label'] = 'Confirmar Nueva Contraseña';
