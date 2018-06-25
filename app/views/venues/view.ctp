<?php /* SVN: $Id: $ */ ?>
<div class=" form">
		<h2><?php echo $this->Html->cText($venue['Venue']['name'],false) . ", "; ?><span class="city"><?php echo $this->Html->cText($venue['City']['name'], false); ?></span></h2>
		<div class="js-tabs clearfix review-tabs-block1">
	      	<ul class="clearfix menu-tabs">
				<li class="js-active-class"><?php echo $this->Html->link(__l('Info'), '#tabs-1');?></li>
				<li class="js-active-class"><?php echo $this->Html->link(__l('Map'), '#tabs-2');?></li>
				<li class="js-active-class"><?php echo $this->Html->link(__l('Videos'), '#tabs-3');?></li>
				<li class="js-active-class"><?php echo $this->Html->link(__l('Pictures'), '#tabs-4');?></li>
			</ul>
			<ul class="clearfix review-tabs menu-tabs">
                <li>
              	     <p class="js-active-class"> <?php echo $this->Html->link(__l('Reviews'), '#reviews'); ?></p>
                </li>
            </ul>
			
	    <div id="tabs-1" class="clearfix">
						<div class="clearfix form-content-block phots-view-block ">
					   	<div class="clearfix">
                            <div class="event-view-img-block grid_6 grid_right">
    							<div class="js-response clearfix add-block1">
                         			<?php
                        				if ($this->Auth->sessionValid()):
                        					if(!empty($venueUser['VenueUser'])) :
                        						echo $this->Html->link(__l('Remove from regular'), array('controller' => 'venue_users', 'action' => 'delete', $venueUser['VenueUser']['id'], $venue['Venue']['slug']), array('title' => __l('Remove from regular'), 'escape' => false, 'class' => "js-ajax-submission removed_class {'added_text':'You\'re a regular remove', 'removed_text':'Become a Regular', 'added_class':'added_class', 'removed_class':'removed_class', 'removed_message':'You are removed from regular list for this venue' , 'added_message':'You are added in regular list for this venue' }"));
                        					else:
                        						echo $this->Html->link(__l('Become a Regular'), array('controller'=> 'venue_users', 'action' => 'add',  $venue['Venue']['id']), array('title' => __l('Become a Regular'), 'escape' => false,'class' => "js-ajax-submission  added_class {'added_text':'You\'re a regular remove','removed_text':'Become a Regular ','added_class':'added_class','removed_class':'removed_class','removed_message':'You are removed from regular list for this venue' , 'added_message':'You are added in regular list for this venue'} "));
                        					endif;
                        				else:
                        					echo $this->Html->link(__l('Become a Regular'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Login'), 'escape' => false, 'class' => 'js-colorbox'));
                        				endif;
                        			?>
                                </div>
                                <?php
                                    if($venue['Venue']['user_id'] == $this->Auth->user('id') && $venue['Venue']['admin_suspend']=='0'): ?>
                                     <div class="add-block1">
                                		<?php echo $this->Html->link(__l('Edit'), array('controller'=> 'venues','action'=>'edit', $venue['Venue']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
            							<?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $venue['Venue']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                                    </div>
                                	<?php endif; ?>
      							<?php
										$venue['Attachment'] = !empty($venue['Attachment']) ? $venue['Attachment'] : array();
										echo $this->Html->link($this->Html->showImage('Venue', $venue['Attachment'], array('dimension' => 'medium_big_thumb', 'title' => $this->Html->cText($venue['Venue']['name'], false), 'escape'=>false, 'alt' => sprintf('[Image: %s]', $this->Html->cText($venue['Venue']['name'], false)))), array('controller' => 'photo_albums', 'action' => 'index', 'venue_id' => $venue['Venue']['id'], 'admin' => false), array('title' => $this->Html->cText($venue['Venue']['name'],false), 'escape' => false));
									?>
							
								<?php
									if (!empty($venue['VenueLogo']['id'])) {
										echo $this->Html->showImage('Venue', $venue['VenueLogo'], array('dimension' => 'big_thumb', 'escape'=>false, 'alt' => sprintf('[Image: %s]', $this->Html->cText($venue['Venue']['name'], false)), 'title' => $this->Html->cText($venue['Venue']['name'], false)));
									}
								?>
								<?php if(!empty($venue['VenueSponsor'])){ ?>
								<div class="clearfix event-sponsor-logo">
    								<h3><?php echo __l('Sponsor info');?></h3>
        							<div class="sponsor-logo clearfix">
        								<?php foreach($venue['VenueSponsor'] as $venuesponsor){
        								echo $this->Html->showImage('VenueSponsor', $venuesponsor['Attachment'], array('dimension' => 'normal_thumb', 'title' => $this->Html->cText($venuesponsor['first_name'], false), 'alt' => sprintf('[Image: %s]', $this->Html->cText($venuesponsor['first_name'], false))));

        								}?>
        							</div>
    							</div>
    							<?php } ?>
							</div>
							<div class="grid_10 omega alpha">
							<div class="clearfix">
       							<dl class="list event-list clearfix">
										<?php if (!empty($venue['Venue']['name'])): ?>
											<dt class="event-title"><?php echo __l('Name: '); ?></dt>
												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['name'],false);?> </dd>
										<?php endif; ?>
										<?php if (!empty($venue['Venue']['address'])): ?>
											<dt class="event-title"><?php echo __l('Address: '); ?></dt>
												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['address']);?></dd>
										<?php endif; ?>
										<?php if (!empty($venue['City']['name']) || !empty($venue['Country']['name'])): ?>
											<dt class="event-title"><?php echo __l('City, Country: '); ?></dt>
												<dd class="event-info">
													<em class="event-address-info">
														<?php if (!empty($venue['City']['name'])): ?>
															<?php echo $this->Html->cText($venue['City']['name']); ?>
														<?php endif; ?>
														<?php if (!empty($venue['Country']['name'])): ?>
															<?php echo $this->Html->cText($venue['Country']['name']); ?>
														<?php endif; ?>
													</em>
												</dd>
										<?php endif; ?>
										<?php if (!empty($venue['Venue']['zip_code'])): ?>
											<dt class="event-title"><?php echo __l('ZIP code: '); ?></dt>
												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['zip_code']);?> </dd>
										<?php endif; ?>
										<?php if (!empty($venue['Venue']['phone'])): ?>
											<dt class="event-title"><?php echo __l('Phone: '); ?></dt>
												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['phone']);?> </dd>
										<?php endif; ?>
										<?php if (!empty($venue['Venue']['website'])): ?>
											<dt class="event-title"><?php echo __l('Website: '); ?></dt>
												<dd class="event-info">
													<?php
														if (!empty($venue['Venue']['website'])) {
															echo $this->Html->link($this->Html->cText($venue['Venue']['website']), $venue['Venue']['website'], array('title' => $venue['Venue']['website'], 'escape' => false, 'target' => 'blank'));
														}
													?>
												</dd>
										<?php endif; ?>
									</dl>
       							<?php if (!empty($venue['MusicType']) || !empty($venue['VenueCategory'])) { ?>
									<dl class="list event-list clearfix">
											<?php if (!empty($venue['VenueCategory'])) { ?>
												<?php
													$venuecate = array();
													foreach($venue['VenueCategory'] as $venuecategory) {
														$venuecate[] = $venuecategory['name'];
													}
												?>
												<dt class="event-title"><?php echo __l('Type (genre):'); ?></dt>
													<dd class="event-info"><?php echo implode(',', $venuecate); ?></dd>
											<?php } ?>
											<?php if (!empty($venue['MusicType'])) { ?>
												<?php
													$venuemusic = array();
													foreach($venue['MusicType'] as $venuemusictype) {
														$venuemusic[] = $venuemusictype['name'];
													}
												?>
												<dt class="event-title"><?php echo __l('Music:'); ?></dt>
													<dd class="event-info"><?php echo implode(', ', $venuemusic); ?></dd>
											<?php } ?>
									</dl>
									<?php } ?>
    								<?php if(!empty($venue['ParkingTYpe']) || !empty($venue['Venue']['door_policy']) || !empty($venue['VenueFeature'])):?>
										<dl class="list event-list clearfix">
											<?php if (!empty($venue['ParkingTYpe'])): ?>
												<?php
													$parkingtype = array();
													foreach($venue['ParkingTYpe'] as $venueparkingtype):
														$parkingtype[] = $venueparkingtype['name'];
													endforeach;
												?>
												<dt class="event-title"><?php echo __l('Parking type: '); ?></dt>
													<dd class="event-info"><?php echo implode(',', $parkingtype); ?></dd>
											<?php endif; ?>
											<?php if (!empty($venue['Venue']['door_policy'])): ?>
												<dt class="event-title"><?php echo __l('Door Policy: '); ?></dt>
													<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['door_policy']);?> </dd>
											<?php endif; ?>
											<?php if(!empty($venue['VenueFeature'])): ?>
												<?php
													$venuefeature = array();
													foreach ($venue['VenueFeature'] as $venuefeatures):
														$venuefeature[] = $venuefeatures['name'];
													endforeach;
												?>
												<dt class="event-title"><?php echo __l('Venue Feature: '); ?></dt>
													<dd class="event-info"><?php echo implode(',', $venuefeature); ?></dd>
												<?php endif; ?>
										</dl>
									<?php endif; ?>
                                    </div>
    							</div>
                                </div>
     							<div class="clearfix insider-info-block round-5">
             					 <?php if (!empty($venue['Venue']['is_closed']) || !empty($venue['Venue']['import_beer_price_id']) || !empty($venue['Venue']['domestic_beer_price_id']) || !empty($venue['Venue']['well_drink_price_id']) || !empty($venue['Venue']['soft_drink_price_id']) || !empty($venue['Venue']['food_sold_id']) || !empty($venue['Venue']['live_band_id']) || !empty($venue['Venue']['guest_dj_id'])): ?>
                                        <div class="grid_9 alpha">
                                            <h3><?php echo __l('Insider info');?></h3>
    										<dl class="list event-list clearfix">
    											<?php if (!empty($venue['Venue']['is_closed'])): ?>
    												<dt class="event-title"><?php echo __l('Status: '); ?></dt>
    													<dd class="event-info"><?php echo $this->Html->cBool($venue['Venue']['is_closed']);?></dd>
    											<?php endif; ?>
    											<?php if (!empty($venue['Venue']['import_beer_price_id'])): ?>
    												<dt class="event-title"><?php echo __l('Import Beer Price: '); ?></dt>
    													<dd class="event-info"><?php echo $this->Html->cText($BeerPrice[$venue['Venue']['import_beer_price_id']]);?></dd>
    											<?php endif; ?>
    											<?php if (!empty($venue['Venue']['domestic_beer_price_id'])): ?>
    												<dt class="event-title"><?php echo __l('Domestic Beer Price: '); ?></dt>
    													<dd class="event-info"><?php echo $this->Html->cText($BeerPrice[$venue['Venue']['domestic_beer_price_id']]);?></dd>
    											<?php endif; ?>
    											<?php if (!empty($venue['Venue']['well_drink_price_id'])): ?>
    												<dt class="event-title"><?php echo __l('Well Drink Price: '); ?></dt>
    													<dd class="event-info"><?php echo $this->Html->cText($BeerPrice[$venue['Venue']['well_drink_price_id']]);?></dd>
    											<?php endif; ?>
    											<?php if (!empty($venue['Venue']['soft_drink_price_id'])): ?>
    												<dt class="event-title"><?php echo __l('Soft Drink Price: '); ?></dt>
    													<dd class="event-info"><?php echo $this->Html->cText($BeerPrice[$venue['Venue']['soft_drink_price_id']]);?></dd>
    											<?php endif; ?>
    											<?php if (!empty($venue['Venue']['food_sold_id'])): ?>
    												<dt class="event-title"><?php echo __l('Food Sold: '); ?></dt>
    													<dd class="event-info"><?php echo $this->Html->cText($FoodSold[$venue['Venue']['food_sold_id']]);?></dd>
    											<?php endif; ?>
    											<?php if (!empty($venue['Venue']['live_band_id'])): ?>
    												<dt class="event-title"><?php echo __l('Live Band: '); ?></dt>
    													<dd class="event-info"><?php echo $this->Html->cText($LiveBand[$venue['Venue']['live_band_id']]);?></dd>
    											<?php endif; ?>
    											<?php if (!empty($venue['Venue']['guest_dj_id'])): ?>
    												<dt class="event-title"><?php echo __l('Guest Dj: '); ?></dt>
    													<dd class="event-info"><?php echo $this->Html->cText($LiveBand[$venue['Venue']['guest_dj_id']]);?></dd>
    											<?php endif; ?>
    										</dl>
										</div>
										<?php endif;?>
										 <div class="grid_9 omega alpha">
                                            <?php if($venue['Venue']['capacity'] || $venue['Venue']['employee_size_id'] || $venue['Venue']['square_footage_id'] || $venue['Venue']['sales_volume_id'] || $venue['Venue']['contact_name'] || $venue['Venue']['contact_email'] || $venue['Venue']['contact_phone'] || $venue['Venue']['contact_fax'] || ($venue['Venue']['open_date'] && $venue['Venue']['open_date']!='0000-00-00 00:00:00') || ($venue['Venue']['closed_date'] && $venue['Venue']['closed_date']!='0000-00-00 00:00:00')): ?>
        									   <h3><?php echo __l('Business info');?></h3>
        									<?php endif;?>
        									<dl class="list event-list clearfix">
        										<?php if($venue['Venue']['capacity']) { ?>
        											<dt class="event-title"><?php echo __l('Capacity: '); ?></dt>
        												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['capacity']);?> </dd>
        										<?php } ?>
        										<?php if($venue['Venue']['employee_size_id']){ ?>
        											<dt class="event-title"><?php echo __l('Employee Size: '); ?></dt>
        												<dd class="event-info"><?php echo $this->Html->cText($employee_size[$venue['Venue']['employee_size_id']]);?> </dd>
        										<?php } ?>
        										<?php if($venue['Venue']['square_footage_id']){ ?>
        											<dt class="event-title"><?php echo __l('Square Footage: '); ?></dt>
        												<dd class="event-info"><?php echo $this->Html->cText($square_footage[$venue['Venue']['square_footage_id']]);?> </dd>
        										<?php } ?>
        										<?php if($venue['Venue']['sales_volume_id']){ ?>
        											<dt class="event-title"><?php echo __l('Sales Volume: '); ?></dt>
        												<dd class="event-info"><?php echo $this->Html->cText($sales_volume[$venue['Venue']['sales_volume_id']]);?> </dd>
        										<?php } ?>
        										<?php if($venue['Venue']['contact_name']){ ?>
        											<dt class="event-title"><?php echo __l('Contact Name: '); ?></dt>
        												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['contact_name']);?> </dd>
        										<?php } ?>
        										<?php if($venue['Venue']['contact_email']){ ?>
        											<dt class="event-title"><?php echo __l('Contact Email: '); ?></dt>
        												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['contact_email']);?> </dd>
        										<?php } ?>
        										<?php if($venue['Venue']['contact_phone']){ ?>
        											<dt class="event-title"><?php echo __l('Contact Phone: '); ?></dt>
        												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['contact_phone']);?> </dd>
        										<?php } ?>
        										<?php if($venue['Venue']['contact_fax']){ ?>
        											<dt class="event-title"><?php echo __l('Contact Fax: '); ?></dt>
        												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['contact_fax']);?> </dd>
        										<?php } ?>
        										<?php if($venue['Venue']['open_date'] && empty($venue['Venue']['is_open'] ) && $venue['Venue']['open_date']!='0000-00-00 00:00:00'){ ?>
        											<dt class="event-title"><?php echo __l('Open Date: '); ?></dt>
        												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['open_date']);?> </dd>
        										<?php } ?>
        										<?php if($venue['Venue']['closed_date'] && empty($venue['Venue']['is_close']) && $venue['Venue']['closed_date']!='0000-00-00 00:00:00'){ ?>
        											<dt class="event-title"><?php echo __l('Closed Date: '); ?></dt>
        												<dd class="event-info"><?php echo $this->Html->cText($venue['Venue']['closed_date']);?> </dd>
        										<?php } ?>
        									</dl>
									    </div>
								</div>
						</div>
				
			
			</div>
  	<div id="tabs-2">
				<div class="form-content-block phots-view-block  videos-center-block">
    			<div class="clearfix hide">
					<input type="hidden" id="VenueAddress"  value="<?php echo $this->Html->cText($venue['Venue']['address'], false);?>" />
					<input type="hidden" id="VenueCity"  value="<?php echo $this->Html->cText($venue['City']['name'], false);?>" />
					<input type="hidden" id="latitude"  value="<?php echo $venue['Venue']['latitude'];?>" />
					<input type="hidden" id="longitude"  value="<?php echo $venue['Venue']['longitude'];?>" />
					<input type="hidden" id="zoomlevel" value="10" />
					<input type="hidden" id="action" value="view" />
				</div>
				<div class="js-view-map">
					<div id="js-map-container"></div>
				</div>
			</div>
			</div>
			<div id="tabs-3">
				<div class="clearfix form-content-block ">
				<h3>
				<?php echo __l('Upload new video for') . ' ' . $this->Html->link($this->Html->cText($venue['Venue']['name'],false) . ' ' . $venue['City']['name'], array('controller' => 'videos', 'action' => 'add', 'venue_id' => $venue['Venue']['id']), array('title' => $venue['Venue']['name'] . ' ' . $venue['City']['name'], 'escape' => false)); ?>
                </h3>
                <?php
				  if(!empty($venue['Video'])) { ?>
					<ol class="list feature-list clearfix">
						<input type="hidden" id="foreign_id" value="<?php echo $venue['Venue']['id']?>" />
						<?php
							echo $this->Form->input('class', array('type' => 'hidden', 'value' => 'Venue'));
							foreach ($venue['Video'] as $video):
						?>
						<li class="clearfix">
    						<div class="grid_4 omega alpha">
    							<?php

    								$video['Thumbnail']['id'] = (!empty($video['default_thumbnail_id'])) ? $video['default_thumbnail_id'] : '';
    								echo $this->Html->link($this->Html->showImage('Video', $video['Thumbnail'], array('dimension' => 'featured_event_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($video['title'], false)), 'title' => $this->Html->cText($video['title'], false))) , array('controller' => 'videos', 'action' => 'view', $video['slug']) , array('escape' => false));
    							?>
							</div>
							<div class="grid_10 omega alpha">
    							<p><h3><?php echo $this->Html->link($this->Html->cText($video['title']) , array('controller' => 'videos', 'action' => 'v', 'slug' => $video['slug'], 'view_type' => ConstViewType::NormalView) , array('escape' => false)) ?></h3></p>
    							<p><?php echo  $this->Html->link($this->Html->truncate($this->Html->cText($venue['Venue']['name'],false), 70), array('controller'=> 'venues', 'action' => 'view', $venue['Venue']['slug']), array('title'=>$this->Html->cText($venue['Venue']['name'],false),'escape' => false));?></p>
        						<p>by <?php echo $this->Html->link($this->Html->cText($video['User']['username']) , array('controller' => 'users', 'action' => 'view', $venue['User']['username']) , array('escape' => false)); ?> on <?php echo $this->Html->cDateTime($video['created']); ?></p>
        						<p> <?php  echo __l('Views'); ?><?php  echo $this->Html->cInt($video['video_view_count']); ?></p>
    						
    						</div>
							</li>
						<?php endforeach; ?>
						
					</ol>
				<?php } else { ?>
					<ol class="list">
    					<li>
    					   <p class="notice">
    					       	<?php echo sprintf('There are no videos for  %s %s.' ,$this->Html->cText($venue['Venue']['name']), $venue['City']['name']); ?>
                            </p>
                        </li>
                    </ol>
                   <?php } ?>
				</div>
			</div>
			<div id="tabs-4">
             <div class="clearfix form-content-block ">
				<h3>
             <?php echo __l('Upload new photo for') . ' ' . $this->Html->link($this->Html->cText($venue['Venue']['name'],false) . ' ' . $venue['City']['name'], array('controller' => 'photo_albums', 'action' => 'add', 'venue_id' => $venue['Venue']['id']), array('title' => $venue['Venue']['name'] . ' ' . $venue['City']['name'], 'escape' => false)); ?>
                </h3>
                <?php
				  if(!empty($venue['PhotoAlbum'])) { ?>
					<ol class="list feature-list clearfix">
						<input type="hidden" id="foreign_id" value="<?php echo $venue['Venue']['id']?>" />
						<?php
							echo $this->Form->input('class', array('type' => 'hidden', 'value' => 'Venue'));
							 foreach ($venue['PhotoAlbum'] as $photoAlbum):?>
                            <li class="clearfix">
        						<div class="grid_4 alpha">
        							<?php   $album_defalut_image = !empty($photoAlbum['Photo'][0]['Attachment']) ? $photoAlbum['Photo'][0]['Attachment'] : array();
        											echo $this->Html->link($this->Html->showImage('Photo', $album_defalut_image, array('dimension' => 'home_newest_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($photoAlbum['title'], false)), 'title' => $this->Html->cText($photoAlbum['title'], false))), array('controller' => 'photos', 'action' => 'index', 'album' => $photoAlbum['slug']), array('escape' => false));
        							?>
    							</div>
    							<div class="grid_10 omega ">
        							<p><h3><?php echo $this->Html->link($this->Html->cText($photoAlbum['title']) , array('controller' => 'photos', 'action' => 'index','album' => $photoAlbum['slug']) , array('escape' => false)) ?></h3></p>
        							<p><?php echo  $this->Html->link($this->Html->truncate($this->Html->cText($venue['Venue']['name']), 70), array('controller'=> 'venues', 'action' => 'view', $venue['Venue']['slug']), array('title'=>$this->Html->cText($venue['Venue']['name'],false),'escape' => false));?></p>
        							<p>by <?php echo $this->Html->link($this->Html->cText($photoAlbum['Photo'][0]['User']['username']) , array('controller' => 'users', 'action' => 'view', $venue['User']['username']) , array('escape' => false)); ?> on <?php echo $this->Html->cDateTime($photoAlbum['Photo'][0]['created']); ?></p>
            						<p> <?php  echo __l('Views'); ?><?php  echo $this->Html->cInt($photoAlbum['Photo'][0]['photo_view_count']); ?></p>
        						</div>
							
							</li>
						<?php endforeach; ?>

					</ol>
				<?php } else { ?>
					<ol class="list">
    					<li>
    					   <p class="notice">
    					       	<?php echo sprintf('There are no photos for  %s %s.' ,$this->Html->cText($venue['Venue']['name'],false), $venue['City']['name']); ?>
                            </p>
                        </li>
                    </ol>
                   <?php } ?>
				</div>
			</div>
	
		<div class="form-content-block  phots-view-block  clearfix">
    		<h3><span><?php echo sprintf('%s' ,$this->Html->cText($venue['Venue']['name'],false));?></span></h3>
            <div class="clearfix">
    		<ul class="share-list grid_right clearfix">
    			<li>
    				<a href="http://twitter.com/share?url=<?php echo Router::url(array('controller' => 'venues', 'events' => 'view', $venue['Venue']['slug']),true); ?>&amp;text=<?php echo $venue['Venue']['name'];?>&amp;lang=en&amp;via=<?php echo Configure::read('site.name'); ?>" class="twitter-share-button"  data-count="none"><?php echo __l('Tweet!');?></a>
    				<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    			</li>
    			<li class="article-fb-share">
    				<a href="http://www.facebook.com/sharer.php?u=<?php echo Router::url(array('controller' => 'venues', 'events' => 'view', $venue['Venue']['slug']),true); ?>&amp;t=<?php echo $venue['Venue']['name']; ?>" target="_blank" class="fb-share-button"><?php echo __l('fbshare'); ?></a>
    			</li>
    		</ul>
    		<div class="grid_left">
    			<?php echo $this->Html->link(__l('Is this your business? click here'), array('controller' => 'contacts', 'action' => 'add', $venue['Venue']['id'], 'admin' => false), array('class' => 'delete', 'title' => __l('Is this your business? click here')));?>
    	         <?php echo $this->Html->link(__l('Suggest a correction'), array('action' => 'edit', $venue['Venue']['id'], 'type' => 'suggestion'), array('class' => 'edit js-edit', 'title' => __l('Suggest a correction')));?>
             </div>
         </div>
		</div>
		<div class="form-content-block phots-view-block ">
			<h3><span><?php echo $this->Html->cText($venue['Venue']['name'],false) . ' ' . $this->Html->cText($venue['City']['name']);?> </span><span class="title">, <?php echo __l('VENUE DESCRIPTION');?></span></h3>
		      <p><?php echo nl2br($this->Html->cText($venue['Venue']['description']));?></p>
		</div>

        </div>
		<div class="tabs-content-block phots-view-block  form-content-block" id="reviews">
	        <div class="comment-event-option clearfix">
			<h3><span><?php echo $this->Html->cText($venue['Venue']['name'],false) . ' ' . $this->Html->cText($venue['City']['name']);?></span> <span class="title">, <?php echo sprintf(__l('Reviews From %s Users'), Configure::read('site.name'));?></span></h3>
		<?php echo __l('Submit a Review'); ?>
			<?php if(!$this->Auth->sessionValid()): ?>
				<div class="event-link">
				<p><?php echo Configure::read('site.name'); ?><?php echo __l(' members: please'). ' '; ?><?php echo $this->Html->link(__l('log in'), array('controller' => 'users', 'action' => 'login'), array('class' => 'login', 'title' => __l('Login'), 'escape' => false)); ?></p>
				<p><?php echo __l('Guests: Please ') . $this->Html->link(__l('create an account'), array('controller' => 'users', 'action' => 'joinus'), array('class' => 'login', 'title' => __l('create an account'), 'escape' => false)); ?><?php echo __l(' or enter your review below.'); ?></p>
				<p><?php echo __l('(Only registered users can delete their reviews)'); ?></p>
                </div>
        	<?php endif; ?>
			<?php
				echo $this->element('../venue_comments/add'); ?>
            </div>
			<?php
			echo $this->element('venue_comments-index', array('venue_id' => $venue['Venue']['id'], 'cache' => array('key' => $venue['Venue']['id'], 'config' => '2sec')));
			if($this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
    		<div class="admin-tabs-block form-content-block">
    		<div class="js-tabs">
    			<ul class="clearfix">
    				<li><?php echo $this->Html->link(__l('Venue Photo Galleries'), array('controller' => 'photo_albums', 'action' => 'index', 'venue_photo' => $venue['Venue']['slug'], 'admin' => true), array('title' => __l('Venue Photo Galleries'), 'escape' => false)); ?></li>
    				<li><?php echo $this->Html->link(__l('Venue Videos'), array('controller' => 'videos', 'action' => 'index', 'venue_video' => $venue['Venue']['slug'], 'admin' => true), array('title' => __l('Venue Videos'), 'escape' => false)); ?></li>
    				<li><?php echo $this->Html->link(__l('Venue Reviews'), array('controller' => 'venue_comments', 'action' => 'index','venue_comment' => $venue['Venue']['slug'], 'admin' => true), array('title' => __l('Venue Reviews'), 'escape' => false)); ?></li>
    			</ul>

    		</div>
    		</div>
        <?php endif; ?>

		</div>

	
</div>