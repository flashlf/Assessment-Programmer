// Define Global Variabel untuk Username dan jumlah item pada keranjang
var username;
var totalProduct;
var listProduct = [];
var cartUser = [];

function login(vUsername) {
    if (vUsername == "") {
        alert("Maaf username tidak boleh kosong");
        return false;
    }

    username = vUsername;
    // set to LocalStorage
    localStorage.setItem("username", username);

    // set to LocalStorage cartUser jika belum ada
    if (localStorage.getItem(`cart-${username}`) == null) {
        localStorage.setItem(`cart-${username}`, cartUser);
    } else {
        cartUser = JSON.parse(localStorage.getItem(`cart-${username}`));
    }

    return true;
}

function logout() {
    localStorage.removeItem('username');
    cartUser = [];
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
        containerProducts.innerHTML = "";
    } else {
        usernameText.innerHTML = username;
        loginAccess.style.display = "none";
        keranjangAccess.style.display = "block";
        logoutAccess.style.display = "block";
        getAllProducts();
    }
}

getAllProducts = async () => {
    try {
        const response = await fetch("https://6554347063cafc694fe63a4b.mockapi.io/api/v1/products");
        const data = await response.json();
        console.log(data);
        mappingProduct(data);

        const detail = await getDetailProducts();
    } catch (error) {
        console.error('Error:', error);
    }
}

function getDetailProducts() {
    return new Promise((resolve, reject) => {
      fetch("https://6554347063cafc694fe63a4b.mockapi.io/api/v1/details")
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => { 
            data.forEach((element, index, array) => {
                if (listProduct[index]['id'] == element.produt_id) {
                    listProduct[index].setDetail(element);
                }
            })
            displayAllProducts();
            resolve(data);
        })
        .catch(error => {
          console.error('Error:', error);
          reject(error);
        });
    });
  }

mappingProduct = (product) => {
    product.forEach((element, index, array) => {

        const tempProductClass = new Product(element.id, element.name, element.price, element.createdAt);

        listProduct.push(tempProductClass);
    });
}

displayAllProducts = () => {
    listProduct.forEach((element, index, array) => {
        const tempElement = document.createElement('div');

        const productTemplate = `
        <div class="border rounded overflow-hidden flex-md-row mb-4 post">
            <div class="col p-4 d-flex flex-column position-static">
                <strong class="d-inline-block mb-2 text-success">$ ${element.price}</strong>
                <h3 class="mb-0">${element.name}</h3>
                <div class="mb-1 text-muted">${element.createdAt}</div>
                <p class="card-text mb-auto">${element.detail.description}</p><br>
                <a class="btn btn-sm btn-success" onclick="addProductToCart(${index})">Add to Cart</a>
            </div>
        </div>
        `;

        tempElement.innerHTML = productTemplate;
        tempElement.id = `product${element.id}`;
        tempElement.classList.add("col-md-4");
        containerProducts.appendChild(tempElement);
    });
}

function addProductToCart(id) {
    cartUser.push(listProduct[id].toJsonObject());
    alert(`Product ${listProduct[id].name} berhasil ditambahkan ke keranjang`);
}
class Product {
    // Properti 
    id;
    name;
    price;
    createdAt;
    detail;
    discount;
    discount_coupon = 0;

    constructor(id, name, price, createdAt) {
        this.id = id, this.name = name; this.price = price;
        this.createdAt = createdAt;

        if (id % 7 == 0) {
            this.discount = 77;
        } else {
            this.discount = 0;
        }
    }

    setDetail(dataDetail) {
        this.detail = dataDetail;
    }

    setCoupon(value) {
        this.discount_coupon = value;
    }

    getPriceAfterDiscount() {
        let totalDiscount = (this.discount + this.discount_coupon);
        return (price - totalDiscount);
    }

    toJsonObject() {
        return JSON.stringify(this);
    }
}

function storeCartLocalStorage() {
    const temp = [];
    const combinedObject = cartUser.reduce((acc, jsonString) => {
        const obj = JSON.parse(jsonString);
        console.log(obj);
        temp.push(obj);
        return { ...acc, ...obj };
    }, {});

    console.log(temp);
    const finalJSON = JSON.stringify(temp);
    localStorage.setItem(`cart-${username}`, finalJSON);
}