<?php
/**
 * Party Planet
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    partyplanet
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class ArticleTagsController extends AppController
{
    public $name = 'ArticleTags';
    public function index() 
    {
        $this->pageTitle = __l('Article Tags');
        $conditions = array();
        if (!empty($this->request->params['named']['article_slug'])) {
            $conditions['Article.slug'] = $this->request->params['named']['article_slug'];
        }
        $articleTag = $this->ArticleTag->find('all', array(
            'recursive' => 1,
            'contain' => array(
                'Article' => array(
                    'fields' => array(
                        'Article.id',
                        'Article.slug',
                    ) ,
                    'conditions' => $conditions,
                )
            )
        ));
        $tag_arr = array();
        $tag_name_arr = array();
        foreach($articleTag as $articleTag) {
            $tag_arr[$articleTag['ArticleTag']['slug']] = count($articleTag['Article']);
            $tag_name_arr[$articleTag['ArticleTag']['slug']] = $articleTag['ArticleTag']['name'];
        }
        $this->set('tag_arr', $tag_arr);
        $this->set('tag_name_arr', $tag_name_arr);
    }
}
?>