<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class MessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check(); // chỉ cho phép nếu đã đăng nhập
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'required_without:metadata|string|nullable',
            'type' => 'required|in:text,image,audio,video,file,call',
            'metadata' => 'nullable|array'
        ];
    }

    /**
     * Custom messages (tùy chọn).
     */
    public function messages(): array
    {
        return [
            'content.required_without' => 'Vui lòng nhập nội dung hoặc đính kèm dữ liệu.',
            'type.required' => 'Loại tin nhắn là bắt buộc.',
            'type.in' => 'Loại tin nhắn không hợp lệ.',
        ];
    }
}
