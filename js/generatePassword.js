
//функция генерирует пароль и подставляет его в input по id
function generatePasswordIntoInputByID(size, id){
    let password = "";
    let symbols = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!№;%:?*()_+=";
    for (let i = 0; i < size; i++){
        password += symbols.charAt(Math.floor(Math.random() * symbols.length)); //возвращает случайный символ из строки symbols
    }

    let fieldById = document.getElementById(id);
    fieldById.value = password;
}