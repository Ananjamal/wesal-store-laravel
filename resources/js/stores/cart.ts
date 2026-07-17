import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useToastStore } from './toast'

export interface CartItem {
  key: string // unique identifier: id-colorId-sizeId
  id: number
  name: string
  slug: string
  price: number
  quantity: number
  colorId?: number | null
  colorName?: string | null
  sizeId?: number | null
  sizeLabel?: string | null
  image: string
  stock_quantity: number
}

export const useCartStore = defineStore('cart', () => {
  const toastStore = useToastStore()
  
  // Load initial cart items from localStorage
  const items = ref<CartItem[]>(
    JSON.parse(localStorage.getItem('cart_items') || '[]')
  )

  const totalItems = computed(() => {
    return items.value.reduce((sum, item) => sum + item.quantity, 0)
  })

  const subtotal = computed(() => {
    return items.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
  })

  function saveCart() {
    localStorage.setItem('cart_items', JSON.stringify(items.value))
  }

  function addItem(product: any, quantity: number = 1, colorId: number | null = null, sizeId: number | null = null) {
    // Generate unique key for product variants combination
    const key = `${product.id}-${colorId || ''}-${sizeId || ''}`
    
    // Check if item already exists
    const existingItem = items.value.find(item => item.key === key)
    
    // Resolve variant details
    const colorObj = product.colors?.find((c: any) => c.id === colorId)
    const sizeObj = product.sizes?.find((s: any) => s.id === sizeId)
    
    const image = product.images && product.images.length > 0 
      ? product.images[0].thumb 
      : 'https://picsum.photos/seed/product-' + product.id + '/100/100'

    if (existingItem) {
      if (existingItem.quantity + quantity > product.stock_quantity) {
        toastStore.addToast(
          localStorage.getItem('locale') === 'en' 
            ? `Cannot add more. Only ${product.stock_quantity} left in stock.` 
            : `عذراً، لا يمكن إضافة المزيد. المتبقي في المخزن ${product.stock_quantity} فقط.`,
          'warning'
        )
        return
      }
      existingItem.quantity += quantity
    } else {
      items.value.push({
        key,
        id: product.id,
        name: product.name,
        slug: product.slug,
        price: product.price,
        quantity,
        colorId,
        colorName: colorObj ? colorObj.name : null,
        sizeId,
        sizeLabel: sizeObj ? (sizeObj.label || sizeObj.name) : null,
        image,
        stock_quantity: product.stock_quantity
      })
    }

    saveCart()
    toastStore.addToast(
      localStorage.getItem('locale') === 'en'
        ? `Added ${product.name} to your cart!`
        : `تم إضافة ${product.name} إلى السلة بنجاح!`
    )
  }

  function removeItem(key: string) {
    const item = items.value.find(i => i.key === key)
    items.value = items.value.filter(i => i.key !== key)
    saveCart()
    
    if (item) {
      toastStore.addToast(
        localStorage.getItem('locale') === 'en'
          ? `Removed ${item.name} from cart.`
          : `تم إزالة ${item.name} من السلة.`,
        'info'
      )
    }
  }

  function updateQuantity(key: string, quantity: number) {
    const item = items.value.find(i => i.key === key)
    if (!item) return

    if (quantity > item.stock_quantity) {
      toastStore.addToast(
        localStorage.getItem('locale') === 'en' 
          ? `Cannot increase. Only ${item.stock_quantity} left in stock.` 
          : `لا يمكن الزيادة. المتبقي في المخزن ${item.stock_quantity} فقط.`,
        'warning'
      )
      return
    }

    if (quantity < 1) {
      removeItem(key)
      return
    }

    item.quantity = quantity
    saveCart()
  }

  function clearCart() {
    items.value = []
    saveCart()
  }

  return {
    items,
    totalItems,
    subtotal,
    addItem,
    removeItem,
    updateQuantity,
    clearCart
  }
})
