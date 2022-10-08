export default interface IResult<T> {
    Data: T,
    Status: number,
    ErrorCode?: string,
    Error?: String[],
}
