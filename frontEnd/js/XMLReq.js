class XMLReq {
  constructor(uri) {
    this.uri = uri;
  }
  #xmlreq(method, data = "") {
    let dataPromise = new Promise((res, rej) => {
      let xhr = new XMLHttpRequest();
      xhr.onload = () => {
        if (xhr.status == 200) {
          res(xhr.responseText);
        } else {
          rej(xhr.statusText);
        }
      };
      xhr.open(method, this.uri);
      data != "" ? xhr.send(data) : xhr.send();
    });
    return dataPromise;
  }
  Post(data) {
    return this.#xmlreq("POST", data);
  }
  Get() {
    return this.#xmlreq("GET");
  }
}
export default XMLReq;
