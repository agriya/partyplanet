<?php /* SVN: $Id: paynow.ctp 1960 2010-05-21 14:46:46Z jayashree_028ac09 $ */ ?>
<div class="guestListUsers paynow">
<h2><?php echo __l('Confirm your Registration');?></h2>
	<?php echo $this->Form->create('Payment', array('action' => 'pay_now', 'class' => 'normal'));?>
	<?php echo $this->Form->input('GuestListUser.id', array('value' => $guestListUser['GuestListUser']['id'], 'type'=>'hidden'));
 ?>
<div class="clearfix grid_9 omega alpha">
      <dl class="list event-list clearfix">
        <dt><?php echo __l('Event Name:')?></dt>
        <dd><?php echo $this->Html->cText($guestListUser['GuestList']['Event']['title'],false); ?></dd>
        <dt><?php echo __l('Venue Name:')?></dt>
        <dd><?php echo $this->Html->cText($guestListUser['GuestList']['Event']['Venue']['name'],false); ?></dd> 
        <dt><?php echo __l('Time:')?></dt>
        <dd>
            <?php
                if($guestListUser['GuestList']['Event']['is_all_day']==1):
                    echo __l('Whole Day');
                else:
                    echo $this->Html->cTime($guestListUser['GuestList']['Event']['start_time']).' - '. $this->Html->cTime($guestListUser['GuestList']['Event']['end_time']);
                 endif;
            ?>
        </dd>               
        <dt><?php echo __l('Ticket Fee (').$guestListUser['GuestListUser']['in_party_count'].__l('*').$this->Html->cCurrency($guestListUser['GuestList']['Event']['ticket_fee']).') :';?></dt>
        <dd><?php echo $this->Html->siteCurrencyFormat($guestListUser['GuestList']['Event']['ticket_fee'] * $guestListUser['GuestListUser']['in_party_count']); ?>
        </dd>
    </dl>
    
    <div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Pay via PayPal')); ?>
    </div>

</div>
<?php echo $this->Form->end();?>
</div>