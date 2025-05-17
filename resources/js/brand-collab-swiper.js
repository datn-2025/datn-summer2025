import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.brandCollabSwiper', {
        slidesPerView: 1.2,
        spaceBetween: 16,
        slidesPerGroup: 1,

        breakpoints: {
            768: { slidesPerView: 3, slidesPerGroup: 3 },
            768: { slidesPerView: 3, slidesPerGroup: 3 },
        },
        navigation: {
            nextEl: '.brand-collab-next',
            prevEl: '.brand-collab-prev',
        },
        pagination: {
            el: '.brand-collab-pagination',
            type: 'bullets',
            clickable: true,
        },
        on: {
            init(swiper) {
                toggleButtons(swiper);
            },
            slideChange(swiper) {
                toggleButtons(swiper);
            },
        },
    });
    function toggleButtons(swiper) {
        const $prev = document.querySelector('.brand-collab-prev');
        const $next = document.querySelector('.brand-collab-next');

        $prev.style.display = swiper.isBeginning ? 'none' : 'block';
        $next.style.display = swiper.isEnd ? 'none' : 'block';
    }
});