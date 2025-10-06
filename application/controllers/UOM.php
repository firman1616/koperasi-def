<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UOM extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') == FALSE || $this->session->userdata('level') != 1 && $this->session->userdata('level') != 2) {
            redirect(base_url("Login"));
        }
        // $this->load->library('Pdf');
        // $this->load->model('M_dashboard', 'dash');
    }
	
	public function index()
	{
		$data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
			'title' => 'Data Satuan',
            'subtitle' => 'List',
			'conten' => 'uom/index',
            'footer_js' => array('assets/js/uom.js')
		];
		$this->load->view('template/conten',$data);
	}

    function tableUOM()
    {
        $data['uom'] = $this->m_data->get_data('tbl_uom')->result();

        echo json_encode($this->load->view('uom/uom-table',$data,false));
    }

    function store()
    {
        $id = $this->input->post('id');
        if ($id != null) {
            $table = 'tbl_uom';
            $dataupdate = [
                'kode' => $this->input->post('kode_satuan'),
                'uom' => $this->input->post('nama_satuan')
            ];
            $where = array('id' => $id);
            $this->m_data->update_data($table, $dataupdate, $where);
        } else {
            $table = 'tbl_uom';
            $data = [
                'kode' => $this->input->post('kode_satuan'),
                'uom' => $this->input->post('nama_satuan')
            ];
            // $die(var_dump($data));
            $this->m_data->simpan_data($table, $data);
        }
    }

    function vedit($id)
    {
        $table = 'tbl_uom';
        $where = array('id' => $id);
        $data = $this->m_data->get_data_by_id($table, $where)->row();
        echo json_encode($data);
    }
}
