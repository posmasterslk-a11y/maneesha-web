export default defineNuxtPlugin((nuxtApp) => {
  if (typeof window !== 'undefined') {
    const originalFetch = window.fetch;

    window.fetch = async function (...args) {
      try {
        const response = await originalFetch.apply(this, args);

        if (response.status === 401) {

          
          // Clear tokens and auth state
          localStorage.removeItem('maneesha-admin-token');
          localStorage.removeItem('maneesha-admin-auth');
          localStorage.removeItem('maneesha-admin-role');
          localStorage.removeItem('maneesha-admin-name');

          // If not already on login page, redirect
          if (window.location.pathname !== '/login') {
            window.location.href = '/login';
          }
        }

        return response;
      } catch (error) {
        throw error;
      }
    };
  }
});
