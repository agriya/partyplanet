<?php
/* SVN: $Id: whats_new.ctp 5641 2009-07-30 08:42:02Z muthukumaran_084at09 $ */
$this->pageTitle = __l('What\'s new');
?>
<div id="breadcrumb">
		<?php echo $this->Html->addCrumb(__l('What\'s new')); ?>
		<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
 </div>
<div class="pages">
 <h2><?php echo $this->pageTitle;?></h2>
 <p class="notice">Coming soon</p>
</div>