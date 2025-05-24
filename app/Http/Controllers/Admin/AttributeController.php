<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    /**
     * Display a listing of the attributes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = Attribute::with('values')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:attributes,name',
            'values' => 'required|array',
            'values.*' => 'required|string|max:100',
        ]);

        try {
            DB::beginTransaction();
            
            // Create the attribute
            $attribute = Attribute::create([
                'name' => $request->name,
            ]);
            
            // Create the attribute values
            foreach ($request->values as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
            
            DB::commit();
            Toastr::success('Thêm Thuoc tính thành công', 'Thành công');
            return redirect()->route('admin.attributes.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating attribute: ' . $e->getMessage());
            Toastr::error('Lỗi khi thêm thuộc tính. Vui lòng thử lại.', 'Lỗi');
            return back();
        }
    }

    /**
     * Display the specified attribute.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attribute = Attribute::with('values')->findOrFail($id);
        return view('admin.attributes.show', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:100|unique:attributes,name,' . $id,
            'values' => 'required|array',
            'values.*' => 'required|string|max:100',
            'value_ids' => 'sometimes|array',
        ]);

        try {
            DB::beginTransaction();
            
            // Update the attribute
            $attribute->update([
                'name' => $request->name,
            ]);
            
            // Get existing value IDs
            $existingValueIds = $attribute->values->pluck('id')->toArray();
            $submittedValueIds = $request->value_ids ?? [];
            
            // Delete values that are no longer in the submitted form
            $valuesToDelete = array_diff($existingValueIds, $submittedValueIds);
            if (!empty($valuesToDelete)) {
                AttributeValue::whereIn('id', $valuesToDelete)->delete();
            }
            
            // Update existing values and add new ones
            foreach ($request->values as $index => $value) {
                if (isset($submittedValueIds[$index])) {
                    // Update existing value
                    AttributeValue::where('id', $submittedValueIds[$index])
                        ->update(['value' => $value]);
                } else {
                    // Create new value
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }
            
            DB::commit();
            Toastr::success('Cập nhật thuộc tính thành công', 'Thành công');
            return redirect()->route('admin.attributes.show', $attribute->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating attribute: ' . $e->getMessage());
            Toastr::error('Lỗi khi cập nhật thuộc tính. Vui lòng thử lại.', 'Lỗi');
            return back();
        }
    }

    /**
     * Remove the specified attribute from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $attribute = Attribute::findOrFail($id);
            
            // Delete all associated attribute values first
            $attribute->values()->delete();
            
            // Then delete the attribute
            $attribute->delete();
            
            DB::commit();
            Toastr::success('Xóa thuộc tính thành công', 'Thành công');
            return redirect()->route('admin.attributes.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting attribute: ' . $e->getMessage());
            Toastr::error('Lỗi khi xóa thuộc tính. Vui lòng thử lại.', 'Lỗi');
            return back();
        }
    }
    
}
