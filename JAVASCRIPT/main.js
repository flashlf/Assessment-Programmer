// Define Global Variabel untuk Username dan jumlah item pada keranjang
var username;
var totalProduct;

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
    loginState(false);
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

getAllProducts = async () => {
    try {
        const response = await fetch("https://6554347063cafc694fe63a4b.mockapi.io/api/v1/products");
        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error('Error:', error);
    }
}

mappingProduct = (resource) => {

}