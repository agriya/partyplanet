<?php /* SVN: $Id: $ */ ?>
<?php if (empty($this->request->params['prefix'])): ?>
	<div class="crumb-block">
		<?php $this->Html->addCrumb(__l('Events'), array('controller' => 'events', 'action' => 'index')); ?>
		<?php $this->Html->addCrumb(__l('Add Event')); ?>
		<?php if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
		 echo $this->Html->getCrumbs(' &raquo; ');
		 else:
		 echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
		 endif;?>
	</div>
<?php endif; ?>
<div class="events form responses">
	<?php if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) { ?>
         	<h2><?php echo __l('Add Event');?></h2>
	<?php } ?>
	<div class="form-content-block">
	<?php echo $this->Form->create('Event', array('class' => 'normal', 'enctype' => 'multipart/form-data')); ?>
		<!-- <fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Premium Services'); ?></legend>
			<h3><?php //echo __l('FEATURED STATUS'). ' ('. Configure::read('site.currency') . Configure::read('site.is_event_fetured_amount') . ' ' .  __l('one time fee'). ')'; ?></h3>
			<?php // echo $this->Form->input('is_featured', array( 'label' => __l('Check here to make this event FEATURED'))); ?>
			<h3><?php //echo __l('Generic Listing (without any options chosen)'); ?></h3>
			<?php //echo $this->Form->input('is_bump_up', array( 'label' => __l('check to upgrade') . ' (' . __l('one time fee of') . ' ' . Configure::read('site.currency') . Configure::read('site.is_event_bumpup_amount') . ')' )); ?>
		</fieldset> -->
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Venue Information'); ?></legend>
			<div class="clearfix">
                <span class="info">
				<?php
					echo __l('Type in the first few letters of the venue to find it in our database');
				?>
				</span>
    			<?php
					$venueurl = Router::url(array('controller' => 'venues', 'action' => 'list_venue', 'admin' => false), true);
					echo $this->Form->autocomplete('Venue.name', array('id'=>'EventVenueName','label' => __l('Venue Name'), 'acFieldKey' => 'Venue.id', 'acFields' => array('Venue.name'), 'acSearchFieldNames' => array('Venue.name'), 'maxlength' => '255'));
				?>
				<?php if ($this->Auth->user('user_type_id') != ConstUserTypes::User):?>
			     <span class="info">
					<?php
						echo __l('Couldn\'t find a venue?') . ' ';
						echo $this->Html->link(__l('Click here'), array('controller' => 'venues', 'action' => 'add', 'page' => 'event'), array('title' => __l('Click here'), 'escape' => false));
						echo ' ' . __l('to add a venue');
					?>
				</span>
				<?php endif;?>
				<div class='js-venue-select-div'></div>
			</div>
		</fieldset>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Main Event Information'); ?></legend>
			<?php 
				if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
					echo $this->Form->input('user_id', array( 'label' => __l('User')));
				endif;
				echo $this->Form->input('title', array('label' => __l('Event Name')));
				echo $this->Form->input('event_category_id', array( 'label' => __l('Event Category'), 'empty' => __l('Select Type')));
				echo $this->Form->input('venue_id', array('label' => 'Venue', 'type' => 'hidden'));
				echo $this->Form->input('is_online', array('class'=>'jsonline','id' => 'js-is-online', 'label' => __l('Online')));
				echo $this->Form->input('url', array('id' => 'js-url', 'label' => __l('URL'), 'info' => __l('eg. http://www.example.com'))); 
				echo $this->Form->input('description', array('label' => __l('Event Details')));
				echo $this->Form->input('dress_code');
				echo $this->Form->input('cover');
				echo $this->Form->input('door_policy');
				echo $this->Form->input('age_requirement_id', array('label' => __l('Age Requirement'),'type' => 'select', 'options' => $ageRrequirments));
			?>
			<div class="clearfix input required">
    				<div class="js-datetime">
				        <?php echo $this->Form->input('listing_appears_on_site', array('type' => 'date', 'orderYear' => 'asc','dateFormat' => 'DMY H:m', 'minYear' => date('Y'), 'div' => false, 'empty' => __l('Please Select'))); ?>
     	            </div>
				</div>
		</fieldset>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Tags'); ?></legend>
			<?php
				echo $this->Form->input('Tags', array('info' => __l('Add a few keywords or phrases that best describes your event')));
			?>
		</fieldset>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Guestlist'); ?></legend>
			<?php
				echo $this->Form->input('is_guest_list', array('label' => __l('Guest list'), 'id' => 'js_is_guest_list'));
			?>
			<div id="js-guestlist">
        			<?php echo $this->Form->input('GuestList.name', array('label' => __l('Name'))); ?>
                    <?php echo $this->Form->input('GuestList.details', array('label' => __l('Details'), 'type' => 'textarea')); ?>
                     <div  class="gust-list-section">
                    	<?php echo $this->Form->input('GuestList.guest_limit', array('label' => __l('Total number of signups allowed for this guestlist (set to 0 for unlimited)?'))); ?>
                    	<?php echo $this->Form->input('GuestList.maximum_guest_limit', array('type' => 'select', 'options' => $guestSignups, 'label' => __l('Maximum number of signups an individual memeber can make (beside themself)?'))); ?>
						<?php  if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){ ?>
                        <?php echo $this->Form->input('ticket_fee', array('id' => 'js-ticket_fee', 'label' => __l('Ticket Fee'), 'info' => __l('Leave it blank for free')));?>   <?php } ?>               
                        <div class="clearfix input required">
							<div class="js-datetime">
								<?php echo $this->Form->input('GuestList.website_close_date', array('type' => 'date', 'label' => __l('Time will the guestlist close on the website'), 'orderYear' => 'asc','dateFormat' => 'DMY H:m', 'minYear' => date('Y'), 'div' => false, 'empty' => __l('Please Select'))); ?>
							</div>
							 <div class="js-time">
								<?php echo $this->Form->input('GuestList.website_close_time', array('type' => 'time', 'label' => ' ', 'default' => date("H:i:s"))); ?>
							</div>
						</div>
                        <?php echo $this->Form->input('GuestList.guest_close_time', array('type' => 'time', 'default' => date("H:i:s"), 'label' => __l('Time will the guestlist close at the actual venue'))); ?>
                      </div>
                  	<?php echo $this->Form->input('GuestList.fax', array('label' => __l('Fax Guestlist to'))); ?>
                <?php echo $this->Form->input('GuestList.email', array('label'=> __l('Email Guestlist to'),'info'=>__l('input emails, each seperated by a comma, or leave blank to not email'))); ?>
               <?php if($is_paypal_required || !empty($this->request->data['UserProfile']['paypal_account'])) { ?>
			   <div class="hide js-paypal-block">
               <fieldset class="form-block">
				<h3>
				   <?php echo __l('PayPal Account'); ?>
				</h3>
				<?php $image_url = Router::url('/', true) . 'img/paypal-profile-currencies.png';?>
				<div class="page-info"><?php echo __l('Only verified PayPal account can receive funds. So, entered details will be validated with PayPal.
Also, your PayPal account must set to accept the site currency, which is ') . Configure::read('paypal.currency_code') . __l(' . Please follow the ') . $this->Html->link(__l('instruction to accept ') . Configure::read('paypal.currency_code'), $image_url, array('target' => '_blank'))  . __l(' , if not done already.');?></div>
				<?php
					echo $this->Form->input('UserProfile.paypal_account', array('label' => __l('PayPal Email'), 'info'=> __l('Verified PayPal Email')));
					echo $this->Form->input('UserProfile.paypal_first_name', array('label' => __l('PayPal First Name'), 'info'=> __l('As given in PayPal')));
					echo $this->Form->input('UserProfile.paypal_last_name', array('label' => __l('PayPal Last Name'), 'info'=> __l('As given in PayPal')));
				?>
			</fieldset>
			</div>
            <?php } ?>
			</div>
		</fieldset>
		<fieldset class="group-block round-5">
	       		<legend class="round-5"><?php echo __l('Event Dates'); ?></legend>
	       		<div class="clearfix input required">
    				<div class="js-datetime">
				        <?php echo $this->Form->input('start_date', array('type' => 'date', 'orderYear' => 'asc','dateFormat' => 'DMY H:m', 'minYear' => date('Y'), 'div' => false, 'empty' => __l('Please Select'))); ?>
				        </div>
					<div class="js-time">
						<?php echo $this->Form->input('start_time',array('label' => ' ', 'default' => date("H:i:s"))); ?>
					</div>
				    </div>
				<?php echo $this->Form->input('is_all_day', array('type' => 'hidden', 'value' => '0', 'label' => __l('Whole day'), 'id' => 'js-is-all-day')); ?>		
				<div class="clearfix input required">
   	                <div class="js-datetime">
				        <?php echo $this->Form->input('end_date', array('type' => 'date', 'orderYear' => 'asc', 'dateFormat' => 'DMY H:m','minYear' => date('Y'),'div' => false, 'empty' => __l('Please Select'))); ?>
    		      	</div>
                    <div class="js-time">
						<?php echo $this->Form->input('end_time', array('label' => false, 'default' => date("H:i:s"))); ?>
					</div>
				</div>
    	</fieldset>
		<?php if (!empty($eventSponsors)): ?>
			<fieldset class="group-block round-5">
				<legend class="round-5"><?php echo __l('Event Sponsors'); ?></legend>
				<div class="clearfix">
					<?php echo $this->Form->input('EventSponsor', array('type'=>'select', 'multiple'=>'checkbox', 'label' =>false,'id'=>'EventSponsor1')); ?>
				</div>
			</fieldset>
		<?php endif; ?>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Scene'); ?></legend>
			<div class="clearfix">
				<?php echo $this->Form->input('event_scene',array('multiple'=>'checkbox','options'=>$eventScenes, 'label'=>false));  ?>
			</div>
		</fieldset>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Music'); ?></legend>
			<div class="clearfix">
				<?php echo $this->Form->input('event_music',array('multiple'=>'checkbox','options'=>$eventMusics, 'label'=>false)); ?>
			</div>
		</fieldset>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Event Image'); ?></legend>
			<?php echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' => __l('Event image'),'info'=>__l('This is where you can make your event look attractive. Attach an image of your event which will help you get more people to join the event. Filename extension jpg,jpeg,gif,png permitted.'))); ?>
		</fieldset>
		<?php if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {  ?>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Admin Actions'); ?></legend>
			<?php
				echo $this->Form->input('is_feature');
			//	echo $this->Form->input('is_private');
				echo $this->Form->input('is_active',array('label'=>__l('Active?')));
			?>
		</fieldset>
		<?php } ?>
		<div class="submit-block clearfix">
        	<?php echo $this->Form->submit(__l('Insert Event')); ?>
        </div>
            <?php echo $this->Form->end(); ?>
</div>

</div>