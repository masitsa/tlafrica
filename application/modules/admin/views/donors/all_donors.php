<?php	
		$result = '<a href="'.site_url().'add-donor/'.$page.'" class="btn btn-success pull-right">Add Donor</a>';
		
		//if donors exist display them
		if ($donors->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			'
				<table class="table table-hover table-bordered ">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>First name</th>
					  <th>Last name</th>
					  <th>Date Created</th>
					  <th>Last Login</th>
					  <th>Status</th>
					  <th colspan="5">Actions</th>
					</tr>
				  </thead>
				  <tbody>
			';
			foreach ($donors->result() as $row)
			{
				$donor_id = $row->donor_id;
				$fname = $row->first_name;
				$last_login = date('jS M Y H:i a',strtotime($row->last_login));
				if($last_login == '1st Jan 1970 01:00 am')
				{
					$last_login = '-';
				}
				//create deactivated status display
				if($row->activated == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'activate-donor/'.$donor_id.'/'.$page.'" onclick="return confirm(\'Do you want to activate '.$fname.'?\');">Activate</a>';
				}
				//create activated status display
				else if($row->activated == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'deactivate-donor/'.$donor_id.'/'.$page.'" onclick="return confirm(\'Do you want to deactivate '.$fname.'?\');">Deactivate</a>';
				}
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$row->first_name.'</td>
						<td>'.$row->other_names.'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
						<td>'.$last_login.'</td>
						<td>'.$status.'</td>
						<td>
							
							<!-- Button to trigger modal -->
							<a href="#donor'.$donor_id.'" class="btn btn-primary" data-toggle="modal">View</a>
							
							<!-- Modal -->
							<div id="donor'.$donor_id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">'.$fname.' '.$row->other_names.'</h4>
										</div>
										
										<div class="modal-body">
											<table class="table table-stripped table-condensed table-hover">
												<tr>
													<th>First Name</th>
													<td>'.$row->first_name.'</td>
												</tr>
												<tr>
													<th>Other Names</th>
													<td>'.$row->other_names.'</td>
												</tr>
												<tr>
													<th>Email</th>
													<td>'.$row->email.'</td>
												</tr>
												<tr>
													<th>Phone</th>
													<td>'.$row->phone.'</td>
												</tr>
												<tr>
													<th>Address</th>
													<td>'.$row->address.'</td>
												</tr>
												<tr>
													<th>Postal Code</th>
													<td>'.$row->post_code.'</td>
												</tr>
												<tr>
													<th>State</th>
													<td>'.$row->city.'</td>
												</tr>
												<tr>
													<th>Date Created</th>
													<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
												</tr>
												<tr>
													<th>Last Modified</th>
													<td>'.date('jS M Y H:i a',strtotime($row->modified)).'</td>
												</tr>
												<tr>
													<th>Last Login</th>
													<td>'.$last_login.'</td>
												</tr>
											</table>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
											<a href="'.site_url().'edit-donor/'.$donor_id.'/'.$page.'" class="btn btn-sm btn-success">Edit</a>
											'.$button.'
											<a href="'.site_url().'reset-donor-password/'.$donor_id.'/'.$page.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you want to reset '.$fname.'\'s password?\');">Reset Password</a>
											<a href="'.site_url().'delete-donor/'.$donor_id.'/'.$page.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$fname.'?\');">Delete</a>
										</div>
									</div>
								</div>
							</div>
						
						</td>
						<td><a href="'.site_url().'edit-donor/'.$donor_id.'/'.$page.'" class="btn btn-sm btn-success">Edit</a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'reset-donor-password/'.$donor_id.'/'.$page.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you really want to reset the password of '.$fname.'?\');">Reset Password</a></td>
						<td><a href="'.site_url().'delete-donor/'.$donor_id.'/'.$page.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$fname.'?\');">Delete</a></td>
					</tr> 
				';
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no donors";
		}
		echo '<div class="center-align">';
		$error = $this->session->userdata('error_message');
		$success = $this->session->userdata('success_message');
		if(!empty($error))
		{
			echo '<div class="alert alert-danger">'.$error.'</div>';
			$this->session->unset_userdata('error_message');
		}
		
		if(!empty($success))
		{
			echo '<div class="alert alert-success">'.$success.'</div>';
			$this->session->unset_userdata('success_message');
		}
		echo '</div>';
		echo $result;
?>