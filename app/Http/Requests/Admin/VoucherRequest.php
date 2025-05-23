<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:100|unique:vouchers,code,' . ($this->voucher ? $this->voucher->id : ''),
            'description' => 'nullable|string',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'max_discount' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive'
        ];
    }
    public function messages()
    {
        return [
            'code.required' => 'Mã voucher không được để trống',
            'code.unique' => 'Mã voucher đã tồn tại',
            'discount_percent.required' => 'Phần trăm giảm giá không được để trống',
            'discount_percent.max' => 'Phần trăm giảm giá không được vượt quá 100%',
            'max_discount.required' => 'Giảm giá tối đa không được để trống',
            'min_order_value.required' => 'Giá trị đơn hàng tối thiểu không được để trống',
            'valid_from.required' => 'Ngày bắt đầu không được để trống',
            'valid_to.required' => 'Ngày kết thúc không được để trống',
            'valid_to.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'quantity.required' => 'Số lượng không được để trống',
            'quantity.min' => 'Số lượng phải lớn hơn 0'
        ];
    }
}
