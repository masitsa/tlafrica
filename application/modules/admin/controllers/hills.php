<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Hills extends admin
{
	var $hills_path;
	var $hills_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/users_model');
		$this->load->model('hills_model');
		$this->load->model('admin/file_model');
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->hills_path = realpath(APPPATH . '../assets/images/hills');
		$this->hills_location = base_url().'assets/images/hills/';
	}
    
	/*
	*
	*	Default action is to show all the hills
	*
	*/
	public function index() 
	{
		$where = 'hill_id > 0';
		$table = 'hill';
		$hill_search = $this->session->userdata('hill_search');
		
		if(!empty($hill_search))
		{
			$where .= $hill_search;
		}
		$segment = 3;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'all-hills';
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
		$query = $this->hills_model->get_all_hills($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['hills_location'] = $this->hills_location;
			$data['content'] = $this->load->view('hills/all_hills', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'add-hill" class="btn btn-success pull-right">Add hill</a>There are no hills';
		}
		$data['title'] = 'All Hills';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Add a new hill
	*
	*/
	public function add_hill() 
	{
		//set the initial images
		$location = array();
		$hill_error = array();
		
		for($r = 1; $r < 4; $r++)
		{
			$location[$r] = 'http://placehold.it/600x600';
			$this->session->unset_userdata('hill_error_message'.$r);
		}
		
		//upload image if it has been selected
		$response = $this->hills_model->upload_hill_image($this->hills_path);
		
		//check if response resulted in an error
		for($r = 1; $r < 4; $r++)
		{
			$error = $this->session->userdata('hill_error_message'.$r);
			if(!empty($error))
			{
				$location[$r] = $this->hill_location.$this->session->userdata('hill_file_name'.$r);
			}
			
			//case of upload error
			else
			{
				$hill_error[$r] = $error;
			}
		}
		$v_data['hill_location'] = $location;
		$v_data['hill_error'] = $hill_error;
		
		$this->form_validation->set_rules('hill_name', 'Hill Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('hill_location', 'Hill Location', 'trim|required|xss_clean');
		$this->form_validation->set_rules('hill_status', 'Hill Status', 'required|xss_clean');
		$this->form_validation->set_rules('hill_description', 'Hill Description', 'xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($hill_error[1]) && empty($hill_error[2]) && empty($hill_error[3]))
			{
				if($this->hills_model->add_hill())
				{
					$this->session->set_userdata('success_message', 'Hill added successfully');
					/*var_dump($this->session->userdata('hill_file_name1')).'<br/>';
					var_dump($this->session->userdata('hill_file_name2')).'<br/>';
					var_dump($this->session->userdata('hill_file_name3')).'<br/>';
					die();*/
					//unset sessions
					for($r = 1; $r < 4; $r++)
					{
						$this->session->unset_userdata('hill_file_name'.$r);
						$this->session->unset_userdata('hill_thumb_name'.$r);
						$this->session->unset_userdata('hill_error_message'.$r);
					}
					redirect('all-hills');
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Could not add hill. Please try again');
				}
			}
		}
		
		$data['content'] = $this->load->view("hills/add_hill", $v_data, TRUE);
		$data['title'] = 'Add hill';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Edit an existing hill
	*	@param int $hill_id
	*
	*/
	public function edit_hill($hill_id) 
	{
		//set the initial images
		$location = array();
		$hill_error = array();
		
		for($r = 1; $r < 4; $r++)
		{
			$location[$r] = 'http://placehold.it/600x600';
			$this->session->unset_userdata('hill_error_message'.$r);
		}
		
		//upload image if it has been selected
		$response = $this->hills_model->upload_hill_image($this->hills_path);
		
		//check if response resulted in an error
		for($r = 1; $r < 4; $r++)
		{
			$error = $this->session->userdata('hill_error_message'.$r);
			if(!empty($error))
			{
				$location[$r] = $this->hills_location.$this->session->userdata('hill_file_name'.$r);
			}
			
			//case of upload error
			else
			{
				$hill_error[$r] = $error;
			}
		}
		
		//get hill details
		$query = $this->hills_model->get_hill($hill_id);
		$v_data['hill'] = $query->result();
		
		$this->form_validation->set_rules('hill_name', 'Hill Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('hill_location', 'Hill Location', 'trim|required|xss_clean');
		$this->form_validation->set_rules('hill_status', 'Hill Status', 'required|xss_clean');
		$this->form_validation->set_rules('hill_description', 'Hill Description', 'xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($hill_error[1]) && empty($hill_error[2]) && empty($hill_error[3]))
			{
				//update images
				for($r = 1; $r < 4; $r++)
				{
					$check_location = $this->session->userdata('hill_file_name'.$r);
					if(empty($check_location))
					{
						if($r == 1)
						{
							$image = $v_data['hill'][0]->hill_image_name1;
							$thumb = $v_data['hill'][0]->hill_thumb_name1;
						}
						else if($r == 2)
						{
							$image = $v_data['hill'][0]->hill_image_name2;
							$thumb = $v_data['hill'][0]->hill_thumb_name2;
						}
						else
						{
							$image = $v_data['hill'][0]->hill_image_name3;
							$thumb = $v_data['hill'][0]->hill_thumb_name3;
						}
						
						if(!empty($image))
						{
							$update_image[$r] = $image;
							$update_thumb[$r] = $thumb;
						}
					}
					else
					{
						$update_image[$r] = $check_location;
						$update_thumb[$r] = 'thumbnail_'.$check_location;
					}
				}
				if($this->hills_model->update_hill($update_image, $update_thumb, $hill_id))
				{
					$this->session->set_userdata('success_message', 'Hill updated successfully');
					
					//unset sessions
					for($r = 1; $r < 4; $r++)
					{
						$this->session->unset_userdata('hill_file_name'.$r);
						$this->session->unset_userdata('hill_thumb_name'.$r);
						$this->session->unset_userdata('hill_error_message'.$r);
					}
					redirect('all-hills');
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Could not add hill. Please try again');
				}
			}
		}
		
		if ($query->num_rows() > 0)
		{	
			//reset previously uploaded images
			for($r = 1; $r < 4; $r++)
			{
				$check_location = $this->session->userdata('hill_file_name'.$r);
				if(empty($check_location))
				{
					if($r == 1)
					{
						$loc = $v_data['hill'][0]->hill_image_name1;
					}
					else if($r == 2)
					{
						$loc = $v_data['hill'][0]->hill_image_name2;
					}
					else
					{
						$loc = $v_data['hill'][0]->hill_image_name3;
					}
					
					if(!empty($loc))
					{
						$location[$r] = $this->hills_location.$loc;
					}
					//var_dump($loc);die();
				}
			}
			
			$v_data['hills_location'] = $location;
			$v_data['hill_error'] = $hill_error;
			$data['content'] = $this->load->view('hills/edit_hill', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Hill does not exist';
		}
		$data['title'] = 'Edit hill';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing hill
	*	@param int $hill_id
	*
	*/
	public function delete_hill($hill_id)
	{
		//delete hill image
		$query = $this->hills_model->get_hill($hill_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->hill_image_name;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->hills_path."/images/".$image);
			//delete thumbnail
			$this->file_model->delete_file($this->hills_path."/thumbs/".$image);
		}
		$this->hills_model->delete_hill($hill_id);
		$this->session->set_userdata('success_message', 'hill has been deleted');
		redirect('all-hills');
	}
    
	/*
	*
	*	Activate an existing hill
	*	@param int $hill_id
	*
	*/
	public function activate_hill($hill_id)
	{
		$this->hills_model->activate_hill($hill_id);
		$this->session->set_userdata('success_message', 'hill activated successfully');
		redirect('all-hills');
	}
    
	/*
	*
	*	Deactivate an existing hill
	*	@param int $hill_id
	*
	*/
	public function deactivate_hill($hill_id)
	{
		$this->hills_model->deactivate_hill($hill_id);
		$this->session->set_userdata('success_message', 'hill disabled successfully');
		redirect('all-hills');
	}
	public function search_hills()
	{

		$hill_name = $this->input->post('hill_name');


		if(!empty($hill_name))
		{
			$hill_name = ' AND hill.hill_name LIKE \'%'.mysql_real_escape_string($hill_name).'%\' ';
		}
		
		
		$search = $hill_name;
		$this->session->set_userdata('hills_search', $search);
		
		$this->index();
		
	}
	public function close_hills_search()
	{
		$this->session->unset_userdata('hills_search');
		redirect('all-hills');
	}
}
?>