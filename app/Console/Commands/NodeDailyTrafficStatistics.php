<?php

namespace App\Console\Commands;

use App\Models\Node;
use Illuminate\Console\Command;
use Log;

class NodeDailyTrafficStatistics extends Command
{
    protected $signature = 'nodeDailyTrafficStatistics';
    protected $description = '节点每日流量统计';

    public function handle()
    {
        $jobTime = microtime(true);

        foreach (Node::whereStatus(1)->orderBy('id')->with('userDataFlowLogs')->whereHas('userDataFlowLogs')->get() as $node) {
            $this->statisticsByNode($node);
        }

        $jobTime = round((microtime(true) - $jobTime), 4);

        Log::info('---【'.$this->description.'】完成---，耗时'.$jobTime.'秒');
    }

    private function statisticsByNode(Node $node)
    {
        $created_at = date('Y-m-d 23:59:59', strtotime('-1 days'));
        $time = strtotime($created_at);
        $traffic = $node->userDataFlowLogs()
            ->whereBetween('log_time', [$time - 86399, $time])
            ->selectRaw('sum(`u`) as u, sum(`d`) as d')->first();

        if ($traffic && $total = $traffic->u + $traffic->d) { // 有数据才记录
            $node->dailyDataFlows()->create([
                'u'          => $traffic->u,
                'd'          => $traffic->d,
                'total'      => $total,
                'traffic'    => flowAutoShow($total),
                'created_at' => $created_at,
            ]);
        }
    }
}
