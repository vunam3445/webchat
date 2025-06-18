<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Xác định người dùng có được phép gửi request này không.
     * Đặt là true để cho phép xử lý.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Các quy tắc kiểm tra dữ liệu gửi lên.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^0[0-9]{9}$/|unique:users,phone',
            'email' => 'email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // ✅ Thêm 'confirmed'
        ];
    }
    public function messages(): array
    {
        return [
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.regex' => 'Số điện thoại không đúng định dạng (bắt đầu bằng 0 và đủ 10 số).',
            'phone.unique' => 'Số điện thoại đã tồn tại.',

            'name.required' => 'Tên là bắt buộc.',
            // 'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',

            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ];
    }
}
