export default class IStudent {
  id?:number;
  user_name?: string;
  password?: string;//Mật khẩu
  student_code ?:string; //Mã sinh viên
  full_name ?:string;//Họ và tên
  gender?: number;//Giới tính
  permanent_residence ?:string;//Hộ khẩu thường trú
  class_id?: number;// Lớp
  major ?:string;//Chuyên ngành
  dob ?:string;// Ngày sinh
  pob ?:string;// Nơi sinh
  address ?:string;//Địa chỉ liên hệ
  countryside ?:string;// Quê quán
  training_type?: number;//Đại học - Cao đẳng
  school_year ?:string;//Niên khóa
  email ?:string;
  phone ?:string;//Số điện thoại
  nationality?: number;// Quốc tịch
  citizen_identification ?:string; //CCCD/CMT
  ethnic ?:string; //Dân tộc
  religion ?:string; //Tôn giáo
  academic_level ?:string;// Trình độ học vấn
  thumbnail ?:string;//Ảnh đại diện
  social_policy_object ?:string;// Đối tượng chính sách xã hội
  note?: string;//Ghi chú
  status?: number;// Tình trạng sinh viên
  role?: number;// Cán bộ lớp-sinh viên thường
    constructor(
        id?:number,
        user_name?: string,
        password?: string,//Mật khẩu
        student_code ?:string, //Mã sinh viên
        full_name ?:string,//Họ và tên
        gender?: number,//Giới tính
        permanent_residence ?:string,//Hộ khẩu thường trú
        class_id?: number,// Lớp
        major ?:string,//Chuyên ngành
        dob ?:string,// Ngày sinh
        pob ?:string,// Nơi sinh
        address ?:string,//Địa chỉ liên hệ
        countryside ?:string,// Quê quán
        training_type?: number,//Đại học - Cao đẳng
        school_year?:string,//Niên khóa
        email ?:string,
        phone ?:string,//Số điện thoại
        nationality?: number,// Quốc tịch
        citizen_identification ?:string, //CCCD/CMT
        ethnic ?:string, //Dân tộc
        religion ?:string, //Tôn giáo
        academic_level ?:string,// Trình độ học vấn
        thumbnail ?:string,//Ảnh đại diện
        social_policy_object ?:string,// Đối tượng chính sách xã hội
        note?: string,//Ghi chú
        status?: number,// Tình trạng sinh viên
        role?: number,
    ) {
        this.id = id;
        this.user_name=  user_name;
        this.password = password;//Mật khẩu
        this.student_code  =student_code; //Mã sinh viên
        this.full_name  =full_name;//Họ và tên
        this.gender = gender;//Giới tính
        this.permanent_residence  =permanent_residence;//Hộ khẩu thường trú
        this.class_id = class_id;// Lớp
        this.major  =major;//Chuyên ngành
        this.dob  =dob;// Ngày sinh
        this.pob  =pob;// Nơi sinh
        this.address  =address;//Địa chỉ liên hệ
        this.countryside  =countryside;// Quê quán
        this.training_type = training_type;//Đại học - Cao đẳng
        this.school_year  =school_year;//Niên khóa
        this.email  =email;
        this.phone  =phone;//Số điện thoại
        this.nationality = nationality;// Quốc tịch
        this.citizen_identification  =citizen_identification; //CCCD/CMT
        this.ethnic  =ethnic; //Dân tộc
        this.religion  =religion; //Tôn giáo
        this.academic_level  =academic_level;// Trình độ học vấn
        this.thumbnail  =thumbnail;//Ảnh đại diện
        this.social_policy_object  =social_policy_object;// Đối tượng chính sách xã hội
        this.note = note;//Ghi chú
        this.status = status;// Tình trạng sinh viên
        this.role = role;
    }
  }
