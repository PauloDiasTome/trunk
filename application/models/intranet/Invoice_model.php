<?php

class Invoice_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetTalkall();
    }

    function Edit($key_id, $data)
    {
        $expire = DateTime::createFromFormat('d/m/Y', $data['input-expire']);

        $values = [
            'id_company' => $data['select-company'],
            'expire' => $expire->getTimestamp(),
            'status'  => $data['select-status'],
            'description' => $data['input-description'],
            'value' => $data['input-value'],
        ];

        if (!empty($data['input-date-payment'])) {
            $datePayment = DateTime::createFromFormat('d/m/Y', $data['input-date-payment']);
            $values['date_payment'] = $datePayment->getTimestamp();
        }

        isset($data['file1']) ? $values['payment_document'] = $data['file1'] : null;
        isset($data['file2']) ? $values['fiscal_document'] = $data['file2'] : null;
        isset($data['file3']) ? $values['proof_of_payment'] = $data['file3'] : null;

        $this->db->where('id_invoice', $key_id);
        $this->db->update('invoice', $values);
        $this->invoice_log($key_id, 2);
    }

    function Add($data)
    {
        $date = new DateTime();
        $expire = DateTime::createFromFormat('d/m/Y', $data['input-expire']);

        $values = [
            'id_company' => $data['select-company'],
            'creation' => $date->getTimestamp(),
            'expire' => $expire->getTimestamp(),
            'status'  => $data['select-status'],
            'description' => $data['input-description'],
            'value' => $data['input-value'],
        ];

        if (!empty($data['input-date-payment'])) {
            $datePayment = DateTime::createFromFormat('d/m/Y', $data['input-date-payment']);
            $values['date_payment'] = $datePayment->getTimestamp();
        }

        isset($data['file1']) ? $values['payment_document'] = $data['file1'] : null;
        isset($data['file2']) ? $values['fiscal_document'] = $data['file2'] : null;
        isset($data['file3']) ? $values['proof_of_payment'] = $data['file3'] : null;

        $this->db->insert('invoice', $values);
        $key_id = $this->db->insert_id();
        $this->invoice_log($key_id, 1);
    }

    function Delete($key_id)
    {
        $this->db->where('id_invoice', $key_id);
        $sql = 'select payment_document, fiscal_document, proof_of_payment from talkall_admin.invoice where id_invoice = ' . $key_id;
        $result = $this->db->query($sql)->result_array();

        if (file_exists(FCPATH . $result[0]['payment_document'])) {
            unlink(FCPATH . $result[0]['payment_document']);
        }
        if (file_exists(FCPATH . $result[0]['fiscal_document'])) {
            unlink(FCPATH . $result[0]['fiscal_document']);
        }
        if (file_exists(FCPATH . $result[0]['proof_of_payment'])) {
            unlink(FCPATH . $result[0]['proof_of_payment']);
        }
        $this->db->delete('invoice');
        $this->invoice_log($key_id, 3);
    }

    function Get($text, $start, $length)
    {
        $sql = "select\n";
        $sql .= "invoice.id_invoice,\n";
        $sql .= "company.corporate_name,\n";
        $sql .= "from_unixtime(invoice.expire,'%d/%m/%Y') as expire,\n";
        $sql .= "invoice.description,\n";
        $sql .= "invoice.value,\n";
        $sql .= "invoice.status,\n";
        $sql .= "invoice.payment_document,\n";
        $sql .= "invoice.fiscal_document,\n";
        $sql .= "invoice.proof_of_payment\n";
        $sql .= "from talkall_admin.invoice\n";
        $sql .= "inner join company\n";
        $sql .= "on company.id_company = invoice.id_company\n";
        $sql .= "where company.corporate_name like '%" . $text . "%'\n";
        $sql .= "order by 1 desc limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function GetMyInvoices($text, $start, $length)
    {
        $id_company = $this->session->userdata('id_company');

        $sql = "select\n";
        $sql .= "invoice.id_invoice,\n";
        $sql .= "company.corporate_name,\n";
        $sql .= "from_unixtime(invoice.expire,'%d/%m/%Y') as expire,\n";
        $sql .= "invoice.description,\n";
        $sql .= "invoice.value,\n";
        $sql .= "case\n";
        $sql .= "when invoice.status = '1' then 'Aberto'\n";
        $sql .= "when invoice.status = '2' then 'Fechado'\n";
        $sql .= "when invoice.status = '3' then 'Cancelado'\n";
        $sql .= "when invoice.status = '4' then 'Revisão'\n";
        $sql .= "end status,\n";
        $sql .= "invoice.payment_document,\n";
        $sql .= "invoice.fiscal_document,\n";
        $sql .= "invoice.proof_of_payment\n";
        $sql .= "from talkall_admin.invoice\n";
        $sql .= "inner join company\n";
        $sql .= "on company.id_company = invoice.id_company\n";
        $sql .= "where company.corporate_name like '%" . $text . "%'\n";
        $sql .= "and company.id_company = '" . $id_company . "'\n";
        $sql .= "order by 1 desc limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "invoice.id_invoice,\n";
        $sql .= "date_format(from_unixtime(invoice.creation),'%d/%m/%Y') as creation,\n";
        $sql .= "invoice.id_company,\n";
        $sql .= "company.corporate_name,\n";
        $sql .= "date_format(from_unixtime(invoice.expire),'%d/%m/%Y') as expire,\n";
        $sql .= "invoice.description,\n";
        $sql .= "payment_document,\n";
        $sql .= "fiscal_document,\n";
        $sql .= "proof_of_payment,\n";
        $sql .= "invoice.value,\n";
        $sql .= "invoice.status,\n";
        $sql .= "date_format(from_unixtime(invoice.date_payment),'%d/%m/%Y') as date_payment\n";
        $sql .= "from talkall_admin.invoice\n";
        $sql .= "inner join company\n";
        $sql .= "on company.id_company = invoice.id_company\n";
        $sql .= "where invoice.id_invoice = '" . $id . "';\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function Count($text)
    {
        $sql = "select count(invoice.id_invoice) count\n";
        $sql .= "from talkall_admin.invoice\n";
        $sql .= "inner join company\n";
        $sql .= "on company.id_company = invoice.id_company\n";
        $sql .= "where company.corporate_name like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function invoice_log($id_invoice, $type)
    {
        $date = new DateTime();

        $log = [
            'id_invoice' => $id_invoice,
            'creation' => $date->getTimestamp(),
            'user_key_remote_id' => $this->session->userdata('key_remote_id'),
            'type'  => $type,
        ];

        $this->db->insert('invoice_log', $log);
    }

    function List($id)
    {

        $result = $this->db->query("SELECT * FROM talkall_admin.invoice where {$id}");
        return $result;
    }
}
