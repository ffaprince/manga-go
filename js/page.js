var series_url = "/manga/" + series_name;
function change_page(p) {
    var t = p.options[p.selectedIndex].value;
    document.location = t + ".html"
}
function change_chapter(c) {
    var t = c.options[c.selectedIndex].value;
    document.location = series_url + "/" + t + "/"
}
function previous_chapter() {
    if (current_page <= 1 && current_chapter_index === 0) {
        document.location = series_url + '/'
    } else {
        document.location = series_url + '/' + ($('top_chapter_list').options[current_chapter_index - 1].value) + '/last.html'
    }
    return false
}
function next_chapter() {
    if (current_page >= total_pages && current_chapter_index + 1 == total_chapters) {
        document.location = series_url + '/'
    } else {
        document.location = series_url + '/' + ($('top_chapter_list').options[current_chapter_index + 1].value) + '/'
    }
    return false
}
function previous_page() {
    if (current_page <= 1) {
        previous_chapter()
    } else {
        document.location = series_url + '/' + current_chapter + '/' + (current_page - 1) + '.html'
    }
    return false
}
function next_page() {
    if (current_page >= total_pages) {
        next_chapter()
    } else {
        document.location = series_url + '/' + current_chapter + '/' + (current_page + 1) + '.html'
    }
    return false
}
function checkFrame() {
    try {
        if (parent.document.getElementById("_chat").src !== "") {
            if (window.top != window.self) {
                window.top.location = parent._scan.location.href
            }
        } else {
            window.location = window.location + "?chat"
        }
    } catch(err) {
        window.location = window.location + "?chat"
    }
    return false
}
function enlarge() {
    var a = $("viewer");
    var b = $("image");
    var c = a.getStyle("width").toInt();
    if (image_width > Window.getWidth() - 30) {
        if (c > Window.getWidth() - 30) {
            next_page()
        } else if (c == Window.getWidth() - 30) {
            a.setStyle("width", image_width + 12);
            b.setStyle("width", image_width)
        } else {
            a.setStyle("width", Window.getWidth() - 30);
            b.setStyle("width", a.getStyle("width").toInt() - 12)
        }
    } else {
        if (c < image_width) {
            a.setStyle("width", image_width + 12);
            b.setStyle("width", image_width)
        } else {
            next_page()
        }
    }
    return false
}
function process(a) {
    if (a == 39) {
        next_page();
        return false
    } else if (a == 37) {
        previous_page();
        return false
    }
}
function process_ie(e) {
    if (!e) {
        e = window.event
    }
    if (e.keyCode) {
        keycode = e.keyCode;
        window.event.keyCode = 0
    } else {
        keycode = e.which
    }
    process(keycode)
}
function process_others(e) {
    if (e.which) {
        keycode = e.which
    } else {
        keycode = e.keyCode
    }
    process(keycode)
}
var browser = navigator.appName;
if (browser == "Microsoft Internet Explorer") {
    document.onkeydown = process_ie
} else {
    document.onkeypress = process_others
}
