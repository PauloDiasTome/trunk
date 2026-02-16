<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$ip_enabled = explode(',', $this->config->item('intranet_ip'));

if (isset($_SERVER['HTTP_HOST'])) {

    //whts.me
    if ($_SERVER['HTTP_HOST'] == "whts.me") {
        $route['(:any)'] = "ShortLinkLogController/Index/$1";
        $route['link/register']['POST'] = "ShortLinkLogController/Register";
        //DESABILITA TODAS AS ROTAS
        $route['(.*)'] = "none";
    }

    //api.talkall.com.br
    if ($_SERVER['HTTP_HOST'] == $this->config->item('api_domain')) {

        $route['404_override'] = '';
        $route['default_controller'] = "DevelopersController/Index";
        $route['oauth/token'] = 'oauth/Token';

        $route['contacts'] = 'api/ContactController';
        $route['contact/validation'] = 'api/ContactController/contactValidation';
        $route['channels'] = 'api/ChannelController';
        $route['messages']['POST'] = 'api/MessageController/store';

        // Novos metódos da API // By Jhonathan

        $route['blocklist'] = 'api/BlockListController/index';
        $route['faq'] = 'api/FaqController/index';
        $route['faq/(:num)'] = 'api/FaqController/get/$1';
        $route['contacts/groups'] = 'api/ContactGroupController/index';

        $route['label'] = 'api/LabelController/index';
        $route['product'] = 'api/ProductController/index';
        $route['shortlink'] = 'api/ShortLinkController/index';
        $route['template'] = 'api/TemplateController/index';
        $route['template/type'] = 'api/TemplateTypeController/index';
        $route['waitlist'] = 'api/WaitListController/index';

        $route['order/status'] = 'api/OrderStatusController/index';
        $route['payment/method'] = 'api/PaymentMethodController/index';
        $route['quick/replies'] = 'api/QuickRepliesController/index';
        $route['users'] = 'api/UserController/index';
        $route['ticket'] = 'api/TicketController/index';
        $route['ticket/type'] = 'api/TicketTypeController/index';
        $route['ticket/status'] = 'api/TicketStatusController/index';
        $route['ticket/sla'] = 'api/TicketSlaController/index';
        $route['groups'] = 'api/GroupsController/index';


        //DESABILITA TODAS AS ROTAS
        $route['(.*)'] = "none";
    }
    //API NÃO USSA AUTENTIÇÃO DO PORTAL
    else {
        if ($_SERVER['HTTP_HOST'] != $this->config->item('intranet_domain')) {
            $route['default_controller'] = "AccountController/Login";
            $route['account/login'] = "AccountController/Login";
            $route['account/logon'] = 'AccountController/Logon';
            $route['account/logoff'] = "AccountController/Logoff";
            $route['account/logon/2fa'] = 'AccountController/Valid2fa';
        } else if (in_array($_SERVER['REMOTE_ADDR'], $ip_enabled)) {
            $route['default_controller'] = "AccountController/Login";
            $route['account/login'] = "AccountController/Login";
            $route['account/logon'] = 'AccountController/Logon';
            $route['account/logoff'] = "AccountController/Logoff";
            $route['account/logon/2fa'] = 'AccountController/Valid2fa';
        }

        $route['404_override'] = 'errors/page_missing';
        $route['translate_uri_dashes'] = FALSE;

        $route['signup/register'] = "SignupController/register";
        $route['signup/post']['POST'] = "CompanyController/register";
        $route['signup/company/(:any)'] = 'SignupController/company/$1';
        $route['signup/company']['POST'] = "CompanyController/add";
        $route['signup/success'] = "SignupController/success";
        $route['signup/company/post']['POST'] = "CompanyController/post";
        $route['signup/success/company'] = "SignupController/companySuccess";
        $route['signup/code'] = "SignupController/code/$1";
        $route['signup/validateCode/(:any)'] = 'SignupController/validateCode/$1';

        $route['signup/whatsapp/code/resend/(:any)'] = "CompanyController/sendTemplateCode/$1";
        $route['signup/sms/code/resend/(:any)'] = "CompanyController/sendSmsCode/$1";
        $route['signup/updatePhone/(:any)'] = 'signupController/updatePhone/$1';

        $route['email/resend_mail']['POST'] = "UserController/validation_email_user";


        $route['account/forgotPassword'] = 'AccountController/ForgotPassword';
        $route['account/ResetPassword']['POST'] = 'AccountController/ResetPassword';
        $route['account/ResetPassword/User']['POST'] = 'AccountController/ResetPasswordUser';
        $route['account/ResetPassDefault']['POST'] = 'AccountController/ResetPassDefault';
        $route['NewPassword/(:any)'] = 'AccountController/NewPassword/$1';
        $route['account/addtwofa']['POST'] = 'AccountController/AddTwoFa';
        $route['account/checktwofa']['POST'] = 'AccountController/CheckTwoFa';
        $route['account/security/(:any)'] = 'AccountController/Security/$1';
        $route['account/changeLanguage']['POST'] = 'AccountController/ChangeLanguage';
        $route['account/withoutPermission'] = 'AccountController/WithoutPermission';
        $route['account/trialPeriod'] = 'AccountController/trialPeriod';
        $route['security/password'] = 'AccountController/securityPassword';
        $route['email/confirm/(:any)']['GET'] = "AccountController/ConfirmEmail/$1";
        $route['email/confirm_success/(:any)']['GET'] = "AccountController/ConfirmSuccess/$1";

        $route['account/smsverify/(:any)']['OPTIONS'] = 'AccountController/SmsVerify/$1';
        $route['account/smsverify/confirm/(:any)/(:any)']['OPTIONS'] = 'AccountController/SmsVerifyConfirm/$1/$2';

        $route['file/upload']['POST'] = 'UploadController/Upload';
        $route['upload/file'] = 'UploadController/Upload';
        $route['file/UploadProfilePicture']['POST'] = 'UploadController/UploadProfilePicture';
        $route['file/UploadPictureProduct']['POST'] = 'UploadController/UploadPictureProduct';
        $route['file/UploadPrintScreen']['POST'] = 'UploadController/UploadPrintScreen';
        $route['file/DeletePictureProduct']['DELETE'] = 'UploadController/DeletePictureProduct';

        $route['file/upload/broadcast/status']['POST'] = 'UploadController/UploadBroadcastStatus';

        $route['api/mobile'] = "MobileController/index";
        $route['api/mobile/add/(:any)/(:any)/(:any)']['OPTIONS'] = "MobileController/add/$1/$2/$3";
    }

    //developpers.talkall.com.br
    if ($_SERVER['HTTP_HOST'] == $this->config->item('dev_domain')) {

        $route['default_controller'] = "DevelopersController/Index";
        $route['apps'] = "developers/AppController/Index";
        $route['apps/add'] = "developers/AppController/Add";
        $route['apps/edit/(:any)'] = "developers/AppController/Edit/$1";
        $route['apps/delete/(:any)']['DELETE'] = "developers/AppController/Delete/$1";
        $route['apps/RequestWebhooks']['POST'] = "developers/AppController/RequestWebhooks";
        //DESABILITA TODAS AS ROTAS
        $route['(.*)'] = "none";
    }


    //MAIN PLATAFORM
    if ($_SERVER['HTTP_HOST'] == $this->config->item('platform_domain')) {

        $route['oauth/authorize'] = 'oauth/Authorize';

        $route['messenger'] = "MessengerController";
        $route['chat/(:any)'] = "MessengerController/OpenChatMessenger/$1";
        $route['messenger/(:num)'] = "MessengerController/$1";
        $route['messenger/getResponsible'] = "MessengerController/GetResponsible";

        $route['feedback/view'] = "FeedbackController/Get";
        $route['file/uploadNotification'] = 'UploadNotificationController/Index';
        //$route['file/deleteNotification']['DELETE'] = 'UploadNotificationController/Delete';

        $route['integration/copel'] = "Integration/copel/CopelController/Index";
        $route['integration/unisuper/(:any)/(:any)/(:any)'] = "Integration/unisuper/UniSuperController/Index/$1/$2/$3";

        $route['integration/shibata'] = "Integration/shibata/ShibataController/Index";
        $route['integration/copacol'] = "Integration/copacol/CopacolController/Index";
        $route['integration/princesa'] = "Integration/princesa/PrincesaController/Index";
        $route['integration/valesorte'] = "Integration/vale-sorte/ValeSorteController/Index";
        $route['integration/valesorte/callback'] = "Integration/vale-sorte/ValeSorteController/callback";
        $route['v1/integration/send/contact/(:any)/(:any)/(:any)/(:any)'] = "Integration/ContactController/sendContact/$1/$2/$3/$4";

        $route['integration/brconsorciosteste'] = "Integration/brconsorcios/uniao/UniaoController/Index";
        $route['integration/araucaria'] = "Integration/brconsorcios/araucaria/AraucariaController/Index";
        $route['integration/santaemilia'] = "Integration/brconsorcios/santaemilia/SantaEmiliaController/Index";
        $route['integration/lyscar'] = "Integration/brconsorcios/lyscar/LyscarController/Index";
        $route['integration/lojacorr'] = "Integration/brconsorcios/lojacorr/LojacorrController/Index";
        $route['integration/mapfre'] = "Integration/brconsorcios/mapfre/MapfreController/Index";

        $route['integration/gsol'] = "Integration/girando-sol/GirandoSolController/Index";
        $route['integration/iguacu'] = "Integration/tres-coracoes/iguacu/IguacuController/Index";
        $route['integration/roldao'] = "Integration/roldao/RoldaoController/Index";
        $route['integration/cativa']['POST'] = "Integration/cativa/CativaController/Index";

        $route['notification/getNotification']['POST'] = "NotificationController/GetNotification";
        $route['notification/markasread']['POST'] = "NotificationController/MarkAsRead";

        $route['contact'] = "ContactController/Index";
        $route['contact/find']['POST'] = 'ContactController/Get';
        $route['contact/(:num)'] = "contact/edit/$1";
        $route['contact/edit/(:any)'] = 'ContactController/Edit/$1';
        $route['contact/save/(:num)']['POST'] = "ContactController/Save/$1";
        $route['contact/action']['POST'] = 'ContactController/ActionContact';
        $route['contact/search/list']['POST'] = "ContactController/searchSelected";
        $route['contact/all'] = "ContactController/GetAllContacts";
        $route['contact/get/personas']['POST'] = "ContactController/GetPersonas";
        $route['contact/save/labels/(:num)']['POST'] = 'ContactController/SaveLabels/$1';

        $route['category'] = "CategoryController/Index";
        $route['category/find']['POST'] = 'CategoryController/Get';
        $route['category/(:num)'] = "Category/edit/$1";
        $route['category/add'] = 'CategoryController/Add';
        $route['category/edit/(:any)'] = 'CategoryController/Edit/$1';
        $route['category/save']['POST'] = "CategoryController/Save/0";
        $route['category/save/(:num)']['POST'] = "CategoryController/Save/$1";
        $route['category/delete/(:any)'] = 'CategoryController/Delete/$1';

        $route['bot/trainer'] = "BotTrainerController/Index";
        $route['bot/trainer/find']['POST'] = 'BotTrainerController/Get';
        $route['bot/trainer/find_access/(:num)']['POST'] = 'BotTrainerController/GetAccess/$1';
        $route['bot/trainer/add'] = 'BotTrainerController/Add';
        $route['bot/trainer/edit/(:any)'] = 'BotTrainerController/Edit/$1';
        $route['bot/trainer/save']['POST'] = "BotTrainerController/Save/0";
        $route['bot/trainer/save/(:num)']['POST'] = "BotTrainerController/Save/$1";
        $route['bot/trainer/save-access']['POST'] = "BotTrainerController/Save/0";
        $route['bot/trainer/save-access/(:num)']['POST'] = "BotTrainerController/Save/$1";
        $route['bot/trainer/delete/(:any)'] = 'BotTrainerController/Delete/$1';
        $route['bot/trainer/(:num)'] = "BotTrainerController/Acesso/$1";
        $route['bot/trainer/(:num)/add'] = 'BotTrainerController/Add/$1';

        $route['blocklist'] = "BlockListController/Index";
        $route['blocklist/find']['POST'] = 'BlockListController/Get';
        $route['blocklist/delete/(:any)'] = 'BlockListController/Delete/$1';

        $route['usergroup'] = "UserGroupController/Index";
        $route['usergroup/find']['POST'] = 'UserGroupController/Get';
        $route['usergroup/(:num)'] = "usergroup/edit/$1";
        $route['usergroup/add'] = 'UserGroupController/Add';
        $route['usergroup/edit/(:any)'] = 'UserGroupController/Edit/$1';
        $route['usergroup/save']['POST'] = "UserGroupController/Save/0";
        $route['usergroup/save/(:num)']['POST'] = "UserGroupController/Save/$1";
        $route['usergroup/delete/(:any)'] = 'UserGroupController/Delete/$1';

        $route['usercall'] = "UserCallController/Index";
        $route['usercall/find']['POST'] = 'UserCallController/Get';
        $route['usercall/(:num)'] = "usercall/edit/$1";
        $route['usercall/add'] = 'UserCallController/Add';
        $route['usercall/edit/(:any)'] = 'UserCallController/Edit/$1';
        $route['usercall/save']['POST'] = "UserCallController/Save/0";
        $route['usercall/save/(:num)']['POST'] = "UserCallController/Save/$1";
        $route['usercall/delete/(:any)'] = 'UserCallController/Delete/$1';

        $route['label'] = "LabelController/Index";
        $route['label/find']['POST'] = 'LabelController/Get';
        $route['label/(:num)'] = "label/edit/$1";
        $route['label/add'] = 'LabelController/Add';
        $route['label/edit/(:any)'] = 'LabelController/Edit/$1';
        $route['label/save']['POST'] = "LabelController/Save/0";
        $route['label/save/(:num)'] = "LabelController/Save/$1";
        $route['label/delete']['POST'] = 'LabelController/Delete';

        $route['persona'] = "PersonaController/Index";
        $route['persona/find']['POST'] = 'PersonaController/Get';
        $route['persona/save']['POST'] = "PersonaController/Save";
        $route['persona/(:num)'] = "persona/edit/$1";
        $route['persona/add'] = 'PersonaController/Add';
        $route['persona/edit/(:any)'] = 'PersonaController/Edit/$1';
        $route['persona/list/contact']['POST'] = 'PersonaController/ListContacts';
        $route['persona/list/participants']['POST'] = 'PersonaController/ListParticipants';
        $route['persona/import/contact']['POST'] = "PersonaController/ImportContacts";
        $route['persona/delete/participants'] = 'PersonaController/DeleteParticipants';
        $route['persona/delete/(:any)'] = 'PersonaController/Delete/$1';

        $route['user/group/work'] = "UserGroupWorkController/Index";
        $route['user/group/work/find']['POST'] = 'UserGroupWorkController/Get';
        $route['user/group/work/(:num)'] = "user/grou/work/edit/$1";
        $route['user/group/work/add'] = 'UserGroupWorkController/Add';
        $route['user/group/work/edit/(:any)'] = 'UserGroupWorkController/Edit/$1';
        $route['user/group/work/save']['POST'] = "UserGroupWorkController/Save/0";
        $route['user/group/work/save/(:num)']['POST'] = "UserGroupWorkController/Save/$1";
        $route['user/group/work/delete/(:any)'] = 'UserGroupWorkController/Delete/$1';

        $route['user'] = "UserController/Index";
        $route['user/find']['POST'] = 'UserController/Get';
        $route['user/(:num)'] = "user/edit/$1";
        $route['user/add'] = 'UserController/Add';
        $route['user/edit/(:any)'] = 'UserController/Edit/$1';
        $route['user/save']['POST'] = "UserController/Save/0";
        $route['user/save/(:num)']['POST'] = "UserController/Save/$1";
        $route['user/delete/(:any)'] = 'UserController/Delete/$1';
        $route['user/deleteUser']['POST'] = "UserController/deleteUser";
        $route['user/save/userCall']['POST'] = "UserController/saveUserCall";
        $route['user/save/userSector']['POST'] = "UserController/saveUserSector";
        $route['user/checkEmail']['POST'] = "UserController/isEmailRegistered";

        $route['permission'] = "PermissionController/Index";
        $route['permission/find'] = "PermissionController/Get";
        $route['permission/save']['POST'] = "PermissionController/Save";
        $route['permission/save/(:any)']['POST'] = "PermissionController/Save/$1";
        $route['permission/add'] = "PermissionController/Add";
        $route['permission/edit/(:num)'] = 'PermissionController/Edit/$1';
        $route['permission/delete/(:any)'] = 'PermissionController/Delete/$1';

        $route['replies'] = "QuickRepliesController/Index";
        $route['replies/find']['POST'] = 'QuickRepliesController/Get';
        $route['replies/(:num)'] = "replies/edit/$1";
        $route['replies/add'] = 'QuickRepliesController/Add';
        $route['replies/edit/(:any)'] = 'QuickRepliesController/Edit/$1';
        $route['replies/save']['POST'] = "QuickRepliesController/Save/0";
        $route['replies/save/(:num)']['POST'] = "QuickRepliesController/Save/$1";
        $route['replies/delete/(:any)'] = 'QuickRepliesController/Delete/$1';

        $route['faq'] = "FaqController/Index";
        $route['faq/find']['POST'] = 'FaqController/Get';
        $route['faq/(:num)'] = "faq/edit/$1";
        $route['faq/add'] = 'FaqController/Add';
        $route['faq/edit/(:num)'] = 'FaqController/Edit/$1';
        $route['faq/read/(:num)'] = 'FaqController/Read/$1';
        $route['faq/save']['POST'] = "FaqController/Save/0";
        $route['faq/save/(:num)']['POST'] = "FaqController/Save/$1";
        $route['faq/delete/(:num)'] = 'FaqController/Delete/$1';

        $route['product'] = "ProductController/Index";
        $route['product/find']['POST'] = 'ProductController/Get';
        $route['product/add'] = 'ProductController/Add';
        $route['product/save']['POST'] = "ProductController/Save/0";
        $route['product/save/(:num)']['POST'] = "ProductController/Save/$1";
        $route['product/edit/(:any)'] = 'ProductController/Edit/$1';
        $route['product/upload']['POST'] = "ProductController/Upload/$0";
        $route['product/list_files/(:num)'] = 'ProductController/List_files/$1';
        $route['product/removeUpload'] = 'ProductController/RemoveUpload';
        $route['product/delete/(:any)'] = 'ProductController/Delete/$1';
        $route['product/appeal/(:any)'] = 'ProductController/Appeal/$1';
        $route['product/appeal/save/(:any)'] = 'ProductController/AppealSave/$1';

        // $route['product/(:num)'] = "product/edit/$1";
        // $route['product/delete/(:any)'] = 'ProductController/Delete/$1';
        // $route['product/deleteimage/(:num)']['POST'] = 'ProductController/DeleteImage/$1';   
        // $route['product/List_files'] = 'ProductController/List_files/$1';

        //$route['product/status'] = "ProductStatusController/Index";
        //$route['product/status/find']['POST'] = 'ProductStatusController/Get';
        //$route['product/status/add'] = 'ProductStatusController/Add';
        //$route['product/status/edit/(:num)'] = 'ProductStatusController/Edit/$1';
        //$route['product/status/save']['POST'] = 'ProductStatusController/Save/0';
        //$route['product/status/save/(:num)']['POST'] = 'ProductStatusController/Save/$1';
        //$route['product/status/delete/(:num)'] = 'ProductStatusController/Delete/$1';

        $route['order'] = "OrderController/Index";
        $route['order/maps/calculate/(:any)/(:any)'] = "OrderController/GoogleMapsCalculate/$1/$2";
        $route['order/find']['POST'] = "OrderController/Get";
        $route['order/items/find']['POST'] = "OrderController/GetItems";
        $route['order/add'] = "OrderController/Add";
        $route['order/edit/(:any)'] = "OrderController/Edit/$1";
        $route['order/save']['POST'] = "OrderController/Save/0";
        $route['order/save/(:num)']['POST'] = "OrderController/Save/$1";
        $route['order/delete/(:any)'] = "OrderController/Delete/$1";
        $route['order/items/delete/(:any)'] = "OrderController/DeleteItems/$1";
        $route['order/items/get/(:any)'] = "OrderController/GetInfoItems/$1";

        $route['order/status'] = "OrderStatusController/Index";
        $route['order/status/find']['POST'] = "OrderStatusController/Get";
        $route['order/status/add'] = "OrderStatusController/Add";
        $route['order/status/edit/(:any)'] = "OrderStatusController/Edit/$1";
        $route['order/status/save']['POST'] = "OrderStatusController/Save/0";
        $route['order/status/save/(:num)']['POST'] = "OrderStatusController/Save/$1";
        $route['order/status/delete/(:any)'] = "OrderStatusController/Delete/$1";

        $route['payment/method'] = "PaymentMethodController/Index";
        $route['payment/method/find']['POST'] = "PaymentMethodController/Get";
        $route['payment/method/add'] = "PaymentMethodController/Add";
        $route['payment/method/edit/(:any)'] = "PaymentMethodController/Edit/$1";
        $route['payment/method/save']['POST'] = "PaymentMethodController/Save/0";
        $route['payment/method/save/(:num)']['POST'] = "PaymentMethodController/Save/$1";
        $route['payment/method/delete/(:any)'] = "PaymentMethodController/Delete/$1";

        $route['wait'] = "WaitListController/Index";
        $route['wait/find']['POST'] = 'WaitListController/Get';
        $route['wait/(:num)'] = "wait/edit/$1";
        $route['wait/add'] = 'WaitListController/Add';
        $route['wait/edit/(:any)'] = 'WaitListController/Edit/$1';
        $route['wait/save']['POST'] = "WaitListController/Save/0";
        $route['wait/save/(:num)']['POST'] = "WaitListController/Save/$1";
        $route['wait/delete/(:any)'] = 'WaitListController/Delete/$1';

        $route['broadcast/sms'] = "BroadcastSMSController/Index";
        $route['broadcast/sms/find']['POST'] = 'BroadcastSMSController/Get';
        $route['broadcast/sms/add'] = 'BroadcastSMSController/Add';
        $route['broadcast/sms/view/(:any)'] = 'BroadcastSMSController/View/$1';
        $route['broadcast/sms/save']['POST'] = "BroadcastSMSController/Save/0";
        $route['broadcast/sms/delete/(:any)'] = 'BroadcastSMSController/Delete/$1';
        $route['broadcast/sms/cancel/(:any)'] = 'BroadcastSMSController/Cancel/$1';
        $route['broadcast/sms/pause/(:any)'] = 'BroadcastSMSController/Pause/$1';
        $route['broadcast/sms/resume/(:any)'] = 'BroadcastSMSController/Resume/$1';
        $route['broadcast/sms/listGroups/(:any)'] = 'BroadcastSMSController/listGroups/$1';

        $route['publication/whatsapp/broadcast'] = "PublicationWhatsappBroadcastController/Index";
        $route['publication/whatsapp/broadcast/find']['POST'] = 'PublicationWhatsappBroadcastController/Get';
        $route['publication/whatsapp/broadcast/add'] = 'PublicationWhatsappBroadcastController/Add';
        $route['publication/whatsapp/broadcast/edit/(:any)'] = 'PublicationWhatsappBroadcastController/Edit/$1';
        $route['publication/whatsapp/broadcast/save']['POST'] = "PublicationWhatsappBroadcastController/Save/0";
        $route['publication/whatsapp/broadcast/save/preview']['POST'] = 'PublicationWhatsappBroadcastController/prepareSendPreview';
        $route['publication/whatsapp/broadcast/save/(:any)'] = "PublicationWhatsappBroadcastController/Save/$1";
        $route['publication/whatsapp/broadcast/checktime/edit/(:any)'] = "PublicationWhatsappBroadcastController/CheckTimeToEdit/$1";
        $route['publication/whatsapp/broadcast/cancel/(:any)'] = 'PublicationWhatsappBroadcastController/Cancel/$1';
        $route['publication/whatsapp/broadcast/cancelgroup'] = 'PublicationWhatsappBroadcastController/CancelGroup';
        $route['publication/whatsapp/broadcast/view/(:any)'] = 'PublicationWhatsappBroadcastController/View/$1';
        $route['publication/whatsapp/broadcast/listGroups']['POST'] = 'PublicationWhatsappBroadcastController/ListGroups';
        $route['publication/whatsapp/broadcast/validateContact']['POST'] = 'PublicationWhatsappBroadcastController/validContactChannel';
        $route['publication/whatsapp/broadcast/pause']['POST'] = 'PublicationWhatsappBroadcastController/pauseBroadcast';
        $route['publication/whatsapp/broadcast/resume']['POST'] = 'PublicationWhatsappBroadcastController/resumeBroadcast';
        $route['publication/whatsapp/broadcast/ruleSchedule']['POST'] = 'PublicationWhatsappBroadcastController/ruleSchedule';
        $route['publication/whatsapp/broadcast/resend/(:any)'] = 'PublicationWhatsappBroadcastController/ResendBroadcast/$1';
        $route['publication/whatsapp/broadcast/countBroadcastsToDelete'] = 'PublicationWhatsappBroadcastController/countBroadcastsToDelete';

        $route['publication/whatsapp/broadcast/community'] = "PublicationWhatsappBroadcastCommunityController/Index";
        $route['publication/whatsapp/broadcast/community/find']['POST'] = 'PublicationWhatsappBroadcastCommunityController/Get';
        $route['publication/whatsapp/broadcast/community/add'] = 'PublicationWhatsappBroadcastCommunityController/Add';
        $route['publication/whatsapp/broadcast/community/edit/(:any)'] = 'PublicationWhatsappBroadcastCommunityController/Edit/$1';
        $route['publication/whatsapp/broadcast/community/save']['POST'] = "PublicationWhatsappBroadcastCommunityController/Save/0";
        $route['publication/whatsapp/broadcast/community/save/(:any)'] = "PublicationWhatsappBroadcastCommunityController/Save/$1";
        $route['publication/whatsapp/broadcast/community/checktime/edit/(:any)'] = "PublicationWhatsappBroadcastCommunityController/CheckTimeToEdit/$1";
        $route['publication/whatsapp/broadcast/community/cancel/(:any)'] = 'PublicationWhatsappBroadcastCommunityController/Cancel/$1';
        $route['publication/whatsapp/broadcast/community/cancelgroup'] = 'PublicationWhatsappBroadcastCommunityController/CancelGroup';
        $route['publication/whatsapp/broadcast/community/view/(:any)'] = 'PublicationWhatsappBroadcastCommunityController/View/$1';
        $route['publication/whatsapp/broadcast/community/listGroups']['POST'] = 'PublicationWhatsappBroadcastCommunityController/ListGroups';
        $route['publication/whatsapp/broadcast/community/list/(:any)'] = 'PublicationWhatsappBroadcastCommunityController/ListCommunity/$1';
        $route['publication/whatsapp/broadcast/community/resend/(:any)'] = 'PublicationWhatsappBroadcastCommunityController/ResendBroadcast/$1';

        $route['publication/whatsapp/broadcast/newsletter'] = "PublicationWhatsappBroadcastNewsletterController/Index";
        $route['publication/whatsapp/broadcast/newsletter/find']['POST'] = 'PublicationWhatsappBroadcastNewsletterController/Get';
        $route['publication/whatsapp/broadcast/newsletter/add'] = 'PublicationWhatsappBroadcastNewsletterController/Add';
        $route['publication/whatsapp/broadcast/newsletter/edit/(:any)'] = 'PublicationWhatsappBroadcastNewsletterController/Edit/$1';
        $route['publication/whatsapp/broadcast/newsletter/save']['POST'] = "PublicationWhatsappBroadcastNewsletterController/Save/0";
        $route['publication/whatsapp/broadcast/newsletter/save/(:any)'] = "PublicationWhatsappBroadcastNewsletterController/Save/$1";
        $route['publication/whatsapp/broadcast/newsletter/checktime/edit/(:any)'] = "PublicationWhatsappBroadcastNewsletterController/CheckTimeToEdit/$1";
        $route['publication/whatsapp/broadcast/newsletter/cancel/(:any)'] = 'PublicationWhatsappBroadcastNewsletterController/Cancel/$1';
        $route['publication/whatsapp/broadcast/newsletter/cancelgroup'] = 'PublicationWhatsappBroadcastNewsletterController/CancelGroup';
        $route['publication/whatsapp/broadcast/newsletter/view/(:any)'] = 'PublicationWhatsappBroadcastNewsletterController/View/$1';
        $route['publication/whatsapp/broadcast/newsletter/resend/(:any)'] = 'PublicationWhatsappBroadcastNewsletterController/ResendBroadcast/$1';

        $route['publication/whatsapp/broadcast/waba'] = "PublicationWhatsappBroadcastWabaController/Index";
        $route['publication/whatsapp/broadcast/waba/find']['POST'] = 'PublicationWhatsappBroadcastWabaController/Get';
        $route['publication/whatsapp/broadcast/waba/add'] = 'PublicationWhatsappBroadcastWabaController/Add';
        $route['publication/whatsapp/broadcast/waba/edit/(:any)'] = 'PublicationWhatsappBroadcastWabaController/Edit/$1';
        $route['publication/whatsapp/broadcast/waba/save']['POST'] = "PublicationWhatsappBroadcastWabaController/Save/0";
        $route['publication/whatsapp/broadcast/waba/save/preview']['POST'] = 'PublicationWhatsappBroadcastWabaController/prepareSendPreview';
        $route['publication/whatsapp/broadcast/waba/save/(:any)'] = "PublicationWhatsappBroadcastWabaController/Save/$1";
        $route['publication/whatsapp/broadcast/waba/checktime/edit/(:any)'] = "PublicationWhatsappBroadcastWabaController/CheckTimeToEdit/$1";
        $route['publication/whatsapp/broadcast/waba/cancel/(:any)'] = 'PublicationWhatsappBroadcastWabaController/Cancel/$1';
        $route['publication/whatsapp/broadcast/waba/cancelgroup'] = 'PublicationWhatsappBroadcastWabaController/CancelGroup';
        $route['publication/whatsapp/broadcast/waba/view/(:any)'] = 'PublicationWhatsappBroadcastWabaController/View/$1';
        $route['publication/whatsapp/broadcast/waba/calculatebalance/(:any)'] = 'PublicationWhatsappBroadcastWabaController/CalculateBalance/$1';
        $route['publication/whatsapp/broadcast/waba/listGroups/(:any)'] = 'PublicationWhatsappBroadcastWabaController/listGroups/$1';
        $route['publication/whatsapp/broadcast/waba/listtemplate/(:any)'] = 'PublicationWhatsappBroadcastWabaController/CheckParameters/$1';
        $route['publication/whatsapp/broadcast/waba/validateContact']['POST'] = 'PublicationWhatsappBroadcastWabaController/validContactChannel';
        $route['publication/whatsapp/broadcast/waba/resend/(:any)'] = 'PublicationWhatsappBroadcastWabaController/ResendBroadcast/$1';

        $route['publication/whatsapp/status'] = "PublicationWhatsappStatusController/Index";
        $route['publication/whatsapp/status/find']['POST'] = 'PublicationWhatsappStatusController/Get';
        $route['publication/whatsapp/status/add'] = 'PublicationWhatsappStatusController/Add';
        $route['publication/whatsapp/status/edit/(:any)'] = 'PublicationWhatsappStatusController/Edit/$1';
        $route['publication/whatsapp/status/save']['POST'] = "PublicationWhatsappStatusController/Save/0";
        $route['publication/whatsapp/status/save/(:any)'] = "PublicationWhatsappStatusController/Save/$1";
        $route['publication/whatsapp/status/checktime/edit/(:any)'] = "PublicationWhatsappStatusController/CheckTimeToEdit/$1";
        $route['publication/whatsapp/status/cancel/(:any)'] = 'PublicationWhatsappStatusController/Cancel/$1';
        $route['publication/whatsapp/status/cancelgroup'] = 'PublicationWhatsappStatusController/CancelGroup';
        $route['publication/whatsapp/status/view/(:any)'] = 'PublicationWhatsappStatusController/View/$1';
        $route['publication/whatsapp/status/ruleSchedule']['POST'] = 'PublicationWhatsappStatusController/ruleSchedule';
        $route['publication/whatsapp/status/resend/(:any)'] = 'PublicationWhatsappStatusController/ResendBroadcast/$1';

        $route['publication/facebook/post'] = "PublicationFacebookPostController/Index";
        $route['publication/facebook/post/find']['POST'] = 'PublicationFacebookPostController/Get';
        $route['publication/facebook/post/add'] = 'PublicationFacebookPostController/Add';
        $route['publication/facebook/post/edit/(:any)'] = 'PublicationFacebookPostController/Edit/$1';
        $route['publication/facebook/post/save']['POST'] = "PublicationFacebookPostController/Save/0";
        $route['publication/facebook/post/save/(:num)']['POST'] = "PublicationFacebookPostController/Save/$1";
        $route['publication/facebook/post/cancel/(:any)'] = 'PublicationFacebookPostController/Cancel/$1';
        $route['publication/facebook/post/cancelgroup'] = 'PublicationFacebookPostController/CancelGroup';
        $route['publication/facebook/post/view/(:any)'] = 'PublicationFacebookPostController/View/$1';

        $route['publication/instagram/post'] = "PublicationInstagramPostController/Index";
        $route['publication/instagram/post/find']['POST'] = 'PublicationInstagramPostController/Get';
        $route['publication/instagram/post/add'] = 'PublicationInstagramPostController/Add';
        $route['publication/instagram/post/edit/(:any)'] = 'PublicationInstagramPostController/Edit/$1';
        $route['publication/instagram/post/save']['POST'] = "PublicationInstagramPostController/Save/0";
        $route['publication/instagram/post/save/(:num)']['POST'] = "PublicationInstagramPostController/Save/$1";
        $route['publication/instagram/post/cancel/(:any)'] = 'PublicationInstagramPostController/Cancel/$1';
        $route['publication/instagram/post/cancelgroup'] = 'PublicationInstagramPostController/CancelGroup';
        $route['publication/instagram/post/view/(:any)'] = 'PublicationInstagramPostController/View/$1';

        $route['publication/tv/broadcast'] = 'PublicationTvBroadcastController/Index';
        $route['publication/tv/broadcast/find']['POST'] = 'PublicationTvBroadcastController/Get';
        $route['publication/tv/broadcast/add'] = 'PublicationTvBroadcastController/Add';
        $route['publication/tv/broadcast/save']['POST'] = 'PublicationTvBroadcastController/Save/0';
        $route['publication/tv/broadcast/check/schedule']['POST'] = 'PublicationTvBroadcastController/handleCampaignScheduledSameDate';
        $route['publication/tv/broadcast/view/(:any)'] = 'PublicationTvBroadcastController/View/$1';
        $route['publication/tv/broadcast/cancel/(:any)'] = 'PublicationTvBroadcastController/Cancel/$1';
        $route['publication/tv/broadcast/pause/(:any)'] = 'PublicationTvBroadcastController/Pause/$1';
        $route['publication/tv/broadcast/resume/(:any)'] = 'PublicationTvBroadcastController/Resume/$1';

        $route['publication/telegram/channel'] = 'PublicationTelegramChannelController/Index';
        $route['publication/telegram/channel/find']['POST'] = 'PublicationTelegramChannelController/Get';
        $route['publication/telegram/channel/add'] = 'PublicationTelegramChannelController/Add';
        $route['publication/telegram/channel/save']['POST'] = 'PublicationTelegramChannelController/Save/0';
        $route['publication/telegram/channel/check/schedule']['POST'] = 'PublicationTelegramChannelController/handleCampaignScheduledSameDate';
        $route['publication/telegram/channel/view/(:any)'] = 'PublicationTelegramChannelController/View/$1';
        $route['publication/telegram/channel/cancel/(:any)'] = 'PublicationTelegramChannelController/Cancel/$1';
        $route['publication/telegram/channel/pause/(:any)'] = 'PublicationTelegramChannelController/Pause/$1';
        $route['publication/telegram/channel/resume/(:any)'] = 'PublicationTelegramChannelController/Resume/$1';

        $route['facebook/share'] = 'FacebookShareController/Share';

        $route['shortlink'] = "ShortLinkController/Index";
        $route['shortlink/qrcode/(:num)'] = "ShortLinkController/qrcode/$1";
        $route['shortlink/find']['POST'] = 'ShortLinkController/Get';
        $route['shortlink/(:num)'] = "shortlink/edit/$1";
        $route['shortlink/add'] = 'ShortLinkController/Add';
        $route['shortlink/edit/(:any)'] = 'ShortLinkController/Edit/$1';
        $route['shortlink/save']['POST'] = "ShortLinkController/Save/0";
        $route['shortlink/save/(:num)']['POST'] = "ShortLinkController/Save/$1";
        $route['shortlink/delete/(:any)'] = 'ShortLinkController/Delete/$1';

        $route['community'] = "CommunityController/Index";
        $route['community/find']['POST'] = 'CommunityController/Get';
        $route['community/edit/(:any)'] = 'CommunityController/Edit/$1';
        $route['community/delete/(:any)'] = 'CommunityController/Delete/$1';
        $route['community/save/(:num)']['POST'] = "CommunityController/Save/$1";
        $route['community/participant/(:any)'] = 'CommunityController/Participant/$1';

        $route['community/participants'] = "CommunityParticipantController/Index";
        $route['community/participants/find']['POST'] = 'CommunityParticipantController/Get';
        $route['community/participant/edit/(:any)'] = 'CommunityParticipantController/Edit/$1';
        $route['community/participant/participant/(:any)'] = 'CommunityParticipantController/Participant/$1';

        $route['templates/add'] = 'TemplatesMsgController/Add';
        $route['templates/getData'] = 'TemplatesMsgController/GetData';
        $route['templates/getDataCloud'] = 'TemplatesMsgController/GetDataCloud';
        $route['templates/find']['POST'] = 'TemplatesMsgController/GetTemplate';
        $route['templates/services'] = 'TemplatesMsgController/CheckServices';
        $route['templates/updateStatus']['POST'] = 'TemplatesMsgController/UpdateStatus/0';
        $route['templates/list'] = 'TemplatesMsgController/CheckStatus';
        $route['templates/save']['POST'] = 'TemplatesMsgController/Save/0';
        $route['templates/delete/(:any)']['DELETE'] = 'TemplatesMsgController/Delete/$1';
        $route['templates/edit/(:num)'] = 'TemplatesMsgController/Edit/$1';
        $route['templates/updateName']['POST'] = 'TemplatesMsgController/updateName/0';
        $route['templates/view/(:num)'] = 'TemplatesMsgController/View/$1';
        $route['templates'] = 'TemplatesMsgController/Index';
        $route['templates/send_msg']['POST'] = 'TemplatesMsgController/SendTemplateMsgToContact/0';
        $route['templates/modal'] = 'TemplatesMsgController/ListTemplete';
        $route['templates/submitTemplateWaba']['POST'] = 'TemplatesMsgController/submitTemplateWaba/0';
        $route['templates/submitTemplateCloud']['POST'] = 'TemplatesMsgController/submitTemplateCloud/0';

        $route['ticket'] = "TicketController/Index";
        $route['ticket/history/(:num)'] = "TicketController/History/$1";
        $route['ticket/find']['POST'] = 'TicketController/Get';
        $route['ticket/add'] = 'TicketController/Add';
        $route['ticket/(:num)'] = "ticket/edit/$1";
        $route['ticket/add'] = 'TicketController/Add';
        $route['ticket/edit/(:any)'] = 'TicketController/Edit/$1';
        $route['ticket/list/subtype/(:num)'] = 'TicketController/ListSubtype/$1';
        $route['ticket/save']['POST'] = "TicketController/Save/0";
        $route['ticket/save/(:num)']['POST'] = "TicketController/Save/$1";
        $route['ticket/delete/(:any)'] = 'TicketController/Delete/$1';
        $route['ticket/list/contact/(:any)'] = 'TicketController/ListContact/$1';
        $route['ticket/save/company']['POST'] = "TicketController/SaveCompany";
        $route['ticket/save/type']['POST'] = "TicketController/saveTicketType";
        $route['ticket/save/status']['POST'] = "TicketController/saveTicketStatus";

        $route['ticket/type'] = "TicketTypeController/Index";
        $route['ticket/type/find']['POST'] = 'TicketTypeController/Get';
        $route['ticket/type/add'] = 'TicketTypeController/Add';
        $route['ticket/type/add/(:num)'] = 'TicketTypeController/Add/$1';
        $route['ticket/type/edit/(:any)'] = 'TicketTypeController/Edit/$1';
        $route['ticket/type/save']['POST'] = "TicketTypeController/Save/0";
        $route['ticket/type/save/(:num)']['POST'] = "TicketTypeController/Save/$1";
        $route['ticket/type/delete/(:any)']['POST'] = 'TicketTypeController/Delete/$1';
        $route['ticket/type/(:num)'] = "TicketTypeController/Subtype/$1";
        $route['ticket/type/subtype/find/(:num)'] = 'TicketTypeController/GetSubtype/$1';
        $route['ticket/type/subtype/save/(:num)']['POST'] = 'TicketTypeController/SaveSubtype/$1';

        $route['ticket/status'] = "TicketStatusController/Index";
        $route['ticket/status/find']['POST'] = 'TicketStatusController/Get';
        $route['ticket/status/(:num)'] = "ticket/status/edit/$1";
        $route['ticket/status/add'] = 'TicketStatusController/Add';
        $route['ticket/status/edit/(:any)'] = 'TicketStatusController/Edit/$1';
        $route['ticket/status/save']['POST'] = "TicketStatusController/Save/0";
        $route['ticket/status/save/(:num)']['POST'] = "TicketStatusController/Save/$1";
        $route['ticket/status/delete/(:any)'] = 'TicketStatusController/Delete/$1';

        $route['ticket/sla'] = "TicketSlaController/Index";
        $route['ticket/sla/find']['POST'] = 'TicketSlaController/Get';
        $route['ticket/sla/(:num)'] = "ticket/sla/edit/$1";
        $route['ticket/sla/add'] = 'TicketSlaController/Add';
        $route['ticket/sla/edit/(:any)'] = 'TicketSlaController/Edit/$1';
        $route['ticket/sla/save']['POST'] = "TicketSlaController/Save/0";
        $route['ticket/sla/save/(:num)']['POST'] = "TicketSlaController/Save/$1";
        $route['ticket/sla/delete/(:any)'] = 'TicketSlaController/Delete/$1';

        $route['kanban/attendance'] = "KanbanController/kanbanAttendance";
        $route['kanban/communication'] = "KanbanController/kanbanCommunication";

        // $route['dashboard/calls'] = "DashboardController/Calls";
        $route['dashboard/shortlink/(:num)'] = "DashboardController/Index/$1";
        $route['dashboard/tickets'] = "DashboardController/Ticket";

        $route['dashboard/communication'] = "DashboardBroadcastController/Index";
        $route['dashboard/communication/getBroadcast']['POST'] = "DashboardBroadcastController/GetBroadcast";
        $route['dashboard/communication/getInteraction']['POST'] = "DashboardBroadcastController/GetInteraction";
        $route['dashboard/communication/getReactions']['POST'] = "DashboardBroadcastController/GetReactions";
        $route['dashboard/communication/getAllContacts']['POST'] = "DashboardBroadcastController/GetAllContacts";
        $route['dashboard/communication/getCampaignStats']['POST'] = "DashboardBroadcastController/GetCampaignStats";
        $route['dashboard/communication/getActiveContacts']['POST'] = "DashboardBroadcastController/GetActiveContacts";
        $route['dashboard/communication/getInactiveContacts']['POST'] = "DashboardBroadcastController/GetInactiveContacts";
        $route['dashboard/communication/engagement']['POST'] = "DashboardBroadcastController/Engagement";

        $route['dashboard/broadcast/getBroadcast']['POST'] = "DashboardBroadcastController/GetBroadcast";
        $route['dashboard/broadcast/getInteraction']['POST'] = "DashboardBroadcastController/GetInteraction";
        $route['dashboard/broadcast/getReactions']['POST'] = "DashboardBroadcastController/GetReactions";
        $route['dashboard/broadcast/getAllContacts']['POST'] = "DashboardBroadcastController/GetAllContacts";
        $route['dashboard/broadcast/getCampaignStats']['POST'] = "DashboardBroadcastController/GetCampaignStats";
        $route['dashboard/broadcast/getActiveContacts']['POST'] = "DashboardBroadcastController/GetActiveContacts";
        $route['dashboard/broadcast/getInactiveContacts']['POST'] = "DashboardBroadcastController/GetInactiveContacts";
        $route['dashboard/broadcast/engagement']['POST'] = "DashboardBroadcastController/Engagement";

        $route['dashboard/attendance'] = "DashboardAttendanceController/Index";
        $route['dashboard/attendance/getAvgWaitTime']['POST'] = "DashboardAttendanceController/GetAvgWaitTime";
        $route['dashboard/attendance/getAvgResponseTime']['POST'] = "DashboardAttendanceController/GetAvgResponseTime";
        $route['dashboard/attendance/getAvgServiceTime']['POST'] = "DashboardAttendanceController/GetAvgServiceTime";
        $route['dashboard/attendance/getTotalAttendances']['POST'] = "DashboardAttendanceController/TotalAttendances";
        $route['dashboard/attendance/getPeakService']['POST'] = "DashboardAttendanceController/GetPeakService";
        $route['dashboard/attendance/getStartedEndClosed']['POST'] = "DashboardAttendanceController/getStartedEndClosed";
        $route['dashboard/attendance/getCategoryDistribution']['GET'] = "DashboardAttendanceController/getCategoryDistribution";
        $route['dashboard/attendance/getUserStatus']['POST'] = "DashboardAttendanceController/getUserStatus";

        $route['dashboard/attendance/getChatbotQuantitative']['POST'] = "DashboardAttendanceController/getChatbotQuantitative";
        $route['dashboard/attendance/getAttendanceOrigin']['GET'] = "DashboardAttendanceController/getAttendanceOrigin";
        $route['dashboard/attendance/getChatbotAbandonment']['POST'] = "DashboardAttendanceController/getChatbotAbandonment";

        $route['dashboard/attendance/avgAttendanceTime']['POST'] = "DashboardController/GetAvgAttendanceTime";
        $route['dashboard/attendance/avgResponseTime']['POST'] = "DashboardController/GetAvgResponseTime";
        $route['dashboard/attendance/startedAttendancesCount']['POST'] = "DashboardController/GetStartedAttendancesCount";
        $route['dashboard/attendance/initialWaitTime']['POST'] = "DashboardController/GetAvgInitialWaitTime";

        $route['qrcode/read'] = "QRCodeController/read";

        $route['search'] = "SearchController/Index/";
        $route['search/(:any)'] = "SearchController/Index/$1";
        $route['searchresult']['POST'] = "SearchController/SearchResult";

        $route['config/edit/(:num)'] = "ConfigController/Edit/$1";
        $route['config/save'] = "ConfigController/Save";
        $route['config/validation'] = 'ConfigController/ValidationOptionsChatBot';
        $route['config/worktime/save'] = "ConfigController/SaveWorktime";
        $route['config/check/ai/channel'] = "ConfigController/checkAiChannel";

        $route['webhook/message/telegram']['POST'] = "WebhookController/onMessageTelegram";
        $route['webhook/message/facebook'] = "WebhookController/onMessageFacebook";
        $route['webhook/message/whatsapp/(:any)'] = "WebhookController/onMessageWhatsapp/$1";
        $route['webhook/message/whatsapp/business/(:any)'] = "WebhookController/onMessageWhatsappBusiness/$1";
        $route['webhook/message/instagram'] = "WebhookController/onMessageInstagram";
        $route['webhook/message/mercadolivre']['POST'] = "WebhookController/onMessageMercadoLivre";

        $route['webhook/message/vindi'] = "WebhookController/onMessageVindi";

        $route['mercadolivre/oauth'] = "OAuthController/OAuthMercadoLivre";
        $route['facebook/oauth'] = "OAuthController/OAuthFacebook";
        $route['telegram/oauth']['POST'] = "OAuthController/OAuthTelegram";
        $route['instagram/oauth'] = "OAuthController/OAuthInstagram";
        $route['waba/oauth'] = "OAuthController/OAuthWABA";

        $route['v1/widget/(:any)'] = "WidgetController/Index/$1";
        $route['widget/get'] = "WidgetController/Get";

        $route['calendar'] = "CalendarController/Index/";
        $route['calendar/add'] = 'CalendarController/Add';
        $route['calendar/form'] = 'CalendarController/Form';
        $route['calendar/edit/(:num)'] = "CalendarController/Edit/$1";
        $route['calendar/save']['POST'] = "CalendarController/Save/0";
        $route['calendar/save/(:num)']['POST'] = "CalendarController/Save/$1";
        $route['calendar/delete/(:num)'] = "CalendarController/Delete/$1";

        $route['worktime'] = "WorkTimeController/Index/";
        $route['worktime/edit/(:num)'] = "WorkTimeController/Edit/$1";
        $route['worktime/edit'] = "WorkTimeController/Edit";
        $route['worktime/delete/(:num)'] = "WorkTimeController/Delete/$1";

        $route['integration'] = "IntegrationController/Index/";
        $route['integration/edit/(:num)'] = "IntegrationController/Edit/$1";
        $route['copy/integration/script/(:num)'] = "IntegrationController/CopyWidget/$1";
        $route['integration/clear/channel'] = "IntegrationController/ClearChannel";
        $route['integration/delete/(:num)']['DELETE'] = "IntegrationController/Delete/$1";
        $route['integration/delete/tv/(:num)'] = "IntegrationController/DeleteTv/$1";
        $route['integration/delete/openai/(:num)'] = "IntegrationController/DeleteOpenAi/$1";
        $route['integration/page/delete/(:num)'] = "IntegrationController/PageDelete/$1";
        $route['integration/add/(:num)'] = "IntegrationController/Add/$1";

        $route['integration/add'] = "IntegrationController/Add/";
        $route['integration/add/cloud'] = "IntegrationController/CloudIntegration";
        $route['integration/add/facebook'] = "IntegrationController/FacebookOAuth";
        $route['integration/add/instagram'] = "IntegrationController/InstagramOAuth";
        $route['integration/add/hubspot'] = "IntegrationController/HubspotAuth";
        $route['integration/add/mercadolivre'] = "IntegrationController/MercadoLivreOAuth";
        $route['integration/add/rdstation'] = "IntegrationController/RdStationAuth";
        $route['integration/add/telegram'] = "IntegrationController/TelegramOAuth";
        $route['integration/add/waba'] = "IntegrationController/WABAOAuth";
        $route['integration/add/business'] = 'IntegrationController/BusinessOAuth';
        $route['integration/business/qrcode'] = 'IntegrationController/QrCode';
        $route['integration/save_business'] = 'IntegrationController/SaveBusiness';
        $route['integration/business/register'] = "IntegrationController/RegisterBusiness";
        $route['integration/add/whatsapp/broadcast'] = "IntegrationController/WhatsappBroadcast";
        $route['integration/add/whatsapp/community'] = "IntegrationController/WhatsappCommunity";
        $route['integration/add/whatsapp/newsletter'] = "IntegrationController/WhatsappNewsletter";
        $route['integration/save/whatsapp/broadcast']['POST'] = "IntegrationController/WhatsappBroadcastSave";
        $route['integration/save/whatsapp/community']['POST'] = "IntegrationController/WhatsappCommunitySave";
        $route['integration/save/whatsapp/community/SaveNumberTrigger']['POST'] = "IntegrationController/SaveNumberTrigger";
        $route['integration/whatsapp/community/phone/duplicate/trigger/(:any)'] = "IntegrationController/WhatsappCommunityDuplicatePhoneTrigger/$1";
        $route['integration/whatsapp/community/phone/duplicate/creator/(:any)'] = "IntegrationController/WhatsappCommunityDuplicatePhoneCreator/$1";
        $route['integration/whatsapp/community/channels'] = "IntegrationController/WhatsappCommunityChannels";
        $route['integration/whatsapp/community/getNumbersCreator'] = "IntegrationController/getNumbersCreator";
        $route['integration/whatsapp/community/getNumbersTrigger'] = "IntegrationController/getNumbersTrigger";
        $route['integration/whatsapp/community/getCommunitiesByNumberCreator'] = "IntegrationController/getCommunitiesByNumberCreator";
        $route['integration/whatsapp/community/getCommunitiesByNumberTrigger'] = "IntegrationController/getCommunitiesByNumberTrigger";
        $route['integration/save/whatsapp/newsletter']['POST'] = "IntegrationController/WhatsappNewsletterSave";
        $route['integration/whatsapp/newsletter/channels'] = "IntegrationController/WhatsappNewsletterChannels";

        $route['integration/whatsapp/broadcast/phone/duplicate/(:any)'] = "IntegrationController/WhatsappBroadcastDuplicatePhone/$1";
        $route['integration/add/whatsapp'] = "IntegrationController/WhatsApp";
        $route['integration/add/widget'] = "IntegrationController/Widget";
        $route['integration/add/tv'] = "IntegrationController/tv";
        $route['integration/save/tv']['POST'] = "IntegrationController/SaveTv";
        $route['integration/cancel/tv']['POST'] = "IntegrationController/CancelTv";
        $route['integration/add/openai'] = "IntegrationController/OpenAI";
        $route['integration/openai/version'] = "IntegrationController/GetVersion";
        $route['integration/save/openai']['POST'] = "IntegrationController/SaveOpenAi";
        $route['integration/add/zendesk'] = "IntegrationController/ZendeskAuth";
        $route['integration/edit/widget'] = "IntegrationController/EditWidget";

        $route['tv'] = "TvController/Login";
        $route['tv/connect']['POST'] = "TvController/Connect";
        $route['tv/(:any)'] = "TvController/Index/$1";

        $route['myinvoice'] = "MyInvoiceController/Index";
        $route['myinvoice/find']['POST'] = "MyInvoiceController/Get";
        $route['myinvoice/addFile/(:any)'] = "MyInvoiceController/AddFile/$1";
        $route['myinvoice/save']['POST'] = "MyInvoiceController/save/0";
        $route['myinvoice/save/(:num)']['POST'] = "MyInvoiceController/save/$1";
        $route['myinvoice/checkMyInvoice'] = "MyInvoiceController/CheckMyInvoice";

        $route['file/upload/build']['POST'] = 'UploadController/buildMediaURL';

        // $route['dashboard/calls'] = "DashboardController/Calls";
        $route['dashboard/shortlink/(:num)'] = "DashboardController/Index/$1";
        $route['dashboard/tickets'] = "DashboardController/Ticket";

        $route['report/broadcast'] = "ReportBroadcastAnalyticalController/Index";
        $route['report/broadcast/find']['POST'] = "ReportBroadcastAnalyticalController/Get";

        $route['report/call'] = "ReportCallController/Index";
        $route['report/call/find']['POST'] = 'ReportCallController/Get';
        $route['report/call/getMessages']['POST'] = 'ReportCallController/GetMessages';
        $route['report/call/conversationHistory']['POST'] = 'ReportCallController/ConversationHistory';

        $route['report/chatbot/analytical'] = "ReportChatbotAnalyticalController/Index";
        $route['report/chatbot/analytical/find']['POST'] = 'ReportChatbotAnalyticalController/Get';
        $route['report/chatbot/analytical/getMessages']['POST'] = 'ReportChatbotAnalyticalController/GetMessages';
        $route['report/chatbot/analytical/conversationHistory']['POST'] = 'ReportChatbotAnalyticalController/ConversationHistory';

        $route['report/chatbot/quantitative'] = "ReportChatbotQuantitativeController/Index";
        $route['report/chatbot/quantitative/find']['POST'] = 'ReportChatbotQuantitativeController/Get';
        $route['report/chatbot/quantitative/getMessages']['POST'] = 'ReportChatbotQuantitativeController/GetMessages';
        $route['report/chatbot/quantitative/conversationHistory']['POST'] = 'ReportChatbotQuantitativeController/ConversationHistory';

        $route['report/sms'] = "ReportSmsController/Index";
        $route['report/sms/find']['POST'] = 'ReportSmsController/Get';

        $route['report/send'] = "ReportSendController/Index";
        $route['report/send/find']['POST'] = 'ReportSendController/Get';
        $route['report/send/view/(:any)'] = 'ReportSendController/View/$1';

        $route['report/contact'] = "ReportContactController/Index";
        $route['report/contact/find']['POST'] = 'ReportContactController/Get';

        $route['report/copacol/analytical'] = "ReportCopacolAnalyticalController/Index";
        $route['report/copacol/find']['POST'] = 'ReportCopacolAnalyticalController/Get';

        $route['report/copacol/synthetic'] = "ReportCopacolSyntheticController/Index";
        $route['report/copacol/synthetic/find_bot']['POST'] = 'ReportCopacolSyntheticController/Bot';
        $route['report/copacol/synthetic/find_ticket']['POST'] = 'ReportCopacolSyntheticController/Ticket';

        $route['report/evaluate'] = "ReportEvaluateController/Index";
        $route['report/evaluate/find']['POST'] = 'ReportEvaluateController/Get';

        $route['report/protocol'] = "ReportProtocolController/Index";
        $route['report/protocol/find']['POST'] = 'ReportProtocolController/Get';

        $route['report/princess_fields'] = "ReportPrincessFieldsController/Index";
        $route['report/princess_fields/find']['POST'] = 'ReportPrincessFieldsController/Get';
        $route['report/princess_fields/history']['POST'] = 'ReportPrincessFieldsController/History';

        $route['report/transfer_automatic'] = "ReportTransferAutomaticController/Index";
        $route['report/transfer_automatic/find']['POST'] = 'ReportTransferAutomaticController/Get';
        $route['report/transfer_automatic/getUsers/(:num)'] = 'ReportTransferAutomaticController/GetUsers/$1';
        $route['report/transfer_automatic/getMessages']['POST'] = 'ReportTransferAutomaticController/GetMessages';
        $route['report/transfer_automatic/historyScroll']['POST'] = 'ReportTransferAutomaticController/HistoryScroll';
        $route['report/transfer_automatic/conversationHistory']['POST'] = 'ReportTransferAutomaticController/ConversationHistory';

        $route['report/interaction'] = "ReportInteractionController/Index";
        $route['report/interaction/find']['POST'] = 'ReportInteractionController/Get';
        $route['report/interaction/getUsers/(:num)'] = 'ReportInteractionController/GetUsers/$1';
        $route['report/interaction/getMessages']['POST'] = 'ReportInteractionController/GetMessages';
        $route['report/interaction/historyScroll']['POST'] = 'ReportInteractionController/HistoryScroll';
        $route['report/interaction/conversationHistory']['POST'] = 'ReportInteractionController/ConversationHistory';

        $route['report/waiting_service'] = "ReportWaitingServiceController/Index";
        $route['report/waiting_service/find']['POST'] = 'ReportWaitingServiceController/Get';
        $route['report/waiting_service/getUsers/(:num)'] = 'ReportWaitingServiceController/GetUsers/$1';
        $route['report/waiting_service/getMessages']['POST'] = 'ReportWaitingServiceController/GetMessages';
        $route['report/waiting_service/historyScroll']['POST'] = 'ReportWaitingServiceController/HistoryScroll';
        $route['report/waiting_service/conversationHistory']['POST'] = 'ReportWaitingServiceController/ConversationHistory';

        $route['report/interaction/synthetic'] = "ReportInteractionSyntheticController/Index";
        $route['report/interaction/synthetic/chatbot']['POST'] = 'ReportInteractionSyntheticController/ChatBot';
        $route['report/interaction/synthetic/waiting_service']['POST'] = 'ReportInteractionSyntheticController/WaitingService';
        $route['report/interaction/synthetic/attendance']['POST'] = 'ReportInteractionSyntheticController/Attendance';

        $route['report/conversation/billable'] = "ReportConversationBillableController/Index";
        $route['report/conversation/billable/find']['POST'] = 'ReportConversationBillableController/Get';

        $route['client/company'] = "ClientCompanyController/Index";
        $route['client/company/find']['POST'] = 'ClientCompanyController/Get';
        $route['client/company/add'] = 'ClientCompanyController/Add';
        $route['client/company/edit/(:any)'] = 'ClientCompanyController/Edit/$1';
        $route['client/company/save']['POST'] = "ClientCompanyController/Save/0";
        $route['client/company/save/(:num)']['POST'] = "ClientCompanyController/Save/$1";
        $route['client/company/delete/(:any)'] = 'ClientCompanyController/Delete/$1';

        $route['modelo'] = "ModeloController/Index";
        $route['modelo/find']['POST'] = 'ModeloController/Get';
        $route['modelo/add'] = 'ModeloController/Add';
        $route['modelo/edit/(:any)'] = 'ModeloController/Edit/$1';
        $route['modelo/save']['POST'] = "ModeloController/Save/0";
        $route['modelo/save/(:num)']['POST'] = "ModeloController/Save/$1";
        $route['modelo/delete/(:any)'] = 'ModeloController/Delete/$1';

        $route['tutorial/getstep']  = 'TutorialController/getStep';
        $route['tutorial/savestep'] = 'TutorialController/saveStep';

        $route['qrcode/read'] = "QRCodeController/read";

        $route['csv/export'] = 'CsvController/Export';
        $route['export/xlsx'] = 'ExportController/SaveExport';

        $route['privacy'] = "PrivacyController/Index/";

        $route['(.*)'] = "none";
    }
}
