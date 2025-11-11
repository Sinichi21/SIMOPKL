$(document).ready(function () {
    var currentURL = window.location.pathname;

    if (currentURL === "/" || currentURL === "/bpiui/") {
        $("#navbar").removeClass("bg-white shadow");
    }

    $("[data-collapse-toggle]").on("click", function () {
        $("#mobile-menu-2").toggleClass("hidden");

        if (
            $("#mobile-menu-2").hasClass("hidden") &&
            $(window).scrollTop() <= 100
        ) {
            $("#navbar").removeClass("bg-white shadow");
        } else {
            $("#navbar").addClass("bg-white shadow");
        }
    });

    var currentYear = new Date().getFullYear();
    $("#footer-year").text(currentYear);

    // Detect scroll to show/hide the button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $("#navbar").addClass("bg-white shadow");
        } else {
            if (currentURL === "/" || currentURL === "/bpiui/") {
                $("#navbar").removeClass("bg-white shadow");
            }
        }

        if ($(this).scrollTop() > 500) {
            $("#scrollTop").removeClass("hidden").fadeIn();
        } else {
            $("#scrollTop").fadeOut();
        }
    });

    // Animate scroll to top on button click
    $("#scrollTop").click(function () {
        $("html, body").animate(
            {
                scrollTop: 0,
            },
            800
        ); // Smooth scroll effect
        return false;
    });
});
