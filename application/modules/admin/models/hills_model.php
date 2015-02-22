<?php

class Hills_model extends CI_Model 
{	
	public function upload_hill_image($hill_path, $edit = NULL)
	{
		//upload product's gallery images
		$resize['width'] = 600;
		$resize['height'] = 600;
		
		//loop 3 times to get the image data from the 3 uploaded images
		for($r = 0; $r < 3; $r++)
		{
			$count = $r+1;
			if(!empty($_FILES['hill_image'.$count]['tmp_name']))
			{
				$image = $this->session->userdata('hill_file_name'.$count);
				
				if((!empty($image)) || ($edit != NULL))
				{
					if($edit != NULL)
					{
						$image = $edit;
					}
					
					//delete any other uploaded image
					if($this->file_model->delete_file($hill_path."\\".$image))
					{
						//delete any other uploaded thumbnail
						$this->file_model->delete_file($hill_path."\\thumbnail_".$image);
					}
					
					else
					{
						$this->file_model->delete_file($hill_path."/".$image);
						$this->file_model->delete_file($hill_path."/thumbnail_".$image);
					}
				}
				//Upload image
				$response = $this->file_model->upload_banner($hill_path, 'hill_image'.$count, $resize);
				if($response['check'])
				{
					$file_name[$r] = $response['file_name'];
					$thumb_name[$r] = $response['thumb_name'];
					
					//crop file to 600 by 600
					/*$response_crop = $this->file_model->crop_file($hill_path."/".$file_name[$r], $resize['width'], $resize['height']);
					
					if(!$response_crop)
					{
						$this->session->set_userdata('hill_error_message'.$count, $response_crop);
					
						return FALSE;
					}
					
					else
					{
						//Set sessions for the image details
						$this->session->set_userdata('hill_file_name'.$count, $file_name[$r]);
						$this->session->set_userdata('hill_thumb_name'.$count, $thumb_name[$r]);
					}*/
					
						//Set sessions for the image details
						$this->session->set_userdata('hill_file_name'.$count, $file_name[$r]);
						$this->session->set_userdata('hill_thumb_name'.$count, $thumb_name[$r]);
				}
			
				else
				{
					$this->session->set_userdata('hill_error_message'.$count, $response['error']);
				}
			}
			
			else
			{
				$this->session->set_userdata('hill_error_message'.$count, '');
			}
		}
		return TRUE;
	}
	/*
	*	Retrieve all active hills
	*
	*/
	public function all_active_hills()
	{
		$this->db->where('hill_status = 1 AND hill_id > 0');
		$this->db->order_by('hill_name');
		$query = $this->db->get('hill');
		
		return $query;
	}
	
	/*
	*	Retrieve latest hill
	*
	*/
	public function latest_hill()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('hill');
		
		return $query;
	}
	
	/*
	*	Retrieve all hills
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_hills($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('hill_name');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new hill
	*	@param string $image_name
	*
	*/
	public function add_hill()
	{
		$data = array(
				'hill_name'=>$this->input->post('hill_name'),
				'hill_location'=>$this->input->post('hill_location'),
				'hill_status'=>$this->input->post('hill_status'),
				'hill_description'=>$this->input->post('hill_description'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id'),
				'hill_image_name1'=>$this->session->userdata('hill_file_name1'),
				'hill_image_name2'=>$this->session->userdata('hill_file_name2'),
				'hill_image_name3'=>$this->session->userdata('hill_file_name3'),
				'hill_thumb_name1'=>$this->session->userdata('hill_thumb_name1'),
				'hill_thumb_name2'=>$this->session->userdata('hill_thumb_name2'),
				'hill_thumb_name3'=>$this->session->userdata('hill_thumb_name3')
			);
			
		if($this->db->insert('hill', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing hill
	*	@param string $image_name
	*	@param int $hill_id
	*
	*/
	public function update_hill($image_name, $thumb_name, $hill_id)
	{
		$data = array(
				'hill_name'=>$this->input->post('hill_name'),
				'hill_location'=>$this->input->post('hill_location'),
				'hill_status'=>$this->input->post('hill_status'),
				'modified_by'=>$this->session->userdata('user_id'),
				'hill_image_name1'=>$image_name[1],
				'hill_image_name2'=>$image_name[2],
				'hill_image_name3'=>$image_name[3],
				'hill_thumb_name1'=>$thumb_name[1],
				'hill_thumb_name2'=>$thumb_name[2],
				'hill_thumb_name3'=>$thumb_name[3]
			);
			
		$this->db->where('hill_id', $hill_id);
		if($this->db->update('hill', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single hill's details
	*	@param int $hill_id
	*
	*/
	public function get_hill($hill_id)
	{
		//retrieve all users
		$this->db->from('hill');
		$this->db->select('*');
		$this->db->where('hill_id = '.$hill_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing hill
	*	@param int $hill_id
	*
	*/
	public function delete_hill($hill_id)
	{
		if($this->db->delete('hill', array('hill_id' => $hill_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated hill
	*	@param int $hill_id
	*
	*/
	public function activate_hill($hill_id)
	{
		$data = array(
				'hill_status' => 1
			);
		$this->db->where('hill_id', $hill_id);
		
		if($this->db->update('hill', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated hill
	*	@param int $hill_id
	*
	*/
	public function deactivate_hill($hill_id)
	{
		$data = array(
				'hill_status' => 0
			);
		$this->db->where('hill_id', $hill_id);
		
		if($this->db->update('hill', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>