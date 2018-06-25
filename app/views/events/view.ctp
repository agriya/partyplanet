    <?php /* SVN: $Id: $ */ ?>
	<div id="breadcrumb">
			<?php $this->Html->addCrumb(__l('Events'), array('controller' => 'events', 'action' => 'index')); ?>
			<?php $this->Html->addCrumb($this->Html->cText($event['Event']['title'],false)); ?>
			<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
		</div>
		<div class="js-tabs clearfix review-tabs-block1">
	       	<ul class="clearfix menu-tabs">
				<li><?php echo $this->Html->link(__l('Event info'), '#tabs-1');?></li>
				<li><?php echo $this->Html->link(__l('MAP'), '#tabs-2');?></li>
				<li><?php echo $this->Html->link(__l('Photos'), '#tabs-3');?></li>
				<li><?php echo $this->Html->link(__l('Videos'), '#tabs-4');?></li>
			</ul>
			<ul class="clearfix review-tabs menu-tabs">
                <li>
              	     <p class="js-active-class"> <?php echo $this->Html->link(__l('Reviews'), '#reviews'); ?></p>
                </li>
            </ul>
			
 		<div id="tabs-1"> 			
					<div class="clearfix form-content-block">
					<h2><span><?php echo $this->Html->cText($event['Event']['title'],false); ?> @ </span><span class="city"> <?php echo $this->Html->cText($event['Venue']['name']);?></span></h2>
                       <div class="clearfix">
                    	<div class="event-view-img-block grid_6 grid_right">
                    	 	<div class="add-block1">
        						<?php
        						if ($event['Event']['user_id'] == $this->Auth->user('id')):
                                  if($event['Event']['admin_suspend']=='0'):
        							echo $this->Html->link(__l('Edit'), array('action'=>'edit', $event['Event']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));
        							echo $this->Html->link(__l('Delete'), array('action'=>'delete', $event['Event']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));
        							endif;
        							if($event['Event']['is_cancel']=='0'){
        								echo $this->Html->link(__l('Cancel'), array('action'=>'cancel', $event['Event']['id']), array('class' => 'cancel js-cancel', 'title' => __l('Cancel')));
        							}
        						endif;
        					?>
        					</div>
							<?php
								echo $this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'medium_big_thumb', 'title' => $this->Html->cText($event['Event']['title'], false), 'alt' => sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false))));
							?>
						<?php if(!empty($event['EventSponsor'])){ ?>
    						<div class="clearfix event-sponsor-logo">
    						    <h3><?php echo __l('Event Sponsor Logo')?></h3>
    							<div class="sponsor-logo clearfix">
    								<?php foreach($event['EventSponsor'] as $eventsponsor){
    								echo $this->Html->showImage('Event', $eventsponsor['Attachment'], array('dimension' => 'normal_thumb', 'title' => $this->Html->cText($eventsponsor['name'], false), 'alt' => sprintf('[Image: %s]', $this->Html->cText($eventsponsor['name'], false))));
    								}?>
    							</div>
                            </div>
							<?php } ?>
						</div>
						<div class="clearfix grid_9 omega alpha">
                		      <dl class="list event-list clearfix">
                    			<dt><?php echo __l('Event Name:')?></dt>
                    			<dd><?php echo $this->Html->cText($event['Event']['title'],false); ?></dd>
                    			<dt><?php echo __l('Date:')?></dt>
                    			<dd>
                    				<?php
                    					echo $this->Html->cDate($event['Event']['start_date']).' '. __l('to').' ';
                    					if($event['Event']['event_type_id'] > 1){
                    						echo $this->Html->cDate($event['Event']['repeat_end_date']);
                    					}else{
                    						echo $this->Html->cDate($event['Event']['end_date']);
                    					}
                    				?>
                    			</dd>
                    			<dt><?php echo __l('Time:')?></dt>
                    			<dd>
                    				<?php
                    					if($event['Event']['is_all_day']==1):
                    						echo __l('Whole Day');
                    					else:
                    						echo $this->Html->cTime($event['Event']['start_time']).' - '. $this->Html->cTime($event['Event']['end_time']);
                    					 endif;
                    				?>
                    			</dd>
                    			<dt><?php echo __l('Venue Name:')?></dt>
                    			<?php $event['Venue']['Attachment'] = !empty($event['Venue']['Attachment']['id']) ? $event['Venue']['Attachment'] : array(); ?>
                    			<dd>
                    				<?php echo $this->Html->link($this->Html->showImage('Venue', $event['Venue']['Attachment'], array('dimension' => 'small_thumb', 'title' => $this->Html->cText($event['Venue']['name'], false), 'alt' => sprintf('[Image: %s]', $this->Html->cText($event['Venue']['name'], false)),'escape'=>false)), array('controller' => 'venues', 'action' => 'view', $event['Venue']['slug'], 'admin' => false), array('title' => $this->Html->cText($event['Event']['title'], false), 'escape' => false), null, array('inline' => false)); ?> <?php echo $this->Html->link($this->Html->cText($event['Venue']['name']), array('controller' => 'venues', 'action' => 'view', $event['Venue']['slug'], 'admin' => false), array('title' => $this->Html->cText($event['Venue']['name'], false),'escape' => false)); ?>
                    			</dd>
                    			<dt><?php echo __l('Event Category:')?></dt>
                    			<dd>
                    				<?php echo  $this->Html->link($this->Html->cText($event['EventCategory']['name']), array('controller'=> 'events', 'action' => 'index', 'category'=>$event['EventCategory']['slug']), array('title'=>$event['EventCategory']['name'],'escape' => false));?>
                    			</dd>
                    			<?php
                    			if(!empty($event['EventScene'])):?>
                    			<dt><?php echo __l('Scene Type:')?></dt>
                    			<dd>
                    				<?php
                                          $tmp_scene_type =array();
                    					foreach($event['EventScene'] as $scene) {
                    						$tmp_scene_type[] = $this->Html->link($this->Html->cText($scene['name']), array('controller'=> 'events', 'action' => 'index', 'scene'=>$scene['id']), array('title'=>$scene['name'], 'escape' => false));
                    						}
                           			$scene_type= implode(", ",$tmp_scene_type);
                    				echo $scene_type;?>
                    			</dd>
                    			<?php endif;?>
                    			<?php if(!empty($event['MusicType'])):?>
                    			<dt><?php echo __l('Music Type:')?></dt>
                    			<dd>
                    				<?php
                    				$tmp_music_type =array();
                    				foreach($event['MusicType'] as $musictype) {
                        				$tmp_music_type[] = $this->Html->link($this->Html->cText($musictype['name']), array('controller' => 'events', 'action' => 'index', 'music' => $musictype['slug']), array('title' => $musictype['name'], 'escape' => false));
                    				}
                    				$music_type= implode(", ",$tmp_music_type);
                    				echo $music_type;?>
                    			</dd>
                    			<?php endif;?>
                    			<dt><?php echo __l('Age Requirement:'); ?></dt>
                    			<dd><?php echo $this->Html->cText($event['AgeRequirment']['name']);?></dd>
                    			<?php if(!empty($event['Event']['dress_code'])):?>
                        		<dt><?php echo __l('Dress Code:'); ?></dt>
                        		<dd><?php echo $this->Html->cText($event['Event']['dress_code']);?></dd>
                    			<?php endif;?>
                    			<?php if(!empty($event['Event']['cover'])):?>
                    			<dt><?php echo __l('Cover:'); ?></dt>
                    			<dd><?php echo $this->Html->cText($event['Event']['cover']);?></dd>
                    			<?php endif; ?>
                    			<?php if(!empty($event['GuestList']['details'])):?>
                    		    <dt><?php echo __l('GuestList Details:'); ?></dt>
                    			<dd><?php echo !empty($event['GuestList']['details'])?$this->Html->cText($event['GuestList']['details']):'';?></dd>
                    			<?php endif;?>

                    			<dt><?php echo __l('Venue Details:')?></dt>
                    			<dd>
                    			<?php
                                  $address=$event['Venue']['address'];
                                  $addre=explode(",",$address);
                                  $addr=implode(",  ",$addre);
                                  echo $this->Html->cText($addr). ", ";?>
                    				<em class="event-address-info">
                    					<?php echo $this->Html->link($this->Html->cText($event['Venue']['City']['name']), array('controller'=> 'venues', 'action' => 'index', 'city'=>$event['Venue']['City']['slug']), array('title'=>$event['Venue']['City']['name'],'escape' => false)). ", ";?>
                                            <?php echo $this->Html->link($this->Html->cText($event['Venue']['Country']['name']), array('controller'=> 'venues', 'action' => 'index', 'country'=>$event['Venue']['Country']['slug']), array('title'=>$event['Venue']['Country']['name'],'escape' => false));?>
                    				</em>
                    			</dd>
                			</dl>
                         </div>
                         </div>
                        <div class="event-details-block">
    						<h3><?php echo __l('Event Details:')?></h3>
                    		<div class="event-description-info"><?php echo !empty($event['Event']['description'])?nl2br($this->Html->cText($event['Event']['description'])):'';?></div>
                        </div>
					</div>
				

        </div>
		<div id="tabs-2">
            <div class="form-content-block phots-view-block  videos-center-block">
    			<div class="clearfix hide">
					<input type="hidden" id="VenueAddress"  value="<?php echo $this->Html->cText($event['Venue']['address'], false);?>" />
					<input type="hidden" id="VenueCity"  value="<?php echo $this->Html->cText($event['Venue']['City']['name'], false);?>" />
					<input type="hidden" id="latitude"  value="<?php echo $event['Venue']['latitude'];?>" />
					<input type="hidden" id="longitude"  value="<?php echo $event['Venue']['longitude'];?>" />
					<input type="hidden" id="zoomlevel" value="10" />
					<input type="hidden" id="action" value="view" />
				</div>
				<div class="js-view-map">
					<div id="js-map-container"></div>
				</div>
			</div>
		</div>
		<div id="tabs-3">
    		<div class="form-content-block">
    			<?php echo $this->element('event-photo-albums-index', array('event_id' => $event['Event']['id'], 'cache' => array('key' => $event['Event']['id'], 'config' => 'sec'))); ?>
            </div>
    	</div>
		<div id="tabs-4">
            <div  class="form-content-block">
    			<?php if(!empty($event['Video'])) { ?>
    				<ol class="list feature-list clearfix">
    					<?php
    						foreach ($event['Video'] as $video):
    					?>
    							<li class="clearfix">
    							<div class="grid_4  alpha">
        							<?php
        								$video['Thumbnail']['id'] = (!empty($video['default_thumbnail_id'])) ? $video['default_thumbnail_id'] : '';
        								echo $this->Html->link($this->Html->showImage('Video', $video['Thumbnail'], array('dimension' => 'home_newest_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($video['title'], false)), 'title' => $this->Html->cText($video['title'], false))) , array('controller' => 'videos', 'action' => 'view', $video['slug']) , array('escape' => false));
        							?>
    							</div>
    							<div class="grid_11 omega">
    						      	<h3><?php echo $this->Html->link($this->Html->cText($video['title']), array('controller' => 'videos', 'action' => 'view', 'action' => 'view', $video['slug']) , array('escape' => false)); ?></h3>
                                </div>
                        </li>
    					<?php
    						endforeach;
    					?>
    				</ol>
    			<?php
    			} else {
    				echo sprintf('There are no videos for %s %s.' ,$this->Html->cText($event['Event']['title'],false), $event['Venue']['City']['name']) . ' ';
    			}
    			echo __l('Submit ') . ' ' . $this->Html->link(__l(sprintf('%s %s' , $this->Html->cText($event['Event']['title'],false), $event['Venue']['City']['name'])), array('controller' => 'videos', 'action' => 'add', 'event_id' => $event['Event']['id']), array('title' => __l(sprintf('%s %s', $this->Html->cText($event['Event']['title'], false), $event['Venue']['City']['name'])), 'escape' => false));
    			?>
			</div>
		</div>

	</div>
	<div class="clearfix form-content-block">
            <?php
                 if(!empty($event['Event']['is_active'])):?>
                <ul class="share-list grid_right clearfix">
    				<li>
    					<a href="http://twitter.com/share?url=<?php echo Router::url(array('controller' => 'events', 'action' => 'view', $event['Event']['slug'],'city'=>$event['Venue']['City']['slug']),true); ?>&amp;text=<?php echo $event['Event']['title'];?>&amp;lang=en&amp;via=<?php echo Configure::read('site.name'); ?>" class="twitter-share-button"  data-count="none"><?php echo __l('Tweet!');?></a>
    					<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    				</li>
    				<li class="article-fb-share">
           				<li class="share-list">
                         <a href="http://www.facebook.com/sharer.php?u=<?php echo Router::url(array('controller' => 'events', 'action' => 'view', $event['Event']['slug']),true); ?>&amp;t=<?php echo $event['Event']['title']; ?>" target="_blank" class="fb-share-button"><?php echo __l('fbshare'); ?></a>
    				</li>
				</ul>
            <?php endif;?>
			
        	<?php 
				$guest_booking_closed_date = $event['GuestList']['website_close_date'] . " " . $event['GuestList']['website_close_time'];
				if($event['Event']['is_guest_list'] && (strtotime(_formatDate('Y-m-d H:i:s', strtotime($guest_booking_closed_date))) > strtotime(date("Y-m-d H:i:s"))) && !empty($event['GuestList']) && (!$event['GuestList']['guest_limit'] || ($event['GuestList']['guest_list_user_count'] < $event['GuestList']['guest_limit']))) {
    				if($this->Auth->user('id') and $this->Auth->user('id') != $event['Event']['user_id']) { 
						?>
						<div class="cancel-block">
						<?php
						 if($event['Event']['ticket_fee'] > 0) {
							 echo $this->Html->link(__l('Book Ticket'), array('controller' => 'guest_list_users', 'action'=>'add', $event['GuestList']['id']), array('class' => 'js-add js-guest-colorbox', 'title' => __l('Book Ticket')));
						 } else {
	    					echo $this->Html->link(__l('Guestlist'), array('controller'=>'guest_list_users','action'=>'add', $event['GuestList']['id']), array('class' => 'js-add js-guest-colorbox', 'title' => __l('Guestlist')));							
						 }
						 ?>
						</div>
						<?php
    				} 
				}
				if($event['Event']['is_guest_list'] and $this->Auth->user('id') and $this->Auth->user('id') == $event['Event']['user_id']) {
						?>
						<div class="cancel-block">
						<?php
						echo $this->Html->link(__l('Guestlist'), array('controller'=>'guest_list_users','action'=>'index', $event['GuestList']['id']), array('class' => 'js-add js-guest-colorbox', 'title' => __l('Guestlist')));
						?>
						</div>
						<?php
				}
            ?>
        </div>
    	<div class="clearfix form-content-block">
       		<h2><span><?php echo __l('People attending the events');?></span></h2>
          	<?php echo $this->element('event_users-index', array('event_id' => $event['Event']['id'],'cache' => array('key' => $event['Event']['id'], 'config' => 'sec')));?>
        </div>

    <div class="tabs-content-block comm" id="reviews">
		<div class="form-content-block phots-view-block">
				<h2><span><?php echo $this->Html->cText($event['Venue']['name']) . ' ' . $this->Html->cText($event['Venue']['City']['name']);?></span> <span class="title">, <?php echo sprintf(__l('Reviews From %s Users'), Configure::read('site.name'));?></span></h2>
				<?php if(!$this->Auth->sessionValid()): ?>
					<p><?php echo Configure::read('site.name'); ?><?php echo __l(' members: please'). ' '; ?><?php echo $this->Html->link(__l('log in'), array('controller' => 'users', 'action' => 'login'), array('class' => 'login', 'title' => __l('Login'), 'escape' => false)); ?></p>
					<p><?php echo __l('Guests: Please ') . $this->Html->link(__l('create an account'), array('controller' => 'users', 'action' => 'joinus'), array('class' => 'login', 'title' => __l('create an account'), 'escape' => false)); ?><?php echo __l(' or enter your review below.'); ?></p>
					<p><?php echo __l('(Only registered users can edit their reviews)'); ?></p>
				<?php endif; ?>
				<?php
				echo $this->element('../event_comments/add'); ?>
			</div>
		<?php
			echo $this->element('event_comments-index', array('event_id' => $event['Event']['id'], 'cache' => array('key' => $event['Event']['id'], 'config' => '2sec')));
			 if($this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
              <div class="admin-tabs-block form-content-block">
		          <div class="js-tabs">
			         <ul class="clearfix menu-tabs">
        				<li><?php echo $this->Html->link(__l('Event Photo Galleries'), array('controller' => 'photo_albums', 'action' => 'index','event_photo' => $event['Event']['slug'], 'admin' => true), array('title' => __l('Event Photo Galleries'), 'escape' => false)); ?></li>
        				<li><?php echo $this->Html->link(__l('Event Videos'), array('controller' => 'videos', 'action' => 'index', 'event_video' => $event['Event']['slug'], 'admin' => true), array('title' => __l('Event Videos'), 'escape' => false)); ?></li>
        				<li><?php echo $this->Html->link(__l('Event Reviews'), array('controller' => 'event_comments', 'action' => 'index','event_comment' => $event['Event']['slug'], 'admin' => true), array('title' => __l('Event Reviews'), 'escape' => false)); ?></li>
			         </ul>
		          </div>
		          </div>
	        <?php endif; ?>
	</div>

