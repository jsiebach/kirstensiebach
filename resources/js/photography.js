import './jquery-global';
import './flickr/js/jquery-ui.min.js';
import './flickr/js/FlickrAPI.js';
import './flickr/js/Flickr.Gallery.js';
import { onPageLoad } from './page-init';

onPageLoad(() => {
    const gallery = $('#gallery');

    if (!gallery.length || gallery.data('initialized')) {
        return;
    }

    gallery.data('initialized', true).flickrGallery({
        Key: 'dd7e89c7f0c07a951c30b34d7a013486',
        Secret: 'd792124bcd9f09bb',
        User: '77604748@N07',
        PhotoSet: gallery.data('album'),
        Speed   : 400,    //Speed of animations
        navigation  : 1,    //(true) <a href="http://www.jqueryscript.net/tags.php?/Navigation/">Navigation</a> (arrows)
        keyboard  : 1,    //(true) Keyboard navigation
        numberEl  : 1     //(true) Number elements,
    });
});
