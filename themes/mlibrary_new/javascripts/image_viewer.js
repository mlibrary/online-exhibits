document.addEventListener('DOMContentLoaded', function () {
    var $map = document.querySelector("#image-zoomer-os");
    var $toolbar;

    var identifier = $map.dataset.identifier;
    var image_base;

    var viewer; var mode;
    // -- info_url is the URL to the IIIF info end point for the image
    var info_url = identifier;

    viewer = OpenSeadragon({
        id: "image-zoomer-os",
        prefixUrl: "//openseadragon.github.io/openseadragon/images/",
        gestureSettingsMouse: {
          scrollToZoom: false,
          clickToZoom: false,
          dblClickToZoom: true,
          flickEnabled: true,
          pinchRotate: true
        },
        gestureSettingsTouch: {
          pinchRotate: true
        },
        showNavigationControl: true,
        showRotationControl: true,
        zoomInButton: 'action-zoom-in',
        zoomOutButton: 'action-zoom-out',
        rotateLeftButton: 'action-rotate-left',
        rotateRightButton: 'action-rotate-right',
        homeButton: 'action-reset-viewer'
    });

   viewer.addHandler('zoom', function(e) {
      document.querySelector("#span-zoom-status").innerText = Math.floor(e.zoom * 10) + '%';
   })

    viewer.open(info_url);
    var rotateViewer = function(delta) {
      var deg = viewer.viewport.getRotation();
      var next_deg = deg + delta;
      if ( next_deg < 0 ) { next_deg = 360 + next_deg; }
      viewer.viewport.setRotation(next_deg);     
    }
})
