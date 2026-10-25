import './jquery-global';
import './jquery.bxslider/jquery.bxslider.js';
import { onPageLoad } from './page-init';

onPageLoad(() => {
    $('.bxslider').each(function () {
        if ($(this).closest('.bx-wrapper').length) {
            return;
        }

        $(this).bxSlider({
            captions: true,
            auto: true,
            pause: 6000,
        });
    });
});
