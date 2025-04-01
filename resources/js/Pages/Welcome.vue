<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { GoogleGenerativeAI, HarmCategory, HarmBlockThreshold } from "@google/generative-ai";
import { ref } from 'vue';

const history = ref([]);

const ai = new GoogleGenerativeAI(import.meta.env.VITE_GEMINI_API_KEY);
const safetySettings = [
    {
        category: HarmCategory.HARM_CATEGORY_HARASSMENT,
        threshold: HarmBlockThreshold.BLOCK_ONLY_HIGH,
    },
];
const generationConfig = { // Ajusta el estilo de respuesta
    stopSequences: ["red"],
    maxOutputTokens: 100,
    temperature: 0.5,
    topP: 0.1,
    topK: 16,
};
const model = ai.getGenerativeModel({
    model: "gemini-1.5-flash",
    generationConfig,
    safetySettings,
});

const chat = model.startChat({
    history: [],
});

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


const currentMessage = ref('');
const loading = ref(false);

function addMessageToHistory(sender, text) {
    history.value.push({ role: sender, parts: [{ text: text }] });
}

const fetchData = async () => { //LLamar a la api
    loading.value = true;
    addMessageToHistory("user", currentMessage.value);
    const result = await chat.sendMessage(currentMessage.value);
    const response = await result.response;

    const text = response.text();
    addMessageToHistory(
        "model",
        text
            .replace(/\n/g, "<br />")
            .replace(/"/g, "")
            .replace(/\*([^*]+)\*/g, "<h3>$1</h3>")
    );
    currentMessage.value = '';
    loading.value = false;
};



async function sendMessage() {
    if (currentMessage.value.trim() === '') return;

    console.log("sending message...");
    console.log("message: ", currentMessage.value);

    fetchData();

    currentMessage.value = '';
}
</script>

<template>
    <div class="bg-gray-50 dark:bg-white text-gray-800 dark:text-gray-200 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white text-white px-6 shadow-md fixed top-0 w-full z-10">
            <div class="container mx-auto flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="/assets/logo-wbg.png" class="w-24 h-24 object-cover" alt="Logo"
                        onerror="this.onerror=null; this.src='/fallback-logo.png';">
                    <span class="text-lg font-semibold text-black">Suri Support</span>
                </div>

                <!-- Navigation -->
                <div class="flex space-x-4">
                    <Link v-if="$page.props.auth.user" :href="route('tickets.index')"
                        class="text-white hover:text-gray-300 transition">
                    Tickets
                    </Link>
                    <template v-else>
                        <Link :href="route('login')"
                            class="px-4 py-2 rounded-md hover:bg-gray-200 transition text-black">
                        Iniciar sesión
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Chat -->
        <main class="flex-grow flex items-center justify-center mt-28">
            <div
                class="chat-container bg-white dark:bg-gray-100 p-6 rounded-lg shadow-lg w-full max-w-5xl h-[450px] flex flex-col border border-gray-300 dark:border-gray-700">
                <!-- Chat Messages -->
                <div class="chat-messages flex-grow overflow-y-auto space-y-4 p-4">
                    <div v-for="(message, index) in history" :key="index" :class="{
                        'text-left': message.role == 'model',
                        'text-right': message.role == 'user',
                    }" class="mb-2">
                        <span :class="{
                            'bg-gray-300 text-black rounded-lg px-4 py-2 inline-block': message.role === 'model',
                            'bg-gray-700 text-white rounded-lg px-4 py-2 inline-block': message.role === 'user',
                        }" v-html="message.parts[0].text">
                        </span>
                    </div>
                </div>

                <!-- Chat Input -->
                <div class="chat-input flex items-center p-3 bg-gray-100 rounded-lg">
                    <input v-model="currentMessage" @keyup.enter="sendMessage" type="text"
                        placeholder="Escribe tu mensaje..."
                        class="flex-1 p-3 border border-gray-300 dark:border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-200 dark:text-black" />
                    <button @click="sendMessage"
                        class="ml-3 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                        <span class="mr-2">Enviar</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-6 text-center text-sm text-gray-900 dark:text-gray-600">
            Suri Support 2025 - Todos los derechos reservados
        </footer>
    </div>
</template>
