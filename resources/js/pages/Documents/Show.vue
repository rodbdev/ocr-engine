<script setup>
defineProps({
    document: Object
})
</script>

<template>
    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">
            Documento procesado
        </h1>

        <pre class="bg-gray-900 text-green-400 p-4 rounded overflow-auto">
{{ document.extracted_json }}
        </pre>
    </div>
</template>
