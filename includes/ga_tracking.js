var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-3825515-1']);
(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

$(document).on("pageshow", "[data-role=page]", function (event, el) {
	if ($.mobile.activePage.attr("data-url")) {
        _gaq.push(['_trackPageview', $.mobile.activePage.attr("data-url")]);
    } else {
        _gaq.push(['_trackPageview']);
    }
});
