<?php

class MyInvoice_model extends TA_model
{

    function Get($param)
    {
        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $this->talkall_admin->select('
            invoice.id_invoice,
            company.corporate_name,
            invoice.description,
            from_unixtime(invoice.expire, "%d/%m/%Y") as expire,
            invoice.value,
            invoice.status,
            invoice.payment_document,
            invoice.fiscal_document,
            invoice.proof_of_payment
        ');

        $this->talkall_admin->from('talkall_admin.invoice');
        $this->talkall_admin->join('company', 'company.id_company = invoice.id_company');
        $this->talkall_admin->like('company.corporate_name', $param['text']);
        $this->talkall_admin->where('company.id_company', $this->session->userdata('id_company'));
        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 1:
                $this->talkall_admin->order_by("invoice.description", $param['order'][0]['dir']);
                break;
            case 2:
                $this->talkall_admin->order_by("invoice.expire", $param['order'][0]['dir']);
                break;
            case 3:
                $this->talkall_admin->order_by("invoice.value", $param['order'][0]['dir']);
                break;
            case 4:
                $this->talkall_admin->order_by("invoice.status", $param['order'][0]['dir']);
                break;
            default:
                $this->talkall_admin->order_by("invoice.expire", $param['order'][0]['dir']);
                break;
        }

        $this->talkall_admin->limit($param['length'], $param['start']);


        return ['query' => $this->talkall_admin->get()->result_array(), 'count' => $count];
    }

    function Edit($key_id, $data)
    {
        $id_company = $this->session->userdata('id_company');
        $datePayment = DateTime::createFromFormat('d/m/Y', $data['input-date-payment']);

        $values = [
            'status'  => 4,
            'date_payment' => $datePayment->getTimestamp(),
            'proof_of_payment' => $data['file'],
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->where('id_invoice', $key_id);
        $this->talkall_admin->where('id_company', $id_company);
        $this->talkall_admin->update('invoice', $values);

        $this->load->model('Invoice_model', '', TRUE);
        $this->Invoice_model->invoice_log($key_id, 2);
    }

    function CheckMyInvoice()
    {
        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $this->talkall_admin->select(' 
                count(id_invoice) number_invoice_open,
                CASE 
                    WHEN DATEDIFF(from_unixtime(min(invoice.expire),"%Y-%m-%d %T"), now()) > 7
                    THEN "success"
                    WHEN DATEDIFF(from_unixtime(min(invoice.expire),"%Y-%m-%d %T"), now()) BETWEEN 0 AND 7
                    THEN "warning"
                    ELSE "danger"
                END AS color_status_invoice,
                CASE 
                    WHEN DATEDIFF(from_unixtime(min(invoice.expire),"%Y-%m-%d %T"), now()) > 7
                    THEN "Sua fatura está em aberto!"
                    WHEN DATEDIFF(from_unixtime(min(invoice.expire),"%Y-%m-%d %T"), now()) BETWEEN 0 AND 7
                    THEN "Sua fatura está prestes a vencer!""
                    ELSE "Sua fatura está vencida!"
                END AS msg_invoice
        ');

        $this->talkall_admin->from('talkall_admin.invoice');
        $this->talkall_admin->where('id_company', $this->session->userdata("id_company"));
        $this->talkall_admin->where('invoice.status', 1);
        $this->talkall_admin->order_by('expires');
        
        $query = $this->talkall_admin->get();
        return $query->result_array();
    }
}
