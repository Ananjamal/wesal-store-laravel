export function useLinkFormatter() {
  function formatLink(url: string | undefined): string {
    if (!url) return '/'
    
    try {
      if (url.startsWith('http://') || url.startsWith('https://')) {
        const parsed = new URL(url)
        // Strip host if it matches local test domain or current host
        if (
          parsed.host.includes('wesal-store.test') || 
          parsed.host.includes('localhost') || 
          parsed.host.includes('127.0.0.1') ||
          (typeof window !== 'undefined' && parsed.host === window.location.host)
        ) {
          return (parsed.pathname + parsed.search + parsed.hash) || '/'
        }
        return url // Return full URL if it's an external link
      }
    } catch (e) {
      // URL parsing failed, fallback
    }

    if (!url.startsWith('/') && !url.startsWith('#') && !url.startsWith('mailto:') && !url.startsWith('tel:')) {
      return '/' + url
    }

    return url
  }

  function isExternal(url: string | undefined): boolean {
    if (!url) return false
    const formatted = formatLink(url)
    return (url.startsWith('http://') || url.startsWith('https://')) && !formatted.startsWith('/')
  }

  return {
    formatLink,
    isExternal
  }
}
