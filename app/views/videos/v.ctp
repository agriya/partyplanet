<?php /* SVN: $Id: v.ctp 1644 2009-08-14 17:37:32Z vinothraja_091at09 $ */ ?>
<div class="photos view clearfix">

		<h2 class="title"><?php echo $this->Html->cText($video['Video']['title']); ?></h2>
		<div class="main-content-block clearfix">


                                		<div class="full-view-block clearfix" ></div>
                                			<div id="video-player" class="view-image-block">
                                				<?php echo $this->Html->link($video['Video']['title'], array('controller' => 'videos', 'action' => 'v', '?' => array('width' => '575', 'height' => '350', 'wmode' => 'transparent', 'allowfullscreen' => 'true', 'name' => 'video_player'), 'slug' => $video['Video']['slug'], 'view_type' => ConstVideoViewType::EmbedAutoPlayView) , array('class' => 'js-flash')); ?>
                                			</div>
                                		<div class="blog-description video-description">
                                    	<?php    echo nl2br($this->Html->cText($video['Video']['description'])); ?>
                                    	</div> 
										   <h4 class="side2-title"><span><?php
                                    echo __l('Category'); ?></span></h4><?php
                                                echo $this->Html->link($this->Html->cText($video['VideoCategory']['name']) , array(
                                                    'controller' => 'videos',
                                                    'action' => 'index',
                                                    'category'=>$video['VideoCategory']['slug']
                                                ) , array(
                                                    'escape' => false
                                                )); ?>
										<div class="photo-section clearfix" >
                                        <h4 class="side2-title"><span><?php
                                    echo __l('Tags'); ?></span></h4>
                                        <ul class="tags clearfix" id="tags">
                                        <?php
                                        if (!empty($video['VideoTag'])) {
                                            foreach($video['VideoTag'] As $photo_tag) {
                                    ?>
                                                    <li><?php
                                                echo $this->Html->link($this->Html->cText($photo_tag['name']) , array(
                                                    'controller' => 'videos',
                                                    'action' => 'index',
                                                    'tag'=>$photo_tag['slug']
                                                ) , array(
                                                    'escape' => false
                                                )); ?></li>
                                                <?php
                                            }
                                        } else {
                                    ?>
                                                <li class="notice"><?php
                                            echo __l('No tags added'); ?></li>
                                            <?php
                                        }
                                    ?>
                                        </ul>
                                        </div>
										
										<?php
                                        if ($video['User']['id'] == $this->Auth->user('id')) {
                                    ?>
                                        		<div class="tag-block clearfix">
                                                    <?php
                                            echo $this->Html->link(__l('Edit') , array(
                                                'action' => 'edit',
                                                $video['Video']['id']
                                            ) , array(
                                                'class' => 'edit js-edit',
                                                'title' => __l('Edit')
                                            )); ?>
                                                    <?php
                                            echo $this->Html->link(__l('Delete') , array(
                                                'action' => 'delete',
                                                $video['Video']['id']
                                            ) , array(
                                                'class' => 'delete js-delete',
                                                'title' => __l('Delete')
                                            )); ?>
                                                </div>
                                            <?php
                                        }
                                    ?>
										
                                                    <div class="js-rating-display">
                                                        <?php
                                                     	$average_rating = (!empty($video['Video']['video_rating_count'])) ? ($video['Video']['total_ratings']/$video['Video']['video_rating_count']) : 0;
                                                echo $this->element('_star-rating-video', array(
                                                    'video_id' => $video['Video']['id'],
                                                    'current_rating' => $average_rating,
                                    			    'canRate' => ($video['Video']['user_id'] != $this->Auth->user('id')) ? 1 : 0
                                                ));
                                                        ?>
                                                    </div>
                                     <?php
                                        
                                             if($this->Auth->sessionValid()):
                                                echo $this->element('../video_comments/add');
                                             else:
                                            	?>
                                            	<div id="photo-comments-login">
                                            	<?php echo $this->Html->link(__l('Login'), array('controller' => 'users', 'action' => 'login', 'admin'=>false)); ?> <?php echo __l('to leave a comment'); ?>
                                            	</div>
                                            	<?php  endif;
												
													echo $this->element('video_comments-index', array(
                                                'cache' => array('config' => 'sec')
                                            ));
												?>

        					<div class="event-option clearfix">
						<ul>
							<li class='twitter'><a href="http://twitter.com/share?url=<?php echo Router::url('/',true).$this->request->url; ?>&amp;text=<?php echo $video['Video']['title'];?>&amp;lang=en&amp;via=<?php echo Configure::read('site.name'); ?>" ><?php echo __l('Tweet!');?></a></li>
							<li class="article-fb-share">
								<a href="http://www.facebook.com/sharer.php?u=<?php echo Router::url(array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']),true); ?>&amp;t=<?php echo $video['Video']['title'];?>" target="_blank" class="fb-share-button"><?php echo __l('fbshare'); ?></a>
							</li>
						</ul>
					</div>


    	</div>
		<div class='clearfix'><?php echo $this->element('video_category-index', array('cache' => array('config' => 'sec'))); ?></div>
	</div>