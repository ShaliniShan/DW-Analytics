$(document).ready(function(){function a(a){var b=$(".sidebar__menu");b.removeClass("active").eq(a.index()).addClass("active"),$(".quickmenu__item").removeClass("active"),a.addClass("active"),b.eq(0).css("margin-left","-"+200*a.index()+"px")}a($(".quickmenu__item.active")),$("body").on("click",".quickmenu__item",function(){a($(this))}),$(".sidebar li").on("click",function(a){a.stopPropagation();var b=$(this).find(".collapse").first();b.length&&(b.collapse("toggle"),$(this).toggleClass("opened"))}),$("body.main-scrollable .main__scroll").scrollbar(),$(".scrollable").scrollbar({disableBodyScroll:!0}),$(window).on("resize",function(){$("body.main-scrollable .main__scroll").scrollbar(),$(".scrollable").scrollbar({disableBodyScroll:!0})}),$(".selectize-dropdown-content").addClass("scrollable scrollbar-macosx").scrollbar({disableBodyScroll:!0}),$(".nav-pills, .nav-tabs").tabdrop(),$("body").on("click",".header-navbar-mobile__menu button",function(){$(".dashboard").toggleClass("dashboard_menu")}),$("input.bs-switch").bootstrapSwitch(),$(".settings-slider").ionRangeSlider({decorate_both:!1})});