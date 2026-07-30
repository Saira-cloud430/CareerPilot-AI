const hour = new Date().getHours();

let greeting = "Good Evening";

if(hour < 12){

greeting = "Good Morning";

}

else if(hour < 18){

greeting = "Good Afternoon";

}

document.getElementById("greeting").innerHTML =
greeting + ", " + document.querySelector(".fw-semibold").innerText + " 👋";



const cards = document.querySelectorAll(".tool-card");

cards.forEach((card,index)=>{

card.style.opacity="0";

card.style.transform="translateY(40px)";

setTimeout(()=>{

card.style.transition=".6s";

card.style.opacity="1";

card.style.transform="translateY(0)";

},index*120);

});