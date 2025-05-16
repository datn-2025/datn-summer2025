    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::table('order_statuses', function (Blueprint $table) {
                $table->enum('name', [
                    'Chờ xác nhận',
                    'Đã xác nhận',
                    'Đang chuẩn bị',
                    'Đang giao hàng',
                    'Đã giao thành công',
                    'Đã nhận hàng',
                    'Thành công',
                    'Giao thất bại',
                    'Đã hủy',
                    'Đã hoàn tiền',
                ])->change();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('order_statuses', function (Blueprint $table) {
                $table->string('name')->change();
            });
        }
    };
