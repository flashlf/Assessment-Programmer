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
        mappingProduct(data);
    } catch (error) {
        console.error('Error:', error);
    }
}

mappingProduct = (resource) => {
    resource.forEach(element => {
        const tempElement = document.createElement('div');

        const productTemplate = `
        <div class="border rounded overflow-hidden flex-md-row mb-4 post">
            <div class="col p-4 d-flex flex-column position-static">
                <strong class="d-inline-block mb-2 text-success">$ ${element.price}</strong>
                <h3 class="mb-0">${element.name}</h3>
                <div class="mb-1 text-muted">${element.createdAt}</div>
                <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                <a href="#">Continue reading</a>
            </div>
        </div>
        `;

        tempElement.innerHTML = productTemplate;
        tempElement.id = `product${element.id}`;
        tempElement.classList.add("col-md-4");
        containerProducts.appendChild(tempElement);

    });
}