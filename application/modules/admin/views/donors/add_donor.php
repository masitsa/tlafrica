          <div class="padd">
            <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
            ?>
            
            <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <!-- First Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">First Name</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="first_name" placeholder="First Name" value="<?php echo set_value('first_name');?>">
                </div>
            </div>
            <!-- Other Names -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Other Names</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="other_names" placeholder="Other Names" value="<?php echo set_value('other_names');?>">
                </div>
            </div>
            <!-- Email -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Email</label>
                <div class="col-lg-4">
                	<input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo set_value('email');?>">
                </div>
            </div>
            <!-- Password -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Password</label>
                <div class="col-lg-4">
                	<input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo set_value('password');?>">
                </div>                
            </div>
            <!-- Phone -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Phone</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo set_value('phone');?>">
                </div>
            </div>
            <!-- Address -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Address</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo set_value('address');?>">
                </div>
            </div>
            <!-- Postal Code -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Postal Code</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="post_code" placeholder="Postal Code" value="<?php echo set_value('post_code');?>">
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
							if($res->country_id == set_value('country_id'))
							{
								$return .= '<option value="'.$res->country_id.'" selected="selected">'.$res->country_name.'</option>';
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
                	<input type="text" class="form-control" name="city" placeholder="State" value="<?php echo set_value('city');?>">
                </div>
            </div>
            <!-- Activate checkbox -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Activate Donor?</label>
                <div class="col-lg-4">
                	<?php
                    	if(set_value('activated') == 1)
						{
					?>
                    <div class="radio">
                        <label>
                            <input id="optionsRadios1" type="radio" checked value="1" name="activated">
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input id="optionsRadios2" type="radio" value="0" name="activated">
                            No
                        </label>
                    </div>
                    <?php
						}
						
						else
						{
					?>
                    <div class="radio">
                        <label>
                            <input id="optionsRadios1" type="radio" value="1" name="activated">
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input id="optionsRadios2" type="radio" checked value="0" name="activated">
                            No
                        </label>
                    </div>
                    <?php
						}
					?>
                </div>
            </div>
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Add Donor
                </button>
            </div>
            <br />
            <?php echo form_close();?>
		</div>