    let listCart = null;
function checkCart() {
    if (sessionStorage.getItem("listCart")) {
        try {
            listCart = JSON.parse(sessionStorage.getItem("listCart"));
        } catch (error) {
            console.error("Error parsing listCart from sessionStorage:", error);
            listCart = [];
        }
    } else {
        listCart = [];
    }
}

checkCart();
addCartToHTML();

function addCartToHTML() {
    let listCartHTML = document.querySelector('.returnCart .list');
    listCartHTML.innerHTML = '';

    let totalQuantityHTML = document.querySelector('.totalQuantity');
    let totalPriceHTML = document.querySelector('.totalPrice');
    let totalQuantity = 0;
    let totalPrice = 0;

    if (Array.isArray(listCart) && listCart.length > 0) {
        listCart.forEach(product => {
            if (product) {
                if (product.image && product.name && product.price && product.quantity) {
                    let newCart = document.createElement('div');
                    newCart.classList.add('item');
                    newCart.innerHTML =
                        `<img src="${product.image}">
                        <div class="info">
                            <div class="name">${product.name}</div>
                            <div class="price">₱${product.price}/1 product</div>
                        </div>
                        <div class="quantity">${product.quantity}</div>
                        <div class="returnPrice">₱${product.price * product.quantity}</div>`;
                    listCartHTML.appendChild(newCart);
                    totalQuantity += product.quantity;
                    totalPrice += product.price * product.quantity;
                }
            }
        });
    }
    totalQuantityHTML.innerText = totalQuantity;
    totalPriceHTML.innerText = totalPrice;
}

function checkBtn(event) {
    if (event) event.preventDefault();

    const inform = JSON.stringify(getCartItems());
    const quantity = calculateTotalQuantity();
    const rPrice = calculateTotalPrice();
    const pname = document.querySelector('input[name="name"]').value;
    const pnumber = document.querySelector('input[name="phone"]').value;
    const paddress = document.querySelector('input[name="address"]').value;
    const ocountry = document.querySelector('input[name="country"]').value;
    const ocity = document.querySelector('input[name="city"]').value;

    const data = new URLSearchParams();
    data.append('checkBtn', '1');
    data.append('inform', inform);
    data.append('oquantity', quantity);
    data.append('rPrice', rPrice);
    data.append('pname', pname);
    data.append('pnumber', pnumber);
    data.append('paddress', paddress);
    data.append('ocountry', ocountry);
    data.append('ocity', ocity);

    fetch('config.php', {
        method: 'POST',
        body: data,
    })
    .then(response => response.text())
    .then(text => {
        // Clear cart after successful checkout
        sessionStorage.removeItem('listCart');
        // Redirect based on user role
        if (typeof window.userRole !== 'undefined') {
            if (window.userRole === 'Member') {
                window.location.href = 'member-history.php';
            } else if (window.userRole === 'Admin') {
                window.location.href = 'orderlist.php';
            } else {
                window.location.href = 'orderlist.php';
            }
        } else {
            window.location.href = 'orderlist.php';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });

    return false;
}

function getCartItems() {
    if (Array.isArray(listCart)) {
        return listCart.filter(item => item && item.id != null).map(item => ({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: item.quantity
        }));
    }
    return [];
}

function calculateTotalQuantity() {
    if (Array.isArray(listCart)) {
        return listCart.filter(item => item && item.quantity != null).reduce((total, item) => total + (item.quantity || 0), 0);
    }
    return 0;
}

function calculateTotalPrice() {
    if (Array.isArray(listCart)) {
        return listCart.filter(item => item && item.price != null && item.quantity != null).reduce((total, item) => total + ((item.price || 0) * (item.quantity || 0)), 0);
    }
    return 0;
}
