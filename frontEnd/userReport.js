$(document).ready(function () {
  loadUsers();
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

function loadUsers() {
  let userType = JSON.parse(sessionStorage.getItem("user")).userType;
  if (userType == "C") {
    $(".table.table-striped").hide();
    var h1Element = document.createElement("h1");
    var textNode = document.createTextNode("You don't have permission.");
    h1Element.appendChild(textNode);
    document.body.appendChild(h1Element);
  } else {
    $.ajax({
      method: "GET",
      url: "http://localhost/php/fast-food-website/BackEnd/userReport.php",
      success: function (response) {
        console.log(response);
        const userList = $("#userTable");
        userList.empty();

        tablePoper(response);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}
