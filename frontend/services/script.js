console.log("Script is ready");

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
    console.log("home is ready");
    get_home_products();
  },
});

function get_home_products() {
  RestClient.get(
    "products",
    function (data) {
      console.log("Rest client data: ", data);
      const productsContainer = document.getElementById("home-products");
      productsContainer.innerHTML = "";

      data.forEach((product) => {
        const productCard = document.createElement("div");
        productCard.className = "col";
        productCard.innerHTML = `
        <div class="card h-100">
        <img src="${product.image_path}" class="card-header" alt="Skyscrapers" />
        <div class="card-body">
          <h5 class="card-title">${product.name}</h5>
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
                <a href="" class="btn btn-primary">View product</a>
              </div>
            </div>
          </div>
        </div>
      </div>
        `;
        productsContainer.appendChild(productCard);
      });
    },
    function (error) {
      console.error("Error: we have the problem huston", error);
    }
  );
}

app.run();
