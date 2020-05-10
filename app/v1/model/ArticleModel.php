<?php


namespace app\v1\model;

use think\Model;
use app\v1\model\CommentModel;
/**
 *
 */
class ArticleModel extends Model
{
    protected $table = 'blog_article';
    protected $pk = 'id';

    protected static function init()
    {
        //TODO:初始化内容
    }

    /**
     * 关联Comment模型
     * @return \think\model\relation\HasMany
     */
    public function comments()
    {
        return $this->hasMany(CommentModel::class,'art_id');
    }

    /**
     * 获取文章列表
     * @return array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getArticleList($num = 2)
    {
        $where = [
            'status' => 1
        ];
        $articleList = $this->order("create_time", "desc")
            ->where($where)
            ->paginate($num);
        if(!$articleList) {
            return \app\common\lib\Arr::paginateData($num);
        }
        $articleList = $articleList->toArray();
        return $articleList;
    }

    /**
     * 根据文章id获取文章详情
     * @param $article_id
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getArticleDetailById($articleId)
    {
        $where = [
            'id' => $articleId,
            'status' => 1
        ];
        $articleDetail = $this->where($where)->find();
        if(!$articleDetail) {
            return [];
        }
        $articleDetail = $articleDetail->toArray();
        return $articleDetail;
    }



    /**
     * 获取文章评论
     * @param $articleId
     * @param int $num
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCommentsList($articleId, $num = 2)
    {
        $where = [
            'id' => $articleId
        ];
        $articleComment = $this->where($where)->find()->comments()->where("status", 1)->paginate($num);
        if(!$articleComment) {
            return \app\common\lib\Arr::paginateData($num);
        }
        $articleComment = $articleComment->toArray();
        return $articleComment;
    }
}
