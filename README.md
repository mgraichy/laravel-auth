# Laravel Video Auth (VueTube)

VueTube is a YouTube-inspired application built with a Vue 3 frontend and a Laravel 11 backend, designed to operate with completely separate frontend and backend domains. It features full OAuth2.0 authentication and serves videos from a private server-side directory for enhanced security.

This is the backend. [The frontend is here](https://github.com/mgraichy/vuetube).

---

## Features

- **Laravel Backend**: A robust and scalable backend to handle API requests and serve video content.
- **OAuth2.0 Authentication**: Secure authentication from any domain whatever, ensuring flexibility and security.
- **Private Video Storage**: Videos are served from a private directory instead of the public folder. This makes it much more difficult to acquire unauthorized access (for example, a paywall would receive additional benefits from this).


### Prerequisites

- Node.js (for Vue 3 frontend).
- PHP 8.2+ and Composer (for Laravel backend).
- An SQL database (MySQL, PostgreSQL, etc.).

## License

This project is licensed under [MIT](https://opensource.org/license/mit). See the LICENSE file for details.


