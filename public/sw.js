// اسم کش
const CACHE_NAME = 'my-site-cache-v1';

// فایل‌هایی که می‌خوای آفلاین بشه
const urlsToCache = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/offline.html'  // صفحه آفلاین (اختیاری)
];

// نصب سرویس ورکر
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

// گرفتن درخواست‌ها
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // اگه تو کش بود، برگردون
        if (response) {
          return response;
        }
        // وگرنه برو اینترنت
        return fetch(event.request);
      })
  );
});

// آپدیت سرویس ورکر
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
});