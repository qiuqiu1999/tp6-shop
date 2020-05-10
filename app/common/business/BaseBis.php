<?php


namespace app\common\business;


class BaseBis
{
    // 添加数据
    public function add($data)
    {
        $data['status'] = config("status.mysql.table_normal");
        $result = $this->model->save($data);
        if (empty($result)) {
            return false;
        }
        return $this->model->id;
    }

    // 删除数据
    public function deleteData($id) {
        if(empty(intval($id))) {
            return false;
        }
        $save = [
            'status' => config("status.mysql.table_delete")
        ];
        $result = $this->model->updateById($id, $save);
        if (empty($result)) {
            return false;
        }
        return true;
    }
}