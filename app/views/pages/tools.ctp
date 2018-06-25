<?php $this->pageTitle = __l('Tools'); ?>
<div>
	<div class="info-details">
		<?php echo __l('When cron is not working, you may trigger it by clicking below link. For the processes that happen during a cron run, refer the ') . $this->Html->link('product manual.', 'http://dev1products.dev.agriya.com/doku.php?id=partyplanet-install#manual_cron_update_process', array('target' => '_blank')); ?>
	</div>
	<div class="add-block1">
		<?php echo $this->Html->link(__l('Manually trigger cron to update guestlist and encode uploaded video'), array('controller' => 'crons', 'action' => 'main', '?f=' . $this->request->url, 'admin' => false), array('title' => __l('Trigger cron functions manually (If for some reasons, cron is not getting triggered, clicking this link will trigger the functionality.'), 'class' => 'js-trigger-cron')); ?>
	</div>
</div>