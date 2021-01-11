/*
 -----------------------------------------------------------------------------------
 Projet BDR
 File        : check_registration.js
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 11.01.2021
 Goal        : Contains the functions used to verify the registration fields

 Comment(s) : -
 -----------------------------------------------------------------------------------
 */

function validateRegistration() {
    // Variables declaration
    let isFormValid = true;      // This is the validation boolean returned at the end of the verification

    // Gets the form fields
    let itemUsername         = document.getElementsByName('reg_username')[0];
    let itemFirstName        = document.getElementsByName('reg_firstname')[0];
    let itemLastName         = document.getElementsByName('reg_lastname')[0];
    let itemEmail            = document.getElementsByName('reg_email')[0];
    let itemPassword         = document.getElementsByName('reg_password')[0];

    // Checks the first name field
    if (!(/^[a-z]+$/i.test(itemFirstName.value))) {
        // Displays the error and deactivates the valid boolean
        isFormValid = false;
        displayError(itemFirstName, "Ce prénom n'est pas valide");
    }

    // Checks the last names field
    if (!(/^[a-z]+$/i.test(itemLastName.value))) {
        // Displays the error and deactivates the valid boolean
        isFormValid = false;
        displayError(itemLastName, "Ce nom n'est pas valide");
    }

    // Checks the username field
    if (!(/^[a-z0-9_\-\.]+$/i.test(itemUsername.value))) {
        // Displays the error and deactivates the valid boolean
        isFormValid = false;
        displayError(itemUsername, "Le pseudo entré est invalide");
    }

    // Checks the mail field
    if (!(/^[a-z0-9\.]{2,}@[a-z0-9]{2,}\.([a-z]{2,4})$/i.test(itemEmail.value))) {
        // Displays the error and deactivates the valid boolean
        isFormValid = false;
        displayError(itemEmail, "Le format du mail doit être xxx@yyy.zzz");
    }

    // Checks the password
    if (!(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,}$/i.test(itemPassword.value))) {
        // Displays the error and deactivates the valid boolean
        isFormValid = false;
        displayError(itemPassword, "Doit contenir une majuscule, un chiffre et au minimum 4 caractères");
    }

    return isFormValid;
}

/**
 * Displays an error message under a wrong field.
 * @param itemWrongDiv, Represents the wrong div.
 * @param strErrorMessage, Represents the error message.
 */
function displayError(itemWrongDiv, strErrorMessage)
{
    // Colors the wrong field
    itemWrongDiv.style.border = "solid 1px red";

    // Creates a new div under the wrong field
    let errorDiv = document.createElement("span");
    errorDiv.setAttribute("class", "reg_error");

    // Inserts the new div
    itemWrongDiv.parentNode.insertBefore(errorDiv, itemWrongDiv.nextSibling);
}

/**
 * Clears all the errors messages.
 */
function clearErrors()
{
    // Gets all the errors divs in an array
    let arrErrorDivs = document.getElementsByClassName("reg_error");  // Contains all the inscription error divs
    let intErrorsCount = arrErrorDivs.length; // Contains the initial number of error divs

    // Deletes all the error divs and sets the borders to gray
    for (let i = 0; i < intErrorsCount; i++)
    {
        // Sets the borders to gray
        arrErrorDivs[0].previousSibling.style.border = "solid 1px darkgray";

        // Deletes the error div
        arrErrorDivs[0].parentNode.removeChild(arrErrorDivs[0]);
    }
}