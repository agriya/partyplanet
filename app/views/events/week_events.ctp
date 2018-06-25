<?php
if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='guest'){
?>
<div id="breadcrumb">
	<?php
		echo $this->Html->addCrumb(__l('Events') , array('controller' => 'events'));
		echo $this->Html->addCrumb(__l('GuestList'));
		echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); 
	?>
</div>
<div class="add-block">
<?php	echo $this->Html->link(__l('Add event'), array('action'=>'add'), array('class' => 'add', 'title' => __l('Add event')));?>
 </div>
<div class="clearfix">
<h2 class="title venue-title">
	<?php echo __l('GuestList Events');	?>
</h2>
</div>
<?php 
$url = Router::url(array('controller' => 'events','action' => 'week_events','type'=>$this->request->params['named']['type']) , true);	
} else {
	$url = Router::url(array('controller' => 'events','action' => 'week_events') , true);
}?>


<?php
	
	
		if(!empty($this->request->params['named']['date']) && $this->request->params['named']['date']=='week'){
				$class="active";
			}else{
				$class="normal";
			}
?>
<ul id="js-calendar-event" class="events-list clearfix">
<li class="<?php  if (isset($this->request->params['named']['date']) and $this->request->params['named']['date']=='up-coming'){ echo 'active'; } else { echo "" ;} ?>">
					<?php echo $this->Html->link(__l('All'), 'javascript:void(0)', array('class'=> "js-calendar-event {'url':'" . $url ."', 'container':'js-response','date':'up-coming'}",'title'=>__l('All'),'escape' => false));?>

                 </li>
        <?php
		$today=time();
        for($i=0; $i<=6; $i++)
            {
               $viewdate=strtotime(date("Y-m-d", $today) . " +".$i." day");
                $dt=date('D',$viewdate);
				$dn=date('d',$viewdate);
	
			if(!empty($this->request->params['named']['date']) && $this->request->params['named']['date']==$viewdate) {
				$class="active";
			}else{
				$class="normal";
			}


	     ?>
                <li class="<?php  if (isset($this->request->params['named']['date'])) { echo $class; } else { if($i == 0 ) { echo 'active'; } }?>">
					<?php echo $this->Html->link($this->Html->cText($dt), 'javascript:void(0)', array('class'=> "js-calendar-event {'url':'" . $url ."', 'container':'js-response','date':'".$viewdate."'}",'title'=>sprintf('%s,%s',$dt,$dn),'escape' => false));?>
					
                 </li>
        <?php    }
		?>

    </ul>
    <table class="list">
     <tr>
        <th><?php echo __l('Date');?></th>
        <th><?php echo __l('Venue');?></th>
        <th><?php echo __l('Event');?></th>
      </tr>
	<?php if($events):
	$i=0;
				foreach($events as $event) :
					$class = null;
					if ($i++ % 2 == 0) {
						$class = 'altrow';
					}
	?>
	    <tr class="<?php echo $class; ?>">
		 <td><?php echo $this->Html->cDateTime($event['Event']['start_date'] . " " .$event['Event']['start_time']).__l(' to ').$this->Html->cDateTime($event['Event']['end_date'] . " " .$event['Event']['end_time']); ?></td>
	     <td><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($event['Venue']['name'],false),22), array('controller'=> 'venues', 'action' => 'view', $event['Venue']['slug']), array('title'=>$this->Html->cText($event['Venue']['name'],false),'escape' => false));?></td>
        <td><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($event['Event']['title'],false),22), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title'=>$this->Html->cText($event['Event']['title'],false),'escape' => false));?></td>

	  </tr>
	   <?php 
		 endforeach;
		 else: ?>
			<tr>
			  <td colspan="3" class="notice"><?php echo __l('No events available'); ?></td>
			</tr>
	  <? 
		  endif;
	  ?>
	</table>
         <div class=" js-pagination">
    	  <?php
    		if (!empty($events)) {
    			echo $this->element('paging_links'); ?>
    		   <?php
    		  }
    		?>
		  
         </div>

      

	 