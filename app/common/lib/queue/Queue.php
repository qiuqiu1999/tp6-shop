<?php


namespace app\common\lib\queue;

use think\queue\Job;

class Queue
{
    protected $delay = 2;

    public function fire(Job $job, $data){

        //....这里执行具体的任务

        if ($job->attempts() > 3) {
            //通过这个方法可以检查这个任务已经重试了几次了
        }


        //如果任务执行成功后 记得删除任务，不然这个任务会重复执行，直到达到最大重试次数后失败后，执行failed方法
        $job->delete();

        // 也可以重新发布这个任务
        $job->release($this->delay); //$delay为延迟时间

    }


    // ...任务达到最大重试次数后，失败了
    public function failed($data){


    }
}