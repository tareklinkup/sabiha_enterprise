<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Delivery extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->sbrunch = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model('Billing_model');
        $this->load->library('cart');
        $this->load->model('Model_table', "mt", TRUE);
        $this->load->helper('form');
    }

    public function index()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Delivery Entry";
        $data['quotationId'] = 0;
        // $data['invoice'] = $this->mt->generateQuotationInvoice();
        $data['content'] = $this->load->view('Administrator/delivery/delivery_entry', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function saveDelivery()
    {
        $res = ['success' => false, 'message' => ''];
        $data = json_decode($this->input->raw_input_stream);

        try {
            $deliveryArray = (array)$data;
            unset($deliveryArray['delivery_id']);

            $deliveryArray['status']   = 'a';
            $deliveryArray['AddBy']    = $this->session->userdata("FullName");
            $deliveryArray['AddTime']  = date('Y-m-d H:i:s');
            $deliveryArray['BranchId'] = $this->session->userdata('BRANCHid');

            $this->db->insert('tbl_courier_delivery', $deliveryArray);
            $deliveryId = $this->db->insert_id();

            $res = ['success' => true, 'message' => 'Courier Delivery Save successfully'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }
    public function updateDelivery()
    {
        $res = ['success' => false, 'message' => ''];
        $data = json_decode($this->input->raw_input_stream);
        $deliveryId = $data->delivery_id;
        try {
            $deliveryArray = (array)$data;
            unset($deliveryArray['delivery_id']);

            $deliveryArray['UpdateBy']    = $this->session->userdata("FullName");
            $deliveryArray['UpdateTime']  = date('Y-m-d H:i:s');
            $deliveryArray['BranchId'] = $this->session->userdata('BRANCHid');

            $this->db->where('delivery_id', $deliveryId)->update('tbl_courier_delivery', $deliveryArray);

            $res = ['success' => true, 'message' => 'Courier Delivery Update successfully'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }
    public function getDeliveries()
    {
        $res = ['success' => false, 'message' => ''];
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if (isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != '') {
            $clauses .= " and date between '$data->dateFrom' and '$data->dateTo'";
        }

        $results = $this->db->query("SELECT * FROM tbl_courier_delivery WHERE status = 'a' and BranchId = ? $clauses
        ", $this->session->userdata('BRANCHid'))->result();

        echo json_encode($results);
    }
    public function deleteDelivery()
    {
        $res = ['success' => false, 'message' => ''];
        $data = json_decode($this->input->raw_input_stream);

        try {
            $this->db->where('delivery_id', $data->deliveryId)->set('status', 'd')->update('tbl_courier_delivery');
            $res = ['success' => true, 'message' => 'Courier Delivery Delete successfully'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }


    public function deliveryRecord()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Delivery Record";
        $data['content'] = $this->load->view('Administrator/delivery/delivery_record', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
}
