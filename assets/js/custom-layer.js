L.Control.Detail = L.Control.extend({
  onAdd: function (map) {
    const container = L.DomUtil.create("div", "box shadow-none rounded-lg");
    container.style.width = "250px";
    L.DomUtil.create("div", "box-header p-3", container);
    L.DomUtil.create("div", "box-body", container);
    return container;
  },
  onRemove: function (map) {
    // event remove
  },
  addTitle: function (title) {
    this.getContainer().children[0].innerHTML = `
        <span class='card-title' style='font-size: 12px;'>${title}</span>
    `;
  },
  color: function (color) {
    this.getContainer().classList.add(`box-${color}`);
  },
  addEvent: function () {
    L.DomEvent.addListener(
      this.getContainer().children[1],
      "click",
      function (e) {
        console.log(e);
      }
    );
  },
  addContent: function (content) {
    this.getContainer().children[1].innerHTML = content;
  },
});

L.control.detail = function (opts) {
  return new L.Control.Detail(opts);
};
