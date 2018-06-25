 <ul class="banner-list clearfix">
	<?php
if(!empty($articles)):
foreach ($articles as $article):
?>
<li>
<?php
    		echo $this->Html->link($this->Html->showImage('Article', $article['Attachment'], array('dimension' => 'normalhigh_thumb','title'=>$this->Html->cText($article['Article']['title'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($article['Article']['title'], false)))), array('controller' => 'artcless', 'action' => 'view',   $article['Article']['slug'],'admin'=>false), array('title'=>$this->Html->cText($article['Article']['title'], false),'escape' => false), null, array('inline' => false));
            ?>
 </li>
 <?php
			endforeach;
			endif;
			?>
</ul>
<?php 
if(!empty($articles)):
$i=0;
foreach ($articles as $article):
	$class= $i==0?'':'hide';
?>
<div class="banner-img <?php echo $class ?>"> 
<?php
    		echo $this->Html->link($this->Html->showImage('Article', $article['Attachment'], array('dimension' => 'home_slide_thumb','title'=>$article['Article']['title'],'alt'=>sprintf('[Image: %s]', $article['Article']['title']))), array('controller' => 'artcless', 'action' => 'view',   $article['Article']['slug'],'admin'=>false), array('title'=>$article['Article']['title'],'escape' => false), null, array('inline' => false));
            ?>

          <div class="banner-info">
           <?php echo $article['Article']['title'] ?>
          </div>
        </div>


 <?php
 $i++;
			endforeach;
			endif;
			?>
        