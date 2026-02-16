<?php

use GuzzleHttp\Psr7\LazyOpenStream;
use Zendesk\API\HttpClient as ZendeskAPI;

class Zendesk_model extends TA_model
{
    public $key = "";
    public $domain = "";
    protected $zendesk;

    public function __construct()
    {
        parent::__construct();

        $this->init();

        $this->client = new ZendeskAPI($this->config->item('zendesk_app_id'));
        $this->client->setAuth('oauth', [
            'token' => $this->key
        ]);
    }

    // https://support.zendesk.com/hc/pt-br/articles/203663836-Uso-de-autentica%C3%A7%C3%A3o-OAuth-com-seu-aplicativo
    function Add($code, $url): bool
    {

        $payload = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->config->item('zendesk_app_id'),
            'client_secret' => $this->config->item('zendesk_secret_id'),
            'redirect_uri' => base_url('integration/add/zendesk'),
            'scope' => array('write', 'read')
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://{$url}.zendesk.com/oauth/tokens",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));


        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ((int)$status_code == 200) {

            $dados = json_decode($response, true);

            $date = new DateTime();
            $data = array(
                'name' => 'Zendesk',
                'id' =>  $url . '.zendesk.com',
                'pw' => $dados['access_token'],
                't' => $date->getTimestamp(),
                'type' => "15"
            );
            $this->db->insert('channel', $data);
            $this->key = $dados['access_token'];
            return true;
        } else {
            throw new Exception("A requisição falhou \n Status CODE: {$status_code} \n Resposta: {$response}");
        }
        return false;
    }


    public function init($DB = null)
    {

        if ($DB != null) {
            $this->db = $DB;
        }

        $sql = "SELECT * FROM channel where type = 15 limit 1";
        $query = $this->db->query($sql);

        $count = $query->num_rows();

        if ($count > 0) {
            $data = $query->result_array();
            $this->key = $data[0]['pw'];
            $this->domain = $data[0]['id'];
        } else {
            throw new Exception('code not found');
        }
    }

    
    public function AddContact($arr)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://{$this->domain}/api/v2/users/create_or_update.json",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($arr),
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ),
        ));

        $body = curl_exec($curl);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($body, 0, $headerSize);
        $header = $this->getHeaders($header);
        $response = substr($body, $headerSize);

        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);


        if ((int)$status_code == 201 || (int)$status_code == 200) {

            $this->Setlimit($header['X-Rate-Limit-Remaining']);

            $dados = json_decode($response, true);
            return $dados;
        } else if ((int)$status_code == 422) {
            return $dados = "Administrador";
        } else {
            throw new Exception("A requisição falhou \n Status CODE: {$status_code} \n Resposta: {$response}");
        }
    }


    public function SyncContacts()
    {

        $i = 0;
        while (true) {

            $sql =  "SELECT * FROM contact where is_private = 1 and is_group = 1 and email != '' and email is not null and crm_profile is null\n";
            $sql .=  "limit $i, 1000\n";

            $query = $this->db->query($sql);

            $count = $query->num_rows();

            if ($count == 0) {
                break;
            } else {
                $aContatos = $query->result_array();

                foreach ($aContatos as $row) {
                    if ($row['email'] != null) {
                        $arr = array(
                            'user' =>
                            array(
                                'name' => $row['full_name'] == null ? $row['key_remote_id'] : $row['full_name'],
                                'email' => $row['email'],
                                'phone' => $row['key_remote_id'],
                                'role' => 'end-user',
                            ),
                        );

                        $data = $this->AddContact($arr);

                        if ($data != null) {

                            $crm_profile = "https://{$this->domain}/agent/users/{$data['user']['id']}";

                            if ($data == "Adminsitrador") {
                                $crm_profile = "https://{$this->domain}/agent/users/";
                            }

                            $date = new DateTime();
                            $this->db->set('crm_profile', $crm_profile);
                            $this->db->set('crm_timestamp', $date->getTimestamp());
                            $this->db->where('email', $row['email']);
                            $this->db->update('contact');
                        }
                    }


                    $i++;
                }
            }
        }
    }


    public function Setlimit($value)
    {
        if ((int)$value == 5) {
            sleep(60);
        }
    }
    

    function getHeaders($respHeaders)
    {
        $headers = array();

        $headerText = substr($respHeaders, 0, strpos($respHeaders, "\r\n\r\n"));

        foreach (explode("\r\n", $headerText) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }
        }

        return $headers;
    }

    /**
    * Parameter examples 
    * $file = "../../tests/assets/UK.png"
    * $type = "image/png"
    * $name = "UK test non-alpha chars.png"
    */
    public function UploadFileAttachment($file, $type, $name)
    {
        $attachment = $this->client->attachments()->upload(array(
            'file' => $file,
            'type' => $type,
            'name' => $name
        ));

        return $attachment;
    }

    /**
    * Parameter examples 
    * $file = "../../tests/assets/UK.png"
    * $type = "image/png"
    * $name = "UK test non-alpha chars.png"
    */
    public function UploadStreamAttachment($file, $type, $name)
    {
        $attachment = $this->client->attachments()->upload(array(
            'file' => new LazyOpenStream($file, 'r'),
            'type' => $type,
            'name' => $name
        ));

        return $attachment;
    }

    public function GetAllGroups()
    {
        $groups = $this->client->groups()->findAll();

        return $groups;
    }

    /**
    * Parameter examples 
    * $name = "2d line"
    */
    public function CreateGroup($name)
    {
        $newGroup = $this->client->groups()->create(array(
            'name' => $name,
        ));

        return $newGroup;
    }

    /**
    * Parameter examples 
    * $locale = "en-us"
    * $title = "Smartest Fish in the World"
    */
    public function CreateArticles($sectionId, $locale, $title)
    {
        $article = $this->client->helpCenter->sections($sectionId)->articles()->create([
            'locale' => $locale,
            'title' => $title,
        ]);

        return $article;
    }

    /**
    * Parameter examples 
    * $position = 1
    * $locale = "en-us"
    * $name = "Super Hero Tricks"
    * $description = "This section contains a collection of super hero tricks"
    */
    public function CreateSections($categoryId, $position, $locale, $name, $description)
    {
        $section = $this->client->helpCenter->categories($categoryId)->sections()->create([
            'position' => $position,
            'locale' => $position,
            'name' => $name,
            'description' => $description,
        ]);

        return $section;
    }

    public function GetAllArticles()
    {
        $articles = $this->client->helpCenter->articles()->findAll();

        return $articles;
    }

    public function GetAllCategories()
    {
        $categories = $this->client->helpCenter->categories()->findAll();

        return $categories;
    }

    public function GetAllSections()
    {
        $sections = $this->client->helpCenter->sections()->findAll();

        return $sections;
    }

    /**
    * Parameter examples 
    * $externalId = "123567854"
    * $name = "Example_Organization"
    * $sharedTickets = 1
    * $sharedComments = 1
    * $tags = array('dog', 'cat', 'fish', 'strange_creature')
    * $organizationFields = array('favorite animal' => 'fish')
    */
    public function CreateOrganization($externalId, $name, $sharedTickets, $sharedComments, $tags, $organizationFields)
    {
        $newOrganization = $this->client->organizations()->create([
            'external_id' => $externalId,
            'name' => $name,
            'shared_tickets' => $sharedTickets,
            'shared_comments' => $sharedComments,
            'tags' => $tags,
            'organization_fields' => $organizationFields
        ]);

        return $newOrganization;
    }

    public function GetAllOrganizations()
    {
        $organizations = $this->client->organizations()->findAll();

        return $organizations;
    }

    /**
    * Parameter examples 
    * $ticketField = 1
    * $name = beetle
    * $value = beetle
    */
    public function CreateDropdownOption($ticketField, $name, $value)
    {
        $result = $this->client->ticketFields($ticketField)->options()->create([
            'name' => $name,
            'value' => $value
        ]);

        return $result;
    }

    /**
    * Parameter examples 
    * $ticketField = 1
    * $id = 1
    * $name = beetle
    * $value = beetle
    */
    public function UpdateDropdownOption($ticketField, $id, $name, $value)
    {
        $result = $this->client->ticketFields($ticketField)->options()->update($id, [
            'name' => $name,
            'value' => $value
        ]);

        return $result;
    }

    /**
    * Parameter examples 
    * $type = "problem"
    * $tags = array('demo', 'testing', 'api', 'zendesk')
    * $subject = "The quick brown fox jumps over the lazy dog"
    * $commentBody = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
    * $localeId = 1,
    * $name => "Example User",
    * $email => "customer@example.com",
    * $priority = "normal",
    */
    public function CreateTicket($type, $tags, $subject, $commentBody, $localeId, $name, $email, $priority, $attachmentUploadToken)
    {
        $ticket = $this->client->tickets()->create([
            'type' => $type,
            'tags'  => $tags,
            'subject'  => $subject,
            'comment'  => array(
                'body' => $commentBody,
                'uploads' => [$attachmentUploadToken]
            ),
            'requester' => array(
                'locale_id' => $localeId,
                'name' => $name,
                'email' => $email,
            ),
            'priority' => $priority,
        ]);
    
        return $ticket;
    }

    /**
    * Parameter examples 
    * $type = "problem"
    * $tags = array('demo', 'testing', 'api', 'zendesk')
    * $subject = "The quick brown fox jumps over the lazy dog"
    * $commentBody = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
    * $localeId = 1,
    * $name => "Example User",
    * $email => "customer@example.com",
    * $priority = "normal",
    */
    public function UpdateTicket($ticketId, $type, $tags, $subject, $commentBody, $localeId, $name, $email, $priority, $attachmentUploadToken)
    {
        $ticket = $this->client->tickets()->update($ticketId, [
            'type' => $type,
            'tags'  => $tags,
            'subject'  => $subject,
            'comment'  => array(
                'body' => $commentBody,
                'uploads' => [$attachmentUploadToken]
            ),
            'requester' => array(
                'locale_id' => $localeId,
                'name' => $name,
                'email' => $email,
            ),
            'priority' => $priority,
        ]);

        return $ticket;
    }

    /**
    * Parameter examples 
    * $ticketId = 1
    */
    public function DeleteTicket($ticketId)
    {
        $this->client->tickets()->delete($ticketId);

        return true;
    }

    public function GetAllTickets()
    {
        $tickets = $this->client->tickets()->findAll();

        return $tickets;
    }

    /**
    * Parameter examples 
    * $ticketId = 1
    */
    public function GetAllTicketComments($ticketId)
    {
        $tickets = $this->client->tickets($ticketId)->comments()->findAll();

        return $tickets;
    }

    /**
    * Parameter examples 
    * $ticketId = 1
    */
    public function GetAllTicketMetrics($ticketId)
    {
        $metrics = $this->client->tickets($ticketId)->metrics()->findAll();

        return $metrics;
    }

    /**
    * Parameter examples 
    * $ticketId = 1
    */
    public function GetTicketById($ticketId)
    {
        $result = $this->client->tickets()->find($ticketId);

        return $result->ticket;
    }

    /**
    * Parameter examples 
    * name = "API Demo"
    * email = "demo@example.com"
    * phone = "+1-954-704-6031"
    * role  = "end-user"
    * details = "This user has been created with the API"
    */
    public function CreateUser($name, $email, $phone, $role, $details)
    {
        $user = $this->client->users()->create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role'  => $role,
            'details' => $details
        ]);

        return $user;
    }

    public function GetAllUsers()
    {
        $users = $this->client->users()->findAll();

        return $users;
    }
}
