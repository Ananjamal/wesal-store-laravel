import { ref } from 'vue';
import { defineStore } from 'pinia';

export const usePreferenceStore = defineStore('preference', () => {
  const darkMode = ref(localStorage.getItem('darkMode') === 'true');

  function toggleDarkMode() {
    darkMode.value = !darkMode.value;
    localStorage.setItem('darkMode', String(darkMode.value));
    updateTheme();
  }

  function updateTheme() {
    if (darkMode.value) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  }

  return { darkMode, toggleDarkMode, updateTheme };
});
