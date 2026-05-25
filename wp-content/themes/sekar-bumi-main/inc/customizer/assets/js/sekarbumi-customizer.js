jQuery(function ($) {
  "use strict";
  const api = wp.customize;

  api.control("blogname").section("sekarbumi_site_identity_section");
  api.control("blogdescription").section("sekarbumi_site_identity_section");
  api.control("site_icon").section("sekarbumi_site_identity_section");
  api.control("blogname").priority(5);
  api.control("blogdescription").priority(5);
  api.control("site_icon").priority(5);
});