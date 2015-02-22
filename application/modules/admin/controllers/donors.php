<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Donors extends admin {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('donors_model');
	}
    
	/*
	*
	*	Default action is to show all the donors
	*
	*/
	public function index() 
	{
		$where = 'donors.donor_id > 0';
		$table = 'donors';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'all-donors';
		$config['total_rows'] = $this->donors_model->count_items($table, $where);
		$config['uri_segment'] = 2;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active">';
		$config['cur_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->donors_model->get_all_donors($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['donors'] = $query;
			$v_data['page'] = $page;
			$data['content'] = $this->load->view('donors/all_donors', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Donor do not exist';
		}
		$data['title'] = 'All Donors';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Add a new donor page
	*
	*/
	public function add_donor($page) 
	{
		//form validation rules
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|is_unique[donors.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		$this->form_validation->set_rules('other_names', 'Other Names', 'xss_clean');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('post_code', 'Post Code', 'xss_clean');
		$this->form_validation->set_rules('country_id', 'Country', 'xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|xss_clean');
		$this->form_validation->set_rules('activated', 'Activate Donor', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//check if donor has valid login credentials
			if($this->donors_model->add_donor())
			{
				$this->session->set_userdata('success_message', $this->input->post('first_name').' has been successfully added as a donor');
				redirect('all-donors/'.$page);
			}
			
			else
			{
				$data['error'] = 'Unable to add donor. Please try again';
			}
		}
		
		//open the add new donor page
		$data['title'] = 'Add New Donor';
		$data['content'] = modules::run('admin/donors/load_add_page');
		$this->load->view('templates/general_admin', $data);
	}
	
	public function load_add_page()
	{
		$v_data['countries'] = $this->donors_model->get_all_countries();
		$this->load->view('donors/add_donor', $v_data);
	}
    
	/*
	*
	*	Edit an existing donor page
	*	@param int $donor_id
	*
	*/
	public function edit_donor($donor_id, $page) 
	{
		//form validation rules
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean');
		$this->form_validation->set_rules('other_names', 'Other Names', 'xss_clean');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('post_code', 'Post Code', 'xss_clean');
		$this->form_validation->set_rules('country_id', 'Country', 'xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|xss_clean');
		$this->form_validation->set_rules('activated', 'Activate Donor', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//check if donor has valid login credentials
			if($this->donors_model->edit_donor($donor_id))
			{
				$this->session->set_userdata('success_message', $this->input->post('first_name').'\'s donor details have been successfully updated');
				redirect('all-donors/'.$page);
			}
			
			else
			{
				$data['error'] = 'Unable to add donor. Please try again';
			}
		}
		
		//open the add new donor page
		$data['title'] = 'Edit Donor';
		$v_data['countries'] = $this->donors_model->get_all_countries();
		
		//select the donor from the database
		$query = $this->donors_model->get_donor($donor_id);
		if ($query->num_rows() > 0)
		{
			$v_data['donors'] = $query->result();
			$data['content'] = $this->load->view('donors/edit_donor', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'donor does not exist';
		}
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing donor page
	*	@param int $donor_id
	*
	*/
	public function delete_donor($donor_id, $page) 
	{
		if($this->donors_model->delete_donor($donor_id))
		{
			$this->session->set_userdata('success_message', 'Donor has been successfully deleted');
		}
		else
		{
			$this->session->set_userdata('error_message', 'Could not delete donor details. Please try again');
		}
		redirect('all-donors/'.$page);
	}
    
	/*
	*
	*	Activate an existing donor page
	*	@param int $donor_id
	*
	*/
	public function activate_donor($donor_id, $page) 
	{
		if($this->donors_model->activate_donor($donor_id))
		{
			$this->session->set_userdata('success_message', 'Donor has been successfully deleted');
		}
		else
		{
			$this->session->set_userdata('error_message', 'Could not delete donor details. Please try again');
		}
		
		redirect('all-donors/'.$page);
	}
    
	/*
	*
	*	Deactivate an existing donor page
	*	@param int $donor_id
	*
	*/
	public function deactivate_donor($donor_id, $page) 
	{
		$this->donors_model->deactivate_donor($donor_id);
		
		redirect('all-donors/'.$page);
	}
	
	/*
	*
	*	Reset a donor's password
	*	@param int $donor_id
	*
	*/
	public function reset_password($donor_id, $page)
	{
		$new_password = $this->login_model->reset_password($donor_id);
		$this->session->set_userdata('success_message', 'Password reset successfull. The new password is <br/><strong>'.$new_password.'</strong>');
		
		redirect('all-donors/'.$page);
	}
}
?>