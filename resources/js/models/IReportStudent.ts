import {IFamilyResult} from "./IFamilyResult";
import IClassesResult from "./IClassesResult";
import {ILearningOutcomeResult} from "./ILearningOutcomeResult";

export interface IReportStudent {
    id?: number,
    title: string,
    subjects?: string,
    content?: string,
    status?: number,
    status_approve?: number,
    gender_text?: string,
    class_id?: number,
}
