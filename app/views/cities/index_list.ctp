<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
	<h2><?php echo __l('Search By');?></h2>
<div class="clearfix  country_flag cities-content">
    <?php
    if (!empty($countries)):

    $i = 0;
    foreach ($countries as $country):
    ?>
    <div  class='flag-image'>
    <?php
    		echo $country['Country']['name'];
    ?>

    </div>
    <?php  endforeach; ?>

    <?php endif;
    ?>
</div>
<div class="cities index">
	<h2> <?php echo __l('City Directory');?></h2>
    <ol class="clearfix">
        <?php
        if (!empty($cities)):
        foreach ($cities as $city):
        		$url=Router::url('/',true);
        		$url.=$city['City']['slug'];
        ?>
        	<li class='clearfix'>
        		<p><?php echo $this->Html->link(__l($city['City']['name']), $url, array('title'=>__l($city['City']['name']),'escape' => false));?></p>
        	</li>
        <?php
            endforeach;
        endif;
        ?>
    </ol>
</div>
