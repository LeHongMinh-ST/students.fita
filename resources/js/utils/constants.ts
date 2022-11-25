import {GenderEnum} from "../enums/gender.enum";
import {TrainingTypeEnum} from "../enums/trainingType.enum";
import {StudentTempStatusEnum} from "../enums/studentTempStatus.enum";
import {StudentStatusEnum} from "../enums/studentStatus.enum";
import {StudentRoleEnum} from "../enums/studentRole.enum";
import {StudentSocialPolicyObjectEnum} from "../enums/studentSocialPolicyObject.enum";

export const VALIDATE_EMAIL_REGEX = /^[a-zA-Z0-9][a-zA-Z0-9_.-]{1,}@[a-z0-9]{1,}(\.[a-z0-9]{1,}){1,2}$/
export const VALIDATE_PHONE_REGEX_RULE = /((^0|^84|^\+84|^\(\+84\))+([0-9]{2}))+([0-9]{7}|[0-9]{1}\.[0-9]{3}\.[0-9]{3}|[0-9]{1}-[0-9]{3}-[0-9]{3}|[0-9]{1}\s[0-9]{3}\s[0-9]{3}$)/
export const MY_LOCALE = {
    days: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ Nhật'],
    daysShort: ['CN', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7'],
    months: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
    monthsShort: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
    firstDayOfWeek: 1, // 0-6, 0 - Sunday, 1 Monday, ...
    format24h: true,
    pluralDay: 'dias'
}

export const GENDER_LIST = [
    {label: 'Nam', value: GenderEnum.Male},
    {label: 'Nữ', value: GenderEnum.FeMale},
    {label: 'Khác', value: GenderEnum.Other},
]

export const TRAINING_TYPE_LIST = [
    {label: 'Đại học chính quy', value: TrainingTypeEnum.FormalUniversity},
    {label: 'Cao đẳng', value: TrainingTypeEnum.College},
]

export const STUDENT_TEMP_STATUS_LIST = [
    {label: 'Chờ duyệt', value: StudentTempStatusEnum.Pending},
    {label: 'Lớp trưởng đã duyệt', value: StudentTempStatusEnum.ClassMonitorApproved},
    {label: 'Giáo viên đã duyệt', value: StudentTempStatusEnum.TeacherApproved},
    {label: 'Đã duyệt', value: StudentTempStatusEnum.Approved},
    {label: 'Hủy', value: StudentTempStatusEnum.Reject},
]

export const STUDENT_STATUS_LIST = [
    {label: 'Đang theo học', value: StudentStatusEnum.Studying},
    {label: 'Đã tốt nghiệp', value: StudentStatusEnum.Graduated},
    {label: 'Tạm đình chỉ', value: StudentStatusEnum.Suspended},
    {label: 'Bộ thôi học', value: StudentStatusEnum.ForcedToQuitSchool},
    {label: 'Bảo lưu', value: StudentStatusEnum.Reserve},
]

export const STUDENT_ROLE_LIST = [
    {label: 'Sinh viên', value: StudentRoleEnum.Normal},
    {label: 'Lớp trưởng', value: StudentRoleEnum.ClassMonitor},
    {label: 'Cán bộ lớp', value: StudentRoleEnum.Officer},
]

export const STUDENT_SOCIAL_POLICY_OBJECT_LIST = [
    {label: 'Không có', value: StudentSocialPolicyObjectEnum.None},
    {label: 'Con thương binh liệt sĩ', value: StudentSocialPolicyObjectEnum.SonOfWounded},
    {label: 'Hoàn cảnh đặc biệt khó khăn', value: StudentSocialPolicyObjectEnum.EspeciallyDifficult},
    {label: 'Người dân tộc thiểu số vùng cao', value: StudentSocialPolicyObjectEnum.EthnicMinorityPeopleInTheHighlands},
    {label: 'Người khuyết tật', value: StudentSocialPolicyObjectEnum.DisabledPerson},
]
