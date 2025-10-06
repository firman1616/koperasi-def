<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') == FALSE || $this->session->userdata('level') != 1 && $this->session->userdata('level') != 2) {
            redirect(base_url("Login"));
        }
        // $this->load->library('Pdf');
        $this->load->model('M_user', 'user');
    }
	
	public function index()
	{
		$data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
			'title' => 'Data User',
            'subtitle' => 'List',
			'conten' => 'user/index',
            'footer_js' => array('assets/js/user.js'),
            'level' => $this->user->get_level(),
		];
		$this->load->view('template/conten',$data);
	}

    function tableUser()
    {
        $data['user'] = $this->user->get_user();
        echo json_encode($this->load->view('user/user-table',$data,false));
    }

    function store()
    {
        $id = $this->input->post('id');
        if ($id != null) {
            $table = 'tbl_user';
            $dataupdate = [
                'nama_user' => $this->input->post('nama_user'),
                'username' => $this->input->post('username'),
                'password' => md5($this->input->post('password')),
                'level' => $this->input->post('level')
            ];
            $where = array('id' => $id);
            $this->m_data->update_data($table, $dataupdate, $where);
        } else {
            $table = 'tbl_user';
            $data = [
                'nama_user' => $this->input->post('nama_user'),
                'username' => $this->input->post('username'),
                'password' => md5($this->input->post('password')),
                'level' => $this->input->post('level'),
                'status' => '1',
            ];
            // $die(var_dump($data));
            $this->m_data->simpan_data($table, $data);
        }
    }

    function vedit($id)
    {
        $table = 'tbl_user';
        $where = array('id' => $id);
        $data = $this->m_data->get_data_by_id($table, $where)->row();
        echo json_encode($data);
    }
}
