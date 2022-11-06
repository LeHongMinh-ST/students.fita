export default class IStudent {
    maSV?: number;
    hoTen?: string;
    ngaySinh?: string;
    lop?: string;
    khoa?:string;

    constructor(maSV?: number, hoTen?: string, ngaySinh?: string, lop?: string, khoa?: string) {
      this.maSV = maSV;
      this.hoTen = hoTen;
      this.ngaySinh = ngaySinh?? new Date().toDateString();
      this.lop = lop;
      this.khoa = khoa;
    }
  }
