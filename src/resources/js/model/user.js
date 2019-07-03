import Department from './department';

export default class User {
  constructor(name, email, { departmentName, sectionName, comment }) {
    this.name = name;
    this.email = email;
    this.department = new Department(departmentName, sectionName, comment);
  }
}
