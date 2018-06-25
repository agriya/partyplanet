<div>
<h2>Categories</h2>
<ul>
<?php
foreach($videoCategories as $category):
?>
<li><?php
    echo $this->Html->link($category['VideoCategory']['name'], array(
        'controller' => 'videos',
        'action' => 'index',
        'type' => 'new_videos',
        'keyword' => 'video_lst',
        'key' => $category['VideoCategory']['slug'],
        'admin' => false
    ) , null, array('inline' => false));
?></li>
<?php
endforeach;
?>
</ul>
</div>