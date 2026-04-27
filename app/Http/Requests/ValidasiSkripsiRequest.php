<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ValidasiSkripsiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Hanya kaprodi yang boleh melakukan validasi skripsi
        return auth()->check() && auth()->user()->role === 'kaprodi';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:disetujui,ditolak',
            'dosen_id' => 'required_if:status,disetujui|nullable|exists:users,id',
            'dosen_id_2' => 'required_if:status,disetujui|nullable|exists:users,id|different:dosen_id',
            'alasan_penolakan' => 'required_if:status,ditolak|nullable|string',
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
            'status.required' => 'Status validasi wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'dosen_id.required_if' => 'Dosen pembimbing 1 wajib dipilih jika judul disetujui.',
            'dosen_id.exists' => 'Dosen pembimbing 1 tidak ditemukan.',
            'dosen_id_2.required_if' => 'Dosen pembimbing 2 wajib dipilih jika judul disetujui.',
            'dosen_id_2.exists' => 'Dosen pembimbing 2 tidak ditemukan.',
            'dosen_id_2.different' => 'Dosen pembimbing 2 tidak boleh sama dengan pembimbing 1.',
            'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi jika judul ditolak.',
        ];
    }
}
