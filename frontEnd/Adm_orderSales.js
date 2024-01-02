$(document).ready(function () {
    debugger;
    loadOrder();

});
const buttonPoper = (tr)=>{
    let td = document.createElement("td");
    tr.append(td);
    return tr;
 }
 
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
 }

 function loadFilter() {
    debugger;
    let startDate = $("#startDate").val();
    let endDate = $("#endDate").val();
    debugger;
    $.ajax({
        method: 'POST',
        url: 'http://localhost/php/fast-food-website/BackEnd/Adm_filter.php',
        data: JSON.stringify({ startDate, endDate }),
        contentType: 'application/json',
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
}

 let sales = [];
function loadOrder() {
    debugger;
    $.ajax({
        method: 'GET',
        url: 'http://localhost/php/fast-food-website/BackEnd/Adm_orderSales.php',
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
        debugger;
        let sum = 0;
        for (let s of sales) {
            for(let v of s){
                sum += parseFloat(v.total_values);
            }
           
        }
        let totalTr = document.querySelector('tfoot').firstElementChild;
        totalTr.lastElementChild.innerHTML = `CAD$ ${sum}`;
    }
}