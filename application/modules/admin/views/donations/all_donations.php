<?php	
		$result = '';
		
		//if donations exist display them
		if ($donations->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			'
				<a href="'.site_url().'export-donations" class="btn btn-success btn-lg pull-right">Export Donations</a>
				<table class="table table-hover table-bordered ">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Donation date</th>
					  <th>Donor</th>
					  <th>Amount</th>
					  <th>Method</th>
					  <th>Hill</th>
					  <th>Status</th>
					  <th colspan="5">Actions</th>
					</tr>
				  </thead>
				  <tbody>
			';
			foreach ($donations->result() as $row)
			{
				$donation_id = $row->donation_id;
				$donor = $row->first_name;
				$donation_date = date('jS M Y H:i a',strtotime($row->donation_date));
				$donation_amount = $row->donation_amount;
				$donation_option_name = $row->donation_option_name;
				$hill_name = $row->hill_name;
				if($donation_date == '1st Jan 1970 01:00 am')
				{
					$donation_date = '-';
				}
				//create deactivated status display
				if($row->donation_status == 0)
				{
					$status = '<span class="label label-important">Plegded</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'activate-donation/'.$donation_id.'/'.$page.'" onclick="return confirm(\'Do you want to activate this donation by '.$donor.'?\');">Activate</a>';
				}
				//create activated status display
				else if($row->donation_status == 1)
				{
					$status = '<span class="label label-success">Paid</span>';
					$button = '<a class="btn btn-warning" href="'.site_url().'deactivate-donation/'.$donation_id.'/'.$page.'" onclick="return confirm(\'Do you want to deactivate this donation by '.$donor.'?\');">Deactivate</a>';
				}
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$donation_date.'</td>
						<td>'.$donor.'</td>
						<td>$'.number_format($donation_amount, 2).'</td>
						<td>'.$donation_option_name.'</td>
						<td>'.$hill_name.'</td>
						<td>'.$status.'</td>
						<td>
							
							<!-- Button to trigger modal -->
							<a href="#donation'.$donation_id.'" class="btn btn-primary" data-toggle="modal">View</a>
							
							<!-- Modal -->
							<div id="donation'.$donation_id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Donation by '.$donor.' on '.$donation_date.'</h4>
										</div>
										
										<div class="modal-body">
											<div class="row">
												<div class="col-md-4">
													<h4 class="center-align">Donor Details</h4>												
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
															<th>City</th>
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
															<td>'.date('jS M Y H:i a',strtotime($row->last_login)).'</td>
														</tr>
													</table>
												</div>
												<div class="col-md-4">
													<h4 class="center-align">Donation Details</h4>
													<table class="table table-stripped table-condensed table-hover">
														<tr>
															<th>Donation date</th>
															<td>'.$donation_date.'</td>
														</tr>
														<tr>
															<th>Amount</th>
															<td>$'.number_format($donation_amount, 2).'</td>
														</tr>
														<tr>
															<th>Donation method</th>
															<td>'.$donation_option_name.'</td>
														</tr>
														<tr>
															<th>Donation status</th>
															<td>'.$status.'</td>
														</tr>
														<tr>
															<th>Comments</th>
															<td>'.$row->donation_details.'</td>
														</tr>
														<tr>
															<th>Last Modified</th>
															<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
														</tr>
													</table>
												</div>
												<div class="col-md-4">
													<h4 class="center-align">Hill Details</h4>
													<table class="table table-stripped table-condensed table-hover">
														<tr>
															<th>Hill name</th>
															<td>'.$row->hill_name.'</td>
														</tr>
														<tr>
															<th>Hill location</th>
															<td>'.$row->hill_location.'</td>
														</tr>
														<tr>
															<th>Hill image</th>
															<td><img src="'.$hills_location.$row->hill_thumb_name1.'" width="80"></td>
														</tr>
														<tr>
															<th>Donation status</th>
															<td>'.$status.'</td>
														</tr>
														<tr>
															<th>Comments</th>
															<td>'.$row->donation_details.'</td>
														</tr>
														<tr>
															<th>Last Modified</th>
															<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
														</tr>
													</table>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
											'.$button.'
										</div>
									</div>
								</div>
							</div>
						
						</td>
						<td>'.$button.'</td>
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
			$result .= "There are no donations";
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