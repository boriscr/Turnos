let estadoTxt = document.getElementById('estadoTxt');
document.getElementById('toggle').addEventListener('change', function() {  
    if (this.checked) {  
        estadoTxt.innerHTML = "Activo"; 
        estadoTxt.style.color = "green";
    } else {  
        estadoTxt.innerHTML = "Inactivo";
        estadoTxt.style.color = "red";
    }  
});  

let estadoTxtEsp = document.getElementById('estadoTxt-esp');
document.getElementById('toggle-esp').addEventListener('change', function() {  
    if (this.checked) {  
        estadoTxtEsp.innerHTML = "Activo"; 
        estadoTxtEsp.style.color = "green";
    } else {  
        estadoTxtEsp.innerHTML = "Inactivo";
        estadoTxtEsp.style.color = "red";
    }  
});  


