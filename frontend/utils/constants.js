var Constants = {
  get_api_base_url: function () {
    if (location.hostname == "localhost") {
      return "http://localhost:8888/project/backend/";
    } else {
      return "https://squid-app-dksi9.ondigitalocean.app/backend/";
    }
  },
};
