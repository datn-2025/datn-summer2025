import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.categorySwiper').forEach(swiperEl => {
        const parent = swiperEl.closest('.tab-content');
        const prefix = parent?.id?.replace('tab-', '') ?? '';

        new Swiper(swiperEl, {
            spaceBetween: 16,
            slidesPerView: 1,
            slidesPerGroup: 1,

            breakpoints: {
                640: { slidesPerView: 2, slidesPerGroup: 2 },
                768: { slidesPerView: 3, slidesPerGroup: 3 },
                1024: { slidesPerView: 4, slidesPerGroup: 4 },
            },

            navigation: {
                nextEl: parent.querySelector('.swiper-next'),
                prevEl: parent.querySelector('.swiper-prev'),
            },

            scrollbar: {
                el: parent.querySelector('.swiper-scrollbar'),
                draggable: true,
            },
        });
    });
    // ✅ Tab switching
    const buttons = document.querySelectorAll('.tab-button');
    const contents = document.querySelectorAll('.tab-content');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;

            contents.forEach(c => c.classList.add('hidden'));
            document.getElementById(`tab-${tab}`)?.classList.remove('hidden');

            buttons.forEach(b => b.classList.remove('bg-black', 'text-white'));
            btn.classList.add('bg-black', 'text-white');
        });
    });
});