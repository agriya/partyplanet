<div class="js-response">
<h3><?php echo __l('News');?></h3>
<ol class="list feature-list clearfix">
<?php
if (!empty($articles)):
$i = 0;
foreach ($articles as $article):
$class = null;
	if ($i++ % 2 == 0){
	$class = 'altrow';
					}
?>
	<li class="clearfix <?php echo $class; ?>">
			<div class="grid_3 omega alpha">
                <?php
        		echo $this->Html->link($this->Html->showImage('Article', $article['Attachment'], array('dimension' => 'sidebar_thumb','title'=>$this->Html->cText($article['Article']['title'],false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($article['Article']['title'],false)))), array('controller' => 'articles', 'action' => 'view',   $article['Article']['slug'],'admin'=>false), array('title'=>$this->Html->cText($article['Article']['title'],false),'escape' => false), null, array('inline' => false));
                ?>
            </div>
			<div class="grid_5 omega alpha ">
				<h3><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($article['Article']['title'],false),25), array('controller' => 'articles', 'action' => 'view',$article['Article']['slug']), array('class' => '', 'title'=>$this->Html->cText($article['Article']['title'],false),'escape' => false)); ?></h3>
				<p>
				<span><?php echo __l('Posted On'); ?></span>
				<span class="date">
					<?php echo $this->Html->cDateTimeHighlight($article['Article']['created']); ?>
				</span>
		        </p>
			</div>
			</li>
					<?php
					endforeach;
				else:
			?>
			<li>
					<p class="notice"><?php echo __l('No Articles available');?></p>
			</li>
			<?php
				endif;
			?>
		</ol>
			<div class="js-pagination">
				<?php
					if (!empty($articles)) {
						echo $this->element('paging_links');
					}
				?>
			</div>

</div>