document
  .getElementById("medicine-form")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const messageContainer = document.getElementById("message-container");

    fetch(form.action, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        messageContainer.innerHTML = data;
        if (data.includes("successfully")) {
          form.reset();
        }
      })
      .catch((error) => {
        messageContainer.innerHTML = `<div class="alert error"><i class="fas fa-exclamation-circle"></i> Error: ${error.message}</div>`;
      });
  });


    function Popup() {
const medname = document.getElementById("name")
const brand = document.getElementById("brand")
const des = document.getElementById("description")
const number = document.getElementById("quantity")
const price = document.getElementById("price")
const dosage = document.getElementById("dosage")
const edate = document.getElementById("edate")
var arr = [medname,brand,des,number,price,dosage,edate]
var count = 0
for(var i =0;i<7;i++)
{
if(arr[i].value.trim() !== "")
count++;
else
count+=0;
}
if(count===7)
{
  const popup = document.getElementById("popup");
  popup.style.display = "flex";
  
  setTimeout(() => {
    popup.style.display = "none";
  }, 3000);
}
else
return;
}

 

