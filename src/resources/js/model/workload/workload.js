
export default class Workload {
  /**
   * 
   * @param {Number|null} id id or null.
   * @param {Date} date 日付
   * @param {Number} amount 工数
   * @param {Project} project Project
   * @param {Category} category Category
   * @param {Boolean} isUpdated Update済みかどうか（POSTする必要があるかどうか）
   */
  constructor(id, date, amount, project, category, isUpdated = false) {
    this._id = id;
    this._date = date;
    this._amount = amount;
    this._project = project;
    this._category = category;
    this._isUpdated = isUpdated;
    this._isDeleted = false;
  }

  get id() {
    return this._id;
  }

  set id(value) {
    this._id = value;
  }

  get date() {
    return this._date;
  }

  get amount() {
    return this._amount;
  }

  get project() {
    return this._project;
  }

  get category() {
    return this._category;
  }

  get isUpdated() {
    return this._isUpdated;
  }
  
  setThisHasBeenOld() {
    this._isUpdated = false;
  }

  setThisHasDeleted() {
    this._isDeleted = true;
  }
}
