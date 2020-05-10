<?php


namespace app\v1\model;

use think\Model;
use app\v1\model\UserModel;
/**
 * 
 */
class CommentModel extends Model
{
	protected $table = 'blog_article_comment';
	protected $pk = 'id';

	protected static function init()
    {
        //TODO:初始化内容
    }

}
