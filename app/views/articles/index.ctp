<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<?php if (empty($this->request->params['requested']) && empty($this->request->params['isAjax'])): ?>
	<?php if (empty($this->request->params['requested']) && empty($this->request->params['isAjax']) && empty($this->request->params['prefix'])): ?>
		<div class="crumb">
			<?php
				$this->Html->addCrumb(__l('News'));
				echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
			?>
		</div>
	<?php endif; ?>
	<h2><?php echo __l('News'); ?></h2>
<?php endif; ?>
<div class="articles index js-response">
<?php if(empty($this->request->params['named']['type']) or $this->request->params['named']['type'] != 'home_more_news'):
 $this->Html->meta('rss', array('controller' => 'articles', 'action' => 'index', 'ext' => 'rss') , array('title' => 'RSS - ' . $this->pageTitle) , false); ?>
<div class="add-block1">
	<?php
		if (!empty($this->request->params['named']['tag'])):
			echo $this->Html->link('RSS', array('controller' => 'articles', 'action' => 'index', 'city' => $_prefixSlug, 'tag' => $this->request->params['named']['tag'],'ext' => 'rss') , array('target' => '_blank', 'class' => 'rss-link', 'title' => sprintf(__l('Subscribe to "%s"'), $this->pageTitle)));
		elseif (!empty($this->request->params['named']['category'])):
			echo $this->Html->link('RSS', array('controller' => 'articles', 'action' => 'index', 'city' => $_prefixSlug, 'category' => $this->request->params['named']['category'], 'ext' => 'rss') , array('target' => '_blank', 'class' => 'rss-link', 'title' => sprintf(__l('Subscribe to "%s"'), $this->pageTitle)));
		else:
			echo $this->Html->link('RSS', array('controller' => 'articles', 'action' => 'index', 'ext' => 'rss') , array('target' => '_blank', 'class' => 'rss-link', 'title' => sprintf(__l('Subscribe to "%s"'), $this->pageTitle)));
		endif;
	?>
</div>
<?php endif;?>
<?php
if($limit!='2'){ ?>
 <div class ="js-pagination">
	<ul class="menu-tabs clearfix">
          <li class="<?php echo (!isset($this->request->params['named']['category']))? 'active' : ''; ?>"><?php
          if(!empty($this->request->params['named']['type'])){
          echo $this->Html->link(__l('All'), array('controller' => 'articles', 'action' => 'index','type'=>$this->request->params['named']['type'],'admin' => false), array('title' => __l('All'),'rel' => 'address:/' . __l('All')));
          }else{
            echo $this->Html->link(__l('All'), array('controller' => 'articles', 'action' => 'index','admin' => false), array('title' => __l('All'),'rel' => 'address:/' . __l('All')));
          }
          ?></li>
          
          <?php foreach($homeArticleCategories as $homeArticleCategorie){?>
			<li class="<?php echo (isset($this->request->params['named']['category']) && $homeArticleCategorie['ArticleCategory']['slug'] == $this->request->params['named']['category']) ? 'active' : ''; ?>">
				<?php
				          if(!empty($this->request->params['named']['type'])){
				               echo $this->Html->link(__l($homeArticleCategorie['ArticleCategory']['name']), array('controller' => 'articles', 'action' => 'index','type'=>$this->request->params['named']['type'],'category'=>$homeArticleCategorie['ArticleCategory']['slug'],'admin' => false), array('title' => __l($homeArticleCategorie['ArticleCategory']['name'])));
				          }else{
				               echo $this->Html->link(__l($homeArticleCategorie['ArticleCategory']['name']), array('controller' => 'articles', 'action' => 'index','category'=>$homeArticleCategorie['ArticleCategory']['slug'],'admin' => false), array('title' => __l($homeArticleCategorie['ArticleCategory']['name'])));
                          }

				?>
			</li>
				  <?php } ?>
</ul>
</div>
<div class="clearfix">
<?php if(empty($this->request->params['named']['type']) or $this->request->params['named']['type'] != 'home_more_news'):?>
<div class="SortBox grid_right">
	       <?php 
			$url = Router::url(array('controller' => 'articles','action' => 'index','city'=>$this->request->params['named']['city']) , true);
				$url.='/index';
			if(!empty($this->request->params['named']['tag'])){
				$url.='/tag:'.$this->request->params['named']['tag'];
			}
			if(!empty($this->request->params['named']['type'])){
				$url.='/type:'.$this->request->params['named']['type'];
			}
			if(!empty($this->request->params['named']['category'])){
				$url.='/category:'.$this->request->params['named']['category'];
			}
            
			 echo $this->Form->input('sort_by', array('label'=>__l('Sort by:'), 'empty' => __l('All'), 'options' =>
        array('title'=>__l('Title'), 'date'=>__l('Date'),'comment'=>__l('Most Commented')),'class'=>"js-sort {'url':'".$url."'}"));
         
        ?>
      </div>
      <?php endif;?>
      </div>
<?php } ?>
<ol class="list feature-list article-list" >
<?php
if (!empty($articles)):
$i=0;
foreach ($articles as $article):
			$class = null;
			if ($i++ % 2 == 0) {
					$class = 'altrow';
			}
 if(!empty($this->request->params['named']['type']) and $this->request->params['named']['type'] == 'home_more_news'){
  $img_grid_class = 'grid_3 omega alpha';
  $thumb_dimention = "sidebar_thumb";
  $content_grid_class ="grid_left article-right alpha omega";
 }else{
   $img_grid_class = "grid_4 alpha";
   $thumb_dimention = "home_newest_thumb";
   $content_grid_class ="grid_12 omega";
 }

?>
          <li class="clearfix <?php echo $class; ?>">
            <div class="<?php echo $img_grid_class;?>"><?php
    		echo $this->Html->link($this->Html->showImage('Article', $article['Attachment'], array('dimension' => $thumb_dimention ,'title'=>$this->Html->cText($article['Article']['title'], false),'escape'=>false, 'alt'=>sprintf('[Image: %s]', $this->Html->cText($article['Article']['title'], false)))), array('controller' => 'articles', 'action' => 'view',   $article['Article']['slug'],'admin'=>false), array('title'=>$this->Html->cText($article['Article']['title'], false),'escape' => false), false, array('inline' => false));
            ?> </div>
            <div class="<?php echo $content_grid_class;?>">
               <?php if(empty($this->request->params['named']['type']) or $this->request->params['named']['type'] != 'home_more_news'):?>
                 <div class="clearfix">
                  <h3 class="grid_left">
                  <?php echo $this->Html->link($this->Html->cText($article['Article']['title'],false), array('controller' => 'articles', 'action' => 'view',$article['Article']['slug']), array('class' => '', 'title'=>$this->Html->cText($article['Article']['title'],false),'escape' => false)); ?>
                  </h3>
                   <p class="meta clearfix posted-date grid_right">
					<span class="publish"><?php echo __l('Posted On'); ?></span>
					<span class="date">
						<?php echo $this->Html->cDateTimeHighlight($article['Article']['created']); ?>
					</span>
			     </p>
			     </div>
			     <?php  else:?>
			     <h3><?php echo $this->Html->link($this->Html->cText($article['Article']['title'], false), array('controller' => 'articles', 'action' => 'view',$article['Article']['slug']), array('class' => '', 'title'=>$this->Html->cText($article['Article']['title'], false),'escape' => false)); ?></h3>
			     <?php endif;?>
                  <p><?php echo $this->Html->truncate($this->Html->cText(strip_tags($article['Article']['description']), false), 200);?></p>
                    <?php echo $this->Html->link(__l('read more'), array('controller' => 'articles', 'action' => 'view', $article['Article']['slug']), array('class' => 'read-more', 'title'=>__l('Read more'),'escape' => false)); ?>
                     <ul class="share-list article-share-list clearfix">
    					 <li>
        						<a href="http://twitter.com/share?url=<?php echo Router::url(array('controller' => 'articles', 'action' => 'view', $article['Article']['slug'], 'city' => $_prefixSlug),true); ?>&amp;text=<?php echo $article['Article']['title'];?>&amp;lang=en&amp;via=<?php echo Configure::read('site.name'); ?>" class="twitter-share-button"  data-count="none"><?php echo __l('Tweet!');?></a>
        						<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    					</li>
    					<li class="article-fb-share">
    						<a href="http://www.facebook.com/sharer.php?u=<?php echo Router::url(array('controller' => 'articles', 'action' => 'view',$article['Article']['slug']),true); ?>&amp;t=<?php echo $article['Article']['title']; ?>" target="_blank" class="fb-share-button"><?php echo __l('fbshare'); ?></a>
    					</li>
    					<li class="comments"><?php echo $this->Html->link($this->Html->cText(sprintf(__l('Comments'). ' [%s]',$article['Article']['article_comment_count'])), array('controller' => 'articles', 'action' => 'view',$article['Article']['slug'],'#comments'), array('title'=>__l('Comments'),'escape' => false)); ?></li>
                    </ul>
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
<?php
if (!empty($articles)){
?>   

          <div class="js-pagination">
			 <?php echo $this->element('paging_links'); ?>
          </div>
    
<?
	}
?>
</div>