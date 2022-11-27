export interface IPage {
    currentPage: number,
    total: number,
    perPage: number,
}

export interface IPayload {
    page: number,
    q?: string,
}

export interface StudentFilter {
    page: number,
    student_code?: string,
    full_name?: string,
}
