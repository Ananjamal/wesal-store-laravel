import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useToastStore } from './toast'

export const useWishlistStore = defineStore('wishlist', () => {
  const toastStore = useToastStore()
  
  // Load initial favorite items from localStorage
  const items = ref<any[]>(
    JSON.parse(localStorage.getItem('wishlist_items') || '[]')
  )

  const totalItems = computed(() => items.value.length)

  function saveWishlist() {
    localStorage.setItem('wishlist_items', JSON.stringify(items.value))
  }

  function hasItem(productId: number): boolean {
    return items.value.some(item => item.id === productId)
  }

  function toggleItem(product: any) {
    const exists = hasItem(product.id)

    if (exists) {
      items.value = items.value.filter(item => item.id !== product.id)
      toastStore.addToast(
        localStorage.getItem('locale') === 'en'
          ? `Removed ${product.name} from wishlist.`
          : `تم إزالة ${product.name} من المفضلة.`,
        'info'
      )
    } else {
      // Prevent duplicates
      if (!items.value.some(item => item.id === product.id)) {
        items.value.push(product)
        toastStore.addToast(
          localStorage.getItem('locale') === 'en'
            ? `Added ${product.name} to wishlist!`
            : `تم إضافة ${product.name} للمفضلة!`
        )
      }
    }

    saveWishlist()
  }

  function removeItem(productId: number) {
    items.value = items.value.filter(item => item.id !== productId)
    saveWishlist()
    toastStore.addToast(
      localStorage.getItem('locale') === 'en'
        ? `Removed from wishlist.`
        : `تم إزالة المنتج من المفضلة.`,
      'info'
    )
  }

  return {
    items,
    totalItems,
    hasItem,
    toggleItem,
    removeItem
  }
})
