<?php


namespace app\common\business;

use app\common\model\mysql\Category as CategoryModel;

class CategoryBis extends BaseBis
{
    public $model = null;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    public function add($data)
    {
        $data['status'] = config("status.mysql.table_normal");
        $category = $this->model->getNormalByName($data['name']);
        if (!empty($category)) {
            throw new \think\Exception('栏目名称已存在');
        }
        $result = $result = $this->model->save($data);
        if (empty($result)) {
            return false;
        }
        return true;
    }

    public function getNormalCategorys()
    {
        $flied = "id, name, pid";
        $categorys = $this->model->getNormalCategorys($flied);
        if (!$categorys) {
            return [];
        }
        $categorys = $categorys->toArray();

        return $categorys;
    }

    public function getLists($data, $offset = 0)
    {
        $categoryList = $this->model->getLists($data, $offset);
        if (!$categoryList) {
            return \app\common\lib\Arr::paginateData($offset);
        }
        $categoryList = $categoryList->toArray();

        $pids = array_column($categoryList['data'],'id');
        $childCountRes = $this->model->getChildInPids(['pids' => $pids]);
        $childCountRes = $childCountRes->toArray();
        $idCounts = [];
        foreach ($childCountRes as $childCount) {
            $idCounts[$childCount['pid']]  = $childCount['count'];
        }
        if($categoryList['data']) {
            foreach ($categoryList['data'] as $key => $val) {
                $categoryList['data'][$key]['childCount'] = $idCounts[$val['id']] ?? 0;
            }
        }
        return $categoryList;
    }

    public function del($data)
    {
        $category = $this->model->getNormalById($data['id']);
        if (empty($category)) {
            throw new \think\Exception('栏目不存在');
        }
        $result = $this->deleteData($data['id']);
        return $result;
    }

    public function listorder($id, $listorder)
    {

        $category = $this->model->getNormalById($id);

        if (empty($category)) {
            throw new \think\Exception('栏目不存在');
        }
        if ($category['listorder'] == $listorder) {
            throw new \think\Exception('排序修改前和修改后一样没有意义哦');
        }
        $save = [
            'listorder' => $listorder
        ];
        $result = $this->model->updateById($id, $save);
        if (empty($result)) {
            return false;
        }
        return true;
    }

    public function status($id, $status)
    {
            $category = $this->model->getNormalById($id);
        if (empty($category)) {
            throw new \think\Exception('栏目不存在');
        }
        if ($category['status'] == $status) {
            throw new \think\Exception('状态修改前和修改后一样没有意义哦');
        }
        $save = [
            'status' => $status
        ];
        $result = $this->model->updateById($id, $save);
        if (empty($result)) {
            return false;
        }
        return true;
    }

    public function getNormalByPid($pid = 0)
    {
        $field = "id, name, pid";
        $categorys = $this->model->getNormalByPid($pid, $field);
        if(empty($categorys)) {
            return [];
        }
        $categorys = $categorys->toArray();
        return $categorys;
    }

    public function getNormalInIds($ids = [0])
    {
        $field = "id, name, icon";
        $categorys = $this->model->getNormalInIds($ids, $field);
        if(empty($categorys)) {
            return [];
        }
        $categorys = $categorys->toArray();
        return $categorys;
    }

    public function getNormalInPids($pids = [0])
    {
        $field = "id, name, pid";
        $categorys = $this->model->getNormalInPids($pids, $field);
        if(empty($categorys)) {
            return [];
        }
        $categorys = $categorys->toArray();
        return $categorys;
    }

    public function search($id)
    {
        $result = [
            "name" => "我是一级分类",
            "focus_id" => [1,11],
            "list" => [

            ]
        ];

        return $result;
    }

    public function subcategory()
    {
        $result = [
            ["id" => 21, "name" => "点二到三级分类1"],
            ["id" => 22, "name" => "点二到三级分类2"],
            ["id" => 33, "name" => "点二到三级分类3"],
        ];
        return $result;
    }
}