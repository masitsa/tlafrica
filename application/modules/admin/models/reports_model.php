<?php

class Reports_model extends CI_Model 
{
	public function get_queue_total($date = NULL, $where = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		if($where == NULL)
		{
			$where = 'close_card = 0 AND visit_date = \''.$date.'\'';
		}
		
		else
		{
			$where .= ' AND close_card = 0 AND visit_date = \''.$date.'\' ';
		}
		
		$this->db->select('COUNT(visit_id) AS queue_total');
		$this->db->where($where);
		$query = $this->db->get('visit');
		
		$result = $query->row();
		
		return $result->queue_total;
	}
	
	public function get_daily_balance($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		//select the user by email from the database
		$this->db->select('SUM(visit_charge_units*visit_charge_amount) AS total_amount');
		$this->db->where('visit_charge_timestamp LIKE \''.$date.'%\'');
		$this->db->from('visit_charge');
		$query = $this->db->get();
		
		$result = $query->row();
		
		return $result->total_amount;
	}
	
	public function get_patients_total($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$this->db->select('COUNT(visit_id) AS patients_total');
		$this->db->where('visit_date = \''.$date.'\'');
		$query = $this->db->get('visit');
		
		$result = $query->row();
		
		return $result->patients_total;
	}
	
	public function get_all_payment_methods()
	{
		$this->db->select('*');
		$query = $this->db->get('payment_method');
		
		return $query;
	}
	
	public function get_payment_method_total($payment_method_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$this->db->select('SUM(amount_paid) AS total_paid');
		$this->db->where('payments.visit_id = visit.visit_id AND payment_method_id = '.$payment_method_id.' AND visit_date = \''.$date.'\'');
		$query = $this->db->get('payments, visit');
		
		$result = $query->row();
		
		return $result->total_paid;
	}
	
	public function get_all_donation_types()
	{
		$this->db->select('*');
		$this->db->order_by('donation_type_name');
		$query = $this->db->get('donation_type');
		
		return $query;
	}
	
	public function get_donations_total($donation_type_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'donation_date LIKE \''.$date.'%\' AND donation_status = '.$donation_type_id;
		
		$this->db->select('SUM(donation_amount) AS total');
		$this->db->where($where);
		$query = $this->db->get('donation');
		
		$result = $query->row();
		
		return $result->total;
	}
	
	public function get_hills_total($hill_id)
	{
		$where = 'donation.donation_status = 1 AND donation.hill_id = '.$hill_id;
		
		$this->db->select('SUM(donation_amount) AS total');
		$this->db->where($where);
		$query = $this->db->get('donation');
		
		$result = $query->row();
		$total = $result->total;;
		
		if($total == NULL)
		{
			$total = 0;
		}
		
		return $total;
	}
	
	public function get_all_appointments($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'visit.visit_type = visit_type.visit_type_id AND visit.appointment_id = 1 AND visit.visit_date >= \''.$date.'\'';
		
		$this->db->select('visit_date, time_start, time_end, visit_type_name');
		$this->db->where($where);
		$query = $this->db->get('visit, visit_type');
		
		return $query;
	}
	
	public function get_all_sessions($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'personnel.personnel_id = session.personnel_id AND session.session_name_id = session_name.session_name_id AND session_time LIKE \''.$date.'%\'';
		
		$this->db->select('session_name_name, session_time, personnel_fname, personnel_onames');
		$this->db->where($where);
		$this->db->order_by('session_time', 'DESC');
		$query = $this->db->get('session, session_name, personnel');
		
		return $query;
	}
	
	public function get_usage_total()
	{
		$this->db->select('SUM(clicks) AS total');
		$query = $this->db->get('product');	
		
		$result = $query->row();
		
		return $result->total;
	}
}