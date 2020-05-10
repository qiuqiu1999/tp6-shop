<?php

namespace app\v1\business;

use app\v1\model\ArticleModel;
use app\v1\model\UserModel;
use think\facade\Db;


class ArticleBusiness
{
    public $articleObj = null;

    public function __construct()
    {
        $this->articleObj = new ArticleModel();
    }

    /*
     * 文章列表
     */
    public function articleList()
    {
        try {
            $articleList = $this->articleObj->getArticleList();
        }catch (\Exception $e) {
            throw new \think\Exception('数据库内部异常1');
        }
        return $articleList;
    }

    /*
     *文章详情
     */
    public function articleDetail($data)
    {
        try {
            $articleDetail = $this->articleObj->getArticleDetailById($data['article_id']);
        }catch (\Exception $e) {
            throw new \think\Exception('数据库内部异常1');
        }
        return $articleDetail;
    }

    /*
     * 文章评论
     */
    public function getCommentsList($data)
    {
        try {
            $articleComment = $this->articleObj->getCommentsList($data['article_id']);
            if(!empty($articleComment['data'])) {
                $userObj = new UserModel();
                foreach ($articleComment['data'] as $key => $val) {
                    $user = $userObj->getUserById($val['uid']);
                    if(empty($user) || $user->status != config("status.mysql.table_normal")) {
                        $userInfo = [];
                    }else{
                        $userInfo = [
                            'nickname' => $user['nickname'],
                            'icon' => $user['icon'],
                        ];
                    }
                    $articleComment['data'][$key]['user'] = $userInfo;
                }
            }
        }catch (\Exception $e) {
            throw new \think\Exception('数据库内部异常1');
        }
        return $articleComment;
    }

    /*
     * 文章热度自增
     */
    public function incClick($data)
    {
        $where = [
            'id' => $data['article_id']
        ];
        try {
            $result = Db::table('blog_article')->where($where)->inc('click')->update();
        }catch (\Exception $e) {
            throw new \think\Exception('数据库内部异常1');
        }
        if($result) {
            return true;
        }
        return false;
    }

    /*
     * 添加文章
     */
    public function add($data)
    {
        try {
            $create = [
                'title' => $data['title'],
                'content' => $data['content'],
                'uid' => $data['uid'],
                'status' => 1
            ];
            $result = ArticleModel::create($create);
        }catch (\Exception $e) {
            throw new \think\Exception('数据库内部异常1');
        }
        if($result) {
            return true;
        }
        return false;
    }

    /*
     * 文章修改
     */
    public function update($data)
    {
        try {
            $articleDetail = $this->articleObj->getArticleDetailById($data['article_id']);
            if(empty($articleDetail)) {
                throw new \think\Exception('文章不存在');
            }
            $where = [
                'status' => 1,
                'id' => $data['article_id']
            ];
            $save = [
                'title' => $data['title'],
                'content' => $data['content'],
                'operate' => $data['operate'],
            ];
            $result = ArticleModel::update($save,$where);
        }catch (\Exception $e) {
            throw new \think\Exception('数据库内部异常1');
        }
        if($result) {
            return true;
        }
        return false;
    }

    /*
     * 文章删除
     */
    public function delete($data)
    {
        try {
            $articleDetail = $this->articleObj->getArticleDetailById($data['article_id']);
        }catch (\Exception $e) {
            throw new \think\Exception('数据库内部异常1');
        }
        if(empty($articleDetail)) {
            throw new \think\Exception('文章不存在');
        }
        $where = [
            'status' => 1,
            'id' => $data['article_id']
        ];
        $save = [
            'status' => -1,
            'operate' => $data['operate'],
        ];
        try {
            $result = ArticleModel::update($save,$where);
        }catch (\Exception $e) {
            throw new \think\Exception('数据库内部异常2');
        }
        if($result) {
            return true;
        }
        return false;
    }

}