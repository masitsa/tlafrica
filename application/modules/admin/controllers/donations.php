<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Donations extends admin 
{
	var $hills_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('donations_model');
		$this->load->model('hills_model');
		$this->load->model('donors_model');
		
		//path to image directory
		$this->hills_location = base_url().'assets/images/hills/';
	}
    
	/*
	*
	*	Default action is to show all the donations
	*
	*/
	public function index() 
	{
		//check if search is active
		$search = $this->session->userdata('all_donations_search');
		$where = 'donation.hill_id = hill.hill_id AND donation.donor_id = donors.donor_id AND donation_option.donation_option_id = donation.donation_option_id';
		
		if(!empty($search))
		{
			$where .= $search;
		}
		$table = 'donation, hill, donors, donation_option';
		$segment = 2;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'all-donations';
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
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
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->donations_model->get_all_donations($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['donations'] = $query;
			$v_data['page'] = $page;
			$v_data['donations_page'] = 1;
			$v_data['hills_location'] = $this->hills_location;
			$v_data['donation_types'] = $this->donations_model->get_donation_types();
			$data['content'] = $this->load->view('donations/all_donations', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'add-donation" class="btn btn-success pull-right">Add donation</a>There are no donations';
		}
		$data['title'] = 'All donations';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Activate an existing donation
	*	@param int $donation_id
	*
	*/
	public function activate_donation($donation_id, $page)
	{
		if($this->donations_model->activate_donation($donation_id))
		{
			$this->session->set_userdata('success_message', 'donation activated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'could not activate donation');
		}
		redirect('all-donations/'.$page);
	}
    
	/*
	*
	*	Deactivate an existing donation
	*	@param int $donation_id
	*
	*/
	public function deactivate_donation($donation_id)
	{
		if($this->donations_model->deactivate_donation($donation_id))
		{
			$this->session->set_userdata('success_message', 'donation disabled successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'could not deactivate donation');
		}
		redirect('all-donations/'.$page);
	}
	
	public function search_donations()
	{
		$donation_status = $this->input->post('donation_status');
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
		
		$search_title = 'Showing reports for: ';
		
		if(!empty($donation_status))
		{
			$donation_status = ' AND donation.donation_status = '.$donation_status.' ';
			
			$this->db->where('donation_type_id', $donation_status);
			$query = $this->db->get('donation_type');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$search_title .= $row->donation_type_name.' ';
			}
		}
		
		if(!empty($date_from) && !empty($date_to))
		{
			$date = ' AND donation.donation_date BETWEEN \''.$date_from.' 00:00:00\' AND \''.$date_to.' 23:59:59\'';
			$search_title .= 'Donation date from '.date('jS M Y', strtotime($date_from)).' to '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else if(!empty($date_from))
		{
			$date = ' AND donation.donation_date LIKE \''.$date_from.'%\'';
			$search_title .= 'Donation date of '.date('jS M Y', strtotime($date_from)).' ';
		}
		
		else if(!empty($date_to))
		{
			$date = ' AND donation.donation_date LIKE \''.$date_to.'%\'';
			$search_title .= 'Donation date of '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else
		{
			$date = '';
		}
		
		$search = $donation_status.$date;
		$donations_search = $this->session->userdata('all_donations_search');
		
		if(!empty($donations_search))
		{
			$search .= $donations_search;
		}
		$this->session->set_userdata('all_donations_search', $search);
		$this->session->set_userdata('search_title', $search_title);
		
		redirect('all-donations');
	}
	
	public function export_donations()
	{
		$this->donations_model->export_donations();
	}
	
	public function close_search()
	{
		$this->session->unset_userdata('all_donations_search');
		$this->session->unset_userdata('search_title');
		
		redirect('all-donations');
	}
}
?>