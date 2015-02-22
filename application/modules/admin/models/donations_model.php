<?php

class donations_model extends CI_Model 
{	
	/*
	*	Retrieve all donations
	*
	*/
	public function all_donations()
	{
		$this->db->where('donation_status = 1');
		$query = $this->db->get('donation');
		
		return $query;
	}
	/*
	*	Retrieve all donations by category
	*	@param int $category_id
	*
	*/
	public function all_donations_by_category($category_id)
	{
		$this->db->where('donation_status = 1 AND (category_id = '.$category_id.' OR category_id = 0)');
		$query = $this->db->get('donation');
		
		return $query;
	}
	
	/*
	*	Retrieve all donations
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_donations($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('donation.*, donation_option.donation_option_name, hill.*, donors.*');
		$this->db->where($where);
		$this->db->order_by('donation_date, donation.donor_id, donation_amount');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new donation
	*
	*/
	public function add_donation()
	{
		$data = array(
				'donation_name'=>ucwords(strtolower($this->input->post('donation_name'))),
				'category_id'=>$this->input->post('category_id'),
				'donation_status'=>$this->input->post('donation_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id')
			);
			
		if($this->db->insert('donation', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing donation
	*	@param int $donation_id
	*
	*/
	public function update_donation($donation_id)
	{
		$data = array(
				'donation_name'=>ucwords(strtolower($this->input->post('donation_name'))),
				'category_id'=>$this->input->post('category_id'),
				'donation_status'=>$this->input->post('donation_status'),
				'modified_by'=>$this->session->userdata('user_id')
			);
			
		$this->db->where('donation_id', $donation_id);
		if($this->db->update('donation', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single donation's details
	*	@param int $donation_id
	*
	*/
	public function get_donation($donation_id)
	{
		$this->db->from('donation');
		$this->db->select('*');
		$this->db->where('donation_id = '.$donation_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing donation
	*	@param int $donation_id
	*
	*/
	public function delete_donation($donation_id)
	{
		if($this->db->delete('donation', array('donation_id' => $donation_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated donation
	*	@param int $donation_id
	*
	*/
	public function activate_donation($donation_id)
	{
		$data = array(
				'donation_status' => 1
			);
		$this->db->where('donation_id', $donation_id);
		
		if($this->db->update('donation', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated donation
	*	@param int $donation_id
	*
	*/
	public function deactivate_donation($donation_id)
	{
		$data = array(
				'donation_status' => 0
			);
		$this->db->where('donation_id', $donation_id);
		
		if($this->db->update('donation', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_donation_types()
	{
		$this->db->order_by('donation_type_name');
		return $this->db->get('donation_type');
	}
	
	/*
	*	Export Donations
	*
	*/
	function export_donations()
	{
		$this->load->library('excel');
		
		//check if search is active
		$search = $this->session->userdata('all_donations_search');
		$where = 'donation.hill_id = hill.hill_id AND donation.donor_id = donors.donor_id AND donation_option.donation_option_id = donation.donation_option_id AND donation.donation_status = donation_type.donation_type_id';
		
		if(!empty($search))
		{
			$where .= $search;
		}
		$table = 'donation, hill, donors, donation_option, donation_type';
		
		$this->db->select('donation.*, donation_option.donation_option_name, hill.*, donors.*, donation_type.donation_type_name');
		$this->db->where($where);
		$this->db->order_by('donation_date, donation.donor_id, donation_amount, donation_type_name');
		$donations_query = $this->db->get($table);
		
		$title = 'Donations Export '.date('jS M Y H:i a',strtotime(date('Y-m-d H:i:s')));
		
		if($donations_query->num_rows() > 0)
		{
			$count = 0;
			/*
				-----------------------------------------------------------------------------------------
				Document Header
				-----------------------------------------------------------------------------------------
			*/
			$row_count = 0;
			$total_donated = 0;
			$report[$row_count][0] = '#';
			$report[$row_count][1] = 'Donation date';
			$report[$row_count][2] = 'Donor';
			$report[$row_count][3] = 'Amount ($)';
			$report[$row_count][4] = 'Method';
			$report[$row_count][5] = 'Hill';
			$report[$row_count][6] = 'Status';
			$row_count = 1;
			
			//display all patient data in the leftmost columns
			foreach($donations_query->result() as $row)
			{
				$count++;
				
				$donor = $row->first_name.' '.$row->other_names;
				$donation_date = date('jS M Y H:i a',strtotime($row->donation_date));
				$donation_amount = $row->donation_amount;
				$donation_option_name = $row->donation_option_name;
				$hill_name = $row->hill_name;
				$status = $row->donation_type_name;
				if($donation_date == '1st Jan 1970 01:00 am')
				{
					$donation_date = '-';
				}
				$total_donated += $donation_amount;
				
				//display the patient data
				$report[$row_count][0] = $count;
				$report[$row_count][1] = $donation_date;
				$report[$row_count][2] = $donor;
				$report[$row_count][3] = number_format($donation_amount, 2);
				$report[$row_count][4] = $donation_option_name;
				$report[$row_count][5] = $hill_name;
				$report[$row_count][6] = $status;
				$row_count++;
			}
			$report[$row_count][0] = '';
			$report[$row_count][1] = '';
			$report[$row_count][2] = '';
			$report[$row_count][3] = number_format($total_donated, 2);
			$report[$row_count][4] = '';
			$report[$row_count][5] = '';
			$report[$row_count][6] = '';
		}
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
}
?>