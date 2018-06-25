<div class="clearfix js-responses js-loadadmin-chart-events-ctp">
<?php
        $arrow = "down-arrow";
 		if($is_ajax_load){
 		$arrow = "up-arrow";
	   }
 ?>
	<div class="main-tl">
		<div class="main-tr">
			<div class="main-tm"> </div>
		</div>
	</div>
	<div class="main-left-shadow">
		<div class="main-right-shadow">
			<div class="main-inner clearfix">
            <div class="admin-side1-tc admin-side1-tc1 clearfix">
					<h2 class="chart-dashboard-title ribbon-title clearfix">
                    <?php echo __l('Events');?>
                		<span class="js-chart-showhide <?php echo $arrow; ?> {'chart_block':'admin-dashboard-events', 'dataloading':'div.js-loadadmin-chart-events-ctp',  'dataurl':'admin/charts/chart_events/is_ajax_load:1'}"><?php echo $arrow;?></span>
                </h2>
	<?php  if($is_ajax_load){ ?>
				<div class="admin-center-block clearfix dashboard-center-block" id="admin-dashboard-events">
					<div class="clearfix">
						<?php echo $this->Form->create('Chart' , array('class' => 'js-chart-form language-form', 'action' => 'chart_events')); ?>
						<?php
						echo $this->Form->input('is_ajax_load', array('type' => 'hidden', 'value' => 1));
                        echo $this->Form->input('select_range_id', array('class' => 'js-chart-autosubmit', 'label' => __l('Select Range'))); ?>
						<div class="hide"> <?php echo $this->Form->submit('Submit');  ?> </div>
						<?php echo $this->Form->end(); ?>
					</div>
					<div class="js-load-line-graph chart-half-section {'data_container':'events_line_data', 'chart_container':'events_line_chart', 'chart_title':'<?php echo __l('Events') ;?>', 'chart_y_title': '<?php echo __l('Events');?>'}">
						<div class="dashboard-tl">
							<div class="dashboard-tr">
								<div class="dashboard-tc"></div>
							</div>
						</div>
						<div class="dashboard-cl">
							<div class="dashboard-cr">
								<div class="dashboard-cc clearfix">
									<div id="events_line_chart" class="<?php echo $class; ?>"></div>
									<div class="hide">
										<table id="events_line_data" class="list">
											<thead>
												<tr>
													<th>Period</th>
													<?php foreach($chart_events_periods as $_period): ?>
														<th><?php echo $_period['display']; ?></th>
													<?php endforeach; ?>
												</tr>
											</thead>
											<tbody>
												<?php foreach($chart_events_data as $display_name => $chart_data): ?>
													<tr>
														<th><?php echo $display_name; ?></th>
														<?php foreach($chart_data as $val): ?>
															<td><?php echo $val; ?></td>
														<?php endforeach; ?>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="dashboard-bl">
							<div class="dashboard-br">
								<div class="dashboard-bc"></div>
							</div>
						</div>
					</div>
                    <div class="js-load-column-chart chart-half-section {'data_container':'joined-events_column_data', 'chart_container':'joined_events_column_chart', 'chart_title':'<?php echo __l('Joined Events') ;?>', 'chart_y_title': '<?php echo __l('Joined Events');?>'}">
                         <div class="dashboard-tl">
                         <div class="dashboard-tr">
                             <div class="dashboard-tc">
                                 </div>
                             </div>
                         </div>
                         <div class="dashboard-cl">
                             <div class="dashboard-cr">
                             <div class="dashboard-cc clearfix">
                            <div id="joined_events_column_chart" class="admin-dashboard-chart"></div>
                    		<div class="hide">
                    			<table id="joined-events_column_data" class="list">
                    			<tbody>
                                		<?php foreach($chart_joined_events_data as $key => $_data): ?>
                    				<tr>
                    				   <th><?php echo $key; ?></th>
                    				   <td><?php echo $_data[0]; ?></td>
                    				</tr>
                    				<?php endforeach; ?>
                    			</tbody>
                    			</table>
                    		</div>
                    	   </div>
                    		</div>
                    		</div>
                            <div class="dashboard-bl">
                                 <div class="dashboard-br">
                                     <div class="dashboard-bc">
                                     </div>
                                 </div>
                             </div>
	               </div>

				</div>
                <?php }?>
				</div>
			</div>
		</div>
	</div>
	<div class="main-bl">
		<div class="main-br">
			<div class="main-bm"> </div>
		</div>
	</div>
</div>