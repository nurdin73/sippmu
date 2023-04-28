function generateMap(id, locationPoint) {
  const map = L.map(id, {
    zoomControl: false,
  }).setView(locationPoint, 15);
  L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
    attribution:
      '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);

  L.control
    .zoom({
      position: "bottomright",
    })
    .addTo(map);

  L.Control.geocoder({
    defaultMarkGeocode: false,
  })
    .on("markgeocode", function (e) {
      const { lat, lng } = e.geocode.center;
      addMarker([lat, lng], map);
    })
    .addTo(map);

  addMarker(locationPoint, map);

  map.addEventListener("click", function (ev) {
    const { lat, lng } = ev.latlng;

    addMarker([lat, lng], map);
  });
}
let marker;
function addMarker(latLong, map) {
  if (marker) map.removeLayer(marker);
  var icon = L.icon({
    iconUrl: mapIcon,
    iconSize: [50, 50],
  });
  marker = new L.Marker(latLong, {
    draggable: true,
    icon: icon,
  });
  setCoordinate(latLong.join(", "));
  map.addLayer(marker);
  map.panTo(latLong, 16, {
    duration: 4,
  });
  marker.on("dragend", function (e) {
    const { lat, lng } = marker.getLatLng();
    getAddressDetail([lat, lng], map);
    map.panTo([lat, lng], 16, {
      duration: 4,
    });
  });
  getAddressDetail(latLong, map);
}

function getAddressDetail(latLong, map) {
  $.get(
    `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latLong[0]}&lon=${latLong[1]}`,
    function (data) {
      const content = {
        title: `${data.address.village ?? data.address.road}, ${
          data.address.city ?? data.address.state
        }, ${data.address.country}`,
        ...data.address,
      };
      setAddress(data.display_name);
      // L.popup({
      //   closeButton: false,
      //   offset: L.point(0, -8),
      //   keepInView: true,
      // })
      //   .setLatLng(latLong)
      //   .setContent(makePopUpContent(content))
      //   .openOn(map);
    }
  );
}

function setCoordinate(latlng) {
  $("input[name=coord]").val(latlng);
}

function setAddress(address) {
  $("textarea[name=alamat]").val(address);
}

function makePopUpContent(data) {
  const keys = Object.keys(data);
  keys.splice(0, 1);
  var table = "";
  keys.forEach((key) => {
    table += `
    <tr>
      <th>${key}</th>
      <td>${data[key]}</td>
    </tr>
  `;
  });
  return `
    <h4>${data.title}</h4>
    <table class="table table-sm table-striped">
      <tbody>
        ${table}
      </tbody>
    </table>
  `;
}

function getMyLocation(map) {
  map
    .locate({ setView: true })
    .on("locationfound", function (e) {
      var marker = L.marker([e.latitude, e.longitude]);
      map.addLayer(marker);
    })
    .on("locationerror", function (e) {
      console.log(e);
      alert("Location access denied.");
    });
}
