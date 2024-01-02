
class Item {
    constructor(id, product, selctAmount, cost) {
        this.id = id;
        this.product = product;
        this.selctAmount = selctAmount;
        this.cost = cost;
    }
}

class User {
    constructor(user_id, user_fname, user_lname, user_type){
        this.user_id = user_id;
        this.user_fname = user_fname;
        this.user_lname = user_lname;
        this.user_type = user_type;
    }
}

let userInfo = JSON.parse(sessionStorage.getItem("user"));

let selectedItems = [];


$(document).ready(function () {
    const userType = userInfo.userType;

    // for ADMIN
    if(userType == "A"){
        loadMenu();
        
        $('#addMenuBtn').on('click', function () {
            const prodName = $('#food').val();
            const quantity = $('#amount').val();
            const price = $('#price').val();
            const prodDescr = $('#description').val();
           
            const userDataForA = {
                user: userType,
                prodName: prodName,
                quantity: quantity,
                price: price,
                prodDescr: prodDescr,
            };

            $.ajax({
                url: 'http://localhost/php/fast-food-website/BackEnd/menu.php',
                method: 'POST',
                // data: JSON.stringify({ prodName, quantity, price, prodDescr }),
                data: JSON.stringify(userDataForA),
                contentType: 'application/json',
                success: function (response) {
                    console.log(response);
                    loadMenu();
                },
                error: function (error) {
                    console.log(error); 
                },
            });
        });
    } else{
        // userType == "C" For CUSTOMER
        loadMenuForC();

        $('#saveMenuButton').on("click", function(){

            const userDataForC = {
                user: JSON.stringify(sessionStorage.getItem("user")),
                prod: JSON.stringify(selectedItems),
            };


            $.ajax({
                url: 'http://localhost/php/fast-food-website/BackEnd/menu.php',
                method: 'POST',
                data: JSON.stringify(userDataForC),
                contentType: 'application/json',
                success: function (response) {
                    console.log(response);
                    location.replace("./orderSales.html");
                    // loadMenuForC();
                },
                error: function(error) {
                    location.replace("./orderSales.html");
                    console.log(error); 
                },
                dataType: 'json',
            });
        })
        
    }

});


const deleteHandler = (id) =>{
    $.ajax({
        url: 'http://localhost/php/fast-food-website/BackEnd/menu.php',
        method: 'DELETE',
        data: JSON.stringify({ id }),
        contentType: 'application/json',
        success: function (response) {
            console.log(response);
            loadMenu();
        },
        error: function (error) {
            console.log(error);
        },
    });
}

const EditHandler = (id, prodName, quantity, price, prodDescr) =>{
    $('#addMenuForm').on('click', function () {
        $('#food').val() = prodName;
    })
    
    $.ajax({
        url: 'http://localhost/php/fast-food-website/BackEnd/menu.php',
        method: 'PUT',
        data: JSON.stringify({ id, prodName, quantity, price, prodDescr }),
        contentType: 'application/json',
        success: function (response) {
            console.log(response);
            loadMenu();
        },
        error: function (error) {
            console.log(error);
        },
    });
}

const buttonPoper = (tr)=>{
    let td = document.createElement("td");
    let editBt = document.createElement("button");
    editBt.innerText = "Edit";
    editBt.className = "btn btn-primary"
    let delBt = document.createElement("button");
    delBt.innerText = "Delete";
    delBt.className = "btn btn-danger";
    let file = document.createElement("input");
    file.type="file";
    file.name="file";
    
    td.append(editBt,delBt);
    tr.append(td);
    editBt.addEventListener("click", ()=>{
        EditHandler(tr.children[0].innerText, tr.children[1].innerText, tr.children[2].innerText, tr.children[3].innerText,tr.children[5].innerText);
    });
    delBt.addEventListener("click", ()=>{
        deleteHandler(tr.children[0].innerText);
    });
    return tr;

 }


//  const FileHandler = (file, submit) =>{
//     var formdata = new FormData();
//     formdata.append("file", fileName);
//     formdata.append("submit", btnName);

//     var request = new XMLHttpRequest();
//     request.open("POST", " http://localhost/php/Final_Project_AdminstratorPage/BackEnd/img_menu.php");
//     request.send(formdata);
//  }

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

function loadMenu() {
    $.ajax({
        method: 'GET',
        url: 'http://localhost/php/fast-food-website/BackEnd/menu.php',
        success: function (response) {
            console.log(response);
            const menuList = $('#menuTable');
            menuList.empty();

            tablePoper(response);
        },
        error: function (error) {
            console.log(error);
        },
    });
}

///////// FOR CUSTOMER ///////////


const FileHandlerForC = (clickedBtn, data) => {
    
    // Get the text and price of the clicked item
    var selectedItem = $(clickedBtn).parent().parent().children(); //slice the string without "add"
    var itemPrice = parseFloat($(clickedBtn).parent().parent().children()[3]);
    var productName = $(clickedBtn).parent().parent().children().eq(1).text();
    let editItem = null;

    // append at selectedItems
    // if it already exit in the selectedItem array -> change only amount
    selectedItems.forEach(function (val, idx) {
        if (val.product == productName) {
        editItem = val;
        }
    });

    if (editItem) {
        // add one more amount
        editItem.selctAmount++;
    } else {
        $.each(data, function (idx, value) {
            // console.log(value);
        // if the value.product and productName is same,
        if (value.prodName == productName) {
            let slcObj = new Item(value.id, value.prodName, 1, value.price);
            selectedItems.push(slcObj);
        }
        });
    }
}

 const buttonPoperForC = (tr, data)=>{
    let td = document.createElement("td");
    let addBt = document.createElement("button");
    addBt.innerText = "Add";
    addBt.className = "btn btn-info"

    addBt.addEventListener("click", ()=>{
        FileHandlerForC(addBt, data);

    });
    td.append(addBt);
    tr.append(td);
    return tr;

 }

const tablePoperForC = (data) => {
    document.querySelector("table").removeAttribute("style");
    for (let m of data) {
       let tr = document.createElement("tr");
       for (let p in m) {
          let td = document.createElement("td");
          td.innerText = m[p];
          tr.append(td);
       }
       document.querySelector("tbody").append(buttonPoperForC(tr, data));
    }
 }

function loadMenuForC() {
    $.ajax({
        method: 'GET',
        url: 'http://localhost/php/fast-food-website/BackEnd/menu.php',
        // data:{ "req":""},
        success: function (response) {
            // console.log(response);
            const menuList = $('#menuTableForC');
            menuList.empty();

            tablePoperForC(response);
        },
        error: function (error) {
            console.log(error);
        },
    });
}