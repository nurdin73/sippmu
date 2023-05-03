const statKeanggotaan = document.getElementById("keanggotaan").getContext("2d");
const statPengurus = document.getElementById("pengurus");
const statPengurusRanting = document.getElementById("pengurus-ranting");
const statAnggota = document.getElementById("anggota");
let marks;
let markShowed = [];
let detailShow;
let listAssets;
let lastViewed;

$(document).ready(function () {
  const map = generateMap("map", [-6.9024981, 107.6187750112255]);
  var markers = initClusterGroup(map);
  groupingLocation(map, markers);
  generateDougnutChart(statKeanggotaan, "keanggotaan");
  generateDougnutChart(statPengurus, "pengurus");
  generateChart("doughnut", {
    identifier: statPengurusRanting,
    showLabel: true,
  });
  generateBarAnggota();

  $("#list-assets").on("click", ".aset", function (e) {
    e.preventDefault();
    const lat = $(this).data("latitude");
    const lng = $(this).data("longitude");
    const aset = $(this).data("aset");
    map.panTo([lat, lng], 16, {
      duration: 4,
    });
    showDetail(aset, null, map);
    $(".aset").removeClass("active");
    $(this).addClass("active");
  });
});

function generateBarAnggota() {
  $.ajax({
    type: "get",
    url: BASE_URL + "/dashboard/statistics/anggota",
    dataType: "json",
    success: function (response) {
      console.log(response);
      generateChart("bar", {
        identifier: statAnggota,
        showLabel: false,
        dataset: {
          labels: response.map((item) => item.label),
          datasets: [
            {
              label: "NBM",
              data: response.map((item) => item.total_nbm),
            },
            {
              label: "Non NBM",
              data: response.map((item) => item.total_non_nbm),
            },
          ],
        },
      });
    },
  });
}

function generateDougnutChart(identifier, key) {
  $.ajax({
    type: "get",
    url: BASE_URL + `/dashboard/statistics/${key}`,
    dataType: "json",
    success: function (response) {
      generateChart("doughnut", {
        identifier: identifier,
        showLabel: true,
        dataset: {
          labels: response.map((r) => r.label),
          datasets: [
            {
              data: response.map((item) => item.total),
              borderRadius: 10,
            },
          ],
        },
      });
    },
  });
}

function initClusterGroup(map) {
  const markers = L.markerClusterGroup({
    chunkedLoading: true,
  });
  markers.on("click", function (e) {
    const { dataset } = e.layer.options;
    showDetail(dataset, markers, map);
  });
  return markers;
}

function showDetail(dataset, markers, map) {
  map.panTo([dataset.latitude, dataset.longitude], 16, {
    duration: 4,
  });
  if (detailShow) map.removeControl(detailShow);
  const detail = L.control
    .detail({
      position: "topleft",
    })
    .addTo(map);
  detailShow = detail;
  lastViewed = dataset.unit_id;
  detail.addTitle(dataset.unit);
  detail.color(dataset.is_pusat == "t" ? "success" : "info");
  detail.addContent(`
        <div class='table-responsive'>
            <table border='1' class='table table-sm table-bordered mb-0' style="font-size: 12px;">
                <tbody>
                    <tr>
                      <td colspan="2" class="text-center">${
                        dataset.is_pusat == "t"
                          ? "<span class='badge badge-success bg-success'>Kantor Pusat</span>"
                          : "<span class='badge badge-info bg-info'>Asset</span>"
                      }</td>
                    </tr>
                    <tr>
                        <th>Total Aset</th>
                        <td>${dataset.total_aset ?? "N/A"}</td>
                    </tr>
                    <tr>
                        <th>Tipe Aset</th>
                        <td>${dataset.tipe_aset ?? "N/A"}</td>
                    </tr>
                    <tr>
                        <th>Perolehan</th>
                        <td>${dataset.perolehan ?? "N/A"}</td>
                    </tr>
                    <tr>
                        <th>Pemanfaatan</th>
                        <td>${dataset.pendayagunaan ?? "N/A"}</td>
                    </tr>
                    <tr>
                        <td colspan='2'>${dataset.alamat}</td>
                    </tr>
                </tbody>
            </table>
        </div>
      `);
  if (!markShowed.find((m) => m == dataset.unit_id)) {
    if (markers) {
      groupingLocation(map, markers, dataset.unit_id, dataset.unit);
      markShowed.push(dataset.unit_id);
    }
  }
}

function groupingLocation(map, markers, unit_id = null, unit = null) {
  $.ajax({
    type: "get",
    url: BASE_URL + "asset/unit/" + (unit_id ? unit_id : ""),
    dataType: "json",
    success: function (response) {
      var icon = L.icon({
        iconUrl: mapIcon,
        iconSize: [50, 50],
      });
      if (unit_id) {
        markers.refreshClusters(marks);
      }
      if (listAssets) map.removeControl(listAssets);
      // if (unit_id) {
      //   listAssets = L.control
      //     .detail({
      //       position: "topright",
      //     })
      //     .addTo(map);
      //   listAssets.addTitle(unit);
      //   let content = "<ul class='list-unstyled mb-0'>";
      //   $.each(response, function (i, val) {
      //     content += `<li class='d-flex flex-column'>
      //       <span class='text-truncate font-weight-bold' style="font-size: 14px">${val.unit}</span>
      //       <span class='text-truncate text-muted' style="font-size: 12px">${val.alamat}</span>
      //      </li>`;
      //   });
      //   content += "</ul>";
      //   listAssets.addContent(content);
      //   listAssets.addEvent();
      // }
      if (unit_id) {
        showListAsset(response, unit);
      }
      $.each(response, function (i, val) {
        // if (!markShowed.find((m) => m == val.unit_id)) {
        //   markShowed.push(val.unit_id);
        // }
        const marker = L.marker(new L.LatLng(val.latitude, val.longitude), {
          title: val.unit,
          icon: icon,
          dataset: val,
        });
        markers.addLayer(marker);
      });
      marks = markers;
      map.addLayer(markers);
    },
    error: function (err) {
      Swal.fire({
        title: "Error",
        text: "Error get map data",
        icon: "error",
      });
    },
  });
}

function generateChart(
  type,
  options = {
    identifier: null,
    showLabel: false,
    dataset: null,
  }
) {
  if (!options.dataset) {
    options.dataset = {
      labels: ["red", "blue"],
      datasets: [
        {
          label: "tes",
          data: [10, 100],
          borderRadius: 10,
        },
      ],
    };
  }
  const chart = new Chart(options.identifier, {
    type: type,
    data: options.dataset,
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "right",
          display: options.showLabel,
        },
      },
    },
  });
}

function generateMap(id, locationPoint) {
  const map = L.map(id, {
    zoomControl: false,
  }).setView(locationPoint, 13);
  L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
    attribution:
      '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);

  L.control
    .zoom({
      position: "bottomright",
    })
    .addTo(map);

  //   L.Control.geocoder({
  //     defaultMarkGeocode: false,
  //   })
  //     .on("markgeocode", function (e) {
  //       const { lat, lng } = e.geocode.center;
  //       addMarker([lat, lng], map);
  //     })
  //     .addTo(map);

  //   addMarker(locationPoint, map);

  map.addEventListener("click", function (ev) {
    const { lat, lng } = ev.latlng;

    // addMarker([lat, lng], map);
  });

  return map;
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
  //   setCoordinate(latLong.join(", "));
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
      //   setAddress(data.display_name);
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

function showListAsset(assets, unit) {
  let content = `<h4>${unit}</h4>`;
  content += `<div class="list-group">`;
  content += `</div>`;
  if (assets.length > 0) {
    assets.forEach((val) => {
      content += `<a href="#" class="list-group-item aset" data-aset='${JSON.stringify(
        val
      )}' data-latitude="${val.latitude}" data-longitude="${val.longitude}">${
        val.unit
      }</a>`;
    });
  } else {
    content +=
      "<div class='alert alert-info'>Asset tidak ditemukan selain kantor pusat!</div>";
  }
  $("#list-assets").html(content);
}
