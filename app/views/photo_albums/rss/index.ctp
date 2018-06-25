<?php
if (!empty($photoAlbums)) :
    foreach($photoAlbums as $photoAlbum) :
        echo $this->Rss->item(array() , array(
            'title' => $photoAlbum['PhotoAlbum']['title'],
            'link' => array(
                'controller' => 'photo_albums',
                'action' => 'view',
                $photoAlbum['PhotoAlbum']['slug']
            ) ,
            'description' => '<p>' . $this->Html->cHtml($this->Html->truncate($photoAlbum['PhotoAlbum']['description'])) . '</p>',
            'createdDate' => $this->Html->cDateTime($photoAlbum['PhotoAlbum']['created'], false) ,
        ));
    endforeach;
endif;
?>