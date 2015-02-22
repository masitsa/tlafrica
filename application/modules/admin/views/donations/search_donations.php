<!-- Widget -->
<div class="widget boxed">
    <!-- Widget head -->
    <div class="widget-head">
        <h4 class="pull-left"><i class="icon-reorder"></i>Search donations</h4>
        <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
        </div>
    
    	<div class="clearfix"></div>
    
    </div>             
    
    <!-- Widget content -->
    <div class="widget-content">
    	<div class="padd">
			<?php
			$search_title = $this->session->userdata('search_title');
			
			if(!empty($search_title))
			{
				echo '<h4>'.$search_title.'</h4>';
			}
            echo form_open("admin/donations/search_donations", array("class" => "form-horizontal"));
            ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Donation status: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="donation_status">
                            	<option value="">---Select Donation Status---</option>
                                <?php
                                    if($donation_types->num_rows() > 0)
									{
                                        foreach($donation_types->result() as $row):
                                            $donation_type_name = $row->donation_type_name;
                                            $donation_type_id = $row->donation_type_id;
                                            ?><option value="<?php echo $donation_type_id; ?>" ><?php echo $donation_type_name ?></option><?php	
                                        endforeach;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Date From: </label>
                        
                        <div class="col-lg-8">
                            <div id="datetimepicker1" class="input-append">
                                <input data-format="yyyy-MM-dd" class="form-control" type="text" name="date_from" placeholder="Visit Date From">
                                <span class="add-on" style="cursor:pointer;">
                                    &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                    </i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Date To: </label>
                        
                        <div class="col-lg-8">
                            <div id="datetimepicker_to" class="input-append">
                                <input data-format="yyyy-MM-dd" class="form-control" type="text" name="date_to" placeholder="Date To">
                                <span class="add-on" style="cursor:pointer;">
                                    &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                    </i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="center-align">
            	<?php
				$search = $this->session->userdata('all_donations_search');
				
				if(!empty($search))
				{
					echo '<a href="'.site_url().'close-donations-search" class="btn btn-warning btn-lg">Close Search</a>';
				}
				?>
            	<button type="submit" class="btn btn-info btn-lg">Search</button>
            </div>
            <?php
            echo form_close();
            ?>
    	</div>
    </div>
</div>