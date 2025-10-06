<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') == FALSE || $this->session->userdata('level') != 1 && $this->session->userdata('level') != 2) {
            redirect(base_url("Login"));
        }
        // $this->load->library('Pdf');
        $this->load->model('M_keuangan', 'keu');
    }
	
	public function index()
	{
		$data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
			'title' => 'Kategori Keuangan',
            'subtitle' => 'List',
			'conten' => 'kategori/index',
            'kateg' => $this->m_data->get_data('tbl_kategori'),
            'footer_js' => array('assets/js/kategori.js')
		];
		$this->load->view('template/conten',$data);
	}

    function tableKateg()
    {
        $data['sub'] = $this->keu->get_data()->result();

        echo json_encode($this->load->view('kategori/kategori-table',$data,false));
    }

    function store()
    {
        $id = $this->input->post('id');
        if ($id != null) {
            $table = 'tbl_kateg_trans';
            $dataupdate = [
                'name' => $this->input->post('nama_sub'),
                'kategori_id' => $this->input->post('kategori')
            ];
            $where = array('id' => $id);
            $this->m_data->update_data($table, $dataupdate, $where);
        } else {
            $table = 'tbl_kateg_trans';
            $data = [
                'name' => $this->input->post('nama_sub'),
                'kategori_id' => $this->input->post('kategori')
            ];
            // $die(var_dump($data));
            $this->m_data->simpan_data($table, $data);
        }
    }

    function vedit($id)
    {
        $table = 'tbl_kateg_trans';
        $where = array('id' => $id);
        $data = $this->m_data->get_data_by_id($table, $where)->row();
        echo json_encode($data);
    }
}
