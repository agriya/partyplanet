<?php
if (!empty($photos)) :
   foreach($photos as $photo) :
                 $photo_image = '';
					if(!empty($photo['Attachment'])):
					$photo_image = $this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => 'home_newest_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)), 'title' => $this->Html->cText($photo['Photo']['title'], false)));
					endif;
					$photo_image = (!empty($photo_image)) ? '<p>'.$photo_image.'</p>':'';

					echo $this->Rss->item(array() , array(
                            'title' => $photo['Photo']['title'],
                            'link' => array(
                                'controller' => 'photos',
                                'action' => 'view',
                                $photo['Photo']['slug']
                            ) ,
                          'description' => $photo_image.'<p>'.$photo['Photo']['description'].'</p>'
                        ));
        
    endforeach;
endif;
?>