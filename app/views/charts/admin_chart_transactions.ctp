<div class="clearfix js-responses js-loadadmin-chart-transactions-ctp">
	<?php
		$chart_title = __l('Transactions');
		$chart_y_title = __l('Value');		
		?>
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
                    <?php echo __l('Overview'); ?>
               		<span class="js-chart-showhide <?php echo $arrow; ?> {'chart_block':'admin-dashboard-transactions', 'dataloading':'div.js-loadadmin-chart-transactions-ctp',  'dataurl':'admin/charts/chart_transactions/is_ajax_load:1'}"><?php echo $arrow;?></span></h2>
				<?php  if($is_ajax_load){ ?>
					<div class="admin-center-block clearfix dashboard-center-block " id="admin-dashboard-transactions">
					<div class="clearfix">
						<?php
                       echo $this->Form->create('Chart' , array('class' => 'js-chart-form language-form', 'action' => 'chart_transactions')); ?>
						<?php
                   			echo $this->Form->input('is_ajax_load', array('type' => 'hidden', 'value' => 1));							
							echo $this->Form->input('select_range_id', array('class' => 'js-chart-autosubmit', 'label' => __l('Select Range')));
						?>
						<div class="hide"> <?php echo $this->Form->submit('Submit');  ?> </div>
						<?php echo $this->Form->end(); ?>
					</div>
						<div class="clearfix">
						<?php  if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){ ?>
					<div class="js-load-line-graph chart-half-section {'data_container':'transactions_line_data', 'chart_container':'transactions_line_chart', 'chart_title':'<?php echo $chart_title ;?>', 'chart_y_title': '<?php echo $chart_y_title;?>'}">
						<div class="dashboard-tl">
							<div class="dashboard-tr">
								<div class="dashboard-tc"></div>
							</div>
						</div>
						<div class="dashboard-cl">
							<div class="dashboard-cr">
								<div class="dashboard-cc clearfix">
									<div id="transactions_line_chart" class="admin-dashboard-chart"></div>
									<div class="hide">
										<table id="transactions_line_data" class="list">
											<thead>
												<tr>
													<th>Period</th>
													<?php foreach($chart_periods as $_period): ?>
														<th><?php echo $_period['display']; ?></th>
													<?php endforeach; ?>
												</tr>
											</thead>
											<tbody>
												<?php foreach($chart_data as $display_name => $chart_data): ?>
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
					</div> <?php } ?>
					<div class="js-load-line-graph chart-half-section {'data_container':'total_requests_column_data', 'chart_container':'total_request_column_chart', 'chart_title':'<?php echo __l('Venue Owner Request') ;?>', 'chart_y_title': '<?php echo __l('Request');?>'}">
                         <div class="dashboard-tl">
                         <div class="dashboard-tr">
                             <div class="dashboard-tc">
                                 </div>
                             </div>
                         </div>
                         <div class="dashboard-cl">
							<div class="dashboard-cr">
								<div class="dashboard-cc clearfix">
									<div id="total_request_column_chart" class="admin-dashboard-chart"></div>
									<div class="hide">
										<table id="total_requests_column_data" class="list">
											<thead>
												<tr>
													<th>Period</th>
													<?php foreach($venue_owner_request_periods as $_period): ?>
														<th><?php echo $_period['display']; ?></th>
													<?php endforeach; ?>
												</tr>
											</thead>
											<tbody>
												<?php foreach($venue_owner_request_data as $display_name => $chart_data): ?>
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
                                     <div class="dashboard-bc">
                                     </div>
                                 </div>
                             </div>
	               </div>
                	</div>
				</div>
			<?php } ?>
			</div> </div>
		</div>
	</div>
	<div class="main-bl">
		<div class="main-br">
			<div class="main-bm"> </div>
		</div>
	</div>
</div>