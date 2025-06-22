<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\GhnService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncGhnOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghn:sync-orders {--order_id=} {--limit=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đồng bộ trạng thái đơn hàng GHN';

    protected $ghnService;

    public function __construct(GhnService $ghnService)
    {
        parent::__construct();
        $this->ghnService = $ghnService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->option('order_id');
        $limit = (int) $this->option('limit');

        if ($orderId) {
            $this->syncSingleOrder($orderId);
        } else {
            $this->syncMultipleOrders($limit);
        }
    }

    /**
     * Đồng bộ một đơn hàng cụ thể
     */
    private function syncSingleOrder($orderId)
    {
        $order = Order::find($orderId);
        
        if (!$order) {
            $this->error("Không tìm thấy đơn hàng với ID: {$orderId}");
            return;
        }

        if (!$order->ghn_order_code) {
            $this->error("Đơn hàng {$orderId} không có mã GHN");
            return;
        }

        $this->info("Đang đồng bộ đơn hàng: {$orderId}");
        $result = $this->syncOrderStatus($order);
        
        if ($result) {
            $this->info("✓ Đồng bộ thành công đơn hàng: {$orderId}");
        } else {
            $this->error("✗ Lỗi đồng bộ đơn hàng: {$orderId}");
        }
    }

    /**
     * Đồng bộ nhiều đơn hàng
     */
    private function syncMultipleOrders($limit)
    {
        // Lấy các đơn hàng có mã GHN nhưng chưa hoàn thành
        $orders = Order::whereNotNull('ghn_order_code')
            ->whereHas('orderStatus', function($query) {
                $query->whereNotIn('name', ['Đã giao', 'Đã hủy', 'Hoàn thành']);
            })
            ->limit($limit)
            ->get();

        if ($orders->isEmpty()) {
            $this->info("Không có đơn hàng nào cần đồng bộ");
            return;
        }

        $this->info("Tìm thấy {$orders->count()} đơn hàng cần đồng bộ");
        
        $successCount = 0;
        $errorCount = 0;

        foreach ($orders as $order) {
            $this->line("Đang xử lý đơn hàng: {$order->id}");
            
            if ($this->syncOrderStatus($order)) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        $this->info("Kết quả: {$successCount} thành công, {$errorCount} lỗi");
    }

    /**
     * Đồng bộ trạng thái một đơn hàng
     */
    private function syncOrderStatus(Order $order)
    {
        try {
            // Gọi API GHN để lấy thông tin đơn hàng
            // Note: GHN API có thể không có endpoint để tra cứu trạng thái đơn hàng
            // Ở đây chúng ta có thể implement webhook để nhận update từ GHN
            // Hoặc sử dụng API khác nếu có
            
            $this->line("  - Kiểm tra trạng thái GHN cho mã: {$order->ghn_order_code}");
            
            // Placeholder - cần implement API call thực tế
            // $ghnStatus = $this->ghnService->getOrderStatus($order->ghn_order_code);
            
            // For now, just log the attempt
            Log::info('GHN Status Sync Attempted', [
                'order_id' => $order->id,
                'ghn_order_code' => $order->ghn_order_code
            ]);

            return true;

        } catch (\Exception $e) {
            $this->error("  - Lỗi: " . $e->getMessage());
            
            Log::error('GHN Status Sync Error', [
                'order_id' => $order->id,
                'ghn_order_code' => $order->ghn_order_code,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
