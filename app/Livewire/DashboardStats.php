<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class DashboardStats extends Component
{
    public $orderCount;
    public $customerCount;
    public $revenue;
    public $balance;
    public $timePeriod = 'month';

    public function mount()
    {
        $this->updateStats();
    }

    public function updatedTimePeriod()
    {
        $this->updateStats();
    }

    public function updateStats()
    {
        $now = now();

        switch ($this->timePeriod) {
            case 'day':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case 'week':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                break;
            case 'quarter':
                $startDate = $now->copy()->startOfQuarter();
                $endDate = $now->copy()->endOfQuarter();
                break;
            default: // 'month'
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
        }

        // Tổng số đơn hàng
        $this->orderCount = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        // Tổng khách hàng có role là 'user'
        $this->customerCount = User::whereHas('role', function ($query) {
                $query->where('name', 'user');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Doanh thu thực nhận (chỉ đơn đã thanh toán & thành công)
        $this->revenue = Order::whereHas('orderStatus', function ($q) {
                $q->where('name', 'Thành công');
            })
            ->whereHas('paymentStatus', function ($q) {
                $q->where('name', 'Đã Thanh Toán');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Số dư: tổng tiền tất cả các đơn trong khoảng thời gian
        $this->balance = Order::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
