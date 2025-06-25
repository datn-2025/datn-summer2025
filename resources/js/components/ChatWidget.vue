<template>
  <div>
    <!-- Nút mở chat -->
    <button 
      @click="toggleChat"
      class="fixed bottom-5 right-5 z-50 transition-transform hover:scale-110 focus:outline-none"
    >
      <div class="relative">
        <img 
          :src="chatBeeImage" 
          alt="Chat Bee"
          class="w-16 h-16 rounded-full object-cover shadow-lg border-2 border-black"
        >
        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
      </div>
    </button>

    <!-- Hộp chat -->
    <div 
      v-show="isChatOpen"
      class="fixed bottom-24 right-5 w-[380px] z-50"
    >
      <div class="bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-black text-white px-4 py-3 flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="relative">
              <img 
                :src="chatBeeImage" 
                alt="Chat Bee"
                class="w-10 h-10 rounded-full object-cover shadow-lg border-2 border-white"
              >
              <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
            </div>
            <h3 class="font-semibold">Hỗ trợ khách hàng</h3>
          </div>
          <button 
            @click="closeChat"
            class="text-white hover:text-gray-300 focus:outline-none"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Chat content -->
        <div 
          ref="chatContent"
          class="p-4 h-[350px] overflow-y-auto bg-gray-50"
        >
          <div 
            v-for="(message, index) in messages" 
            :key="index"
            class="flex items-end mb-3"
            :class="{
              'justify-end': isMe(message),
              'justify-start': !isMe(message)
            }"
          >
            <!-- Avatar người gửi (nếu không phải mình) -->
            <img 
              v-if="!isMe(message)"
              :src="getAvatar(message.sender)" 
              :alt="message.sender.name"
              class="w-10 h-10 rounded-full object-cover shadow-lg mr-2"
            >
            
            <!-- Nội dung tin nhắn -->
            <div 
              class="bg-white rounded-lg shadow-sm px-4 py-2 max-w-[70%]"
              :class="{'ml-2': isMe(message)}"
            >
              <!-- Tin nhắn hình ảnh -->
              <img 
                v-if="message.type === 'image' && message.file_path" 
                :src="getImageUrl(message.file_path)" 
                class="rounded mb-1" 
                style="max-width: 200px;"
              >
              
              <!-- Tin nhắn văn bản -->
              <p 
                v-else-if="message.content" 
                class="text-sm text-gray-600"
              >
                {{ message.content }}
              </p>
              
              <p v-else class="text-sm text-gray-400 italic">[Không có nội dung]</p>
              
              <small class="text-xs text-gray-400 block text-end">
                {{ formatTime(message.created_at) }}
              </small>
            </div>
            
            <!-- Avatar của mình (nếu là tin nhắn mình gửi) -->
            <img 
              v-if="isMe(message)"
              :src="currentUserAvatar" 
              alt="Bạn"
              class="w-10 h-10 rounded-full object-cover shadow-lg ml-2"
            >
          </div>
        </div>

        <!-- Input area -->
        <div class="p-4 border-t border-gray-200 bg-white">
          <form 
            @submit.prevent="sendMessage"
            class="flex items-center space-x-2"
          >
            <input 
              type="text" 
              v-model="newMessage"
              class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:border-black"
              placeholder="Nhập tin nhắn..." 
              autocomplete="off"
            >
            <button 
              type="submit"
              class="bg-black text-white rounded-full p-2 hover:bg-gray-800 transition-colors focus:outline-none"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
              </svg>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    initialMessages: {
      type: Array,
      default: () => []
    },
    currentUser: {
      type: Object,
      required: true
    },
    conversationId: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      isChatOpen: false,
      messages: [...this.initialMessages],
      newMessage: '',
      chatBeeImage: '/images/bookbeee.jpg'
    };
  },

  computed: {
    currentUserAvatar() {
      return this.currentUser.avatar 
        ? `/storage/avatars/${this.currentUser.avatar}` 
        : '/storage/avatars/default.png';
    }
  },

  methods: {
    toggleChat() {
      this.isChatOpen = !this.isChatOpen;
      if (this.isChatOpen) {
        this.$nextTick(this.scrollToBottom);
      }
    },
    closeChat() {
      this.isChatOpen = false;
    },
    isMe(message) {
      // So sánh kiểu dữ liệu để tránh lỗi hiển thị
      if (!message || !message.sender_id || !this.currentUser || !this.currentUser.id) return false;
      return String(message.sender_id) === String(this.currentUser.id);
    },
    getAvatar(sender) {
      if (!sender) return '/storage/avatars/default.png';
      return sender.avatar 
        ? `/storage/avatars/${sender.avatar}` 
        : '/storage/avatars/default.png';
    },
    getImageUrl(filePath) {
      return `/storage/${filePath}`;
    },
    formatTime(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    },
    scrollToBottom() {
      this.$nextTick(() => {
        const container = this.$refs.chatContent;
        if (container) container.scrollTop = container.scrollHeight;
      });
    },
    async sendMessage() {
      if (!this.newMessage.trim()) return;
      try {
        const response = await axios.post('/api/messages', {
          conversation_id: this.conversationId,
          // conversation_id: '013bf190-2615-41df-a6b6-b9a9a262aba4',
          content: this.newMessage
        });
        this.messages.push(response.data);
        this.newMessage = '';
        this.scrollToBottom();
      } catch (error) {
        console.error('Lỗi khi gửi tin nhắn:', error);
      }
    },
    async fetchMessages() {
      // Đã truyền cứng conversation_id nên không cần kiểm tra nữa
      try {
        const response = await axios.get('/api/messages', {
          params: {
            conversation_id: this.conversationId,
            // conversation_id: '013bf190-2615-41df-a6b6-b9a9a262aba4'
          }
        });
        this.messages = response.data;
        this.scrollToBottom();
      } catch (error) {
        console.error('Lỗi khi lấy tin nhắn:', error);
      }
    },
    setupRealtime() {
      // Để trống hoặc thêm logic realtime nếu có
    }
  },

  watch: {
    messages: {
      handler() {
        this.$nextTick(this.scrollToBottom);
      },
      deep: true
    }
  },

  mounted() {
    // Tự động scroll xuống khi component được tạo
    this.scrollToBottom();
    
    // Khởi tạo real-time (nếu cần)
    this.setupRealtime();
    
    // Gọi fetchMessages để hiển thị tin nhắn cũ
    this.fetchMessages();
  }
};
</script>

<style scoped>
/* Thêm các kiểu tùy chỉnh ở đây nếu cần */
</style>
