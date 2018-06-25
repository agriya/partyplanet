<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<ol class="sub-menu">
    <?php
    if (!empty($cities)):
    foreach ($cities as $city):
    		$url=Router::url('/',true);
    		$url.=$city['City']['slug'];
    ?>
    	<li>
    		<?php echo $this->Html->link(__l($city['City']['name']), $url, array('title'=>__l($city['City']['name']),'escape' => false));?>
    	</li>
    <?php
        endforeach;
    endif;
    ?>
</ol>

