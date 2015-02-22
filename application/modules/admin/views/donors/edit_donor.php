          <div class="padd">
            <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
			$first_name = $donors[0]->first_name;
			$other_names = $donors[0]->other_names;
			$email = $donors[0]->email;
			$password = $donors[0]->password;
			$phone = $donors[0]->phone;
			$address = $donors[0]->address;
			$post_code = $donors[0]->post_code;
			$city = $donors[0]->city;
			$activated = $donors[0]->activated;
			$country_id = $donors[0]->country_id;
			$donor_id = $donors[0]->donor_id;
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
				$first_name = set_value('first_name');
				$other_names = set_value('other_names');
				$email = set_value('email');
				$password = set_value('password');
				$phone = set_value('phone');
				$address = set_value('address');
				$post_code = set_value('post_code');
				$city = set_value('city');
				$activated = set_value('activated');
				$country_id = set_value('country_id');
            }
            ?>
            
            <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <!-- First Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">First Name</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="first_name" placeholder="First Name" value="<?php echo $first_name;?>">
                </div>
            </div>
            <!-- Other Names -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Other Names</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="other_names" placeholder="Other Names" value="<?php echo $other_names;?>">
                </div>
            </div>
            <!-- Email -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Email</label>
                <div class="col-lg-4">
                	<input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $email;?>">
                </div>
            </div>
            <?php if(isset($admin_donor)){ ?>
            <!-- Password -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Current Password</label>
                <div class="col-lg-4">
                	<input type="hidden" class="form-control" name="admin_donor" value="<?php echo $admin_donor;?>">
                	<input type="hidden" class="form-control" name="old_password" value="<?php echo $password;?>">
                	<input type="password" class="form-control" name="current_password" placeholder="Password">
                </div>                
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">New Password</label>
                <div class="col-lg-4">
                	<input type="password" class="form-control" name="new_password" placeholder="Password">
                </div>                
            </div>
            <?php } ?>
            <!-- Phone -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Phone</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo $phone;?>">
                </div>
            </div>
            <!-- Address -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Address</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo $address;?>">
                </div>
            </div>
            <!-- Postal Code -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Postal Code</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="post_code" placeholder="Postal Code" value="<?php echo $post_code;?>">
                </div>
            </div>
            <!-- Country -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Country</label>
                <div class="col-lg-4">
                	<?php 
		
					$return = '<select name="country_id" class="form-control">';
					
					//if donors exist display them
					if ($countries->num_rows() > 0)
					{
						$result = $countries->result();
						
						foreach($result as $res)
						{
							if($country_id == $res->country_id)
							{
								$return .= '<option value="'.$res->country_id.'" selected>'.$res->country_name.'</option>';
							}
							
							else
							{
								$return .= '<option value="'.$res->country_id.'">'.$res->country_name.'</option>';
							}
						}
					}
					
					else
					{
						$return .= "<option>There are no countries</option>";
					}
					
					$return .= '</select>';
					
					echo $return;
					?>
                </div>
            </div>
            <!-- City -->
            <div class="form-group">
                <label class="col-lg-4 control-label">State</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="city" placeholder="State" value="<?php echo $city;?>">
                </div>
            </div>
            <!-- Activate checkbox -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Activate Donor?</label>
                <div class="col-lg-4">
                    <div class="radio">
                        <label>
                        	<?php
                            if($activated == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="activated">';}
							else{echo '<input id="optionsRadios1" type="radio" value="1" name="activated">';}
							?>
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                        	<?php
                            if($activated == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="activated">';}
							else{echo '<input id="optionsRadios1" type="radio" value="0" name="activated">';}
							?>
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Edit Donor
                </button>
            </div>
            <br />
            <?php echo form_close();?>
		</div>