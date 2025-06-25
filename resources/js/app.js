
// import './bootstrap';

import './clock';
import './description-toggle';
import './collection-swiper';
import './review-swiper';
import './quantity';
import { createApp } from 'vue';
import ChatWidget from './components/ChatWidget.vue';

const app = createApp({});

app.component('chat-widget', ChatWidget);

app.mount('#app');
