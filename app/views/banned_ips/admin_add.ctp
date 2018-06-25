<?php /* SVN: $Id: admin_add.ctp 1456 2010-04-28 08:53:26Z vinothraja_091at09 $ */ ?>
<div class="bannedIps form">

        <?php echo $this->Form->create('BannedIp', array('class' => 'normal clearfix'));?>
		<legend class="crumb"><?php echo $this->Html->link(__l('Banned Ips'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Banned Ip');?></legend>
     		<div class="ip-info">
     	        <span><?php echo __l('Your IP: ');?></span><?php echo $ip;?>
     	        <p>
            	<span><?php echo __l('Your Hostname: ');?></span><?php echo gethostbyaddr($ip);?>
            	</p>
        	</div>
        	<div>
                <h3><?php echo __l('Type');?></h3>
                <?php echo $this->Form->input('type_id', array('type' => 'radio', 'label' => __l('Select method'),'legend' => false));?>
                <?php echo $this->Form->input('address', array('label' => __l('Address/Range'))); ?>
                <?php echo $this->Form->input('range', array('label' => '', 'after' => __l('(IP address, domain or hostname)'))); ?>
            </div>
        	<div class="banned-info">
            	<h3><?php echo __l('Possibilities:'); ?></h3>
               	<div>
        			<p><?php echo __l('- Single IP/Hostname: Fill in either a hostname or IP address in the first field.'); ?></p>
        			<p><?php echo __l('- IP Range: Put the starting IP address in the left and the ending IP address in the right field.'); ?></p>
        			<p><?php echo __l('- Referer block: To block google.com put google.com in the first field. To block google altogether.'); ?></p>
                </div>
            </div>
            <?php
        		echo $this->Form->input('reason', array('info' => __l('(optional, shown to victim)')));
        		echo $this->Form->input('redirect', array('info' => __l('(optional)')));
        		echo $this->Form->input('duration_id', array('label' => __l('How long')));
        		echo $this->Form->input('duration_time', array('label' => '', 'info' => __l('Leave field empty when using permanent. Fill in a number higher than 0 when using another option!')));
        	?>
        	<div class="banned-info">
        		<h3><?php echo __l('Hints and tips:'); ?></h3>
        		<div>
            		<p><?php echo __l('- Banning hosts in the 10.x.x.x / 169.254.x.x / 172.16.x.x or 192.168.x.x range probably won\'t work.'); ?></p>
            		<p><?php echo __l('- Banning by internet hostname might work unexpectedly and resulting in banning multiple people from the same ISP!'); ?></p>
            		<p><?php echo __l('- Wildcards on IP addresses are allowed. Block 84.234.*.* to block the whole 84.234.x.x range!'); ?></p>
            		<p><?php echo __l('- Setting a ban on a range of IP addresses might work unexpected and can result in false positives!'); ?></p>
            		<p><?php echo __l('- An IP address always contains 4 parts with numbers no higher than 254 separated by a dot!'); ?></p>
            		<p><?php echo __l('- If a ban does not seem to work try to find out if the person you\'re trying to ban doesn\'t use <a href="http://en.wikipedia.org/wiki/DHCP" target="_blank">DHCP.</a>'); ?></p>
            		<p><?php echo __l('- A temporary ban is automatically removed when it expires.'); ?></p>
            		<p><?php echo __l('- To block a domain you can use keywords. Just blocking "meandmymac" would work almost the same as blocking "meandmymac.net". However, when putting just \'meandmymac\', ALL extensions (.com .net .co.ck. co.uk etc.) are blocked!!'); ?></p>
            		<p><?php echo __l('- For more questions please seek help at my <a href="http://forum.at.meandmymac.net/" target="_blank">support pages.</a>'); ?></p>
                </div>
        	</div>
        <div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add'));?>
		</div>
				<?php echo $this->Form->end();?>
 
</div>