function downloadURI(uri, name) {
    var link = document.createElement("a");

    link.download = name;
    link.href = uri;
    document.body.appendChild(link);
    link.click();
}

function DownloadAsImage() {
    var element = $("#capture")[0];
    
    html2canvas(element, {
        scrollX: -window.scrollX,
        scrollY: -window.scrollY,
        windowWidth: document.documentElement.offsetWidth,
        windowHeight: document.documentElement.offsetHeight,
        backgroundColor: null
    }).then(function (canvas) {
        var myImage = canvas.toDataURL();
        downloadURI(myImage, "publicidade.png");
    });
}