<?php

//declare(strict_types=1);

namespace app\common\lib;

/**
 * 数组类库
 * Class Arr
 * @package app\common\lib
 */
class Arr
{
    /**
     * 返回Tree结构数据
     * @param $data
     * @return array|bool
     */
//    public static function getTree($data)
//    {
//        if (empty($data)) {
//            return false;
//        }
//        $items = [];
//        foreach ($data as $val) {
//            $items[$val['category_id']] = $val;
//        }
//        $tree = [];
//        foreach ($items as $key => $val) {
//            if(isset($items[$val['pid']])) {
//                $items[$val['pid']]['list'][] = &$items[$key];
//            } else {
//                $tree[] = &$items[$key];
//            }
//        }
//        return $tree;
//    }

    /**
     * 返回树状数据
     * @param $data
     * @param int $pid
     * @return array|bool
     */
    public static function getTree($data, $pid = 0) {
        if (empty($data)) {
            return false;
        }
        $tree = [];
        foreach ($data as $k => $v) {
            if ($pid == $v['pid']) {
                $v['list'] = self::getTree($data, $v['id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    /**
     * 截取数组
     * @param $data
     * @param int $firstCount
     * @param int $secondCount
     * @param int $threeCount
     * @return array|bool
     */
    public static function treeSlice($data, $firstCount = 8, $secondCount = 3, $threeCount = 2)
    {
        if (empty($data)) {
            return false;
        }
        $data = array_slice($data, 0, $firstCount);
        foreach ($data as $k1 => $v1) {
            if(!empty($v1['list'])) {
                $data[$k1]['list'] = array_slice($v1['list'], 0, $secondCount);
                foreach ($v1['list'] as $k2 => $v2) {
                    if(!empty($v2['list'])) {
                        $data[$k1]['list'][$k2]['list'] = array_slice($v2['list'], 0, $threeCount);
                    }
                }
            }
        }
        return $data;
    }

    public static function paginateData($num)
    {
        if(!$num) {
            return false;
        }
        $result = [
            "total" => 0,
            "per_page" => $num,
            "last_page" => 0,
            "data" => []
        ];
        return $result;
    }

    public static function arrsSortByKey($array, $key, $sort = SORT_DESC)
    {
        if (!is_array($array) || empty($array) || empty($key)) {
            return [];
        }
        array_multisort(array_column($array, $key), $sort, $array);
        return $array;
    }
}