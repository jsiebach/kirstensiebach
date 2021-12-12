window.$ = window.jQuery = require('jquery');
require('./flickr/js/jquery-ui.min.js')

$('.bxslider').bxSlider({
    captions:true,
    auto:true,
    pause:6000
});
