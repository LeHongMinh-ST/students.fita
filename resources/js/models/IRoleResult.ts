import IPermissionResult from "./IPermissionResult";

export default interface IRoleResult {
    id: number,
    name: string,
    description?: string,
    permissions?: IPermissionResult[],
}
