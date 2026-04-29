# Project Gemini: Social Media Platform

This project is a Laravel-based social media application featuring content sharing, messaging, following systems, and user interactions.

## Technical Stack
- **Backend:** Laravel (PHP)
- **Frontend:** Blade Templates, JavaScript, CSS (Vite)
- **Database:** MySQL/MariaDB (implied by migrations)
- **Real-time:** Laravel Reverb (configured in `config/reverb.php`)

## Core Features
- **Authentication:** Custom auth logic (AuthController).
- **Content Management:** Content items (posts/reels), comments, likes, and interests.
- **Social Interaction:** Following system, real-time messaging, and notifications.
- **User Settings:** Profile management and settings.

## Development Standards & Style
- **No Compromise on Style:** Always adhere to high-quality, idiomatic PHP/Laravel and JavaScript standards.
- **Consistency:** Follow existing naming conventions and architectural patterns found in the codebase.
- **Surgical Updates:** When modifying code, ensure changes are precise and don't introduce side effects.
- **Validation:** Always verify changes through testing or manual validation where applicable.

## Project Structure Notes
- `app/Models`: Contains the core data structures (User, ContentItem, Message, etc.).
- `app/Http/Controllers`: Handles the business logic for various modules.
- `resources/views`: Contains the UI components and pages using Blade.
- `routes/web.php`: Defines the application's routing.
