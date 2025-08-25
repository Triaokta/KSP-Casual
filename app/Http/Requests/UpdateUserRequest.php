<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $authUser = auth()->user();

        // Allow admin full access
        if ($authUser->role === Role::ADMIN->status()) {
            return true;
        }

        // Allow user to update their own data
        if ($this->id == $authUser->id) {
            return true;
        }

        // Get the target user being updated
        $targetUser = \App\Models\User::find($this->id);

        if (!$targetUser) {
            return false;
        }

        // If Department Admin, only allow if same department
        if (
            $authUser->role === Role::DEPARTMENT_ADMIN->status() &&
            $authUser->department_id &&
            $authUser->department_id === $targetUser->department_id
        ) {
            return true;
        }

        // If Division Admin, only allow if same division
        if (
            $authUser->role === Role::DIVISION_ADMIN->status() &&
            $authUser->division_id &&
            $authUser->division_id === $targetUser->division_id
        ) {
            return true;
        }

        return false;
    }

    public function attributes(): array
    {
        return [
            'name' => __('model.user.name'),
            'email' => __('model.user.email'),
            'phone' => __('model.user.phone'),
            'registration_id' => 'Registration ID',
            'jabatan_id' => 'Jabatan',
            'jabatan_full' => 'Jabatan Lengkap',
            'department_id' => 'Department',
            'division_id' => 'Divisi',
            'directorate_id' => 'Direktorat',
            'superior_registration_id' => 'Atasan (Registration ID)',
            'nik' => 'NIK',
            'address' => 'Alamat',
            'role' => 'Peran',
        ];
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->id)],
            'phone' => ['nullable', 'string'],
            'registration_id' => ['required', 'string', Rule::unique('users', 'registration_id')->ignore($this->id)],
            'jabatan_id' => ['nullable', 'exists:jabatans,id'],
            'jabatan_full' => ['nullable', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'directorate_id' => ['nullable', 'exists:directorates,id'],
            'superior_registration_id' => ['nullable', 'exists:users,registration_id'],
            'nik' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'role' => ['required', Rule::in(array_column(Role::cases(), 'value'))],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->filled('superior_registration_id')) {
            $superior = \App\Models\User::where('registration_id', $this->superior_registration_id)->first();
            if ($superior) {
                $this->merge(['superior_id' => $superior->id]);
            }
        }
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        unset($data['superior_registration_id']);
        return $data;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hasDepartment = $this->input('department_id');
            $hasDirectorate = $this->input('directorate_id');

            if (!$hasDepartment && !$hasDirectorate) {
                $validator->errors()->add('department_id', 'Departemen atau Direktorat harus diisi.');
                $validator->errors()->add('directorate_id', 'Departemen atau Direktorat harus diisi.');
            }
        });
    }
}
