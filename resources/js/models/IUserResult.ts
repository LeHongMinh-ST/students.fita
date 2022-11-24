export default interface IUserResult {
    id: number,
    full_name: string,
    user_name: string,
    email: string,
    email_verified_at: string | null,
    created_at: string,
    updated_at: string,
    phone: string | null,
    teacher_code: string | null,
    department_id: number | null,
    is_super_admin: boolean,
    is_teacher: boolean,
    role_id: number | null,
    created_by: number | null,
    updated_by: number | null,
}
