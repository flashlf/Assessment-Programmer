// Define Global Variabel untuk Username dan jumlah item pada keranjang
var username;
var totalPriceProductsBeforeDiscount;
var totalDiscount;
var totalPriceProducts;
var totalShipmentPrice;
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
        if (localStorage.getItem(`cart-${username}`) == '') {
            cartUser = [];
        } else {
            cartUser = JSON.parse(localStorage.getItem(`cart-${username}`));
        }
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
        cartListProducts.innerHTML = "";
        totalDiscount = 0;
        totalPriceProducts = 0;
        totalPriceProductsBeforeDiscount = 0;
        totalShipmentPrice = 0;
        cartTotalDiscount.innerHTML = "";
        cartTotalPrice.innerHTML = "";
        cartTotalShipment.innerHTML = "";
        cartFinalPrice.innerHTML = "";
    } else {
        usernameText.innerHTML = username;
        loginAccess.style.display = "none";
        keranjangAccess.style.display = "block";
        logoutAccess.style.display = "block";
        getAllProducts();

        // set to LocalStorage cartUser jika belum ada
        if (localStorage.getItem(`cart-${username}`) == null) {
            localStorage.setItem(`cart-${username}`, cartUser);
        } else {
            cartUser = JSON.parse(localStorage.getItem(`cart-${username}`));
        }
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
    const coupon = prompt("Masukan voucher coupon apabila ada :");
    valueCoupon(coupon, id);
    temp = [];
    cartUser.forEach((element, index, array) => {
        temp.push(element);
    });
    cartUser = [];
    temp.push(listProduct[id]);
    cartUser = temp;
    alert(`Product ${listProduct[id].name} berhasil ditambahkan ke keranjang`);

    storeCartLocalStorage();
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

    toJsonObject() {
        return JSON.stringify(this);
    }
}

function storeCartLocalStorage() {
    const temp = [];
    const combinedObject = cartUser.reduce((acc, obj) => {
        temp.push(obj);
        return { ...acc, ...obj };
    }, {});

    console.log(temp);
    const finalJSON = JSON.stringify(temp);
    localStorage.setItem(`cart-${username}`, finalJSON);
}

function listCart() {
    if (cartUser.length == 0) {
        alert("Keranjang Anda masih kosong");
        cartListProducts.innerHTML = "";
    } else {

        let i = 1; 
        cartListProducts.innerHTML = "";
        totalPriceProducts = 0;
        totalPriceProductsBeforeDiscount = 0;
        totalShipmentPrice = 0;
        totalDiscount = 0;
        let currentProduct;
        cartUser.forEach((element, index, array) => {
            listProduct.forEach((elProduct, idxProduct, arrProduct) => {
                if (elProduct.id == element.id) {
                    currentProduct = elProduct;
                }
            });
    
            const tempElement = document.createElement('tr');
    
            const productTemplate = `
            <td>${i++}</td>
            <td>${element.name}</td>
            <td>${element.price}</td>
            <td class="text-center">${element.discount} : ${element.discount_coupon}</td>
            <td class="text-center">${getShipmentPrice(element.price)}</td>
            <td><strong>${getFinalPriceProduct(element.price, element.discount, element.discount_coupon)}</strong></td>
            `;
            totalDiscount += parseInt(element.discount + element.discount_coupon);
            totalPriceProductsBeforeDiscount += parseInt(element.price);
            totalPriceProducts += getFinalPriceProduct(element.price, element.discount, element.discount_coupon);
            totalShipmentPrice += getShipmentPrice(element.price);
    
            tempElement.innerHTML = productTemplate;
            tempElement.id = `cartProduct${element.id}`;
            cartListProducts.appendChild(tempElement);
        })
    
        // Summary last row
        const tempElement = document.createElement('tr');
    
        const productTemplate = `
        <td colspan="2" class="text-right"><strong>Summary</strong></td>
        <td class="text-primary text-center"><strong>${totalPriceProductsBeforeDiscount}</strong></td>
        <td class="text-primary text-center"><strong>${totalDiscount}</strong></td>
        <td class="text-primary text-center"><strong>${totalShipmentPrice}</strong></td>
        <td class="text-primary "><strong>${totalPriceProducts + totalShipmentPrice}</strong></td>
        `;
    
        tempElement.innerHTML = productTemplate;
        tempElement.id = `cartSummary`;
        cartListProducts.appendChild(tempElement);
    
        cartTotalDiscount.innerHTML = totalDiscount;
        cartTotalShipment.innerHTML = totalShipmentPrice;
        cartTotalPrice.innerHTML = totalPriceProductsBeforeDiscount;
        cartFinalPrice.innerHTML = (totalPriceProducts + totalShipmentPrice);
    }

}

function valueCoupon(stringCode, indexProduct) {
    let valueCoupon;
    switch (stringCode) {
        case "TOKPED1111":
            valueCoupon = 11;
            break;
        case "BELANJAHAPPY" :
            valueCoupon = 20;
            break;
        default:
            valueCoupon = 0;
            break;
    }

    listProduct[indexProduct].setCoupon(valueCoupon);
}

getFinalPriceProduct = (price, discount, coupon) => {
    return price - (discount + coupon);
}

getShipmentPrice = (price) => {
    let shipmentPrice;
    switch (price) {
        case (price > 10 && price < 20):
            shipmentPrice = 10;
            break;
        case (price > 20 && price < 30):
            shipmentPrice = 20;
            break;
        case (price > 30 && price < 50):
            shipmentPrice = 30;
            break;
        case (price > 60 && price < 100):
            shipmentPrice = 40;
            break;
        case (price > 100):
        default:
            shipmentPrice = 50;
            break;
    }

    return shipmentPrice;
}

function checkout() {
    let message = `
        Anda berhasil checkout ${cartUser.length} Produk, dengan total biaya $ ${totalShipmentPrice + totalPriceProducts}
    `;
    alert(message);
    cartUser = [];
    cartListProducts.innerHTML = 0;
    localStorage.setItem(`cart-${username}`, '');
    totalDiscount = 0;
    totalPriceProducts = 0;
    totalPriceProductsBeforeDiscount = 0;
    totalShipmentPrice = 0;
    cartTotalDiscount.innerHTML = "";
    cartTotalPrice.innerHTML = "";
    cartTotalShipment.innerHTML = "";
    cartFinalPrice.innerHTML = "";
}