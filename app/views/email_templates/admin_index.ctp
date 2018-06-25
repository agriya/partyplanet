<div class="js-tabs clearfix">
<?php
	if (!empty($emailTemplates)):
?>
	<ul class="clearfix">
<?php
		foreach ($emailTemplates as $emailTemplate):
?>		
			<li><?php echo $this->Html->link($this->Html->cText($emailTemplate['EmailTemplate']['name'], false), array('controller' => 'email_templates', 'action' => 'edit', $emailTemplate['EmailTemplate']['id']), array('escape' => false));?></li>		
<?php
		endforeach;
?>
	</ul>
<?php
	else:
?>
	<ul>
		<li><?php echo __l('No e-mail templates added yet.'); ?></li>
	</ul>
<?php
	endif;
?>	
</div>