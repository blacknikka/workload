import Department from './department';

export default class User {
  constructor(id, name, email, { departmentName, sectionName, comment }) {
    this.id = id;
    this.name = name;
    this.email = email;
    this.department = new Department(departmentName, sectionName, comment);
  }
}
