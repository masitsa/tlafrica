<?php

class Donors_model extends CI_Model 
{
	/*
	*	Count all items from a table
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function count_items($table, $where, $limit = NULL)
	{
		if($limit != NULL)
		{
			$this->db->limit($limit);
		}
		$this->db->from($table);
		$this->db->where($where);
		return $this->db->count_all_results();
	}
	
	/*
	*	Retrieve all donors
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_donors($table, $where, $per_page, $page)
	{
		//retrieve all donors
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('donors.first_name, donors.other_names');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Retrieve all administrators
	*
	*/
	public function get_all_administrators()
	{
		$this->db->from('donors');
		$this->db->select('*');
		$this->db->where('donor_level_id = 1');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve all front end donors
	*
	*/
	public function get_all_front_end_donors()
	{
		$this->db->from('donors');
		$this->db->select('*');
		$this->db->where('donor_level_id = 2');
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_all_countries()
	{
		//retrieve all donors
		$query = $this->db->get('country');
		
		return $query;
	}
	
	/*
	*	Add a new donor to the database
	*
	*/
	public function add_donor()
	{
		$data = array(
				'first_name'=>ucwords(strtolower($this->input->post('first_name'))),
				'other_names'=>ucwords(strtolower($this->input->post('other_names'))),
				'email'=>$this->input->post('email'),
				'password'=>md5($this->input->post('password')),
				'phone'=>$this->input->post('phone'),
				'address'=>$this->input->post('address'),
				'post_code'=>$this->input->post('post_code'),
				'country_id'=>$this->input->post('country_id'),
				'city'=>$this->input->post('city'),
				'created'=>date('Y-m-d H:i:s'),
				'activated'=>$this->input->post('activated')
			);
			
		if($this->db->insert('donors', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit an existing donor
	*	@param int $donor_id
	*
	*/
	public function edit_donor($donor_id)
	{
		$data = array(
				'first_name'=>ucwords(strtolower($this->input->post('first_name'))),
				'other_names'=>ucwords(strtolower($this->input->post('other_names'))),
				'email'=>$this->input->post('email'),
				'password'=>md5($this->input->post('password')),
				'phone'=>$this->input->post('phone'),
				'address'=>$this->input->post('address'),
				'post_code'=>$this->input->post('post_code'),
				'country_id'=>$this->input->post('country_id'),
				'city'=>$this->input->post('city'),
				'activated'=>$this->input->post('activated')
			);
		
		//check if donor wants to update their password
		$pwd_update = $this->input->post('admin_donor');
		if(!empty($pwd_update))
		{
			if($this->input->post('old_password') == md5($this->input->post('current_password')))
			{
				$data['password'] = md5($this->input->post('new_password'));
			}
			
			else
			{
				$this->session->set_donordata('error_message', 'The current password entered does not match your password. Please try again');
			}
		}
		
		$this->db->where('donor_id', $donor_id);
		
		if($this->db->update('donors', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit an existing donor
	*	@param int $donor_id
	*
	*/
	public function edit_frontend_donor($donor_id)
	{
		$data = array(
				'first_name'=>ucwords(strtolower($this->input->post('first_name'))),
				'other_names'=>ucwords(strtolower($this->input->post('last_name'))),
				'phone'=>$this->input->post('phone')
			);
		
		//check if donor wants to update their password
		$pwd_update = $this->input->post('admin_donor');
		if(!empty($pwd_update))
		{
			if($this->input->post('old_password') == md5($this->input->post('current_password')))
			{
				$data['password'] = md5($this->input->post('new_password'));
			}
			
			else
			{
				$this->session->set_donordata('error_message', 'The current password entered does not match your password. Please try again');
			}
		}
		
		$this->db->where('donor_id', $donor_id);
		
		if($this->db->update('donors', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit an existing donor's password
	*	@param int $donor_id
	*
	*/
	public function edit_password($donor_id)
	{
		if($this->input->post('slug') == md5($this->input->post('current_password')))
		{
			if($this->input->post('new_password') == $this->input->post('confirm_password'))
			{
				$data['password'] = md5($this->input->post('new_password'));
		
				$this->db->where('donor_id', $donor_id);
				
				if($this->db->update('donors', $data))
				{
					$return['result'] = TRUE;
				}
				else{
					$return['result'] = FALSE;
					$return['message'] = 'Oops something went wrong and your password could not be updated. Please try again';
				}
			}
			else{
					$return['result'] = FALSE;
					$return['message'] = 'New Password and Confirm Password don\'t match';
			}
		}
		
		else
		{
			$return['result'] = FALSE;
			$return['message'] = 'You current password is not correct. Please try again';
		}
		
		return $return;
	}
	
	/*
	*	Retrieve a single donor
	*	@param int $donor_id
	*
	*/
	public function get_donor($donor_id)
	{
		//retrieve all donors
		$this->db->from('donors');
		$this->db->select('*');
		$this->db->where('donor_id = '.$donor_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve a single donor by their email
	*	@param int $email
	*
	*/
	public function get_donor_by_email($email)
	{
		//retrieve all donors
		$this->db->from('donors');
		$this->db->select('*');
		$this->db->where('email = \''.$email.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing donor
	*	@param int $donor_id
	*
	*/
	public function delete_donor($donor_id)
	{
		if($this->db->delete('donors', array('donor_id' => $donor_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated donor
	*	@param int $donor_id
	*
	*/
	public function activate_donor($donor_id)
	{
		$data = array(
				'activated' => 1
			);
		$this->db->where('donor_id', $donor_id);
		
		if($this->db->update('donors', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated donor
	*	@param int $donor_id
	*
	*/
	public function deactivate_donor($donor_id)
	{
		$data = array(
				'activated' => 0
			);
		$this->db->where('donor_id', $donor_id);
		
		if($this->db->update('donors', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Reset a donor's password
	*	@param string $email
	*
	*/
	public function reset_password($email)
	{
		//reset password
		$result = md5(date("Y-m-d H:i:s"));
		$pwd2 = substr($result, 0, 6);
		$pwd = md5($pwd2);
		
		$data = array(
				'password' => $pwd
			);
		$this->db->where('email', $email);
		
		if($this->db->update('donors', $data))
		{
			//email the password to the donor
			$donor_details = $this->donors_model->get_donor_by_email($email);
			
			$donor = $donor_details->row();
			$donor_name = $donor->first_name;
			
			//email data
			$receiver['email'] = $this->input->post('email');
			$sender['name'] = 'Fad Shoppe';
			$sender['email'] = 'info@fadshoppe.com';
			$message['subject'] = 'You requested a password change';
			$message['text'] = 'Hi '.$donor_name.'. Your new password is '.$pwd;
			
			//send the donor their new password
			if($this->email_model->send_mail($receiver, $sender, $message))
			{
				return TRUE;
			}
			
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}
?>