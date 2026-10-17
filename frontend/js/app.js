$(document).ready(function () {

    
    const app = $.spapp({
        templateDir: "./views/",
        defaultView: "dashboard",
        pageNotFound: "error_404"
    });

    
    function onViewChange() {
        const view = location.hash.slice(1) || "dashboard";
        const isAuthPage = (view === "login" || view === "register");
        $("body").toggleClass("auth-mode", isAuthPage)
        
        $("#main-nav .nav-link")
            .removeClass("active")
            .filter('[href="#' + view + '"]')
            .addClass("active");

        
        const title = $('#main-nav .nav-link[href="#' + view + '"]').text().trim();
        $("#page-title").text(title || "GymHub");

        const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("main-nav"));
        if (offcanvas) {
            offcanvas.hide();
        }
        window.scrollTo(0, 0);
    }

    window.addEventListener("hashchange", onViewChange);
    onViewChange();

    app.run();
});