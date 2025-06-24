<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        return view('profile.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        // Log incoming request data for debugging
        Log::info('Address store request data:', $request->all());
        
        $request->validate([
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address_detail' => 'required|string|max:500',
            'is_default' => 'sometimes|boolean'
        ]);

        DB::beginTransaction();
        try {
            // Nếu đặt làm mặc định, bỏ mặc định của các địa chỉ khác
            if ($request->is_default) {
                Auth::user()->addresses()->update(['is_default' => false]);
            }

            // Nếu đây là địa chỉ đầu tiên, tự động đặt làm mặc định
            $isFirstAddress = Auth::user()->addresses()->count() == 0;

            $address = Auth::user()->addresses()->create([
                'city' => $request->city,
                'district' => $request->district,
                'ward' => $request->ward,
                'address_detail' => $request->address_detail,
                'is_default' => $request->is_default || $isFirstAddress
            ]);
            
            Log::info('Address created successfully:', $address->toArray());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm địa chỉ thành công!',
                'address' => $address
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Address store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm địa chỉ: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        return response()->json($address);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address_detail' => 'required|string|max:500',
            'is_default' => 'sometimes|boolean'
        ]);

        DB::beginTransaction();
        try {
            $address = Auth::user()->addresses()->findOrFail($id);

            // Nếu đặt làm mặc định, bỏ mặc định của các địa chỉ khác
            if ($request->is_default) {
                Auth::user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
            }

            $address->update([
                'city' => $request->city,
                'district' => $request->district,
                'ward' => $request->ward,
                'address_detail' => $request->address_detail,
                'is_default' => $request->is_default ?: $address->is_default
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật địa chỉ thành công!',
                'address' => $address
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Address update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật địa chỉ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $address = Auth::user()->addresses()->findOrFail($id);

            // Không cho phép xóa địa chỉ mặc định nếu còn địa chỉ khác
            if ($address->is_default && Auth::user()->addresses()->count() > 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa địa chỉ mặc định. Vui lòng đặt địa chỉ khác làm mặc định trước!'
                ], 400);
            }

            $address->delete();

            // Nếu xóa địa chỉ mặu định cuối cùng, đặt địa chỉ đầu tiên (nếu có) làm mặc định
            if (Auth::user()->addresses()->count() > 0 && !Auth::user()->addresses()->where('is_default', true)->exists()) {
                Auth::user()->addresses()->first()->update(['is_default' => true]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Xóa địa chỉ thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa địa chỉ!'
            ], 500);
        }
    }

    public function setDefault($id)
    {
        try {
            DB::beginTransaction();

            // Bỏ mặc định tất cả địa chỉ khác
            Auth::user()->addresses()->update(['is_default' => false]);

            // Đặt địa chỉ được chọn làm mặc định
            $address = Auth::user()->addresses()->findOrFail($id);
            $address->update(['is_default' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã đặt làm địa chỉ mặc định!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra!'
            ], 500);
        }
    }
}
