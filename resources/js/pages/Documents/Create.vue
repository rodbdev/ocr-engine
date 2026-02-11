<script setup lang="ts">
    import { ref } from 'vue'
    import { router } from '@inertiajs/vue3'

    const file = ref<File | null>(null)
    const promptId = ref<number | null>(null)

    function submit() {
    const data = new FormData()
    data.append('file', file.value!)
    data.append('prompt_id', String(promptId.value))

    router.post('/documents', data)
    }
</script>

<template>
  <form @submit.prevent="submit" class="space-y-4">
    <input type="file" @change="e => file = e.target.files[0]" />
    <select v-model="promptId">
      <option v-for="p in $page.props.prompts" :value="p.id">
        {{ p.name }}
      </option>
    </select>
    <button class="btn-primary">Procesar</button>
  </form>
</template>
