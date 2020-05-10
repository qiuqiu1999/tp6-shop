<?php


namespace app\api\command;


use app\common\business\order\OrderBis;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Order extends Command
{
    protected function configure()
    {
        $this->setName('order')->setDescription('the second command');
    }

    protected function execute(Input $input, Output $output)
    {
        $obj = new OrderBis();
        while (true) {
            $obj->checkOrderStatus();
            sleep(1);
        }
        $output->writeln('second111');
    }
}