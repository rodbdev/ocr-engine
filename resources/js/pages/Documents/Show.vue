<script setup>
defineProps({
    document: Object
})
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-950 p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">
                            Resultados del Procesamiento
                        </h1>
                        <p class="text-gray-400">
                            Documento analizado por IA
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-full text-sm font-medium border border-blue-500/30">
                            JSON Extraído
                        </span>
                        <button 
                            @click="navigator.clipboard.writeText(JSON.stringify(document.extracted_json, null, 2))"
                            class="px-4 py-2 bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center space-x-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Copiar</span>
                        </button>
                    </div>
                </div>

                <!-- Document Info -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <p class="text-sm text-gray-400">Nombre del Documento</p>
                            <p class="text-lg font-medium text-white truncate">{{ document.name || 'Sin nombre' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm text-gray-400">Tamaño</p>
                            <p class="text-lg font-medium text-white">{{ document.size || 'N/A' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm text-gray-400">Fecha de Procesamiento</p>
                            <p class="text-lg font-medium text-white">{{ new Date().toLocaleDateString() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JSON Viewer -->
            <div class="bg-gray-900/80 backdrop-blur-sm rounded-xl overflow-hidden border border-gray-700/50 shadow-2xl">
                <!-- JSON Header -->
                <div class="bg-gray-800 px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <span class="text-gray-300 font-mono text-sm">extracted.json</span>
                    </div>
                    <div class="text-sm text-gray-400">
                        {{ Object.keys(document.extracted_json || {}).length }} propiedades
                    </div>
                </div>

                <!-- JSON Content -->
                <div class="relative">
                    <pre class="text-sm font-mono p-6 overflow-x-auto max-h-[70vh] json-viewer">
{{ JSON.stringify(document.extracted_json, null, 2) }}
                    </pre>
                    
                    <!-- Line Numbers -->
                    <div class="absolute left-0 top-0 bottom-0 w-16 bg-gray-900/50 border-r border-gray-700/50 py-6 text-right pr-4 text-gray-500 text-sm select-none hidden md:block">
                        <div v-for="line in (JSON.stringify(document.extracted_json, null, 2) || '').split('\n').length" 
                             :key="line" class="h-6">
                            {{ line }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-wrap gap-4 justify-end">
                <button 
                    @click="$inertia.visit('/documents/create')"
                    class="px-6 py-3 bg-gray-800 text-gray-300 rounded-xl hover:bg-gray-700 transition-all duration-200 flex items-center space-x-2 border border-gray-700"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nuevo Documento</span>
                </button>
                <button 
                    @click="window.print()"
                    class="px-6 py-3 bg-blue-600/20 text-blue-300 rounded-xl hover:bg-blue-600/30 transition-all duration-200 flex items-center space-x-2 border border-blue-500/30"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/>
                    </svg>
                    <span>Exportar</span>
                </button>
            </div>

            <!-- Footer Note -->
            <div class="mt-12 text-center text-gray-500 text-sm">
                <p>Los datos han sido procesados automáticamente por inteligencia artificial.</p>
                <p class="mt-1">Pueden requerir verificación manual para casos específicos.</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.json-viewer {
    color: #e1e4e8;
    line-height: 1.6;
}

.json-viewer :deep(.string) { color: #9ece6a; }
.json-viewer :deep(.number) { color: #ff9e64; }
.json-viewer :deep(.boolean) { color: #f7768e; }
.json-viewer :deep(.null) { color: #bb9af7; }
.json-viewer :deep(.key) { color: #7aa2f7; }

/* Scrollbar styling */
pre::-webkit-scrollbar {
    height: 10px;
    width: 10px;
}

pre::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 5px;
}

pre::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 5px;
}

pre::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>