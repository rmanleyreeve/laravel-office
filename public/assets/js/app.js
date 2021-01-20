/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/js/admin.js":
/*!**************************************!*\
  !*** ./resources/assets/js/admin.js ***!
  \**************************************/
/***/ (() => {

function setCookie(key, value, days) {
  var expires = new Date();
  expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
  document.cookie = key + '=' + value + ';path=/;expires=' + expires.toUTCString();
}

function getCookie(key) {
  var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
  return keyValue ? keyValue[2] : null;
}

function deleteCookie(key) {
  document.cookie = key + '=;path=/;expires=Thu, 01 Jan 1970 00:00:00 UTC;';
}

(function ($) {
  'use strict';

  if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
  }

  $.AdminBSB = {};
  /* Options =================================================================================================================
  *  You can manage the panel options */

  $.AdminBSB.options = {
    leftSideBar: {
      scrollColor: 'rgba(0,0,0,0.32)',
      scrollWidth: '5px',
      scrollAlwaysVisible: false,
      scrollBorderRadius: '8px',
      scrollRailBorderRadius: '8px'
    },
    rightSideBar: {
      scrollColor: 'rgba(0,0,0,0.32)',
      scrollWidth: '5px',
      scrollAlwaysVisible: false,
      scrollBorderRadius: '8px',
      scrollRailBorderRadius: '8px'
    },
    dropdownMenu: {
      effectIn: 'fadeIn',
      effectOut: 'fadeOut'
    },
    navbar: {
      toggleClass: 'ls-toggled'
    },
    panel: {
      iconClass: {
        close: 'fa fa-close',
        fullscreenOn: 'fa fa-expand',
        fullscreenOff: 'fa fa-compress',
        collapse: 'fa fa-chevron-up',
        expand: 'fa fa-chevron-down'
      },
      tooltip: {
        show: true,
        closeText: 'Close',
        fullscreenOnOffText: 'Toggle Fullscreen',
        collapseExpandText: 'Collapse/Expand',
        closePlacement: 'bottom',
        fullscreenPlacement: 'bottom',
        collapsePlacement: 'bottom'
      },
      controls: {
        collapsable: true,
        fullscreen: true,
        close: true
      }
    }
  };
  /* Panel - Function =======================================================================================================
  *  You can manage the panel options */

  $.AdminBSB.panel = {
    init: function init() {
      var $this = this;
      $this.initIcons();
    },
    initIcons: function initIcons() {
      var $this = this;
      var configs = $.AdminBSB.options.panel;
      $('.panel').each(function (i, key) {
        if (!$(key).parent().hasClass('panel-group')) {
          var dataAttrs = $(key).data();
          var $panelControls = $('<div>').addClass('panel-controls');
          if ($(key).find('.panel-controls').length > 0) $panelControls = $(key).find('.panel-controls'); //Collapsable Icon

          if (dataAttrs["panelCollapsable"] != undefined) {
            if (dataAttrs["panelCollapsable"]) $panelControls.append($this.collapsableIcon());
          } else {
            if (configs.controls.collapsable) $panelControls.append($this.collapsableIcon());
          } //Fullscreen Icon


          if (dataAttrs["panelFullscreen"] != undefined) {
            if (dataAttrs["panelFullscreen"]) $panelControls.append($this.fullscreenIcon());
          } else {
            if (configs.controls.fullscreen) $panelControls.append($this.fullscreenIcon());
          } //Close Icon


          if (dataAttrs["panelClose"] != undefined) {
            if (dataAttrs["panelClose"]) $panelControls.append($this.closeIcon());
          } else {
            if (configs.controls.close) $panelControls.append($this.closeIcon());
          } //Add to panel heading


          $($(key).find('.panel-heading')[0]).append($panelControls);
        }
      });
      setTimeout(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $this.closeEvent();
        $this.collapseEvent();
        $this.fullScreenEvent();
      }, 120);
    },
    closeIcon: function closeIcon() {
      var configs = $.AdminBSB.options.panel;
      var $anchor = $('<a>').attr('href', 'javascript:void(0);').addClass('panel-close'); //Check tooltip active/passive

      if (configs.tooltip.show) {
        $anchor.attr({
          'data-toggle': 'tooltip',
          'data-title': configs.tooltip.closeText,
          'data-placement': configs.tooltip.closePlacement
        });
      }

      var $i = $('<i>').addClass(configs.iconClass.close);
      return $anchor.append($i);
    },
    collapsableIcon: function collapsableIcon() {
      var configs = $.AdminBSB.options.panel;
      var $anchor = $('<a>').attr('href', 'javascript:void(0);').addClass('panel-collapsable'); //Check tooltip active/passive

      if (configs.tooltip.show) {
        $anchor.attr({
          'data-toggle': 'tooltip',
          'data-title': configs.tooltip.collapseExpandText,
          'data-placement': configs.tooltip.collapsePlacement
        });
      }

      var $i = $('<i>').addClass(configs.iconClass.collapse);
      return $anchor.append($i);
    },
    fullscreenIcon: function fullscreenIcon() {
      var configs = $.AdminBSB.options.panel;
      var $anchor = $('<a>').attr('href', 'javascript:void(0);').addClass('panel-fullscreen'); //Check tooltip active/passive

      if (configs.tooltip.show) {
        $anchor.attr({
          'data-toggle': 'tooltip',
          'data-title': configs.tooltip.fullscreenOnOffText,
          'data-placement': configs.tooltip.fullscreenPlacement
        });
      }

      var $i = $('<i>').addClass(configs.iconClass.fullscreenOn);
      return $anchor.append($i);
    },
    closeEvent: function closeEvent() {
      $('.panel').on('click', 'a.panel-close', function () {
        $(this).parents('.panel').fadeOut(function () {
          $(this).remove();
          $(this).tooltip('hide');
        });
      });
    },
    collapseEvent: function collapseEvent() {
      var configs = $.AdminBSB.options.panel;
      $('.panel').on('click', 'a.panel-collapsable', function () {
        var $icon = $(this).find('i');
        var iconClass = $icon.hasClass(configs.iconClass.collapse) ? configs.iconClass.expand : configs.iconClass.collapse;
        $icon.removeAttr('class').addClass(iconClass);
        $icon.parents('.panel').toggleClass('panel-collapsed');
        $icon.parents('.panel').find('.panel-body').slideToggle();
        $(this).tooltip('hide');
      });
    },
    fullScreenEvent: function fullScreenEvent() {
      var configs = $.AdminBSB.options.panel;
      $('.panel').on('click', 'a.panel-fullscreen', function () {
        var $icon = $(this).find('i');
        var iconClass = $icon.hasClass(configs.iconClass.fullscreenOn) ? configs.iconClass.fullscreenOff : configs.iconClass.fullscreenOn;
        $icon.removeAttr('class').addClass(iconClass);
        $icon.parents('.panel').toggleClass('panel-fullscreen');
        $(this).tooltip('hide');
      });
    }
  };
  /* Left Sidebar - Function =================================================================================================
  *  You can manage the left sidebar menu options */

  var windowWidthForLeftSideBar = $(window).width();
  $.AdminBSB.leftSideBar = {
    init: function init() {
      var $this = this;
      var $menu = $('.metismenu'),
          $body = $('body'); //Init menu

      $menu.metisMenu();
      $this.setMenuWhenFixedAndToggled();
      $(window).bind('load resize', function () {
        $this.setVerticalScrollBar();
        $this.setMenuOnlyFixedSidebar();
        $this.setMenuNonFixed();
        $this.setMenuFixedButNavbarNonFixed();
        $this.changeHiddenStatu();
      });
      $(window).bind('scroll', function () {
        $this.setMenuOnlyFixedSidebar();
        $this.setMenuNonFixed();
        $this.setMenuFixedButNavbarNonFixed();
      });
    },
    fadeEffect: function fadeEffect() {
      var $menu = $('.metismenu');
      $menu.hide();
      setTimeout(function () {
        $menu.fadeIn();
      }, 400);
    },
    setMenuWhenFixedAndToggled: function setMenuWhenFixedAndToggled() {
      var $this = this;
      var $menu = $('.metismenu');
      var $body = $('body');

      if ($this.isFixed() && $this.isToggled()) {
        $menu.hover(function () {
          //$this.fadeEffect();
          $body.removeClass('ls-toggled');
        }, function () {
          //$this.fadeEffect();
          $body.addClass('ls-toggled');
        });
      } else {
        $menu.unbind('mouseenter mouseleave');
      }
    },
    setSubMenuHeight: function setSubMenuHeight() {
      $('.metismenu').find('li').has('ul').children('a').on('click', function () {
        var $this = $(this);
        var heightVal = $(window).height() - $this.offset().top;
        $this.next().css({
          'max-height': heightVal,
          'overflow-y': 'hidden'
        });
        setTimeout(function () {
          $this.next().css('overflow-y', 'auto');
        }, 400);
      });
    },
    setVerticalScrollBar: function setVerticalScrollBar() {
      var $this = this;

      if ($this.isFixed()) {
        var $menu = $('.metismenu');
        var height = $.AdminBSB.navbar.isFixed() ? $(window).height() - $('.navbar').height() : $(window).height();
        $menu.slimScroll({
          destroy: true
        }).height('auto');
        $menu.parent().find('.slimScrollBar, .slimScrollRail').remove();
        var configs = $.AdminBSB.options.leftSideBar;
        $menu.slimscroll({
          height: height + "px",
          color: configs.scrollColor,
          size: configs.scrollWidth,
          alwaysVisible: configs.scrollAlwaysVisible,
          borderRadius: configs.scrollBorderRadius,
          railBorderRadius: configs.scrollRailBorderRadius
        });
      }
    },
    isFixed: function isFixed() {
      return $('body').hasClass('ls-fixed');
    },
    isToggled: function isToggled() {
      return $('body').hasClass('ls-toggled');
    },
    setVerticalScrollbar: function setVerticalScrollbar() {
      var $menu = $('.metismenu');

      if (typeof $.fn.slimScroll != 'undefined' && $('body').hasClass('fixed-sidebar')) {
        var $body = $('body');
        var height;

        if ($body.hasClass('fixed-sidebar') && !$body.hasClass('fixed-navbar')) {
          height = $(window).height();
        } else if ($body.hasClass('navbar-fixed')) {
          height = $(window).height() - $('.navbar').height();
        } else {
          $menu.slimScroll({
            destroy: true
          });
          return;
        }

        var configs = $.AdminBSB.options.leftSideBar;
        $menu.slimScroll({
          destroy: true
        }).height('auto');
        $menu.parent().find('.slimScrollBar, .slimScrollRail').remove();
        $menu.slimscroll({
          height: height + "px",
          color: configs.scrollColor,
          size: configs.scrollWidth,
          alwaysVisible: configs.scrollAlwaysVisible,
          borderRadius: configs.scrollBorderRadius,
          railBorderRadius: configs.scrollRailBorderRadius
        });
      } else {
        $menu.slimScroll({
          destroy: true
        });
      }
    },
    setMenuOnlyFixedSidebar: function setMenuOnlyFixedSidebar() {
      var $body = $('body');

      if ($body.hasClass('fixed-sidebar') && !$body.hasClass('fixed-navbar')) {
        var paddingTop = 50 - $(window).scrollTop();
        paddingTop = paddingTop < 0 ? 0 : paddingTop;
        $('.sidebar').css('padding-top', paddingTop);
      }
    },
    setMenuNonFixed: function setMenuNonFixed() {
      var $this = this;
      $this.setSidebarHeight();
      $('.metismenu').on('click', '.collapse.in li a', function (e) {
        e.stopPropagation();
      });
    },
    setSidebarHeight: function setSidebarHeight() {
      var $sidebar = $('.sidebar');
      var $content = $('.content');
      var $doc = $(document);
      var sidebarHeight = $sidebar.find('.sidebar-nav').height();
      var contentHeight = $content.height();
      var docHeight = $doc.height() - $('.navbar').height();
      var sidebarNewHeight = Math.max(sidebarHeight, contentHeight, docHeight) + ($sidebar.innerHeight() - $sidebar.height());
      $sidebar.css('height', sidebarNewHeight);
      $content.each(function (i, key) {
        if ($(key).parents('.wizard').length === 0) {
          $(key).css('min-height', sidebarHeight);
        }
      });
    },
    setMenuFixedButNavbarNonFixed: function setMenuFixedButNavbarNonFixed() {
      var $this = this;
      var $sidebar = $('.sidebar');

      if ($this.isFixed() && !$.AdminBSB.navbar.isFixed()) {
        var scrollTop = $(window).scrollTop();
        var top = 50 - scrollTop < 0 ? 0 : scrollTop > 50 ? scrollTop : 50 - scrollTop;
        $sidebar.css('top', top);
      }
    },
    changeHiddenStatu: function changeHiddenStatu() {
      var width = $(window).width();
      var $body = $('body');

      if (width < 767) {
        $body.addClass('ls-hidden');
      } else {
        $body.removeClass('ls-hidden');
      }
    }
  };
  /* Right Sidebar - Function ================================================================================================
  *  You can manage the right sidebar menu options */

  $.AdminBSB.rightSideBar = {
    init: function init() {
      var $this = this;
      var $sidebar = $('.right-sidebar');
      var $closeBtn = $('.right-sidebar-close');
      var $openSidebarBtn = $('.js-right-sidebar');
      $openSidebarBtn.on('click', function () {
        $sidebar.addClass('open');
      });
      $closeBtn.on('click', function () {
        $sidebar.removeClass('open');
      });
      $this.setVerticalScroll();
      $(window).resize($this.setVerticalScroll);
    },
    setVerticalScroll: function setVerticalScroll() {
      var $contentAreas = $('.right-sidebar .tab-container');
      var configs = $.AdminBSB.options.rightSideBar;
      var height = $(window).height() - $('.right-sidebar .nav-tabs').height();
      $contentAreas.slimscroll({
        height: height + "px",
        color: configs.scrollColor,
        size: configs.scrollWidth,
        alwaysVisible: configs.scrollAlwaysVisible,
        borderRadius: configs.scrollBorderRadius,
        railBorderRadius: configs.scrollRailBorderRadius
      });
    }
  }; //==========================================================================================================================

  /* Navbar - Function =======================================================================================================
  *  You can manage the navbar options */

  $.AdminBSB.navbar = {
    init: function init() {
      var $this = this;
      var $navbarToggle = $('.js-toggle-left-sidebar');
      var $leftNavbarToggle = $('.js-left-toggle-left-sidebar');
      var $body = $('body');
      var $navbarMenu = $('.dropdown .body .menu');
      var $searchBar = $('.search-bar');
      $navbarToggle.on('click', function () {
        $body.toggleClass($.AdminBSB.options.navbar.toggleClass);
        $.AdminBSB.leftSideBar.fadeEffect();
        $.AdminBSB.leftSideBar.setMenuWhenFixedAndToggled();
        deleteCookie('lsb-toggled');
        setCookie('lsb-toggled', $body.hasClass($.AdminBSB.options.navbar.toggleClass), 365);
      });

      if (getCookie('lsb-toggled') == 'true') {
        $navbarToggle.trigger('click');
      }

      $leftNavbarToggle.on('click', function () {
        $body.toggleClass('ls-hidden');
      });
      $navbarMenu.slimscroll({
        height: 255,
        color: 'rgba(0,0,0,0.5)',
        size: '4px',
        alwaysVisible: false,
        borderRadius: '0',
        railBorderRadius: '0'
      }); //Open search bar

      $('.js-search').on('click', function () {
        $searchBar.addClass('open');
        $searchBar.find('input[type="text"]').focus();
      }); //Close search bar

      $('.js-close-search').on('click', function () {
        $searchBar.removeClass('open');
      });
      $(document).keyup(function (e) {
        if (e.keyCode == 27 && $searchBar.hasClass('open')) {
          // escape key maps to keycode `27`
          $searchBar.removeClass('open');
        }
      });
      $this.fullScreen();
    },
    isFixed: function isFixed() {
      return $('body').hasClass('navbar-fixed');
    },
    fullScreen: function fullScreen() {
      var $fullScreen = $('.js-fullscreen');
      var $icon = $fullScreen.find('.material-icons');

      if (screenfull.enabled) {
        $fullScreen.on('click', function () {
          if (screenfull.isFullscreen) {
            screenfull.exit();
          } else {
            screenfull.request();
          }
        });
        $(document).on(screenfull.raw.fullscreenchange, function () {
          if (screenfull.isFullscreen) {
            $icon.text('fullscreen_exit');
          } else {
            $icon.text('fullscreen');
          }
        });
      }
    }
  };
  /* Browser - Function ======================================================================================================
  *  You can manage browser
  *  
  */

  var edge = 'Microsoft Edge';
  var ie10 = 'Internet Explorer 10';
  var ie11 = 'Internet Explorer 11';
  var opera = 'Opera';
  var firefox = 'Mozilla Firefox';
  var chrome = 'Google Chrome';
  var safari = 'Safari';
  $.AdminBSB.browser = {
    init: function init() {
      var _this = this;

      var className = _this.getClassName();

      if (className !== '') $('html').addClass(_this.getClassName());
    },
    getBrowser: function getBrowser() {
      var userAgent = navigator.userAgent.toLowerCase();

      if (/edge/i.test(userAgent)) {
        return edge;
      } else if (/rv:11/i.test(userAgent)) {
        return ie11;
      } else if (/msie 10/i.test(userAgent)) {
        return ie10;
      } else if (/opr/i.test(userAgent)) {
        return opera;
      } else if (/chrome/i.test(userAgent)) {
        return chrome;
      } else if (/firefox/i.test(userAgent)) {
        return firefox;
      } else if (!!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/)) {
        return safari;
      }

      return undefined;
    },
    getClassName: function getClassName() {
      var browser = this.getBrowser();

      if (browser === edge) {
        return 'edge';
      } else if (browser === ie11) {
        return 'ie11';
      } else if (browser === ie10) {
        return 'ie10';
      } else if (browser === opera) {
        return 'opera';
      } else if (browser === chrome) {
        return 'chrome';
      } else if (browser === firefox) {
        return 'firefox';
      } else if (browser === safari) {
        return 'safari';
      } else {
        return '';
      }
    }
  }; //==========================================================================================================================

  /* DropdownMenu - Function =================================================================================================
  *  You can manage the dropdown menu */

  $.AdminBSB.dropdownMenu = {
    init: function init() {
      var $this = this;
      $('.dropdown, .dropup, .btn-group').on({
        "show.bs.dropdown": function showBsDropdown() {
          var dropdown = $this.dropdownEffect(this);
          $this.dropdownEffectStart(dropdown, dropdown.effectIn);
        },
        "shown.bs.dropdown": function shownBsDropdown() {
          var dropdown = $this.dropdownEffect(this);

          if (dropdown.effectIn && dropdown.effectOut) {
            $this.dropdownEffectEnd(dropdown, function () {});
          }
        },
        "hide.bs.dropdown": function hideBsDropdown(e) {
          var dropdown = $this.dropdownEffect(this);

          if (dropdown.effectOut) {
            e.preventDefault();
            $this.dropdownEffectStart(dropdown, dropdown.effectOut);
            $this.dropdownEffectEnd(dropdown, function () {
              dropdown.dropdown.removeClass('open');
            });
          }
        }
      });
    },
    dropdownEffect: function dropdownEffect(target) {
      var effectIn = $.AdminBSB.options.dropdownMenu.effectIn,
          effectOut = $.AdminBSB.options.dropdownMenu.effectOut;
      var dropdown = $(target),
          dropdownMenu = $('.dropdown-menu', target);

      if (dropdown.length > 0) {
        var udEffectIn = dropdown.data('effect-in');
        var udEffectOut = dropdown.data('effect-out');

        if (udEffectIn !== undefined) {
          effectIn = udEffectIn;
        }

        if (udEffectOut !== undefined) {
          effectOut = udEffectOut;
        }
      }

      return {
        target: target,
        dropdown: dropdown,
        dropdownMenu: dropdownMenu,
        effectIn: effectIn,
        effectOut: effectOut
      };
    },
    dropdownEffectStart: function dropdownEffectStart(data, effectToStart) {
      if (effectToStart) {
        data.dropdown.addClass('dropdown-animating');
        data.dropdownMenu.addClass('animated dropdown-animated');
        data.dropdownMenu.addClass(effectToStart);
      }
    },
    dropdownEffectEnd: function dropdownEffectEnd(data, callback) {
      var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
      data.dropdown.one(animationEnd, function () {
        data.dropdown.removeClass('dropdown-animating');
        data.dropdownMenu.removeClass('animated dropdown-animated');
        data.dropdownMenu.removeClass(data.effectIn);
        data.dropdownMenu.removeClass(data.effectOut);

        if (typeof callback == 'function') {
          callback();
        }
      });
    }
  }; //==========================================================================================================================

  /* Page Load - Function ====================================================================================================
  *  You can manage the function when page loaded */

  $(function () {
    $.AdminBSB.leftSideBar.init();
    $.AdminBSB.rightSideBar.init();
    $.AdminBSB.navbar.init();
    $.AdminBSB.panel.init();
    $.AdminBSB.dropdownMenu.init();
    $.AdminBSB.browser.init();
  });
})(jQuery);

/***/ }),

/***/ "./resources/assets/js/app.js":
/*!************************************!*\
  !*** ./resources/assets/js/app.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

var admin = __webpack_require__(/*! ./admin */ "./resources/assets/js/admin.js");

function checkNotify() {
  return 'Notification' in window;
}

if (checkNotify() && Notification && Notification.permission === 'default') {
  Notification.requestPermission().then(function (permission) {
    if (!('permission' in Notification)) {
      Notification.permission = permission;
    }
  });
}

function sendDesktopNotification(title, text, autoclose) {
  if (!checkNotify()) {
    return;
  }

  if (Notification.permission === "granted") {
    var notification = new Notification(title, {
      icon: '/assets/images/date_time.png',
      body: text,
      tag: 'office.re-media.biz Notification',
      requireInteraction: !autoclose
    });

    notification.onclick = function () {
      parent.focus();
      window.focus();
      this.close();
    };

    if (autoclose) {
      setTimeout(notification.close.bind(notification), 5000);
    }
  }
}

function setICheckbox() {
  $('input:not(.js-switch)').iCheck({
    checkboxClass: 'icheckbox_square-aero',
    radioClass: 'iradio_square-aero'
  });
  $('input:not(.js-switch)').on('ifToggled', function (e) {
    $(this).parents('li').toggleClass('closed');
  });
}

window.updateClock = function () {
  var currentTime = new Date();
  var currentHrs = currentTime.getHours();
  var currentMins = currentTime.getMinutes();
  var currentSecs = currentTime.getSeconds();

  if (0 == currentHrs && 0 == currentMins && currentSecs < 4) {
    location.reload();
  }

  currentHrs = (currentHrs < 10 ? "0" : "") + currentHrs;
  currentMins = (currentMins < 10 ? "0" : "") + currentMins;
  var currentTimeString = currentHrs + ":" + currentMins;
  $("#cal-time").html(currentTimeString);
}; // set notification counter from session on page load


var globalNotificationBadgeNum = 0;
fetch('/ajax/header-notifications-count').then(function (response) {
  return response.json();
}).then(function (responseJson) {
  globalNotificationBadgeNum = responseJson.count;
})["catch"](function (ex) {
  console.log('set globalNotificationBadgeNum failed', ex);
});

function getNewNotifications() {
  var _notifications = [];
  var _unread = [];
  var _read = [];
  fetch('/ajax/header-notifications').then(function (response) {
    return response.json();
  }).then(function (responseJson) {
    //console.log(responseJson);
    $('#header-notifications-list li').remove();
    $('#mark-all-read').data('uids', '');
    $.each(responseJson, function (i, e) {
      //console.log(e);
      var _class = "unread";

      _notifications.push(e.uid);

      if (e.notification_read > 0) {
        _read.push(e.uid);

        _class = "read";
      } else {
        _unread.push(e.uid);
      }

      var li = '<li data-uid="' + e.uid + '" class="notification ' + _class + '"><a href="javascript:void(0);">';
      li += 'ENTRY' == e.activity ? '<div class="icon-circle bg-success"><i class="material-icons">person</i></div>' : '<div class="icon-circle bg-warning"><i class="material-icons">person_outline</i></div>';
      li += '<div class="menu-info"><h4>' + e.firstname + ' ' + e.surname + ' ' + e.activity + '</h4>';
      li += '<p><i class="material-icons">access_time</i> ' + e.time + '</p></div></a></li>'; //console.log(li);

      $('#header-notifications-list').append($(li));
    });
    $('#mark-all-read').data('uids', _unread);
    var count = _notifications.length - _read.length;

    if (count > 0) {
      $('#header-notifications span.label-count').html(count).show(1000);

      if (count > globalNotificationBadgeNum) {
        //console.log('new activity detected: ',count,globalNotificationBadgeNum);
        var msg = responseJson[0].firstname + ' ' + responseJson[0].surname + ' ' + responseJson[0].activity + ' ' + responseJson[0].time;
        sendDesktopNotification('Back Office System', msg, true);
      }
    } else {
      $('#header-notifications span.label-count').html('0').hide(1000);
    }

    globalNotificationBadgeNum = count;
  })["catch"](function (ex) {
    console.log('getNewNotifications failed', ex);
  });
}

function getAlerts() {
  fetch('/ajax/header-alerts').then(function (response) {
    return response.json();
  }).then(function (responseJson) {
    //console.log(responseJson);
    $('#header-alerts-list li').remove();
    $.each(responseJson, function (i, e) {
      //console.log(i,e);
      var li = '<li class="unread"><a href="/admin/check-attendance">';
      li += '<div class="icon-circle bg-warning"><i class="material-icons">warning</i></div>';
      li += '<div class="menu-info"><h4>' + e.name + ' ' + e.date + '</h4><p>';
      $.each(e.activity, function (ii, ee) {
        li += ee.activity + ' ' + ee.time + (ii < e.activity.length - 1 ? '<br>' : '');
      });
      li += '</p></div></a></li>';
      $('#header-alerts-list').append($(li));
    });
    var count = Object.keys(responseJson).length;

    if (count > 0) {
      $('#header-alerts span.label-count').html(count).show(1000);
      sendDesktopNotification('Attention', 'There are ' + count + ' attendance errors requiring repair!', false);
    } else {
      $('#header-alerts span.label-count').html('0').hide(1000);
    }
  })["catch"](function (ex) {
    console.log('getAlerts failed', ex);
  });
}

function updateRead(uid) {
  fetch('/ajax/header-notifications', {
    method: 'post',
    headers: {
      "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
    },
    body: 'uid=' + JSON.stringify(uid)
  }).then(function (response) {
    return response.text();
  }).then(function (responseText) {
    console.log(uid + " read " + responseText);
  })["catch"](function (ex) {
    console.log(uid + ' read failed', ex);
  });
}

$(function () {
  getNewNotifications();
  setInterval(getNewNotifications, 20000);
  getAlerts();
  $('#mark-all-read').on('click', function () {
    var uids = $(this).data('uids');
    updateRead(uids);
    $('#header-notifications-list li').addClass("read").removeClass("unread");
    $('#header-notifications span.label-count').html('0').hide(1000);
  });
  var t;
  $(document).on('mouseenter', '#header-notifications-list li.unread', function () {
    var $el = $(this);
    var uid = $el.data('uid');
    t = setTimeout(function () {
      updateRead(uid);
      $el.addClass("read").removeClass("unread");
      var c = $('#header-notifications span.label-count').html();
      $('#header-notifications span.label-count').html(c - 1);
    }, 1000);
  });
  $(document).on('mouseleave', '#header-notifications-list li.unread', function () {
    clearTimeout(t);
  });
  $('#header-notifications').on('show.bs.dropdown', function (e) {}); //tooltip init

  $('[data-toggle="tooltip"]').tooltip({
    container: 'body',
    trigger: 'hover'
  }); // set BS modal title

  $('#bsModal').on('shown.bs.modal', function (e) {
    $('#modalTitle').text($('#dynamicModalTitle').text());
  }); // clear BS modal cache

  $('#bsModal').on('hidden.bs.modal', function () {
    $('#bsModal').removeData('bs.modal');
  }); //Init switch buttons

  var $switchButtons = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
  $switchButtons.forEach(function (e) {
    var size = $(e).data('size');
    var options = {};
    options['color'] = '#009688';
    if (size !== undefined) options['size'] = size;
    var switchery = new Switchery(e, options);
  }); //Init iCheckbox

  setICheckbox(); // button alerts

  $(document).on('click', '.confirm-delete', function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    bootbox.confirm({
      size: "small",
      closeButton: false,
      message: 'Are you absolutely sure you wish to delete this record?',
      callback: function callback(r) {
        if (r) {
          window.location = href;
        }
      }
    });
  });
  $(document).on('click', '.delete-record-disabled', function (e) {
    e.preventDefault();
    var dep = $(this).data('assoc');
    bootbox.alert({
      size: "small",
      closeButton: false,
      message: 'This record cannot be deleted because it has ' + dep + ' assigned.\nYou must delete or reassign the ' + dep + ' first.'
    });
  });
  $('.confirm-logoff').on('click', function (e) {
    var href = $(this).attr('href');
    e.preventDefault();
    bootbox.confirm({
      size: "small",
      closeButton: false,
      message: "Are you sure you want to log out?",
      callback: function callback(r) {
        if (r) {
          window.location = href;
        }
      }
    });
  }); //form validation

  $('form.validate').validate({
    rules: {
      username: {
        required: true,
        remote: {
          param: {
            url: "/users/check-username/",
            type: 'post'
          },
          depends: function depends(e) {
            return $(e).val() !== $('#original_username').val();
          }
        }
      }
    },
    highlight: function highlight(element) {
      $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function unhighlight(element) {
      $(element).closest('.form-group').removeClass('has-error');
    },
    errorPlacement: function errorPlacement(error, element) {
      if (element.parent('.input-group').length) {
        error.insertAfter(element.parent());
      } else {
        error.insertAfter(element);
      }
    }
  }); // add date to csv export files URL, so the file gets generated with this name

  $('a.add-date').on('click', function (e) {
    var href = $(this).data('href');
    var t = $.datepicker.formatDate('yy-mm-dd', new Date());

    if (href.indexOf('.csv') == -1) {
      $(this).attr('href', href + '_' + t + '.csv');
      return true;
    }

    $(this).attr('href', href.replace('Export', 'Export_' + t));
    return true;
  });
});

/***/ }),

/***/ "./resources/assets/scss/app.scss":
/*!****************************************!*\
  !*** ./resources/assets/scss/app.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/assets/css/fonts.css":
/*!****************************************!*\
  !*** ./resources/assets/css/fonts.css ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		if(__webpack_module_cache__[moduleId]) {
/******/ 			return __webpack_module_cache__[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/******/ 	// the startup function
/******/ 	// It's empty as some runtime module handles the default behavior
/******/ 	__webpack_require__.x = x => {}
/************************************************************************/
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => Object.prototype.hasOwnProperty.call(obj, prop)
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// Promise = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/js/app": 0
/******/ 		};
/******/ 		
/******/ 		var deferredModules = [
/******/ 			["./resources/assets/js/app.js"],
/******/ 			["./resources/assets/scss/app.scss"],
/******/ 			["./resources/assets/css/fonts.css"]
/******/ 		];
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		var checkDeferredModules = x => {};
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime, executeModules] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0, resolves = [];
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					resolves.push(installedChunks[chunkId][0]);
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			for(moduleId in moreModules) {
/******/ 				if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 					__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 				}
/******/ 			}
/******/ 			if(runtime) runtime(__webpack_require__);
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			while(resolves.length) {
/******/ 				resolves.shift()();
/******/ 			}
/******/ 		
/******/ 			// add entry modules from loaded chunk to deferred list
/******/ 			if(executeModules) deferredModules.push.apply(deferredModules, executeModules);
/******/ 		
/******/ 			// run deferred modules when all chunks ready
/******/ 			return checkDeferredModules();
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 		
/******/ 		function checkDeferredModulesImpl() {
/******/ 			var result;
/******/ 			for(var i = 0; i < deferredModules.length; i++) {
/******/ 				var deferredModule = deferredModules[i];
/******/ 				var fulfilled = true;
/******/ 				for(var j = 1; j < deferredModule.length; j++) {
/******/ 					var depId = deferredModule[j];
/******/ 					if(installedChunks[depId] !== 0) fulfilled = false;
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferredModules.splice(i--, 1);
/******/ 					result = __webpack_require__(__webpack_require__.s = deferredModule[0]);
/******/ 				}
/******/ 			}
/******/ 			if(deferredModules.length === 0) {
/******/ 				__webpack_require__.x();
/******/ 				__webpack_require__.x = x => {};
/******/ 			}
/******/ 			return result;
/******/ 		}
/******/ 		var startup = __webpack_require__.x;
/******/ 		__webpack_require__.x = () => {
/******/ 			// reset startup function so it can be called again when more startup code is added
/******/ 			__webpack_require__.x = startup || (x => {});
/******/ 			return (checkDeferredModules = checkDeferredModulesImpl)();
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	// run startup
/******/ 	return __webpack_require__.x();
/******/ })()
;