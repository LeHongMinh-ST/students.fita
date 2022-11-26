<?php

use App\Enums\LearningOutcome\LearningOutcomeTypeAverage;
use App\Enums\Student\StudentGender;
use App\Enums\Student\StudentRole;
use App\Enums\Student\StudentSocialPolicyObject;
use App\Enums\Student\StudentStatus;
use App\Enums\Student\StudentTempStatus;
use App\Enums\Student\StudentTrainingType;

return [
    StudentRole::class => [
        StudentRole::Normal => 'Sinh viên',
        StudentRole::ClassMonitor => 'Lớp trưởng',
        StudentRole::Officer => 'Cán bộ lớp'
    ],
    StudentSocialPolicyObject::class => [
        StudentSocialPolicyObject::None => 'Không có',
        StudentSocialPolicyObject::SonOfWounded => 'Con thương binh liệt sĩ',
        StudentSocialPolicyObject::EspeciallyDifficult => 'Gia đình đặc biệt khó khăn',
        StudentSocialPolicyObject::EthnicMinorityPeopleInTheHighlands => 'Người dân tộc ít người ở vùng cao.',
        StudentSocialPolicyObject::DisabledPerson => 'Người tàn tật theo quy định chung của Nhà nước và gặp khó khăn về kinh tế.'
    ],
    StudentStatus::class => [
        StudentStatus::Studying => 'Đang theo học',
        StudentStatus::Graduated => 'Đã tốt nghiệp',
        StudentStatus::Suspended => 'Tạm đình chỉ',
        StudentStatus::ForcedToQuitSchool => 'Buộc thôi học',
        StudentStatus::Reserve => 'Bảo lưu'
    ],
    StudentGender::class => [
        StudentGender::Male => 'Nam',
        StudentGender::Female => 'Nữ',
        StudentGender::Other => 'Khác',
    ],
    StudentTrainingType::class => [
        StudentTrainingType::FormalUniversity => 'Đại học chính quy',
        StudentTrainingType::College => 'Cao đẳng',
    ],
    LearningOutcomeTypeAverage::class => [
        LearningOutcomeTypeAverage::Poor => 'Kém',
        LearningOutcomeTypeAverage::Average => 'Trung bình',
        LearningOutcomeTypeAverage::Good => 'Khá',
        LearningOutcomeTypeAverage::VeryGood => 'Giỏi',
        LearningOutcomeTypeAverage::Excellent => 'Xuất xắc',
    ],
    StudentTempStatus::class => [
        StudentTempStatus::Pending => 'Chờ phê duyệt',
        StudentTempStatus::ClassMonitorApproved => 'Cán bộ lớp duyệt',
        StudentTempStatus::TeacherApproved => 'Giáo viên chủ nhiệm đã duyệt',
        StudentTempStatus::Approved => 'Đã phê duyệt',
    ]
];
