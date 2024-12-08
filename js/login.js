document.addEventListener("DOMContentLoaded", (event)=>{
    let loginForm = document.getElementById("loginForm");

    loginForm.addEventListener("submit", (element) =>{
        console.log("Submitted");

        var emailInput = document.querySelector("#email");
        var passwordInput = document.querySelector("#password");

        const username = emailInput?.value?.toLowerCase()?.trim();
        const password = passwordInput?.value?.trim();

        emailInput.value = "";
        passwordInput.value = "";

        var valid = true;
        
        if(isEmpty(username)){
            valid = false;
        }

        if(isEmpty(password)){
            valid = false;
        }

        if(!valid){
            console.log("Invalid");
            alert("Invalid Credentials");
            element.preventDefault();
            return;
        }
        console.log("Valid");


    });
})

function isEmpty(elementValue) {
    if (elementValue.length == 0) {
      console.log('field is empty');
      return true;
    }
  
    return false;
  }
  