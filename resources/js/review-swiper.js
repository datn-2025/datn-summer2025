import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/pagination';

new Swiper('.reviewSwiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        768: {
            slidesPerView: 2
        }
    }
});
