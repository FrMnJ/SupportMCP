<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

const messages = ref([
    { sender: 'bot', text: '¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte?' },
]);

const currentMessage = ref('');

function sendMessage() {
    if (currentMessage.value.trim() === '') return;

    messages.value.push({ sender: 'user', text: currentMessage.value });

    setTimeout(() => {
        messages.value.push({
            sender: 'bot',
            text: 'Esto es una respuesta automática. Estoy aquí para ayudarte.',
        });
    }, 1000);

    currentMessage.value = '';
}
</script>

<template>
    <div class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gray-900 text-white py-4 px-6 shadow-md fixed top-0 w-full z-10">
            <div class="container mx-auto flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="/assets/logo.jpeg" class="w-16 h-16 rounded-full object-cover" alt="Logo" onerror="this.onerror=null; this.src='/fallback-logo.png';">
                    <span class="text-lg font-semibold">Suri Support</span>
                </div>

                <!-- Navigation -->
                <div class="flex space-x-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-white hover:text-gray-300 transition"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 transition text-white"
                        >
                            Iniciar sesión
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-800 transition text-white"
                        >
                            Registrarse
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Chat -->
        <main class="flex-grow flex items-center justify-center mt-20">
            <div class="chat-container bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-5xl h-[550px] flex flex-col border border-gray-300 dark:border-gray-700">
                <!-- Chat Messages -->
                <div class="chat-messages flex-grow overflow-y-auto space-y-4 p-4">
                    <div
                        v-for="(message, index) in messages"
                        :key="index"
                        :class="{
                            'text-left': message.sender === 'bot',
                            'text-right': message.sender === 'user',
                        }"
                        class="mb-2"
                    >
                        <span
                            :class="{
                                'bg-gray-300 text-black rounded-lg px-4 py-2 inline-block': message.sender === 'bot',
                                'bg-blue-500 text-white rounded-lg px-4 py-2 inline-block': message.sender === 'user',
                            }"
                        >
                            {{ message.text }}
                        </span>
                    </div>
                </div>

                <!-- Chat Input -->
                <div class="chat-input flex items-center p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <input
                        v-model="currentMessage"
                        @keyup.enter="sendMessage"
                        type="text"
                        placeholder="Escribe tu mensaje..."
                        class="flex-1 p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                    />
                    <button
                        @click="sendMessage"
                        class="ml-3 px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition flex items-center"
                    >
                        <span class="mr-2">Enviar</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-6 text-center text-sm text-gray-600 dark:text-gray-400">
            Suri Support - Todos los derechos reservados
        </footer>
    </div>
</template>