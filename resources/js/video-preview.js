// Swap a video preview for the real YouTube player only when it's clicked
$(document).on('click', '.video-preview', function () {
    const preview = $(this);

    preview.replaceWith($('<iframe>', {
        src: `https://www.youtube-nocookie.com/embed/${preview.data('youtube-id')}?autoplay=1`,
        title: preview.data('title'),
        allow: 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture',
        allowfullscreen: true,
    }));
});
