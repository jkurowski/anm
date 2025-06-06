// Easing
jQuery.easing.jswing=jQuery.easing.swing,jQuery.extend(jQuery.easing,{def:"easeInOutExpo",swing:function(e,n,u,i,r){return jQuery.easing[jQuery.easing.def](e,n,u,i,r)},easeInOutExpo:function(e,n,u,i,r){return 0===n?u:n===r?u+i:(n/=r/2)<1?i/2*Math.pow(2,10*(n-1))+u:i/2*(2-Math.pow(2,-10*--n))+u}});

// Init AOS
if (window.AOS !== undefined) {
  window.matchMedia("(min-width: 1400px)").matches &&
    AOS.init({
      offset: -1000,
      duration: 1000,
      once: true,
    });

  window.matchMedia("(max-width: 1399px)").matches &&
    AOS.init({
      offset: -400,
      duration: 1000,
      disable: () => window.matchMedia("(max-width: 990px)").matches,
      once: true,
    });
}

//
//
//
//
//  CHECKBOX IN SELECT HERO FORM
//
//

document.addEventListener("DOMContentLoaded", function() {
  const urlParams = new URLSearchParams(window.location.search);
  const sDodatkowe = urlParams.get('s_dodatkowe');
  const chBoxes = document.querySelectorAll('.dropdown-menu input[type="checkbox"]');
  const dpBtn = document.getElementById("multiSelectDropdown");
  const hiddenInput = document.getElementById("s_dodatkowe");
  let mySelectedListItems = [];

  function handleCB() {
    mySelectedListItems = [];
    let mySelectedLabels = [];

    chBoxes.forEach((checkbox) => {
      if (checkbox.checked) {
        mySelectedListItems.push(checkbox.value);
        mySelectedLabels.push(checkbox.parentElement.textContent.trim());
      }
    });

    dpBtn.innerText =
        mySelectedLabels.length > 0
            ? mySelectedLabels.join(", ")
            : "Udogodnienia";

    hiddenInput.value = mySelectedListItems.join(",");
  }

  // Initialize checkboxes based on s_dodatkowe parameter
  if (sDodatkowe) {
    const selectedAmenities = sDodatkowe.split(',');
    selectedAmenities.forEach(value => {
      const checkbox = document.querySelector(`input[type="checkbox"][value="${value}"]`);
      if (checkbox) {
        checkbox.checked = true;
      }
    });
  }

  // Initialize the dropdown button text and hidden input with the current selection
  handleCB();

  // Add event listeners to checkboxes
  if (chBoxes && dpBtn) {
    chBoxes.forEach((checkbox) => {
      checkbox.addEventListener("change", handleCB);
    });
  }
});

//
//
//
//
//
//
// Grid Change

const gridRows = document.querySelector("#grid-rows");
const gridTails = document.querySelector("#grid-tails");
const gridBtn1 = document.querySelector("#grid-btn-1");
const gridBtn2 = document.querySelector("#grid-btn-2");

function toggleActiveStatus(btn1, btn2) {
  btn1.addEventListener("click", () => {
    if (btn1.classList.contains("inactive")) {
      btn1.classList.add("active");
      btn1.classList.remove("inactive");
      btn2.classList.add("inactive");
      btn2.classList.remove("active");
      gridRows.classList.toggle("invisible");
      gridTails.classList.toggle("invisible");
    }
  });
}

if (gridBtn1 && gridBtn2) {
  toggleActiveStatus(gridBtn1, gridBtn2);
  toggleActiveStatus(gridBtn2, gridBtn1);
}

if (gridRows && gridTails && gridBtn1 && gridBtn2) {
  gridBtn1.addEventListener("click", () => {
    gridRows.querySelectorAll("li").forEach((el) => {
      el.classList.remove("aos-animate");
      setTimeout(function () {
        el.classList.add("aos-animate");
      }, 1);
    });
  });

  gridBtn2.addEventListener("click", () => {
    gridTails.querySelectorAll("div").forEach((el) => {
      el.classList.remove("aos-animate");
      setTimeout(function () {
        el.classList.add("aos-animate");
      }, 1);
    });
  });
}

//
//
//
//
//
// add .scrolled class to header when page is scrolled
const header = document.querySelector("header");
if (header) {
  window.addEventListener("scroll", () => {
    if (window.scrollY >= 100) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  });
}

document.addEventListener("scroll", () => {
  const headerHeight = 150; // height of the header in pixels
  window.scrollY + headerHeight;
});

//
//
//
//
//
// Leaflet Map

const mapId = document.getElementById("map");

if (mapId) {
  let $coordinates = [51.85109819957367, 19.418445297865162]; // Mława
  var map = L.map("map").setView($coordinates, 13);

  L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 22,
  }).addTo(map);

  var myIcon = L.icon({
    iconUrl: "../images/map-slider/apartamenty-marker.png",
    iconAnchor: [36, 86],
    popupAnchor: [-3, -76],
  });

  var marker = L.marker($coordinates, { icon: myIcon }).addTo(map);
}

//
//
//
//
//
//
// Slick sliders

//Grid
let breakpoints = {
  xs: 500,
  sm: 576,
  md: 768,
  lg: 995,
  xl: 1200,
  xxl: 1400,
};

const slickMap = document.querySelector(".slick-slider-map");

if (slickMap) {
  window.matchMedia(`(min-width: ${breakpoints.lg}px)`).matches &&
    $(document).ready(function () {
      $(".slick-slider-map").slick({
        dots: false,
        infinite: true,
        speed: 250,
        slidesToShow: 7,
        slidesToScroll: 1,
        vertical: true,
        touchMove: false,
        prevArrow:
          '<button type="button" class="slick-prev"><img src="../images/map-slider/arrow_up.svg" width="25" height="25" /></button>',
        nextArrow:
          '<button type="button" class="slick-next"><img src="../images/map-slider/arrow_down.svg" width="25" height="25" /></button>',
        responsive: [
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 5,
            },
          },
        ],
      });
    });

  window.matchMedia(`(max-width: ${breakpoints.lg}px)`).matches &&
    $(document).ready(function () {
      $(".slick-slider-map").slick({
        dots: false,
        infinite: true,
        speed: 250,
        slidesToShow: 3.33,
        slidesToScroll: 2,
        vertical: false,
        touchMove: true,
        prevArrow:
          '<button type="button" class="slick-prev"><img src="../images/map-slider/arrow_up.svg" width="25" height="25" /></button>',
        nextArrow:
          '<button type="button" class="slick-next"><img src="../images/map-slider/arrow_down.svg" width="25" height="25" /></button>',
        responsive: [
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
            },
          },
          {
            breakpoint: 576,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            },
          },
        ],
      });
    });
}

const slickAboutRight = document.querySelector(".slick-slider-about");

if (slickAboutRight) {
  $(document).ready(function () {
    $(".slick-slider-about").slick({
      arrows: false,
      dots: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      asNavFor: ".slick-slider-about-nav",
    });
    $(".slick-slider-about-nav").slick({
      slidesToShow: 2,
      slidesToScroll: 1,
      asNavFor: ".slick-slider-about",
      dots: false,
      focusOnSelect: true,
      prevArrow:
        '<button type="button" aria-label="poprzedni" class="slick-prev"><img src="../images/arrow_left.svg" width="25" height="25" /></button>',
      nextArrow:
        '<button type="button" aria-label="nastepny" class="slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 15.644 15.644"><path fill="#fff" id="arrow_downward_FILL0_wght100_GRAD0_opsz48" d="M7.5,0V14.418L.467,7.355,0,7.822l7.822,7.822,7.822-7.822-.467-.467L8.143,14.418V0Z" transform="translate(0 15.644) rotate(-90)"/></svg></button>',
    });
  });
}

const slickAboutLeft = document.querySelector(".slick-slider-about-l");

if (slickAboutLeft) {
  $(document).ready(function () {
    $(".slick-slider-about-l").slick({
      arrows: true,
      dots: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      rtl: true,
      asNavFor: ".slick-slider-about-nav-l",
      nextArrow:
        '<button type="button" aria-label="nastepny" class="slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 15.644 15.644"><path fill="#000" id="arrow_downward_FILL0_wght100_GRAD0_opsz48" d="M7.5,0V14.418L.467,7.355,0,7.822l7.822,7.822,7.822-7.822-.467-.467L8.143,14.418V0Z" transform="translate(0 15.644) rotate(-90)"/></svg></button>',
      prevArrow:
        '<button type="button" aria-label="poprzedni" class="slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="25.194" height="25.194" viewBox="0 0 25.194 25.194"><path fill="#fff" id="arrow_downward_FILL0_wght100_GRAD0_opsz48" d="M224.08-748v23.219l-11.328-11.375L212-735.4l12.6,12.6,12.6-12.6-.752-.752L225.114-724.78V-748Z" transform="translate(-722.806 -212) rotate(90)" /></svg></button>',
    });
    $(".slick-slider-about-nav-l").slick({
      slidesToShow: 2,
      slidesToScroll: 1,
      asNavFor: ".slick-slider-about-l",
      dots: false,
      focusOnSelect: true,
      rtl: true,
      arrows: false,
    });
  });
}

//
//
//
//
//
//
// Gallery Change

const tab1 = document.querySelector("#tab1");
const tab2 = document.querySelector("#tab2");
const tab3 = document.querySelector("#tab3");

function toggleActiveTabStatus(btn1, btn2, btn3) {
  const setActive = (activeBtn) => {
    btn1.classList.toggle("active", btn1 === activeBtn);
    btn2.classList.toggle("active", btn2 === activeBtn);
    btn3.classList.toggle("active", btn3 === activeBtn);
  };

  btn1.addEventListener("click", () => setActive(btn1));
  btn2.addEventListener("click", () => setActive(btn2));
  btn3.addEventListener("click", () => setActive(btn3));
}

if (tab1 && tab2 && tab3) {
  toggleActiveTabStatus(tab1, tab2, tab3);
  toggleActiveTabStatus(tab2, tab1, tab3);
  toggleActiveTabStatus(tab3, tab1, tab2);
}

//
//
//
// Add non-breaking space after single letter
//

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("p").forEach(function (p) {
    p.innerHTML = p.innerHTML.replace(/ (\w) /g, " $1&nbsp;");
  });
});
