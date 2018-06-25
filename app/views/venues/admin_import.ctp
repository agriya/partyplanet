<?php /* SVN: $Id: import.ctp 3972 2010-01-06 15:24:42Z senthilkumar_017ac09 $ */ ?>
<div class="deals form rightbar">
	<?php echo $this->Form->create('Venue', array('controller' => 'events', 'action'=> 'admin_import', 'class' => 'normal', 'enctype' => 'multipart/form-data'));?>
        <div class="info-details">
	       	 <p>
                <?php echo __l('Ensure your file has a '); ?>
                <?php echo __l('.XLS ' ) ;?>
                <?php echo __l('extension.' ) ;?>
            </p>
             <p>
                <?php echo __l('<span>Important</span>: Your file must be a valid XLS file in order for the import to work successfully.'); ?>
		        <?php echo $this->Html->link(__l("View Sample XLS File"),Router::url('/files/venue.xls',true),array('class'=>'download-link', 'target' => '_blank','escape' => false)); ?>
            </p>
        </div>
        <h3><?php echo __l('Choose your file from your desktop.'); ?></h3>
		<?php echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' => false)); ?>
		<?php if(!empty($type)):?>
		<?php echo $this->Form->input('type', array('type' => 'hidden', 'value' => $type)); ?>
		<?php endif;?>
        <div class="submit-block clearfix">
       	<?php echo $this->Form->submit(__l('Import'));?>
       	</div>
        <?php echo $this->Form->end(); ?>
</div>