$(document).ready(function () {
  loadOrder();
});
const buttonPoper = (tr) => {
  let td = document.createElement("td");
  tr.append(td);
  return tr;
};

const tablePoper = (data) => {
  document.querySelector("table").removeAttribute("style");
  for (let m of data) {
    let tr = document.createElement("tr");
    for (let p in m) {
      let td = document.createElement("td");
      td.innerText = m[p];
      tr.append(td);
    }
    document.querySelector("tbody").append(buttonPoper(tr));
  }
};

let sales = [];
function loadOrder() {
  $.ajax({
    method: "GET",
    url: "http://localhost/php/fast-food-website/BackEnd/orderSales.php",
    data: { userid: JSON.stringify(sessionStorage.getItem("user")) },
    success: function (response) {
      debugger;
      console.log(response);
      sales.push(response);

      tablePoper(response);
      salesTotal();
    },
    error: function (error) {
      console.log(error);
    },
  });

  const salesTotal = () => {
    let sum = 0;
    for (let s of sales) {
      for (let v of s) {
        sum += parseFloat(v.total_values);
      }
    }
    let totalTr = document.querySelector("tfoot").firstElementChild;
    totalTr.lastElementChild.innerHTML = `CAD$ ${sum}`;
  };
}
