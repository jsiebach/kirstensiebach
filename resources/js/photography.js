window.$ = window.jQuery = require('jquery');
require('./flickr/js/jquery-ui.min.js')
require('./flickr/js/FlickrAPI.js')
require('./flickr/js/Flickr.Gallery.js')

let albumName = $('#gallery').data('album')

$("#gallery").flickrGallery({
    Key: 'dd7e89c7f0c07a951c30b34d7a013486',
    Secret: 'd792124bcd9f09bb',
    User: '77604748@N07',
    PhotoSet: albumName,
    Speed   : 400,    //Speed of animations
    navigation  : 1,    //(true) <a href="http://www.jqueryscript.net/tags.php?/Navigation/">Navigation</a> (arrows)
    keyboard  : 1,    //(true) Keyboard navigation
    numberEl  : 1     //(true) Number elements,
});
