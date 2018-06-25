<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="cities index">
    <h2><?php echo __l('Select your city');?></h2>
    <div class="clearfix cities-index-block">
    <?php
    if (!empty($countries)):

    $i = 0;
    foreach ($countries as $country):
    	if(!empty($country['City'][0]['name'])) {
    ?>
    <div class="cities-list grid_left">
    <h3> <?php echo $country['Country']['name']; ?></h3>
   <ol class="cities-name clearfix">
    <?php
    	foreach($country_cities As $city) {
    		if($city['City']['country_id'] == $country['Country']['id']) {
    		$url=Router::url('/',true);
    		$url.=$city['City']['slug'];
    	?>
    	<li>
    		<?php echo $this->Html->link(__l($city['City']['name']),$url, array('title'=>__l($city['City']['name']), 'class'=>"js-set-city-cookie {'slug':'".$city['City']['slug']."'}",'escape' => false));?>
    	</li>
    	<?php
    		}
    	}
    	?>
    </ol>
    </div>
    <?php } ?>
    <?php  endforeach; ?>
    <?php endif;
    ?>
    </div>
</div>