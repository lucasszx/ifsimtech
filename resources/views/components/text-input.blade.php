@props(['disabled' => false])

<input @disabled($disabled)
  {{ $attributes->merge([
    'class' =>
      'border-gray-300 focus:border-[#a7f3d0] focus:ring-[#a7f3d0] rounded-md shadow-sm'
  ]) }}>
