var body = document.querySelector('body')
var nav = document.getElementsByClassName('navigation_bar')[0];

// Div objects
var menubtn = document.createElement('button');
menubtn.classList.add("user_openbtn");
menubtn.innerHTML = "&#9776;";
menubtn.id = "menubtn";
nav.appendChild(menubtn);

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
// Log out (All)
var a4 = document.createElement('a');
a4.href="urban_apparel_logout_employee.php";
a4.textContent="Logout";
a4.classList.add("last-menu-item");
sideDiv.appendChild(a4);

body.appendChild(sideDiv);

var btn = document.getElementById("menubtn");
    btn.addEventListener('click', (event) => {
        if (btn.classList.contains("user_openbtn")) {
            document.getElementById("mySidebar").style.width = "250px";
            btn.classList.remove("user_openbtn");
            btn.classList.add("user_closebtn");
        } 
        else {
            document.getElementById("mySidebar").style.width = "0";
            btn.classList.remove("user_closebtn");
            btn.classList.add("user_openbtn");
        }
});