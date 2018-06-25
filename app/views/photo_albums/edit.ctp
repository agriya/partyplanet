<?php /* SVN: $Id: edit.ctp 490 2009-07-06 10:28:29Z boopathi_23ag08 $ */ ?>
<div class="photoAlbums form">
<?php echo $this->Form->create('PhotoAlbum', array('class' => 'normal'));?>
		<fieldset>
			<?php
				echo $this->Form->input('id');
				if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
					echo $this->Form->input('user_id', array('empty' => __l('Please Select')));
				endif;
				echo $this->Form->input('title');?>
				<div class="clearfix input required">
    				<div class="js-datetime">
                    <?php echo $this->Form->input('captured_date', array('type' => 'date', 'orderYear' => 'asc','dateFormat' => 'DMY H:m', 'minYear' => date('Y')-100, 'div' => false, 'empty' => __l('Please Select'))); ?>
				    </div>
				</div>
				<?php echo $this->Form->input('description');
			?>
		</fieldset>
		<div class="submit-block clearfix">
        	<?php echo $this->Form->end(__l('Update Gallery'));?>
        </div>

</div>