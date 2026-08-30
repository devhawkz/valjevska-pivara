/**
 * Homepage V1 brand slideshow: scoped Swiper 12 instance.
 */
(function () {
  "use strict";

  var SwiperCtor = window.VpSwiper;

  if (typeof SwiperCtor !== "function") {
    return;
  }

  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)");

  function prefersReducedMotion() {
    return reduced.matches;
  }

  function setControlState(button, hide) {
    if (!button) {
      return;
    }

    if (hide) {
      button.setAttribute("hidden", "");
      button.setAttribute("disabled", "disabled");
      button.setAttribute("aria-hidden", "true");
      button.tabIndex = -1;
      return;
    }

    button.removeAttribute("hidden");
    button.removeAttribute("disabled");
    button.removeAttribute("aria-hidden");
    button.tabIndex = 0;
  }

  function syncControls(swiper, prev, next) {
    setControlState(prev, swiper.isBeginning);
    setControlState(next, swiper.isEnd);
  }

  function initRoot(root) {
    var container = root.querySelector("[data-vp-brands-swiper]");
    var prev = root.querySelector("[data-vp-brands-prev]");
    var next = root.querySelector("[data-vp-brands-next]");
    var pagination = root.querySelector("[data-vp-brands-pagination]");
    var slides = root.querySelectorAll(".swiper-slide");
    var swiper;

    if (!container || slides.length < 2) {
      return;
    }

    swiper = new SwiperCtor(container, {
      slidesPerView: 1,
      loop: false,
      rewind: false,
      watchOverflow: true,
      autoHeight: false,
      speed: prefersReducedMotion() ? 0 : 450,
      autoplay: false,
      containerModifierClass: "vp-brands-swiper-",
      wrapperClass: "vp-brands__wrapper",
      slideClass: "swiper-slide",
      keyboard: {
        enabled: false,
        onlyInViewport: true,
      },
      a11y: {
        enabled: true,
        prevSlideMessage: root.getAttribute("data-vp-a11y-prev") || "",
        nextSlideMessage: root.getAttribute("data-vp-a11y-next") || "",
        firstSlideMessage: root.getAttribute("data-vp-a11y-first") || "",
        lastSlideMessage: root.getAttribute("data-vp-a11y-last") || "",
        paginationBulletMessage: root.getAttribute("data-vp-a11y-bullet") || "",
        slideLabelMessage: root.getAttribute("data-vp-a11y-slide") || "",
      },
      pagination: pagination
        ? {
            el: pagination,
            clickable: true,
            bulletElement: "button",
            bulletClass: "vp-brands__bullet",
            bulletActiveClass: "is-active",
          }
        : undefined,
      navigation: {
        prevEl: prev,
        nextEl: next,
        disabledClass: "is-disabled",
        hiddenClass: "is-hidden",
      },
      on: {
        init: function (instance) {
          syncControls(instance, prev, next);
        },
        slideChange: function (instance) {
          syncControls(instance, prev, next);
        },
        toEdge: function (instance) {
          syncControls(instance, prev, next);
        },
        fromEdge: function (instance) {
          syncControls(instance, prev, next);
        },
      },
    });

    function onMotionChange() {
      swiper.params.speed = prefersReducedMotion() ? 0 : 450;
    }

    if (typeof reduced.addEventListener === "function") {
      reduced.addEventListener("change", onMotionChange);
    } else if (typeof reduced.addListener === "function") {
      reduced.addListener(onMotionChange);
    }

    if (typeof IntersectionObserver !== "function") {
      swiper.keyboard.enable();
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        var entry = entries[0];

        if (!entry) {
          return;
        }

        if (entry.isIntersecting) {
          swiper.keyboard.enable();
        } else {
          swiper.keyboard.disable();
        }
      },
      { threshold: 0.2 }
    );

    observer.observe(root);
  }

  document.querySelectorAll("[data-vp-brands]").forEach(initRoot);
})();
