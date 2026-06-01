<template>
  <div class="relative w-full">
    <!-- Label -->
    <label v-if="label" :for="id" class="block text-xs font-medium text-gray-600 mb-1.5">
      {{ label }}
      <span v-if="required" class="text-red-500 ml-0.5">*</span>
    </label>

    <div class="relative flex items-center">
      <!-- Icon kiri -->
      <div
        v-if="$slots.prefix || prefixIcon"
        class="absolute left-3 text-gray-400 flex items-center pointer-events-none z-10"
      >
        <slot name="prefix">
          <component :is="prefixIcon" class="w-4 h-4" v-if="prefixIcon" />
        </slot>
      </div>

      <!-- Input -->
      <input
        :id="id"
        :type="inputType"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        :autocomplete="autocomplete"
        @input="$emit('update:modelValue', $event.target.value)"
        @focus="
          focused = true
          $emit('focus', $event)
        "
        @blur="
          focused = false
          $emit('blur', $event)
        "
        :class="inputClasses"
        v-bind="$attrs"
      />

      <!-- Toggle password -->
      <button
        v-if="type === 'password'"
        type="button"
        @click="showPassword = !showPassword"
        class="absolute right-3 text-gray-400 hover:text-gray-600 transition-colors z-10"
      >
        <svg
          v-if="showPassword"
          class="w-4 h-4"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.5"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
          />
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.5"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
          />
        </svg>
        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.5"
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"
          />
        </svg>
      </button>

      <!-- Icon kanan (suffix) -->
      <div
        v-else-if="$slots.suffix"
        class="absolute right-3 text-gray-400 flex items-center pointer-events-none"
      >
        <slot name="suffix" />
      </div>

      <!-- Animated search/send icon -->
      <div
        v-else-if="animated"
        class="absolute right-3 text-gray-400 pointer-events-none transition-all duration-200"
      >
        <svg
          v-if="modelValue && modelValue.length > 0"
          class="w-4 h-4 text-emerald-500 transition-all"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.5"
            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
          />
        </svg>
        <svg
          v-else
          class="w-4 h-4 transition-all"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.5"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          />
        </svg>
      </div>
    </div>

    <!-- Dropdown results (untuk search bar) -->
    <transition name="dropdown">
      <div
        v-if="animated && focused && results && results.length > 0"
        class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden"
      >
        <ul>
          <li
            v-for="result in results"
            :key="result.id"
            @mousedown.prevent="$emit('select', result)"
            class="flex items-center justify-between px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition-colors"
          >
            <div class="flex items-center gap-3">
              <span class="text-lg">{{ result.icon }}</span>
              <div>
                <p class="text-sm font-medium text-gray-800">{{ result.label }}</p>
                <p v-if="result.description" class="text-xs text-gray-400">
                  {{ result.description }}
                </p>
              </div>
            </div>
            <span
              v-if="result.end"
              class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-md"
              >{{ result.end }}</span
            >
          </li>
        </ul>
        <div class="px-4 py-2 border-t border-gray-100 flex items-center justify-between">
          <span class="text-xs text-gray-400">{{ results.length }} hasil ditemukan</span>
          <span class="text-xs text-gray-400">ESC untuk tutup</span>
        </div>
      </div>
    </transition>

    <!-- Error message -->
    <p v-if="error" class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
      <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
        <path
          fill-rule="evenodd"
          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
          clip-rule="evenodd"
        />
      </svg>
      {{ error }}
    </p>

    <!-- Helper text -->
    <p v-else-if="helper" class="text-xs text-gray-400 mt-1.5">{{ helper }}</p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  modelValue: { type: String, default: '' },
  type: { type: String, default: 'text' },
  label: String,
  placeholder: String,
  id: String,
  disabled: Boolean,
  required: Boolean,
  error: String,
  helper: String,
  prefixIcon: Object,
  autocomplete: String,
  animated: Boolean,
  results: Array,
  size: { type: String, default: 'md' }, // sm | md | lg
})

defineEmits(['update:modelValue', 'focus', 'blur', 'select'])

const focused = ref(false)
const showPassword = ref(false)

const inputType = computed(() => {
  if (props.type === 'password') return showPassword.value ? 'text' : 'password'
  return props.type
})

const sizeMap = {
  sm: 'h-8 text-xs px-3 rounded-lg',
  md: 'h-10 text-sm px-4 rounded-xl',
  lg: 'h-12 text-sm px-4 rounded-xl',
}

const inputClasses = computed(() => {
  const hasPrefixSlot = !!props.prefixIcon
  const hasRightSlot = props.type === 'password' || props.animated

  return [
    'w-full border bg-white text-gray-800 placeholder:text-gray-400 outline-none transition-all duration-200',
    sizeMap[props.size] || sizeMap['md'],
    hasPrefixSlot ? 'pl-10' : '',
    hasRightSlot ? 'pr-10' : '',
    props.disabled ? 'bg-gray-50 text-gray-400 cursor-not-allowed' : '',
    props.error
      ? 'border-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100'
      : focused.value
        ? 'border-emerald-400 ring-2 ring-emerald-100'
        : 'border-gray-200 hover:border-gray-300',
  ]
    .filter(Boolean)
    .join(' ')
})
</script>

<style scoped>
.dropdown-enter-active {
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.dropdown-leave-active {
  transition: all 0.15s ease;
}
.dropdown-enter-from {
  opacity: 0;
  transform: translateY(-8px);
}
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>
