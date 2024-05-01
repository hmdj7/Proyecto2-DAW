
//boton ver catalogo
document.addEventListener('DOMContentLoaded', function() {
    const btnVerCatalogo = document.querySelector('.btn-custom.btn-large');
  
    if (btnVerCatalogo) {
        btnVerCatalogo.addEventListener('click', function() {
            window.location.href = '/videojuegos'; 
        });
    }
  });

  // Buscador
window.addEventListener('scroll', function() {
    var searchBox = document.querySelector('.search-box');
    if (window.scrollY > 0) {
        searchBox.classList.add('active');
    } else {
        searchBox.classList.remove('active');
    }
});

//alerta de añadir objetos al carrito 
function addProductToCart(id){
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/agregarAlCarrito?id='+id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            'id': id,
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.exito){
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se agrego correctamente',
                showConfirmButton: false,
                timer: 1000
            })
        }else if(!data.sesion){
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Sesión no iniciada',
                text: 'Inicie sesión para poder anadir objetos al carrito',
                showConfirmButton: false,
                timer: 1000
            })
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

//alerta de realizar pedido
function realizarPedido(id) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/realizarPedido?id=' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                'id': id,
            })
        })
        .then(response => response.json())
    .then(data => {
        if(data.exito){
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Pedido realizado correctamente',
                showConfirmButton: false,
                timer: 1000
            })
        }else if(!data.sesion){
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Sesión no iniciada',
                text: 'Inicie sesión para poder realizar el pedido',
                showConfirmButton: false,
                timer: 1000
            })
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });    
}

//icono usuario
document.addEventListener('DOMContentLoaded', function() {
    var userIcon = document.getElementById('user-icon');
    var dropdownContent = document.getElementById('user-dropdown-content');

    userIcon.addEventListener('click', function() {
        if (dropdownContent.style.display === 'block') {
            dropdownContent.style.display = 'none';
        } else {
            dropdownContent.style.display = 'block';
            positionDropdown();
        }
});

    // Función para posicionar el dropdown dependiendo del tamaño de la pantalla
function positionDropdown() {
    var rect = userIcon.getBoundingClientRect();
    var viewportHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
        
    if (viewportHeight - rect.bottom < dropdownContent.offsetHeight) {
        dropdownContent.style.bottom = (document.documentElement.clientHeight - rect.top) + 'px';
        dropdownContent.style.top = 'auto';
    } else {
        dropdownContent.style.top = (rect.top + userIcon.offsetHeight) + 'px';
        dropdownContent.style.bottom = 'auto';
    }

    dropdownContent.style.left = rect.left + 'px';
}

    // Cerrar el dropdown si se hace clic fuera de él
document.addEventListener('click', function(event) {
    if (!dropdownContent.contains(event.target) && event.target !== userIcon) {
        dropdownContent.style.display = 'none';
    }
});

    // Recalcular la posición del dropdown al cambiar el tamaño de la ventana
window.addEventListener('resize', function() {
    if (dropdownContent.style.display === 'block') {
            positionDropdown();
    }
});

    if (window.location.pathname === '/videojuegos') {
        loadProducts();
    }
});

// Filtro los productos
function filterProducts() {
    var searchText = document.getElementById('searchInput').value.toLowerCase();
    var products = document.querySelectorAll('.product-box');
    var productsByName = [];
    var productosRestantes = [];

    products.forEach(function(product) {
        var productName = product.querySelector('.product-title').textContent.toLowerCase();


        if (productName.includes(searchText)) {
            productsByName.push(product);
        }else{
            productosRestantes.push(product);
        }     
    });

    if(productsByName.length == 1){
        productsByName.forEach(function(product) {
            if(product.querySelector('.add-cart').getAttribute('data-id') == productsByName[0].querySelector('.add-cart').getAttribute('data-id')){ 
                window.location.href = `/videojuegos/${product.querySelector('.add-cart').getAttribute('data-id')}`;
            }
        });
    }else{
        productsByName.forEach(function(product) {
            product.style.display = 'block';
        });
    }

    productosRestantes.forEach(function(product) {
        product.style.display = 'none';
    });
}

// Función para cargar los productos desde la API
function loadProducts() {
    fetch('/api/videojuegos', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const shopContent = document.getElementById('shopContent');
        shopContent.innerHTML = ''; 
        data.forEach(videojuego => {
            const productBox = document.createElement('div');
            productBox.classList.add('product-box');
            
            
            const productLink = document.createElement('a');
            productLink.href = `/videojuegos/${videojuego.id}`; 
            productLink.classList.add('product-link');
            productBox.appendChild(productLink);
            
            const productImg = document.createElement('img');
            productImg.src = `/img/juegos/${videojuego.portada}`;
            productImg.alt = 'Imagen del videojuego';
            productImg.classList.add('product-img');
            productLink.appendChild(productImg);
            
            const productTitle = document.createElement('a');
            productTitle.classList.add('product-title');
            productTitle.textContent = videojuego.nombre;
            productBox.appendChild(productTitle);

            const lineBreak = document.createElement('br');
            productBox.appendChild(lineBreak);
            
            const productPrice = document.createElement('span');
            productPrice.classList.add('precio');
            productPrice.textContent = `${videojuego.precio} €`;
            productBox.appendChild(productPrice);
            
            const addToCartIcon = document.createElement('i');
            addToCartIcon.dataset.id = videojuego.id;
            addToCartIcon.classList.add('bx', 'bx-shopping-bag', 'add-cart');
            addToCartIcon.setAttribute('onclick', 'addProductToCart(' + videojuego.id + ')');
            productBox.appendChild(addToCartIcon);
            
            shopContent.appendChild(productBox);
        });
    })
    .catch(error => console.error('Error cargando productos:', error));
} 