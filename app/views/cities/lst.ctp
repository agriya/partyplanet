<?php
	if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'user_profile'):?>
        <?php if(!empty($cities)):?>
    	<div class="required">
    		 <?php echo $this->Form->input('UserProfile.city_id', array('options' => $cities, 'empty' => __l('Please Select'), 'type' => 'select'));?>
    	</div>
    	 <?php endif;
            else:
               echo $this->Form->input('City.city_id', array('options' => $cities, 'empty' => __l('Please Select'), 'type' => 'select'));
	 endif;?>
