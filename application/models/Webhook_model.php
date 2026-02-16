<?php

class Webhook_model extends TA_model
{
    function Add($channel, $json)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'channel' => $channel,
            'json' => $json,
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->insert('webhook', $values);

        $sql = "select\n";
        $sql .= "oauth_clients.webhooks\n";
        $sql .= "from oauth_clients\n";
        $sql .= "inner join oauth_access_tokens on oauth_clients.client_id = oauth_access_tokens.client_id\n";
        $sql .= "inner join user on oauth_access_tokens.user_id = user.id_user\n";
        $sql .= "inner join company on user.id_company = company.id_company\n";
        $sql .= "inner join channel on company.id_company = channel.id_company\n";
        $sql .= "where oauth_access_tokens.expires >= now() and channel.id = '$channel' and oauth_access_tokens.scope like '%message%'\n";

        $result = $this->talkall_admin->query($sql);

        if ($result->num_rows() > 0) {

            $values = [
                'creation' => $date->getTimestamp(),
                'url' => $result->result_array()[0]['webhooks'],
                'json' => $json,
                'status' => 1
            ];

            $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
            $this->talkall_admin->insert('callback', $values);
        }

        $this->talkall_admin->close();
    }


    function Send($channel, $json)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'channel' => $channel,
            'json' => $json,
            'type' => 1
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->insert('webhook', $values);
        $this->talkall_admin->close();
    }


    function createBill($content)
    {
        $values = [
            // 'id_customer_vindi' => $content->event->data->bill->customer->id,
            'id_customer_vindi' => "17899282",
            'price' => $content->event->data->bill->amount,
            'status' => 1,
            'id_bill_vindi' => $content->event->data->bill->id
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $res = $this->talkall_admin->insert('bill', $values);
        $this->talkall_admin->close();

        return $res;
    }


    function createCharge($content)
    {

        // $values = [
        //     'id_payment_method' => 1,
        //     'amount' => 0,
        //     'status' => 1,
        //     'id_bill' => 84,
        //     'id_charge_vindi' => 6873654,
        //     'test' => $content
        // ];

        $values = [
            'id_payment_method_vindi' => $content->event->data->charge->payment_method->id,
            'amount' => $content->event->data->charge->amount,
            'status' => 1,
            'id_bill_vindi' => $content->event->data->charge->bill->id,
            'id_charge_vindi' => $content->event->data->charge->id,
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $res = $this->talkall_admin->insert('charge', $values);
        $this->talkall_admin->close();

        return $res;
    }


    function payBill($content)
    {
        $values = [
            'status' => '2'
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->where('id_bill_vindi', $content->event->data->bill->id);
        $res = $this->talkall_admin->update('bill', $values);
        $this->talkall_admin->close();

        return $res;
    }


    function cancelBill($content)
    {
        $values = [
            'status' => '4'
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->where('id_bill', $content);
        $this->talkall_admin->update('bill', $values);
        $this->talkall_admin->close();
    }



    function payCharge($content)
    {
        $values = [
            'status' => 2
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->where('id_charge_vindi', $content->event->data->bill->charges[0]->id);
        $res = $this->talkall_admin->update('charge', $values);
        $this->talkall_admin->close();

        return $res;
    }

    function createIssue($content)
    {
        //     $values = [
        //         'status' => 3
        //     ];

        // $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        // $this->talkall_admin->where('id_bill', $content->event->data->issue->);
        // $this->talkall_admin->update('bill', $values);
        // $this->talkall_admin->close();

        $values = [
            'id_payment_method_vindi' => 6360,
            'amount' => 22,
            'status' => 4,
            'id_bill_vindi' => 7056732,
            'id_charge_vindi' => 6891811,
            'test' => $content
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $res = $this->talkall_admin->insert('charge', $values);
        $this->talkall_admin->close();

        return $res;
    }


    function getCompanyId($id)
    {
        $sql = "SELECT company.id_company
                FROM company
                WHERE id_customer_vindi = $id";

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $result = $this->talkall_admin->query($sql);
        $this->talkall_admin->close();

        return $result->result_array();
    }


    function getPaymentMethodId($id)
    {
        $sql = "SELECT payment_method.id_payment_method
                FROM payment_method
                WHERE id_payment_method_vindi = $id";

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $result = $this->talkall_admin->query($sql);
        $this->talkall_admin->close();

        return $result->result_array();
    }
}
