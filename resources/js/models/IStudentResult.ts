import {IFamilyResult} from "./IFamilyResult";
import IClassesResult from "./IClassesResult";
import {ILearningOutcomeResult} from "./ILearningOutcomeResult";

export interface IStudentResult {
    id?: number,
    full_name: string,
    description?: string,
    user_name?: string,
    student_code: string,
    gender?: number,
    gender_text?: string,
    permanent_residence?: string,
    class_id?: number,
    major?: string,
    dob?: string,
    pob?: string,
    address?: string,
    countryside?:string,
    training_type?: number,
    training_text?: string,
    school_year?: string,
    email?: string,
    email_edu?: string,
    phone?:string,
    thumbnail?: string,
    thumbnail_url?: string,
    citizen_identification?: string,
    nationality?: number,
    ethnic?: number,
    religion?: number,
    academic_level?: number,
    social_policy_object?: number,
    social_policy_object_text?: string,
    note?: string,
    status?: number,
    status_text?: string,
    role?: number,
    role_text?: string,
    families?: IFamilyResult[],
    general_class?: IClassesResult,
    learning_outcomes?: ILearningOutcomeResult[]
}
