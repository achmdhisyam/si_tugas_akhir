<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PengajuanSkripsiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Hanya mahasiswa yang boleh mengajukan skripsi
        return auth()->check() && auth()->user()->role === 'mahasiswa';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'file_skripsi' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul skripsi wajib diisi.',
            'judul.max' => 'Judul skripsi maksimal 255 karakter.',
            'file_skripsi.required' => 'File draft skripsi wajib diunggah.',
            'file_skripsi.mimes' => 'Format file harus PDF, DOC, atau DOCX.',
            'file_skripsi.max' => 'Ukuran file maksimal 10MB.',
        ];
    }
}
