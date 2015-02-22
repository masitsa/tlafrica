<?php 
$v_data['view_type'] = 0;
//echo $this->load->view('search/search_hills', $v_data, TRUE); ?>
<?php

		$error = $this->session->userdata('error_message');
		$success = $this->session->userdata('success_message');
		$search_result ='';
		$search_result2  ='';
		if(!empty($error))
		{
			$search_result2 = '<div class="alert alert-danger">'.$error.'</div>';
			$this->session->unset_userdata('error_message');
		}
		
		if(!empty($success))
		{
			$search_result2 ='<div class="alert alert-success">'.$success.'</div>';
			$this->session->unset_userdata('success_message');
		}
				
		$search = $this->session->userdata('hills_search');
		
		if(!empty($search))
		{
			$search_result = '<a href="'.site_url().'close-hills-search" class="btn btn-danger">Close Search</a>';
		}


		$result = '<div class="padd">';	
		$result .= ''.$search_result2.'';
		$result .= '
					<div class="row" style="margin-bottom:8px;">
						<div class="pull-left">
						'.$search_result.'
						</div>
	            		<div class="pull-right">
							<a href="'.site_url().'add-hill" class="btn btn-success pull-right">Add hill</a>
						
						</div>
					</div>
				';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			'
			<div class="row">
				<table class="table table-hover table-bordered table-striped table-condensed">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Image</th>
					  <th>Hill Name</th>
					  <th>Hill Location</th>
					  <th>Date Created</th>
					  <th>Last Modified</th>
					  <th>Status</th>
					  <th colspan="3">Actions</th>
					</tr>
				  </thead>
				  <tbody>
			';
			
			//get all administrators
			$administrators = $this->users_model->get_all_administrators();
			if ($administrators->num_rows() > 0)
			{
				$admins = $administrators->result();
			}
			
			else
			{
				$admins = NULL;
			}
			
			foreach ($query->result() as $row)
			{
				$hill_id = $row->hill_id;
				$hill_name = $row->hill_name;
				$hill_location = $row->hill_location;
				$hill_status = $row->hill_status;
				$image = $row->hill_image_name1;
				$thumb = $row->hill_thumb_name1;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				
				//status
				if($hill_status == 1)
				{
					$status = 'Active';
				}
				else
				{
					$status = 'Disabled';
				}
				
				//create deactivated status display
				if($hill_status == 0)
				{
					$status = '<span class="label label-danger">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'activate-hill/'.$hill_id.'" onclick="return confirm(\'Do you want to activate '.$hill_name.'?\');">Activate</a>';
				}
				//create activated status display
				else if($hill_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'deactivate-hill/'.$hill_id.'" onclick="return confirm(\'Do you want to deactivate '.$hill_name.'?\');">Deactivate</a>';
				}
				
				//creators & editors
				if($admins != NULL)
				{
					foreach($admins as $adm)
					{
						$user_id = $adm->user_id;
						
						if($user_id == $created_by)
						{
							$created_by = $adm->first_name;
						}
						
						if($user_id == $modified_by)
						{
							$modified_by = $adm->first_name;
						}
					}
				}
				
				else
				{
				}
				
				$actions = '
					<td><a href="'.site_url().'edit-hill/'.$hill_id.'" class="btn btn-sm btn-success">Edit</a></td>
					<td>'.$button.'</td>
					<td><a href="'.site_url().'delete-hill/'.$hill_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$hill_name.'?\');">Delete</a></td>
					';
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td><img src="'.$hills_location.$thumb.'" width="80"></td>
						<td>'.$hill_name.'</td>
						<td>'.$hill_location.'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
						<td>'.$status.'</td>
						'.$actions.'
					</tr> 
				';
			}
			
			$result .= 
			'
						  </tbody>
						</table>
					</div>
			';
		}
		
		else
		{
			$result .= "There are no hills";
		}
		$result .= '</div>';
		
		echo $result;
?>