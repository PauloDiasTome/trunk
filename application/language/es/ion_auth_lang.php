<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name:  Ion Auth Lang - Portuguese (UTF-8)
 *
 * Author: André Brás Simões
 *       andrebrassimoes@gmail.com
 *
 * Adjustments by @Dentxinho and @MichelAlonso and @marcelod
 *
 * Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
 *
 * Created:  17.05.2010
 *
 * Description:  Portuguese language file for Ion Auth messages and errors
 *
 */

// Account Creation
$lang['account_creation_successful']             = 'Cuenta creada con éxito';
$lang['account_creation_unsuccessful']           = 'No se pudo crear la cuenta';
$lang['account_creation_duplicate_email']        = 'Correo electrónico en uso o inválido';
$lang['account_creation_duplicate_username']     = 'Nombre de usuario en uso o inválido';
$lang['account_creation_missing_default_group'] = 'Grupo predeterminado no está definido';
$lang['account_creation_invalid_default_group'] = 'El nombre del grupo predeterminado es inválido';

// Password
$lang['password_change_successful']         = 'Contraseña cambiada con éxito';
$lang['password_change_unsuccessful']       = 'No se pudo cambiar la contraseña';
$lang['forgot_password_successful']         = 'Nueva contraseña enviada por correo electrónico';
$lang['forgot_password_unsuccessful']       = 'No se pudo crear una nueva contraseña';

// Activation
$lang['activate_successful']                = 'Cuenta activada';
$lang['activate_unsuccessful']              = 'No se pudo activar la cuenta';
$lang['deactivate_successful']              = 'Cuenta desactivada';
$lang['deactivate_unsuccessful']            = 'No se pudo desactivar la cuenta';
$lang['activation_email_successful']        = 'Correo electrónico de activación enviado con éxito';
$lang['activation_email_unsuccessful']      = 'No se pudo enviar el correo electrónico de activación';

// Login / Logout
$lang['login_successful']                   = 'Sesión iniciada con éxito';
$lang['login_unsuccessful']                 = 'Usuario o contraseña inválidos';
$lang['login_unsuccessful_not_active']      = 'La cuenta está desactivada';
$lang['login_timeout']                      = 'Cuenta temporalmente bloqueada. Intente nuevamente más tarde';
$lang['logout_successful']                    = 'Sesión cerrada con éxito';

// Account Changes
$lang['update_successful']                  = 'Información de la cuenta actualizada con éxito';
$lang['update_unsuccessful']                = 'No se pudo actualizar la información de la cuenta';
$lang['delete_successful']                  = 'Usuario eliminado con éxito';
$lang['delete_unsuccessful']                = 'No se pudo eliminar al usuario';

// Groups
$lang['group_creation_successful']          = 'Grupo creado con éxito';
$lang['group_already_exists']               = 'Ya existe un grupo con este nombre';
$lang['group_update_successful']            = 'Datos del grupo actualizados con éxito';
$lang['group_delete_successful']            = 'Grupo eliminado con éxito';
$lang['group_delete_unsuccessful']          = 'No se pudo eliminar el grupo';
$lang['group_delete_notallowed']            = 'No se puede eliminar el grupo de administradores';
$lang['group_name_required']                 = 'El nombre del grupo es obligatorio';
$lang['group_name_admin_not_alter']         = 'No se puede cambiar el nombre del grupo de administradores';

// Activation Email
$lang['email_activation_subject']           = 'Activación de cuenta';
$lang['email_activate_heading']                = 'Active su cuenta para %s';
$lang['email_activate_subheading']             = 'Por favor, haga clic en este enlace para %s.';
$lang['email_activate_link']                   = 'Active su cuenta';

// Forgot Password Email
$lang['email_forgotten_password_subject']   = 'Olvidé la contraseña';
$lang['email_forgot_password_heading']        = 'Restablecimiento de la contraseña para %s';
$lang['email_forgot_password_subheading']     = 'Por favor, haga clic en este enlace para %s.';
$lang['email_forgot_password_link']           = 'Restablezca su contraseña';

// New Password Email
$lang['email_new_password_subject']         = 'Nueva contraseña';
$lang['email_new_password_heading']            = 'Nueva contraseña para %s';
$lang['email_new_password_subheading']         = 'Su contraseña ha sido restablecida a: %s';
