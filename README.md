# SkyTrack - Flight Price Alerts

SkyTrack is a modern web application designed to track flight prices and provide real-time notifications when prices drop. Built with a focus on speed, reliability, and user experience, it leverages the power of Laravel, Livewire, and Firebase to deliver a seamless Progressive Web App (PWA) experience.

## 🚀 Objective

The primary goal of SkyTrack is to empower users to find the best deals on flights without manual monitoring. Users can save their search criteria, and the system automatically tracks price changes, notifying them via Push Notifications as soon as a significant drop is detected.

## 🏗️ Architecture

The project follows a **Screaming Architecture** and **Clean Architecture** principles within the Laravel ecosystem:

-   **Backend:** Laravel 13 (PHP 8.4) provides a robust foundation.
-   **Frontend:** Livewire 4 and Flux UI for a reactive, server-side-driven interface without the complexity of a separate JS framework.
-   **Styling:** Tailwind CSS 4 for a modern and responsive design.
-   **Integration:**
    -   **SerpApi:** Used to fetch real-time flight data from Google Flights.
    -   **Firebase Cloud Messaging (FCM):** Manages the delivery of push notifications across devices.
-   **Action Pattern:** Business logic is encapsulated in dedicated Action classes (`app/Actions`) to maintain clean controllers and models.
-   **Data Flow:** Uses DTOs (`app/DTOs`) for structured data transfer between integrations and the core application.

## 📱 PWA Implementation

SkyTrack is fully configured as a Progressive Web App, allowing it to be installed on mobile and desktop devices with an "app-like" feel.

### Configuration Details:
1.  **Web Manifest:** A `manifest.json` file in the `public` directory defines the app name, icons, theme colors, and display mode (standalone).
2.  **Service Worker (`sw.js`):**
    -   **Caching Strategy:** Essential assets (`/`, `/offline.html`) are pre-cached during the installation phase to ensure offline availability.
    -   **Fetch Interceptor:** The service worker intercepts network requests, serving cached content when the user is offline.
3.  **Layout Integration:** The `resources/views/layouts/pwa.blade.php` layout includes the necessary meta tags, manifest link, and script to register the service worker and initialize theme preferences.

## 🔔 Push Notifications with Firebase

Real-time alerts are handled via Firebase Cloud Messaging (FCM).

### Detailed Setup:
1.  **Firebase Project:** A project was created in the Firebase Console, and the Web App configuration was integrated.
2.  **Client-Side Integration:**
    -   The Firebase SDK is loaded in the PWA layout.
    -   **Token Management:** Upon page load, the application requests notification permissions. If granted, it retrieves an FCM token using a **VAPID Key**.
    -   **Server Sync:** The token is sent to the `/fcm-token` endpoint via a POST request and stored in the database, linked to the user's account.
3.  **Background Handling:** The Service Worker (`sw.js`) includes an `onBackgroundMessage` handler. This allows the app to receive and display notifications even when the browser tab is closed or the app is in the background.
4.  **Server-Side Logic:** Laravel uses the `laravel-notification-channels/fcm` package to send notifications to stored tokens when price drops are detected by scheduled tasks.

## 🛠️ Getting Started

Follow these steps to set up and run the project locally:

### Prerequisites
-   PHP 8.4+
-   Composer
-   Node.js & NPM
-   Laravel Herd (recommended) or PHP Artisan

### Installation

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd flight_prices
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database Configuration:**
    Configure your database settings in `.env` and run migrations:
    ```bash
    php artisan migrate
    ```

5.  **Firebase & API Keys:**
    Add your credentials to the `.env` file:
    ```env
    SERPAPI_KEY=your_serpapi_key
    
    FIREBASE_API_KEY=your_api_key
    FIREBASE_AUTH_DOMAIN=your_project.firebaseapp.com
    FIREBASE_PROJECT_ID=your_project_id
    FIREBASE_STORAGE_BUCKET=your_project.appspot.com
    FIREBASE_MESSAGING_SENDER_ID=your_sender_id
    FIREBASE_APP_ID=your_app_id
    FIREBASE_VAPID_KEY=your_public_vapid_key
    ```

6.  **Compile Assets:**
    ```bash
    npm run dev
    # or for production
    npm run build
    ```

7.  **Run the Application:**
    If using Laravel Herd, the app will be available at `http://flight-prices.test`.
    Otherwise, run:
    ```bash
    php artisan serve
    ```

## 🧪 Testing

The project uses **Pest** for testing. To run the suite:
```bash
php artisan test
```
