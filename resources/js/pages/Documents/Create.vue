<script setup lang="ts">
    import { ref } from 'vue'
    import { router } from '@inertiajs/vue3'

    const file = ref<File | null>(null)
    const promptId = ref<number | null>(null)

    function submit() {
        if (!file.value || !promptId.value) {
            alert('Por favor, selecciona un archivo y un prompt')
            return
        }
        
        const data = new FormData()
        data.append('file', file.value!)
        data.append('prompt_id', String(promptId.value))

        router.post('/documents', data)
    }
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Subir Documento
                    </h1>
                    <p class="text-gray-600">
                        Sube un documento para procesarlo con IA
                    </p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- File Upload -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">
                            Archivo a procesar
                        </label>
                        <div 
                            @click="$refs.fileInput.click()"
                            class="border-3 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-blue-500 transition-colors duration-200 bg-gray-50 hover:bg-blue-50"
                        >
                            <div class="mx-auto w-12 h-12 mb-4 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-gray-600 mb-2" v-if="!file">
                                Arrastra o haz clic para subir
                            </p>
                            <p class="text-sm text-gray-500" v-if="!file">
                                Formatos soportados: PDF, DOC, TXT
                            </p>
                            <p class="text-blue-600 font-medium truncate max-w-xs mx-auto" v-if="file">
                                📄 {{ file.name }}
                            </p>
                            <input 
                                ref="fileInput"
                                type="file" 
                                @change="e => file = e.target.files?.[0] || null"
                                class="hidden"
                                accept=".pdf,.doc,.docx,.txt"
                            />
                        </div>
                    </div>

                    <!-- Prompt Selector -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">
                            Seleccionar Prompt
                        </label>
                        <div class="relative">
                            <select 
                                v-model="promptId"
                                class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl appearance-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all duration-200"
                            >
                                <option :value="null" disabled selected>
                                    Selecciona un prompt...
                                </option>
                                <option 
                                    v-for="p in $page.props.prompts" 
                                    :value="p.id"
                                    class="py-2"
                                >
                                    {{ p.name }}
                                </option>
                            </select>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        :disabled="!file || !promptId"
                        class="w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-200 focus:outline-none transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                    >
                        <div class="flex items-center justify-center space-x-2">
                            <svg v-if="!file || !promptId" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>
                                {{ file && promptId ? 'Procesar Documento' : 'Selecciona archivo y prompt' }}
                            </span>
                        </div>
                    </button>
                </form>

                <!-- Info -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="flex items-start space-x-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>El documento será procesado por IA y los resultados estarán disponibles inmediatamente.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>