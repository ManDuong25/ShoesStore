! function () {
    "use strict";

    function e(e) {
        (console.error ? console.error : console.log).call(console, e)
    }

    function t(e) {
        return l.innerHTML = '<a href="' + e.replace(/"/g, "&quot;") + '"></a>', l.childNodes[0].getAttribute("href")
    }

    function r(e, t) {
        var r = e.substr(t, 2);
        return parseInt(r, 16)
    }

    function n(e, n) {
        for (var o = "", c = r(e, n), a = n + 2; a < e.length; a += 2) {
            var l = r(e, a) ^ c;
            o += String.fromCharCode(l)
        }
        return t(o)
    }
    var o = "/cdn-cgi/l/email-protection#",
        c = ".__cf_email__",
        a = "data-cfemail",
        l = document.createElement("div");
    ! function () {
        for (var t = document.getElementsByTagName("a"), r = 0; r < t.length; r++) try {
            var c = t[r],
                a = c.href.indexOf(o);
            a > -1 && (c.href = "mailto:" + n(c.href, a + o.length))
        } catch (t) {
            e(t)
        }
    }(),
        function () {
            for (var t = document.querySelectorAll(c), r = 0; r < t.length; r++) try {
                var o = t[r],
                    l = n(o.getAttribute(a), 0),
                    i = document.createTextNode(l);
                o.parentNode.replaceChild(i, o)
            } catch (t) {
                e(t)
            }
        }()
}();