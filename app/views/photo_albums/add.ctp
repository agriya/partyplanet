<?php /* SVN: $Id: add.ctp 489 2009-07-06 09:10:54Z boopathi_23ag08 $ */ ?>
<div class="photoAlbums  form">
	<?php if (empty($this->request->params['prefix'])): ?>
		<div class="breadcrumb">
			<?php
				$this->Html->addCrumb(__l('Galleries'), array('controller' => 'photo_albums', 'action' => 'index'));
				if (!empty($venue)):
					$this->Html->addCrumb($this->Html->cText($venue['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $venue['Venue']['slug']));
					$city_id = $venue['Venue']['city_id'];
				endif;
				if (!empty($event)):
					$this->Html->addCrumb($this->Html->cText($event['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $event['Event']['slug']));
				endif;
				$this->Html->addCrumb(__l('Create New Gallery'));
				if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
					echo $this->Html->getCrumbs(' &raquo; ');
				else:
					echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
				endif;
			?>
		</div>
		<h2><?php echo __l('Create New Gallery'); ?></h2>
	<?php endif; ?>
    <div class="form-content-block">
	<?php echo $this->Form->create('PhotoAlbum', array('class' => 'normal'));?>
		<fieldset>
			<?php
				if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
				echo $this->Form->autocomplete('User.username', array('label' => __l('User'), 'acFieldKey' => 'User.id', 'acFields' => array('User.username'), 'acSearchFieldNames' => array('User.username'), 'maxlength' => '255'));
				else:
					echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
				endif;
   				echo $this->Form->input('city_id', array('type' => 'hidden', 'value' => $city_id));
				if (!empty($this->request->data['PhotoAlbum']['venue_id'])):
					echo $this->Form->input('venue_id', array('type' => 'hidden', 'value' => $this->request->data['PhotoAlbum']['venue_id']));
					echo $this->Form->input('photoalbum_type', array('type' => 'hidden', 'value' => $this->request->params['named']['type']));
				elseif ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && !empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'venue'):
                    echo $this->Form->input('venue_id', array('empty' => __l('Please Select')));
				endif;
				if (!empty($this->request->data['PhotoAlbum']['event_id'])):
					echo $this->Form->input('event_id', array('type' => 'hidden', 'value' => $this->request->data['PhotoAlbum']['event_id']));
				elseif ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && !empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'event'):
                    echo $this->Form->input('event_id', array('empty' => __l('Please Select')));
				endif;
				echo $this->Form->input('title', array('label' => __l('Title')));?>
                <div class="clearfix input required">
    				<div class="js-datetime">
                    <?php echo $this->Form->input('captured_date', array('type' => 'date', 'orderYear' => 'asc','dateFormat' => 'DMY H:m', 'minYear' => date('Y')-100, 'div' => false, 'empty' => __l('Please Select'))); ?>
				    </div>
				</div>
    			<?php echo $this->Form->input('description', array('label' => __l('Description')));
				if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
					echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
				endif;
			?>
		</fieldset>
		<div class="submit-block clearfix">
        	<?php echo $this->Form->submit(__l('Insert New Gallery')); ?>
        </div>
        
            <?php echo $this->Form->end(); ?>
        </div>

</div>