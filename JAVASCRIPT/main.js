// Define Global Variabel untuk Username dan jumlah item pada keranjang
let username;
let totalProduct;

function login(vUsername) {
    if (vUsername == "") {
        alert("Maaf username tidak boleh kosong");
        return false;
    }

    username = vUsername;
    // set to LocalStorage
    localStorage.setItem("username", username);

    return true;
}

function logout() {
    localStorage.removeItem('username');
    username = "";
    alert("Berhasil logout");
}

function loginState(state = false)
{
    if (state === false) {
        usernameText.innerHTML = "";
        loginAccess.style.display = "block";
        keranjangAccess.style.display = "none";
        logoutAccess.style.display = "none";
    } else {
        usernameText.innerHTML = username;
        loginAccess.style.display = "none";
        keranjangAccess.style.display = "block";
        logoutAccess.style.display = "block";
    }
}