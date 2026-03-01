# Digital Profile

Create, manage, and share your professional digital profile and projects with a scannable digital card.

## Overview

**Digital Profile** is a web application that allows users to:
- Create a personalized digital profile with contact info, bio, and project showcase.
- Instantly generate a unique QR code for their profile.
- Share their digital card so others can scan and view their details and projects—no more paper business cards!

## Features

- User registration and authentication
- Create and edit a digital profile (name, job title, bio, contact links, location, profile image, etc.)
- Add and showcase projects on your profile
- Automatic QR code generation for each profile
- Public profile sharing via QR code or direct link
- Responsive, modern UI (React + Tailwind CSS)
- Secure, privacy-focused (profiles can be public or private)

## How It Works

1. **Create Your Profile:**  
	Add your photo, details, bio, and projects. Customize your digital identity in minutes.

2. **Get Your QR Code:**  
	The system generates a unique QR code that links directly to your profile. Print it, save it, or add it to your email signature.

3. **Share & Connect:**  
	Let others scan your QR code to instantly view your profile, projects, and contact information.

## Tech Stack

- Laravel (API & backend)
- React (frontend, with Vite)
- Tailwind CSS (styling)
- Inertia.js (SPA routing)
- SimpleSoftwareIO/QrCode (QR code generation)
- MySQL (database)

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL or compatible DB

### Installation

1. Clone the repository:
	```sh
	git clone https://github.com/Shakeelnasafian/Digital-Profile.git
	cd Digital-Profile
	```

2. Install PHP dependencies:
	```sh
	composer install
	```

3. Install JS dependencies:
	```sh
	npm install
	```

4. Copy `.env.example` to `.env` and set your environment variables. If using SQLite, set `DB_DATABASE=database/database.sqlite`.

5. Generate app key:
	```sh
	php artisan key:generate
	```

6. Run migrations:
	```sh
	php artisan migrate
	```

7. Build frontend assets:
	```sh
	npm run build
	```

8. Start the development servers:
	```sh
	php artisan serve
	npm run dev
	```

### Usage

- Register or log in.
- Create your digital profile and add your projects.
- Share your QR code or profile link.

## Contributing

Pull requests are welcome! For major changes, please open an issue first to discuss what you would like to change.

## License

[MIT](LICENSE)
