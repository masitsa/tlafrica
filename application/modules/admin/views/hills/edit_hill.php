<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
          <div class="padd">
            <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
			
			//the hill details
			$hill_id = $hill[0]->hill_id;
			$hill_name = $hill[0]->hill_name;
			$hill_location = $hill[0]->hill_location;
			$hill_description = $hill[0]->hill_description;
			$hill_status = $hill[0]->hill_status;
			$image1 = $hill[0]->hill_image_name1;
			$image2 = $hill[0]->hill_image_name2;
			$image3 = $hill[0]->hill_image_name3;
			$thumb1 = $hill[0]->hill_thumb_name1;
			$thumb2 = $hill[0]->hill_thumb_name2;
			$thumb3 = $hill[0]->hill_thumb_name3;
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
				$hill_name = set_value('hill_name');
				$hill_status = set_value('hill_status');
				$hill_description = set_value('hill_description');
				$hill_location = set_value('hill_location');
				
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
			
            ?>
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <!-- hill Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Hill Name</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="hill_name" placeholder="Hill Name" value="<?php echo $hill_name;?>" required>
                </div>
            </div>
            <!-- hill location -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Hill Location</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="hill_location" placeholder="Hill Location" value="<?php echo $hill_location;?>" required>
                </div>
            </div>
            <!-- hill description -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Hill Description</label>
                <div class="col-lg-4">
                	<textarea class="form-control" name="hill_description" placeholder="Hill Description"><?php echo $hill_description;?></textarea>
                </div>
            </div>
            <div class="row">
            <?php
            for($r = 1; $r < 4; $r++)
			{
				//var_dump($r);
			?>
            <div class="col-md-4">
            	<?php
				if(!empty($hill_error[$r]))
				{
                	echo '<div class="alert alert-danger center-align">'.$hill_error[$r].'</div>';
				}
				?>
                <!-- Image -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">Hill Image <?php echo $r?></label>
                    <div class="col-lg-4">
                        
                        <div class="row">
                        
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:200px; height:200px;">
                                        <img src="<?php echo $hills_location[$r];?>">
                                    </div>
                                    <div>
                                        <span class="btn btn-file btn_pink"><span class="btn btn-primary fileinput-new">Select Image</span><span class="btn btn-default fileinput-exists">Change</span><input type="file" name="hill_image<?php echo $r?>"></span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <?php } ?>
            </div>
            <!-- Activate checkbox -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Activate hill?</label>
                <div class="col-lg-4">
                    <div class="radio">
                        <label>
                        	<?php
                            if($hill_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="hill_status">';}
							else{echo '<input id="optionsRadios1" type="radio" value="1" name="hill_status">';}
							?>
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                        	<?php
                            if($hill_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="hill_status">';}
							else{echo '<input id="optionsRadios1" type="radio" value="0" name="hill_status">';}
							?>
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Update hill
                </button>
            </div>
            <br />
            <?php echo form_close();?>
		</div>