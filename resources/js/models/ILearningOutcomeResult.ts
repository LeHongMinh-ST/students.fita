import {ILearningOutcomeResultDetail} from "./ILearningOutcomeResultDetail";

export interface ILearningOutcomeResult {
    semester?:number,
    id?:number,
    year_school_start?:number,
    year_school_end?:number,
    student_id?:number,
    semester_average_4?:number,
    semester_average_10?:number,
    cumulative_average_10?:number,
    cumulative_average_4?:number,
    credits?:number,
    cumulative_credits?:number,
    type_average?:string,
    detail?: ILearningOutcomeResultDetail[]
}
