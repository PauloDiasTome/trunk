<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//
//  TALKALL Controller
//
//
// REGRAS DE NEGOCIO DE AUTENTICAÇÃO
// PERMISSÕES
//
// AS MODELS Permission_model , User_model JÁ ESTÃO CARREGADAS NÃO CARREGUE NOVAMENTE :)
//
class TA_Controller extends CI_Controller
{
     public $UserPermissions;
     public $DevelopUser = false;
     public $IntranetUser = false;
     public $HomePage = '/contact';


     public function __construct()
     {
          parent::__construct();

          $this->load->model('Permission_model', '', TRUE);
          $this->load->model('intranet/Permission_intranet_model', '', TRUE);
          $this->load->model('User_model', '', TRUE);

          if ($_SERVER['HTTP_HOST'] == $this->config->item('dev_domain')) {
               $this->HomePage = 'apps';
               $this->DevelopUser = true;
          }

          if ($_SERVER['HTTP_HOST'] == $this->config->item('intranet_domain')) {
               $this->HomePage = '/company';
               $this->IntranetUser = true;
          }
     }


     public function checkPermission($permission)
     {
          //verifica se abriu a mesma conta em outro navegador
          $this->checkSessionbrowser();

          $this->checkSession();

          $this->Permission_model->syncCompanyData();

          if (!$this->session->userdata('is_in_trial_period') && $this->session->userdata('is_trial') == 1) {
               return redirect('account/trialPeriod');
          };

          try {
               $ip = $this->input->ip_address();
               $key_remote_id = $this->session->userdata('key_remote_id');

               if (!$this->Permission_model->CheckUserPermissions($key_remote_id, $permission)) {
                    return redirect('account/withoutPermission');
                    exit;
               }

               if (!$this->Permission_model->IPPermissions($key_remote_id, $ip)) {
                    session_destroy();
                    return show_error("Esse IP não possui permissão para acessar o sistema", 404, 'Um erro foi encontrado');
               }

               return;
          } catch (Exception $e) {
               session_destroy();
               return show_error($e->getMessage(), 404, 'Um erro foi encontrado');
          }
     }


     public function checkIntranetPermission($permission)
     {
          if ($this->session->userdata('key_remote_id') == null) {
               session_destroy();
               return redirect('account/login');
          }

          try {
               $ip = $this->input->ip_address();
               $key_remote_id = $this->session->userdata('key_remote_id');

               if (!$this->Permission_intranet_model->CheckUserPermissions($key_remote_id, $permission)) {
                    session_destroy();
                    return show_error("Você não possui permissão para ver esse item!", 404, 'Um erro foi encontrado');
               }

               if (!$this->Permission_model->IPPermissions($key_remote_id, $ip)) {
                    session_destroy();
                    return show_error("Esse IP não possui permissão para acessar o sistema", 404, 'Um erro foi encontrado');
               }

               return;
          } catch (Exception $e) {
               session_destroy();
               return show_error($e->getMessage(), 404, 'Um erro foi encontrado');
          }
     }

     //REDIRECIONA SE USUARIO JÁ ESTÁ LOGADO (NÃO DEIXA ACESSAR SEM LOGIN)
     public function checkLogin()
     {
          if ($this->session->userdata('key_remote_id') != null) {
               return redirect($this->HomePage);
          }
     }


     //VERIFICA SE O USUARIO ESTÁ LOGADO
     public function checkSession()
     {
          if ($this->session->userdata('key_remote_id') == null) {
               session_destroy();
               return redirect('account/login');
          }
     }

     //VERIFICA SE O USUARIO ESTA EM OUTRO NAVEGADOR
     public function checkSessionbrowser()
     {
          if ($this->session->userdata('key_remote_id') && $this->session->userdata["WebSessionToken"] != $this->User_model->GetBrowserWebSession($this->session->userdata('WebSessionToken'))) {

               session_destroy();
               return redirect('account/login');
          }
     }
}
