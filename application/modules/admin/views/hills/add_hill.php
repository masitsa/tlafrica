<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
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
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <!-- hill Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Hill Name</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="hill_name" placeholder="Hill Name" value="<?php echo set_value('hill_name');?>" required>
                </div>
            </div>
            <!-- hill location -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Hill Location</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="hill_location" placeholder="Hill Location" value="<?php echo set_value('hill_location');?>" required>
                </div>
            </div>
            <!-- hill description -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Hill Description</label>
                <div class="col-lg-4">
                	<textarea class="form-control" name="hill_description" placeholder="Hill Description"><?php echo set_value('hill_description');?></textarea>
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
                                        <img src="<?php echo $hill_location[$r];?>">
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
                            <input id="optionsRadios1" type="radio" checked value="1" name="hill_status">
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input id="optionsRadios2" type="radio" value="0" name="hill_status">
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Add hill
                </button>
            </div>
            <br />
            <?php echo form_close();?>
		</div>