<?php

namespace App\Imports;

use App\Enums\Student\StudentRole;
use App\Enums\Student\StudentSocialPolicyObject;
use App\Enums\Student\StudentStatus;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    private int $classId;

    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Student([
            'full_name' => $row['ho_va_ten'],
            'student_code' => $row['ma_sinh_vien'],
            'password' => @$row['ngay_sinh'],
            'gender' => @$row['gioi_tinh'],
            'permanent_residence' => @$row['ho_khau_thuong_tru'],
            'major' => @$row['chuyen_nganh'],
            'dob' => @$row['ngay_sinh'],
            'pob' => @$row['noi_sinh'],
            'address' => @$row['dia_chi'],
            'countryside' => @$row['que_quan'],
            'training_type' => @$row['chuong_trinh_dao_tao'],
            'school_year' => @$row['nien_khoa'],
            'email' => @$row['email'],
            'email_edu' => $row['ma_sinh_vien'] . config('vnua.mail_student'),
            'phone' => @$row['so_dien_thoai'],
            'nationality' => @$row['quoc_tich'],
            'citizen_identification' => @$row['can_cuoc_cong_dan'],
            'ethnic' => @$row['dan_toc'],
            'religion' => @$row['ton_giao'],
            'academic_level' => @$row['trinh_do_hoc_van'],
            'social_policy_object' => StudentSocialPolicyObject::None,
            'note' => @$row['note'],
            'status' => StudentStatus::Studying,
            'role' => StudentRole::Normal,
            'class_id' => $this->classId
        ]);
    }

    public function rules(): array
    {
        return [
            '*.ho_va_ten' => 'required',
            '*.ma_sinh_vien' => 'required|unique:students,student_code',
            '*.ngay_sinh' => 'required',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'ho_va_ten' => 'họ và tên',
            'ma_sinh_vien' => 'mã sinh viên',
            'ngay_sinh' => 'ngày sinh',
        ];
    }
}
