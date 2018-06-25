<?php if($this->request->params['named']['type'] !='home'){ ?>
	<div id="breadcrumb">
	<?php
	if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='type'):
		echo $this->Html->addCrumb(__l('Advanced Search by Type'));
	else:
      	echo $this->Html->addCrumb(__l('Advanced Search by Location'));
      	endif;
		 echo $this->Html->getCrumbs(' &raquo; ', __l('Search')); ?>
	</div>
	<div class="events form responses">
		<h2 class="title"><?php echo __l('Advanced ');?><span><?php echo __l(' Search');?></span></h2>
		<div class="form-content-block event-search-block clearfix">
		
			<?php echo $this->Form->create('Event', array('action' => 'index/type:search','id'=>'EventIndexTypeSearch', 'type' => 'POST', 'class' => 'normal', 'id' => 'EventSearch', 'enctype' => 'multipart/form-data')); ?>
             <fieldset class="group-block round-5">
                
                	<div class="clearfix input">
                	<span class="label-content dob-info"><?php echo __l('Events that start between');?></span>
    				<div class="js-datetime">
				        <?php echo $this->Form->input('start_date', array('type' => 'date', 'label' =>false, 'orderYear' => 'asc','dateFormat' => 'DMY H:m', 'minYear' => date('Y'), 'div' => false, 'empty' => __l('Please Select'))); ?>
     	            </div>
				
				</div>
				<span class="label-content dob-info"><?php echo __l('and');?></span>
				<div class="clearfix input">
   	                <div class="js-datetime">
				        <?php echo $this->Form->input('end_date', array('type' => 'date','label' =>false , 'orderYear' => 'asc', 'dateFormat' => 'DMY H:m','minYear' => date('Y'),'div' => false, 'empty' => __l('Please Select'))); ?>
    		      	</div>
				</div>
                     <?php
                echo $this->Form->input('event_category', array('label' => __l('Event Category'), 'empty' => 'All'));
				echo $this->Form->input('formtype', array('value' => 'search', 'type' => 'hidden'));
			?>
			</fieldset>
			<?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'location') { ?>
			
				  <fieldset class="group-block round-5">
                   <legend class="round-5"><?php echo __l('City'); ?></legend>
                   	<?php echo $this->Form->input('zipcode',array( 'label' => __l('ZIP code'))); ?>
			     	<div class="venue-type-block clearfix">
					<?php echo $this->Form->input('city', array('multiple' => 'checkbox','options' => $cities, 'label' => false)); ?>
				    </div>
				</fieldset>
			
			<?php } else { ?>
                  <fieldset class="group-block round-5">
                   <legend class="round-5"><?php echo __l('Event Scene Type'); ?></legend>
					 <div class="venue-type-block">
						<?php echo $this->Form->input('event_scene', array('multiple' => 'checkbox', 'options' => $eventScenes, 'label' => false)); ?>
					</div>
				</fieldset>
   			    <fieldset class="group-block round-5">
                   <legend class="round-5"><?php echo __l('Event Music Type'); ?></legend>
 					<div class="venue-type-block">
						<?php echo $this->Form->input('event_music', array('multiple' => 'checkbox', 'options' => $eventMusics, 'label' => false)); ?>
					</div>
    			</fieldset>
		
			<?php } ?>
			<div class="submit-block clearfix">
				<?php echo $this->Form->submit(__l('Search')); ?>
			</div>
                <?php echo $this->Form->end(); ?>
		</div>
	</div>
<?php } else { ?>
     <h3><?php echo __l('Find your party'); ?></h3>
     <div class="party-block">
     <h4 class="serch-by"><?php echo __l('Search by:'); ?></h4>
		<?php echo $this->Form->create('Event', array('action'=>'index/type:search','id'=>'EventIndexTypeSearch1', 'class' => 'party-search-form normal', 'enctype' => 'multipart/form-data')); ?>
		<ul class="party-list">
			<li>
				<a class="js-toggle-div {'divClass':'js-party-type'}" href="javascript:void(0);"><?php echo __l('Party Type'); ?></a>
				<div class="hide js-party-type clearfix">
					<?php echo $this->Form->input('event_scene', array('multiple' => 'checkbox', 'options' => $eventScenes, 'label' => false)); ?>
				</div>
			</li>
			<li>
				<a class="js-toggle-div {'divClass':'js-music-type'}" href="javascript:void(0);"><?php echo __l('Music Type'); ?></a>
				<div class="hide js-music-type clearfix">
					<?php echo $this->Form->input('event_music',array('multiple' => 'checkbox', 'options' => $eventMusics, 'label' => false)); ?>
				</div>
			</li>
		</ul>
	
		<div class="submit-block clearfix">
			<?php echo $this->Form->submit(__l('Search')); ?>
		</div>
	</div>
		<?php echo $this->Form->end(); ?>

         
  

<?php } ?>