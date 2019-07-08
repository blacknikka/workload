
export default class Workload {
  constructor(date, amount, projectId, categoryId) {
    this._date = date;
    this._amount = amount;
    this._projectId = projectId;
    this._categoryId = categoryId;
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
}
