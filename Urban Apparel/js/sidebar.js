var body = document.querySelector('body')

var mainDiv = document.createElement('div');
mainDiv.id = "main";
// Div objects
var menubtn = document.createElement('button');
menubtn.classList.add("openbtn");
menubtn.innerHTML = "&#9776;";
menubtn.id = "menubtn";
mainDiv.appendChild(menubtn);

var sideDiv = document.createElement('div');
sideDiv.classList.add("sidebar");
sideDiv.id = "mySidebar";

// Populating sidebar

//Search bar
var form = document.createElement('form');
form.classList.add("search-form");
form.action = "search.php";
form.method = "POST";

var span = document.createElement('span');

var input = document.createElement('input');
input.classList.add("search-input");
input.type = "text";
input.id = "prodSearch";
input.name = "prodSearch";
input.placeholder = "Search here";
span.appendChild(input);

var btn = document.createElement('button');
btn.classList.add("search-button");
btn.type = "submit";
btn.id = "submitS";
btn.name = "submitS";
btn.innerHTML = "&#9906;";
span.appendChild(btn);

form.appendChild(span);
sideDiv.appendChild(form);

// Creating links
// View profile (All)
var a1 = document.createElement('a');
a1.href="urban_apparel_profile.php";
a1.textContent="Profile";
sideDiv.appendChild(a1);
// View catalog (All)
var a2 = document.createElement('a');
a2.href="urban_apparel_home.php";
a2.textContent="Catalog";
sideDiv.appendChild(a2);
// Add product (Manager)
var a6 = document.createElement('a');
a6.href="add_product.php";
a6.textContent="Add Product";
sideDiv.appendChild(a6);
// Add employee (Manager)
var a7 = document.createElement('a');
a7.href="add_employee.php";
a7.textContent="Add Employee";
sideDiv.appendChild(a7);
// Manage employees (Manager)
var a8 = document.createElement('a');
a8.href="manage_employees.php";
a8.textContent="Manage Employees";
sideDiv.appendChild(a8);
// Log out (All)
var a4 = document.createElement('a');
a4.href="urban_apparel_logout.php";
a4.textContent="Logout";
a4.classList.add("last-menu-item");
sideDiv.appendChild(a4);

mainDiv.appendChild(sideDiv);

body.appendChild(mainDiv);

var btn = document.getElementById("menubtn");
    btn.addEventListener('click', (event) => {
        if (btn.classList.contains("openbtn")) {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
            btn.classList.remove("openbtn");
            btn.classList.add("closebtn");
        } else {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
            btn.classList.remove("closebtn");
            btn.classList.add("openbtn");
        }
});