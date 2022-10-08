export default interface IPaginate<Data> {
    data: Data,
    current_page: number,
    last_page: number,
    per_page: number,
    total: number,
}
