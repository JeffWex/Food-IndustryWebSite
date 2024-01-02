$(document).ready(function () {
    debugger;
    loadUsers();

});
const buttonPoper = (tr)=>{
    debugger;
    let td = document.createElement("td");
    tr.append(td);
    return tr;
 }
 
const tablePoper = (data) => {
    debugger;
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

function loadUsers() {
    debugger;
    $.ajax({
        method: 'GET',
        url: 'http://localhost/php/fast-food-website/BackEnd/Adm_userReport.php',
        success: function (response) {
            debugger;
            console.log(response);
            const userList = $('#userTable');
            userList.empty();

            tablePoper(response);
        },
        error: function (error) {
            console.log(error);
        },
    });
}