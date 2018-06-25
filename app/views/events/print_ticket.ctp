<div style="border:1px solid #e4e4e4; width:700px; margin:0 auto; font:14px/18px tahoma">
    <div align="center">
        <div align="left">
            <img src="<?php echo Router::url('/', true); ?>/img/theme/<?php echo Configure::read('site.theme'); ?>/logo.png">
        </div>
        <div align="left" style="width:600px">
            <h3><?php echo __l('Ticket').' #'. $guestListUser['GuestListUser']['id']; ?></h3>
        </div>
        <div align="center">
            <h2><?php echo $this->Html->link($this->Html->cText($guestListUser['GuestList']['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $guestListUser['GuestList']['Event']['slug'])); ?> @ <?php echo $this->Html->link($this->Html->cText($guestListUser['GuestList']['Event']['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $guestListUser['GuestList']['Event']['Venue']['slug'])); ?></h2>
         </div>       
        <div align="justify" style="width:400px;">
            <dl style="line-height:20px;">
                 <dt style="float:left"><b><?php echo __l('Event Date'); ?></b></dt>
                   <dd style="padding:0 0 0 55px"><?php echo $this->Html->cDate($guestListUser['GuestList']['Event']['start_date']) . ' ' . $this->Html->cTime($guestListUser['GuestList']['Event']['start_time']) . ' - ' . $this->Html->cDate($guestListUser['GuestList']['Event']['end_date']) . ' ' . $this->Html->cTime($guestListUser['GuestList']['Event']['end_time']); ?></dd>
                   <br/>
                 <dt style="float:left"><b><?php echo __l('No of Guests'); ?></b></dt>
                   <dd style="padding:0 0 0 55px"><?php echo $this->Html->cInt($guestListUser['GuestListUser']['in_party_count']);; ?></dd>
            </dl>
            </div>
    <div align="center" style="padding:20px 0 20px 0">&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</div>

    </div>
</div>