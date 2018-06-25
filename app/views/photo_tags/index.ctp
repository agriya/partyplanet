        <h3><?php echo __l('Tags');?></h3>
        <div class="form-content-block tag-cloud clearfix">
            <?php
            if (!empty($photoTags)) :
            	foreach ($photoTags AS $photoTag) :
                        echo $this->Html->link($photoTag['PhotoTag']['name'], array('controller'=> 'photos', 'action'=> 'tag', $photoTag['PhotoTag']['slug']),array('class'=>$photoTag['PhotoTag']['class']));
                endforeach;
           	else :
                ?>
              	<p class="notice"><?php echo __l('Sorry, no tags found');?></p>
                <?php
           	endif;
            ?>
        </div>

