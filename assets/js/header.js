/**
 * Header menu: mobile toggle, nested submenus, keyboard support.
 */
(function () {
  "use strict";

  var header = document.querySelector(".vp-header");

  if (!header) {
    return;
  }

  var toggle = header.querySelector(".vp-header__toggle");
  var closeButton = header.querySelector(".vp-header__close");
  var nav = header.querySelector(".vp-header__nav");
  var panel = header.querySelector(".vp-header__panel");
  var barBrand = header.querySelector(".vp-header__brand--bar");
  var strings =
    typeof valjevskaPivaraHeader === "object" && valjevskaPivaraHeader !== null
      ? valjevskaPivaraHeader
      : {};
  var desktopQuery = window.matchMedia("(min-width: 74.875em)");
  var submenuIndex = 0;

  header.classList.add("vp-header--js");

  function isDesktop() {
    return desktopQuery.matches;
  }

  function setSubmenuOpen(item, open) {
    var button = item.querySelector(":scope > .vp-header__sub-toggle");
    var label = button
      ? button.querySelector(".vp-visually-hidden")
      : null;

    item.classList.toggle("is-sub-open", open);

    if (button) {
      button.setAttribute("aria-expanded", open ? "true" : "false");
    }

    if (label) {
      label.textContent = open
        ? strings.collapseSubmenu || "Collapse submenu"
        : strings.expandSubmenu || "Expand submenu";
    }
  }

  function closeSubmenus(scope) {
    var root = scope || header;
    var openItems = root.querySelectorAll(".is-sub-open");
    var i;

    for (i = 0; i < openItems.length; i += 1) {
      setSubmenuOpen(openItems[i], false);
    }
  }

  function setMenuOpen(open) {
    header.classList.toggle("is-open", open);

    if (toggle) {
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    }

    if (panel) {
      panel.setAttribute("aria-hidden", open || isDesktop() ? "false" : "true");

      if ("inert" in panel) {
        panel.inert = !open && !isDesktop();
      }

      if (open && !isDesktop()) {
        if (closeButton) {
          closeButton.focus();
        } else {
          panel.setAttribute("tabindex", "-1");
          panel.focus();
        }
      }
    }

    document.body.classList.toggle("vp-header-open", open && !isDesktop());

    if (barBrand) {
      barBrand.setAttribute("aria-hidden", open && !isDesktop() ? "true" : "false");
    }

    if (!open) {
      closeSubmenus(header);
    }
  }

  function closeMenu(restoreFocus) {
    var wasOpen = header.classList.contains("is-open");

    setMenuOpen(false);

    if (restoreFocus && wasOpen && toggle && !isDesktop()) {
      toggle.focus();
    }
  }

  function enhanceSubmenus() {
    var parents = header.querySelectorAll(".menu-item-has-children");
    var i;

    for (i = 0; i < parents.length; i += 1) {
      (function (item) {
        var link = item.querySelector(":scope > a");
        var submenu = item.querySelector(":scope > .sub-menu");
        var button;
        var label;
        var chevron;
        var submenuId;

        if (!link || !submenu || item.querySelector(":scope > .vp-header__sub-toggle")) {
          return;
        }

        submenuIndex += 1;
        submenuId = submenu.id || "vp-submenu-" + submenuIndex;
        submenu.id = submenuId;

        button = document.createElement("button");
        button.type = "button";
        button.className = "vp-header__sub-toggle";
        button.setAttribute("aria-expanded", "false");
        button.setAttribute("aria-controls", submenuId);

        chevron = document.createElement("span");
        chevron.className = "vp-header__chevron";
        chevron.setAttribute("aria-hidden", "true");

        label = document.createElement("span");
        label.className = "vp-visually-hidden";
        label.textContent = strings.expandSubmenu || "Expand submenu";

        button.appendChild(chevron);
        button.appendChild(label);
        link.after(button);

        button.addEventListener("click", function (event) {
          var willOpen = !item.classList.contains("is-sub-open");
          var parentList = item.parentElement;
          var sibling;
          var s;

          event.preventDefault();
          event.stopPropagation();

          if (parentList) {
            for (s = 0; s < parentList.children.length; s += 1) {
              sibling = parentList.children[s];

              if (
                sibling !== item &&
                sibling.classList.contains("menu-item-has-children")
              ) {
                setSubmenuOpen(sibling, false);
              }
            }
          }

          setSubmenuOpen(item, willOpen);
        });
      })(parents[i]);
    }
  }

  if (toggle && (panel || nav)) {
    toggle.addEventListener("click", function () {
      setMenuOpen(!header.classList.contains("is-open"));
    });
  }

  if (closeButton) {
    closeButton.addEventListener("click", function () {
      closeMenu(true);
    });
  }

  document.addEventListener("keydown", function (event) {
    if (event.key !== "Escape") {
      return;
    }

    if (header.classList.contains("is-open") && !isDesktop()) {
      closeMenu(true);
      return;
    }

    var active = document.activeElement;
    var focusedSub =
      active && typeof active.closest === "function"
        ? active.closest(".vp-header .is-sub-open")
        : null;
    var subToggle;

    if (focusedSub && header.contains(focusedSub)) {
      subToggle = focusedSub.querySelector(":scope > .vp-header__sub-toggle");
      setSubmenuOpen(focusedSub, false);

      if (subToggle) {
        subToggle.focus();
      }
    }
  });

  function onBreakpointChange() {
    if (isDesktop()) {
      closeMenu(false);
      document.body.classList.remove("vp-header-open");

      if (panel) {
        panel.setAttribute("aria-hidden", "false");

        if ("inert" in panel) {
          panel.inert = false;
        }
      }
    } else if (panel && !header.classList.contains("is-open")) {
      panel.setAttribute("aria-hidden", "true");

      if ("inert" in panel) {
        panel.inert = true;
      }
    }
  }

  if (typeof desktopQuery.addEventListener === "function") {
    desktopQuery.addEventListener("change", onBreakpointChange);
  } else if (typeof desktopQuery.addListener === "function") {
    desktopQuery.addListener(onBreakpointChange);
  }

  document.addEventListener("click", function (event) {
    var target = event.target;

    if (!target || typeof target.closest !== "function") {
      return;
    }

    if (target.closest(".vp-header__sub-toggle")) {
      return;
    }

    if (target.closest(".vp-header .is-sub-open > .sub-menu")) {
      return;
    }

    closeSubmenus(header);
  });

  enhanceSubmenus();
  onBreakpointChange();
})();
