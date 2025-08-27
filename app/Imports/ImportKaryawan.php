<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;

class ImportKaryawan implements 
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsOnError,
    WithBatchInserts,
    WithChunkReading,
    WithEvents
{
    use SkipsFailures, SkipsErrors;

    // Simpan data duplikat yang ditemukan selama proses import
    protected $duplicates = [];
    
    // Simpan jumlah baris yang diproses
    protected $rowCount = 0;
    
    public function model(array $row)
    {
        try {
            if (empty($row['id_karyawan']) || empty($row['nama_lengkap']) || empty($row['no_ktp'])) {
                Log::warning('⚠️ Baris dengan data tidak lengkap dilewati: ' . json_encode($row));
                return null;
            }
            
            $this->rowCount++;
            
            $existingEmployee = Employee::where('employee_id', $row['id_karyawan'])
                ->orWhere('nik_ktp', $row['no_ktp'])
                ->first();
                
            if (!empty($row['npwp'])) {
                $existingByNpwp = Employee::where('npwp', $row['npwp'])->first();
                if ($existingByNpwp) {
                    $existingEmployee = $existingByNpwp;
                }
            }
            
            if ($existingEmployee) {
                $reason = [];
                
                if ($existingEmployee->employee_id == $row['id_karyawan']) {
                    $reason[] = 'ID Karyawan sudah digunakan';
                }
                if ($existingEmployee->nik_ktp == $row['no_ktp']) {
                    $reason[] = 'NIK sudah digunakan';
                }
                if (!empty($row['npwp']) && $existingEmployee->npwp == $row['npwp']) {
                    $reason[] = 'NPWP sudah digunakan';
                }
                
                $this->duplicates[] = [
                    'row' => $row,
                    'reason' => implode(', ', $reason)
                ];
                
                Log::warning('⚠️ Data duplikat ditemukan: ' . json_encode([
                    'data' => $row,
                    'reason' => implode(', ', $reason)
                ]));
                
                return null;
            }
            
            $department = Department::updateOrCreate(
                ['name' => $row['departemen']],
                ['directorate_id' => 1]
            );

            Log::info('📥 Import baris:', $row);

            return new Employee([
                'employee_id'     => $row['id_karyawan'],
                'name'            => $row['nama_lengkap'],
                'nik_ktp'         => $row['no_ktp'],
                'address'         => $row['alamat'],
                'npwp'            => $row['npwp'],
                'no_rek'          => $row['no_rek'] ?? null,
                'nama_bank'       => $row['nama_bank'] ?? null,
                'department_id'   => $department->id,
                'is_active'       => strtolower(trim($row['status_aktif'])) === 'aktif',
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Gagal simpan employee: ' . json_encode($row));
            Log::error('Error: ' . $e->getMessage());
            return null;
        }
    }
    
    public function getDuplicates()
    {
        return $this->duplicates;
    }
    
    public function getRowCount()
    {
        return $this->rowCount;
    }

    public function rules(): array
    {
        return [
            'id_karyawan' => 'required|string',
            'nama_lengkap'   => 'required|string',
            'no_ktp'         => 'required|string|size:16',
            'departemen'     => 'required|string',
            'status_aktif'   => 'required|in:Aktif,Nonaktif',
            'no_rek'         => 'nullable|string',
            'nama_bank'      => 'nullable|string',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
    

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                \Log::info('Import selesai. Total baris diproses: ' . $this->rowCount);
                \Log::info('Total data duplikat: ' . count($this->duplicates));
            },
        ];
    }
}
