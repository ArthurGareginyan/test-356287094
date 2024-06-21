function getOrders() {
    const clientId = document.getElementById('client_id').value;
    fetch(`server.php?client_id=${clientId}`)
        .then(response => response.json())
        .then(data => {
            let result = '';
            if (data.error) {
                result = data.error;
            } else {
                data.forEach(order => {
                    result += `Full Name: ${order.full_name}, Title: ${order.title}, Price: ${order.price}<br>`;
                });
            }
            document.getElementById('result').innerHTML = result;
        });
}

function addProducts(event) {
    event.preventDefault();
    const products = [];
    const productForms = document.querySelectorAll('.product-form');
    let isValid = true;
    let errorMessage = '';

    productForms.forEach(form => {
        const title = form.querySelector('.product-title').value;
        const price = form.querySelector('.product-price').value;
        if (title && price) {
            products.push({
                title: title,
                price: parseFloat(price)
            });
        } else {
            isValid = false;
            errorMessage = 'All product fields must be filled out.';
        }
    });

    if (isValid) {
        fetch('add_products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(products)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Products added successfully');
                } else {
                    alert('Failed to add products');
                }
            });
    } else {
        alert(errorMessage);
    }
}

function addProductForm() {
    const productContainer = document.getElementById('product-container');
    const productForm = document.createElement('div');
    productForm.className = 'product-form';
    productForm.innerHTML = `
        <label>Title: <input type="text" class="product-title" /></label>
        <label>Price: <input type="number" step="0.01" class="product-price" /></label>
        <button type="button" onclick="this.parentElement.remove()">Remove</button>
        <br><br>
    `;
    productContainer.appendChild(productForm);
}

document.getElementById('add-product-form').addEventListener('click', addProductForm);
document.getElementById('submit-products').addEventListener('click', addProducts);
