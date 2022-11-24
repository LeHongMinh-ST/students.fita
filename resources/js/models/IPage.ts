export interface IPage {
    currentPage: number,
    total: number,
    perPage: number,
}

export interface IPayload {
    page: number,
    q?: string,
}
