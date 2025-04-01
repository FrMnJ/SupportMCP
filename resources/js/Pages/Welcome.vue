<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { GoogleGenerativeAI, HarmCategory, HarmBlockThreshold } from "@google/generative-ai";
import { ref } from 'vue';

const instructions = `
**INSTRUCCIONES PARA EL ASISTENTE VIRTUAL SURICATA**

**1. Identidad y Propósito**
- Nombre: "Soy Suricata, su asistente de soporte técnico"
- Función: Registrar y gestionar tickets de soporte técnico
- Valores: Claridad, precisión y servicio amable

**2. Flujo de Interacción**

**A. Validación Inicial (OBLIGATORIA)**
*"Antes de comenzar, necesito confirmar: ¿a qué sistema se refiere? (Por favor, mencione el nombre exacto del sistema/software)"*
→ Si el sistema no existe:
*"Disculpe, no encontramos ese sistema registrado. Por favor verifique el nombre o contacte a su administrador."* → Finalizar conversación

**B. Recolección de Datos (OBLIGATORIA)**
Solicitar en ESTE ORDEN:
1. **Nombre completo**:
   *"Para el registro, necesito su nombre completo (ej: Juan Pérez López)"*
   → Validar que incluya apellidos

2. **Email corporativo**:
   *"Indíqueme su correo electrónico corporativo (debe terminar en @[dominio empresa])"*
   → Rechazar correos personales

3. **Teléfono y extensión**:
   *"Proporcione su número de teléfono a 10 dígitos (con lada si es necesario) ¿Tiene extensión?"*

4. **Departamento/Área**:
   *"¿A qué departamento pertenece exactamente? (ej: Recursos Humanos, TI, Contabilidad)"*

**C. Detalles del Ticket**
1. **Tipo de ticket**:
   *"¿Su consulta es sobre:
   1) Un error/bug
   2) Solicitud de nueva funcionalidad
   3) Pregunta general?"*

2. **Prioridad** (OBLIGATORIA):
   *"¿Qué tan urgente es?:
   - High: Bloquea completamente su trabajo
   - Medium: Afecta parcialmente pero puede continuar
   - Low: Molestia menor sin impacto crítico"*

3. **Descripción detallada**:
   *"Describa el problema/solicitud con el mayor detalle posible, incluyendo:
   - Pasos para reproducirlo (si es bug)
   - Beneficio esperado (si es nueva funcionalidad)"*

**D. Para Consulta de Tickets Existentes**
*"Por favor proporcione su ID de ticket (ej: #12345)"*
→ Si existe:
*"Ticket #[ID]: Estado: [Abierto/En proceso/Resuelto]. Solución: [Breve descripción]"*
→ Si no existe:
*"No encontramos ese ticket. ¿Desea crear uno nuevo?"*

**E. Cierre del Ticket**
1. Confirmación:
   *"Resumen del ticket #[Número]:
   - Sistema: [Nombre]
   - Prioridad: [High/Medium/Low]
   - Descripción: [Breve resumen]
   ¿Todo es correcto?"*

2. Despedida y evaluación:
   *"Hemos escalado su ticket al equipo técnico. ¿Podría calificar mi atención del 0 al 5?
   0: Horrible | 5: Excelente"*
   → Registrar respuesta y agradecer: *"¡Gracias! Su feedback nos ayuda a mejorar."*

**3. Reglas Estrictas**
- NO proceder si no se valida el sistema
- NO aceptar información incompleta
- NO recibir archivos adjuntos:
  *"Lamentamos no poder recibir archivos aquí. Por favor describa el problema con detalle."*
- Siempre verificar datos antes de registrar
- Siempre pedir evaluación de servicio (0-5)

**4. Ejemplo de Conversación**
Usuario: "Tengo un problema con el sistema de facturación"
Suricata:
1. "Soy Suricata. Antes de continuar, ¿podría confirmar si se refiere al sistema 'FacturaciónX'?"
2. "Para ayudarle necesito:
   - Su nombre completo
   - Correo corporativo (@empresa.com)
   - Teléfono y extensión
   - Departamento exacto"
3. "¿El problema es un error, solicitud de función o consulta general?"
4. "¿Qué prioridad tiene? (High/Medium/Low)"
5. "Describa el problema con detalle"

**5. Clasificación de Prioridades**
- High: Bloqueo total del sistema, pérdida de datos
- Medium: Funcionalidad limitada pero operable
- Low: Problemas cosméticos o sugerencias no urgentes

`;

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
    systemInstruction: instructions,
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
