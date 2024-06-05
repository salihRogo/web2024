dNoneAddRemove = function () {
  const currentUser = window.localStorage.getItem("user");
  if (currentUser) {
    $("#login-link").addClass("d-none");
    $("#logout-link").removeClass("d-none");
    $("#profile-link").removeClass("d-none");
    $("#product-link").removeClass("d-none");
    $("#cart-link").removeClass("d-none");
  }
};

var app = $.spapp({
  defaultView: "#home",
  templateDir: "../frontend/pages/",
  pageNotFound: "error_404",
  reloadView: true,
});

app.route({
  view: "home",
  load: "home.html",
  onCreate: function () {},
  onReady: function () {
    get_home_products();
    if (window.localStorage.getItem("user_id") != null) {
      initialiseEmptyCart(window.localStorage.getItem("user_id"));
      findCartId(window.localStorage.getItem("user_id"))
        .then((cart_id) => {
          window.localStorage.setItem("cart_id", cart_id);
          console.log("cart_id in localstorage: " + cart_id);
        })
        .catch((error) => console.error(error.message));
    }
    dNoneAddRemove();
  },
});

initContactForm = function (formId) {
  var user_id = localStorage.getItem("user_id");
  FormValidation.validate(`${formId}`, {}, function (data) {
    data.user_id = user_id;
    Utils.block_ui("#sendMessageButton");
    RestClient.post(
      "inquiries",
      data,
      function (response) {
        Utils.unblock_ui("#sendMessageButton");
        document.getElementById(formId).reset();
        toastr.success("Your response is recorded.");
      },
      function (xhr) {}
    );
  });
};

initialiseEmptyCart = function (id) {
  RestClient.post(
    "carts",
    { user_id: id },
    function () {},
    function (xhr) {}
  );
};

findCartId = function (userId) {
  return new Promise((resolve, reject) => {
    RestClient.get("carts", function (data) {
      data.forEach((cart) => {
        if (cart.user_id == userId && cart.is_ordered == false) {
          resolve(cart.id);
        }
      });
      reject(new Error("No cart found for this user"));
    });
  });
};

app.route({
  view: "register",
  load: "register.html",
  onCreate: function () {},
  onReady: function () {},
});

registerForm = function () {
  FormValidation.validate("register-form", {}, function (data) {
    Utils.block_ui("#register-button");
    RestClient.post(
      "users",
      data,
      function (response) {
        Utils.unblock_ui("#register-button");
        toastr.success("User registered successfully.");
        document.getElementById("register-form").reset();
      },
      function (xhr) {
        toastr.error("Error occurred while registering user.");
      }
    );
  });
};

app.route({
  view: "login",
  load: "login.html",
  onCreate: function () {},
  onReady: function () {},
});

loginForm = function () {
  FormValidation.validate("login-form", {}, function (data) {
    Utils.block_ui("login-button");
    RestClient.post(
      "login",
      data,
      function (response) {
        window.localStorage.setItem("token", response.token);
        window.localStorage.setItem("user_id", response.id);
        window.localStorage.setItem("user", response.first_name);
        Utils.unblock_ui("login-button");
        toastr.success("You logged in successfully.");
        window.location.hash = "#home";
      },
      function (error) {
        toastr.error("Error occurred while logging into your account.");
      }
    );
  });
};

app.route({
  view: "cart",
  load: "cart.html",
  onCreate: function () {},
  onReady: function () {
    dNoneAddRemove();
    var cart_id = localStorage.getItem("cart_id");
    console.log("cart_id in cart.html from localstorage: " + cart_id);
    getCartProducts(cart_id);
    calculateAmount("expected-amount", cart_id);
  },
});

getCartProducts = function (cart_id) {
  RestClient.get(
    "cart_products?cart_id=" + encodeURIComponent(cart_id),
    function (data) {
      const cartProductsContainer = document.getElementById("cart-items");
      cartProductsContainer.innerHTML = "";

      data.forEach((product) => {
        const productCard = document.createElement("div");
        productCard.className = "row";
        productCard.innerHTML = `
      <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
        <div
          class="bg-image hover-overlay hover-zoom ripple rounded"
          data-mdb-ripple-color="light"
        >
          <img
            src="/../../frontend/assets/img/${product.image}.webp"
            class="w-100"
            alt="Product image"
          />
        </div>
      </div>

      <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
        <p><strong>${product.name}</strong></p>
        <p>
          ${product.description}
        </p>
        <button
          type="button"
          data-mdb-button-init
          data-mdb-ripple-init
          class="btn btn-primary btn-sm me-1 mb-2"
          data-mdb-tooltip-init
          title="Remove item"
          onclick="deleteProduct('${product.id}')"
        >
          <i class="fas fa-trash"></i>
        </button>
      </div>

      <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
      <form id="product-quantity-in-cart" name="product-quantity" validate="validate">
        <div class="d-flex mb-4" style="max-width: 300px">
          <button
            data-mdb-button-init
            data-mdb-ripple-init
            class="btn btn-primary px-3 me-2"
            onclick="this.parentNode.querySelector('input[type=number]').stepDown(); decreaseQuantity('${product.id}');"
            type="button"
          >
            <i class="fas fa-minus"></i>
          </button>

          <div data-mdb-input-init class="form-outline text-center">
            <input
              id="quantity"
              name="quantity"
              value="${product.quantity}"
              min="1"
              type="number"
              required
              class="form-control mx-1"
              disabled
            />
            <label class="form-label" for="quantity">Quantity</label>
          </div>

          <button
            data-mdb-button-init
            data-mdb-ripple-init
            class="btn btn-primary px-3 ms-2"
            onclick="this.parentNode.querySelector('input[type=number]').stepUp(); increaseQuantity('${product.id}');"
            type="button"
          >
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </form>

        <p class="text-start text-md-center">
          <strong>Product price: $${product.price}</strong>
        </p>
      </div>
      `;
        cartProductsContainer.appendChild(productCard);
        if (product != data[data.length - 1]) {
          const hr = document.createElement("hr");
          hr.className = "mb-4";
          cartProductsContainer.appendChild(hr);
        }
      });
    }
  );
};

calculateAmount = function (elementId, cart_id) {
  var total = 0;
  RestClient.get(
    "cart_products?cart_id=" + encodeURIComponent(cart_id),
    function (data) {
      data.forEach((product) => {
        total += product.price * product.quantity;
      });
      document.getElementById(elementId).innerHTML = total;
    }
  );
};

app.route({
  view: "checkout",
  load: "checkout.html",
  onCreate: function () {},
  onReady: function () {
    dNoneAddRemove();
    var cart_id = localStorage.getItem("cart_id");
    calculateAmount("expected-amount-in-checkout", cart_id);
    insertExpectedDeliveryDate();
    calculateAmount("total-amount", cart_id);
  },
});

initCheckoutForm = function (formId) {
  var cart_id = localStorage.getItem("cart_id");
  FormValidation.validate(`${formId}`, {}, function (data) {
    Utils.block_ui("#place-order-button");
    var totalAmount = document.getElementById("total-amount").innerText;
    data.total_amount = totalAmount;
    RestClient.post(
      "orders",
      data,
      function (response) {
        Utils.unblock_ui("#place-order-button");
        document.getElementById(formId).reset();
        toastr.success("Your order is placed.");
        editIsOrdered(cart_id);
        reloadUserCart();
        setTimeout(function () {
          window.location.hash = "home";
        }, 2500); // Change page after 2.5 seconds
      },
      function (xhr) {
        toastr.error("Error occurred while sending the message.");
      }
    );
  });
};

editIsOrdered = function (cart_id) {
  RestClient.patch("carts/" + cart_id, {}, function () {});
};

reloadUserCart = function () {
  initialiseEmptyCart(localStorage.getItem("user_id"));
  findCartId(localStorage.getItem("user_id"))
    .then((cart_id) => {
      window.localStorage.setItem("cart_id", cart_id);
    })
    .catch((error) => console.error(error.message));
};

app.route({
  view: "products",
  load: "products.html",
  onCreate: function () {},
  onReady: function () {
    dNoneAddRemove();
    get_products();
  },
});

app.route({
  view: "single_product",
  load: "single_product.html",
  onCreate: function () {},
  onReady: function () {
    dNoneAddRemove();
  },
});

app.route({
  view: "logout",
  load: "logout.html",
  onCreate: function () {},
  onReady: function () {
    console.log("Logout is ready!");
    window.localStorage.clear();
    window.location.hash = "#home";
    window.location.reload();
  },
});

app.route({
  view: "profile",
  load: "profile.html",
  onCreate: function () {},
  onReady: function () {
    dNoneAddRemove();
    RestClient.get("users/current", function (data) {
      console.log("Current user: ", data);
      $("#first_name").val(data.first_name);
      $("#last_name").val(data.last_name);
      $("#phone").val(data.mobile_number);
      $("#addressLine1").val(data.address_line1);
      $("#addressLine2").val(data.address_line2);
      $("#city").val(data.city);
      $("#zipCode").val(data.zip_code);

      $("#personal-info-form").validate({
        rules: {
          password: "required",
          password_confirmation: "required",
          email: {
            required: true,
            email: true,
          },
        },
        invalidHandler: function (event, validator) {
          $(".alert-danger").show();
          console.log("Invalid form");
        },
        submitHandler: function (form, event) {
          event.preventDefault();
          let data = {};
          $.each($(form).serializeArray(), function () {
            console.log(this.name, this.value);
            data[this.name] = this.value;
          });

          console.log("valid form", data);
          Utils.block_ui("#address-button");
          RestClient.post(
            "users/me",
            data,
            function (response) {
              console.log("Account updated", response);
              toastr.success("Account updated successfully.");
              Utils.unblock_ui("#address-button");
            },
            function (error) {
              $(".alert-danger").show();
            }
          );
        },
      });
    });
  },
});

display_user_profile = function (user_id) {
  RestClient.get("users/" + user_id, function (user) {
    const userContainer = document.getElementById("profile-page");
    userContainer.innerHTML = `
    <div
    class="col-md-4 gradient-custom text-center text-white"
    style="
      border-top-left-radius: 0.5rem;
      border-bottom-left-radius: 0.5rem;
    "
  >
    <img
      src="/../../frontend/assets/img/user-image.jpeg"
      alt="Avatar"
      class="img-fluid my-4"
      style="width: 80px"
    />
    <h5 style="color: #0d6efd">${user.first_name} ${user.last_name}</h5>
  </div>
  <div class="col-md-8">
    <div class="card-body p-4">
      <h6>Information</h6>
      <hr class="mt-0 mb-4" />
      <div class="row pt-1">
        <h6>Email</h6>
        <p class="text-muted">${user.email}</p>
      </div>
    </div>
  </div>
    `;
  });
};

insertExpectedDeliveryDate = function () {
  var deliveryDate = new Date();
  let startDelivery = deliveryDate.toLocaleDateString("en-GB");
  document.getElementById("expected-delivery-start").innerHTML = startDelivery;
  deliveryDate.setDate(deliveryDate.getDate() + 7);
  let endDelivery = deliveryDate.toLocaleDateString("en-GB");
  document.getElementById("expected-delivery-end").innerHTML = endDelivery;
};

increaseQuantity = function (productId) {
  var cart_id = localStorage.getItem("cart_id");
  RestClient.put("cart_products/" + productId, {}, function () {
    getCartProducts(cart_id);
    calculateAmount("expected-amount", cart_id);
  });
};

decreaseQuantity = function (productId) {
  var cart_id = localStorage.getItem("cart_id");
  RestClient.patch("cart_products/" + productId, {}, function () {
    getCartProducts(cart_id);
    calculateAmount("expected-amount", cart_id);
  });
};

deleteProduct = function (productId) {
  var cart_id = localStorage.getItem("cart_id");
  RestClient.delete("cart_products/" + productId, {}, function () {
    getCartProducts(cart_id);
    calculateAmount("expected-amount", cart_id);
  });
};

display_product = function (product_id) {
  RestClient.get("products/" + product_id, function (product) {
    const productContainer = document.getElementById("single-product-page");
    productContainer.innerHTML = `
    <div class="row gx-4 gx-lg-5 align-items-center">
    <div class="col-md-6">
      <img
        class="card-img-top mb-5 mb-md-0"
        src="/../../frontend/assets/img/${product.image}.webp"
        alt="Ma kakva sliba"
      />
    </div>
    <div class="col-md-6">
      <h1 class="display-5 fw-bolder">${product.name}</h1>
      <div class="fs-5 mb-5">
        <span>Product price: </span>
        <span>$${product.price}</span>
      </div>
      <p class="lead">
        ${product.description}
      </p>
      <form id="product-quantity" name="product-quantity" validate="validate">
        <div class="d-flex">
          <button
            data-mdb-button-init
            data-mdb-ripple-init
            class="btn btn-primary mx-1"
            onclick="this.parentNode.querySelector('input[type=number]').stepDown()"
            type="button"
          >
            <i class="fas fa-minus"></i>
          </button>

          <div data-mdb-input-init class="form-outline text-center">
            <input
              id="quantity"
              name="quantity"
              value="1"
              min="1"
              type="number"
              required
              class="form-control mx-1"
            />
            <label class="form-label" for="quantity">Quantity</label>
          </div>

          <button
            data-mdb-button-init
            data-mdb-ripple-init
            class="btn btn-primary mx-2"
            onclick="this.parentNode.querySelector('input[type=number]').stepUp()"
            type="button"
          >
            <i class="fas fa-plus"></i>
          </button>

          <button 
            class="btn btn-secondary flex-shrink-0 mx-1" 
            id="add-to-cart-button" 
            type="submit"
            onclick="addToCart('product-quantity', '${product.id}')"
          >
            <i class="bi-cart-fill me-1"></i>
            Add to cart
          </button>
        </div>
      </form>
    </div>
  </div>
    `;
  });
};

addToCart = function (formId, productId) {
  userId = localStorage.getItem("user_id");
  FormValidation.validate(`${formId}`, {}, function (data) {
    data["product_id"] = productId;
    data["user_id"] = userId;
    Utils.block_ui("#add-to-cart-button");
    RestClient.post(
      "cart_products",
      data,
      function (response) {
        Utils.unblock_ui("#add-to-cart-button");
        toastr.success("Product added to cart.");
        setTimeout(function () {
          window.location.hash = "products";
        }, 2500); // Change page after 2.5 seconds
      },
      function (xhr) {
        toastr.error("Error occurred while sending the message.");
      }
    );
  });
};

get_home_products = function () {
  RestClient.get("products", function (data) {
    const productsContainer = document.getElementById("home-products");
    productsContainer.innerHTML = "";

    data.slice(0, 3).forEach((product) => {
      const productCard = document.createElement("div");
      productCard.className = "col";
      productCard.innerHTML = `
        <div class="card h-100">
        <div class="card-header card-title"> 
          <h5> ${product.name} </h5>
        </div>
        <div class="card-body">
          <p class="card-text">
            ${product.description}
          </p>
        </div>
        <div class="card-footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col">
                <h5 class="text-muted">Item price: $${product.price}</h5>
              </div>
              <div class="col text-end">
              <a href="#single_product" class="btn btn-primary" onclick="display_product('${product.id}')">View product</a>
              </div>
            </div>
          </div>
        </div>
      </div>
        `;
      productsContainer.appendChild(productCard);
    });
  });
};

get_products = function () {
  RestClient.get("products", function (data) {
    const productsContainer = document.getElementById("products-products");
    productsContainer.innerHTML = "";

    data.forEach((product) => {
      const productCard = document.createElement("div");
      productCard.className = "col";
      productCard.innerHTML = `
        <div class="card h-100">
        <div class="card-header card-title"> 
          <h5> ${product.name} </h5>
        </div>
        <div class="card-body">
          <p class="card-text">
            ${product.description}
          </p>
        </div>
        <div class="card-footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col">
                <h5 class="text-muted">Item price: $${product.price}</h5>
              </div>
              <div class="col text-end">
              <a href="#single_product" class="btn btn-primary" onclick="display_product('${product.id}')">View product</a>
              </div>
            </div>
          </div>
        </div>
      </div>
        `;
      productsContainer.appendChild(productCard);
    });
  });
};

app.run();
