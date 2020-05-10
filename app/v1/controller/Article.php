<?php


namespace app\v1\controller;

use app\v1\business\ArticleBis;

class Article extends CommonController
{
    /*
     * 文章列表
     */
    public function list()
    {
        $articleSeBusiness = new ArticleBis();
        $articleList = $articleSeBusiness->articleList();

        return show(config("status.success"),"ok", $articleList);
    }

    /*
     * 文章详情
     */
    public function detail()
    {
        $articleId = input('param.article_id', 0, 'intval');
        $data = [
            'article_id' => $articleId
        ];
        try {
            $articleBusiness = new ArticleBis();
            $articleDetail = $articleBusiness->articleDetail($data);
            if(empty($articleDetail)){
                return show(config("status.success"),"数据为空", []);
            }
            $articleBusiness->incClick($data);
        }catch (\Exception $e) {
            return show(config("status.error"),$e->getMessage(), null);
        }
        return show(config("status.success"),"ok", $articleDetail);
    }

    public function comment()
    {
        $articleId = input('param.article_id', 0, 'intval');

        $data = [
            'article_id' => $articleId
        ];
        try {
            $articleBusiness = new ArticleBis();
            $articleComment = $articleBusiness->getCommentsList($data);
            return show(config("status.success"),"ok", $articleComment);
        }catch (\Exception $e) {
            return show(config("status.error"),$e->getMessage(), null);
        }
    }

    public function add()
    {
        $title = input('param.title', '', 'trim');
        $content = input('param.content', '', 'trim');
        $uid = $this->userInfo['id'];

        $data = [
            'title' => $title,
            'content' => $content,
            'uid' => $uid,
            'operate' => $uid
        ];
        try {
            $result = (new ArticleBis)->add($data);
        }catch (\Exception $e) {
            return show(config("status.error"),$e->getMessage(), null);
        }
        if($result) {
            return show(config("status.success"),"添加成功", null);
        }
        return show(config("status.error"),"添加失败", null);
    }

    public function update()
    {
        $articleId = input('param.article_id', 0, 'intval');
        $title = input('param.title', '', 'trim');
        $content = input('param.content', '', 'trim');
        $uid = $this->userInfo['id'];

        $data = [
            'operate' => $uid,
            'article_id' => $articleId,
            'title' => $title,
            'content' => $content,
        ];
        try {
            $result = (new ArticleBis)->delete($data);
        }catch (\Exception $e) {
            return show(config("status.error"),$e->getMessage(), null);
        }
        if($result) {
            return show(config("status.success"),"修改成功", null);
        }
        return show(config("status.error"),"修改失败", null);

    }

    public function delete()
    {
        $articleId = input('param.article_id', 0, 'intval');
        $uid = $this->userInfo['id'];
        $uid = 1;

        $data = [
            'operate' => $uid,
            'article_id' => $articleId,
        ];
        try {
            $result = (new ArticleBis)->delete($data);
        }catch (\Exception $e) {
            return show(config("status.error"),$e->getMessage(), null);
        }
        if($result) {
            return show(config("status.success"),"删除成功", null);
        }
        return show(config("status.error"),"删除失败", null);
    }
}