<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.backend')]
class RevenueReport extends Component
{
    public $timeRange = 'month';
    public $chartLabels = [];
    public $chartData = [];

   

    public function updatedTimeRange()
    {
        $this->loadData();
    }

    public function loadData()
    {
        logger('⚠️ DEBUG: Đã gọi loadData()');

        $query = Order::query();
            // ->where('order_status_id', '163b43c1-d00c-43f6-8387-84d3f9c10c12') // ✅ Đơn thành công
            // ->where('payment_status_id', '32d26c8e-ec37-4696-9875-9f931be1bf88'); // ✅ Đã thanh toán

        switch ($this->timeRange) {
            case 'day':
                $orders = $query
                    ->whereDate('created_at', Carbon::today())
                    ->selectRaw('HOUR(created_at) as label, SUM(total_amount) as revenue')
                    ->groupBy('label')
                    ->get();
                $this->chartLabels = $orders->pluck('label')->map(fn($h) => $h . 'h')->toArray();
                break;

            case 'week':
                $orders = $query
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->selectRaw('DATE(created_at) as label, SUM(total_amount) as revenue')
                    ->groupBy('label')
                    ->get();
                $this->chartLabels = $orders->pluck('label')->toArray();
                break;

            case 'quarter':
                $orders = $query
                    ->whereBetween('created_at', [now()->startOfQuarter(), now()])
                    ->selectRaw('DATE(created_at) as label, SUM(total_amount) as revenue')
                    ->groupBy('label')
                    ->get();
                $this->chartLabels = $orders->pluck('label')->toArray();
                break;

            default: // month
                $orders = $query
                    ->whereBetween('created_at', [now()->startOfMonth(), now()])
                    ->selectRaw('DATE(created_at) as label, SUM(total_amount) as revenue')
                    ->groupBy('label')
                    ->get();
                $this->chartLabels = $orders->pluck('label')->toArray();
                break;
        }

        // ⚠️ Ép kiểu số để Chart.js không lỗi
        $this->chartData = $orders->pluck('revenue')->map(fn($r) => (float)$r)->toArray();

       $this->dispatch('refreshChart', chartLabels: $this->chartLabels, chartData: $this->chartData);


        logger('DEBUG chartLabels:', $this->chartLabels);
        logger('DEBUG chartData:', $this->chartData);
    }

    public function render()
    {
        $this->loadData();
        return view('livewire.revenue-report');
    }
}
