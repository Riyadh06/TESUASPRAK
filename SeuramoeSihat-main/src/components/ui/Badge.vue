<template>
  <span :class="classes" v-bind="$attrs">
    <span v-if="dot" class="w-1.5 h-1.5 rounded-full bg-current opacity-75" />
    <slot />
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    // primary | secondary | success | warning | info | destructive | outline
  },
  appearance: {
    type: String,
    default: 'default',
    // default | light | outline | ghost
  },
  size: {
    type: String,
    default: 'md',
    // lg | md | sm | xs
  },
  shape: {
    type: String,
    default: 'default',
    // default | circle
  },
  dot: Boolean,
  disabled: Boolean,
})

const base = 'inline-flex items-center justify-center border border-transparent font-medium'

const sizeMap = {
  lg: 'rounded-md px-2 h-7 min-w-7 gap-1.5 text-xs',
  md: 'rounded-md px-1.5 h-6 min-w-6 gap-1.5 text-xs',
  sm: 'rounded px-1 h-5 min-w-5 gap-1 text-[11px]',
  xs: 'rounded px-1 h-4 min-w-4 gap-1 text-[10px]',
}

const variantAppearanceMap = {
  'primary-default': 'bg-emerald-600 text-white border-transparent',
  'primary-light': 'bg-emerald-50 text-emerald-700 border-transparent',
  'primary-outline': 'bg-emerald-50 text-emerald-700 border-emerald-200',
  'primary-ghost': 'bg-transparent text-emerald-600 border-transparent px-0',

  'secondary-default': 'bg-gray-100 text-gray-700 border-transparent',
  'secondary-light': 'bg-gray-100 text-gray-600 border-transparent',
  'secondary-outline': 'bg-gray-50 text-gray-600 border-gray-200',
  'secondary-ghost': 'bg-transparent text-gray-600 border-transparent px-0',

  'success-default': 'bg-green-500 text-white border-transparent',
  'success-light': 'bg-green-100 text-green-800 border-transparent',
  'success-outline': 'bg-green-50 text-green-700 border-green-200',
  'success-ghost': 'bg-transparent text-green-500 border-transparent px-0',

  'warning-default': 'bg-yellow-500 text-white border-transparent',
  'warning-light': 'bg-yellow-100 text-yellow-700 border-transparent',
  'warning-outline': 'bg-yellow-50 text-yellow-700 border-yellow-200',
  'warning-ghost': 'bg-transparent text-yellow-500 border-transparent px-0',

  'info-default': 'bg-violet-500 text-white border-transparent',
  'info-light': 'bg-violet-100 text-violet-700 border-transparent',
  'info-outline': 'bg-violet-50 text-violet-700 border-violet-200',
  'info-ghost': 'bg-transparent text-violet-500 border-transparent px-0',

  'destructive-default': 'bg-red-500 text-white border-transparent',
  'destructive-light': 'bg-red-50 text-red-700 border-transparent',
  'destructive-outline': 'bg-red-50 text-red-700 border-red-200',
  'destructive-ghost': 'bg-transparent text-red-500 border-transparent px-0',

  'outline-default': 'bg-transparent text-gray-700 border-gray-200',
  'outline-light': 'bg-gray-50 text-gray-600 border-gray-200',
  'outline-outline': 'bg-transparent text-gray-600 border-gray-300',
  'outline-ghost': 'bg-transparent text-gray-500 border-transparent px-0',
}

const classes = computed(() => {
  const key = `${props.variant}-${props.appearance}`
  const variantClass = variantAppearanceMap[key] || variantAppearanceMap['primary-default']
  const sizeClass = sizeMap[props.size] || sizeMap['md']
  const shapeClass = props.shape === 'circle' ? 'rounded-full' : ''
  const disabledClass = props.disabled ? 'opacity-50 pointer-events-none' : ''
  return [base, variantClass, sizeClass, shapeClass, disabledClass].filter(Boolean).join(' ')
})
</script>
