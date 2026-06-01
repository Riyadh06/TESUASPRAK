<template>
  <component
    :is="to ? RouterLink : href ? 'a' : 'button'"
    :to="to"
    :href="href"
    :disabled="disabled || loading"
    :class="classes"
    v-bind="$attrs"
  >
    <span
      v-if="loading"
      class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"
    />
    <slot />
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { RouterLink } from 'vue-router'

const props = defineProps({
  variant: { type: String, default: 'primary' },
  // primary | secondary | outline | ghost | destructive | mono | dim
  size: { type: String, default: 'md' },
  // lg | md | sm | icon
  shape: { type: String, default: 'default' },
  // default | circle
  mode: { type: String, default: 'default' },
  // default | icon | link
  appearance: { type: String, default: 'default' },
  disabled: Boolean,
  loading: Boolean,
  to: String,
  href: String,
})

const base =
  'cursor-pointer inline-flex items-center justify-center font-medium transition-all duration-200 whitespace-nowrap disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2'

const sizeMap = {
  lg: 'h-10 px-4 text-sm gap-1.5 rounded-xl',
  md: 'h-9 px-3.5 text-sm gap-1.5 rounded-xl',
  sm: 'h-7 px-3 text-xs gap-1 rounded-lg',
  icon: 'h-9 w-9 rounded-xl',
}

const variantMap = {
  primary:
    'bg-emerald-600 hover:bg-emerald-700 text-white focus-visible:ring-emerald-500 shadow-sm',
  secondary: 'bg-gray-100 hover:bg-gray-200 text-gray-700 focus-visible:ring-gray-400 shadow-sm',
  outline:
    'border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 focus-visible:ring-gray-400 shadow-sm',
  ghost: 'bg-transparent hover:bg-gray-100 text-gray-700 focus-visible:ring-gray-400',
  destructive: 'bg-red-500 hover:bg-red-600 text-white focus-visible:ring-red-500 shadow-sm',
  mono: 'bg-gray-900 hover:bg-gray-800 text-white focus-visible:ring-gray-700 shadow-sm',
  dim: 'bg-transparent hover:text-gray-900 text-gray-500 focus-visible:ring-gray-400',
}

const modeMap = {
  link: 'h-auto p-0 bg-transparent hover:bg-transparent text-emerald-600 hover:underline underline-offset-4 rounded-none shadow-none',
  icon: 'p-0',
}

const classes = computed(() => {
  const variantClass = variantMap[props.variant] || variantMap['primary']
  const sizeClass = props.mode === 'icon' ? sizeMap['icon'] : sizeMap[props.size] || sizeMap['md']
  const modeClass = modeMap[props.mode] || ''
  const shapeClass = props.shape === 'circle' ? '!rounded-full' : ''
  return [base, variantClass, sizeClass, modeClass, shapeClass].filter(Boolean).join(' ')
})
</script>
