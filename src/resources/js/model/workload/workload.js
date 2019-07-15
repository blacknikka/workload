
export default class Workload {
  /**
   * 
   * @param {Number|null} id id or null.
   * @param {Date} date 日付
   * @param {Number} amount 工数
   * @param {Number} projectId Project ID
   * @param {Number} categoryId Category ID
   * @param {Boolean} isUpdated Update済みかどうか（POSTする必要があるかどうか）
   */
  constructor(id, date, amount, projectId, categoryId, isUpdated = false) {
    this._id = id;
    this._date = date;
    this._amount = amount;
    this._projectId = projectId;
    this._categoryId = categoryId;
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

  get projectId() {
    return this._projectId;
  }

  get categoryId() {
    return this._categoryId;
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
