import IPermissionResult from "./IPermissionResult";

export default interface IGroupPermissionResult {
    id: number,
    name: string,
    code: string,
    description?: string,
    permissions?: IPermissionResult[],
}
