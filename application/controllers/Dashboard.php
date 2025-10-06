<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') == FALSE || $this->session->userdata('level') != 1 && $this->session->userdata('level') != 2 && $this->session->userdata('level') != 3) {
            redirect(base_url("Login"));
        }
        // $this->load->library('Pdf');
        $this->load->model('M_dashboard', 'dash');
    }

	public function index()
	{
		$data = [
			'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
			'title' => 'Dashboard',
			'conten' => 'conten/dashboard',
			'trans_month' => $this->dash->trans_month(),
			'trans_day' => $this->dash->trans_day(),
			'count_anggota' => $this->dash->count_anggota(),
			'tempo' => $this->dash->count_tempo(),
			'footer_js' => array('assets/js/dashboard.js')
		];
		$this->load->view('template/conten',$data);
	}

	function tableBarangHabis()
    {
        $data['qty_0'] = $this->dash->qty_0()->result();

        echo json_encode($this->load->view('conten/barang-habis-table',$data,false));
    }

	function tableBarangHampirHabis()
    {
        $data['qty_kurang'] = $this->dash->qty_kurang()->result();

        echo json_encode($this->load->view('conten/barang-hampir-habis-table',$data,false));
    }
}
