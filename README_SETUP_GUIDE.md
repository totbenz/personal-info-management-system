# Personal Info Management System - Setup Guide

This repository contains a comprehensive setup guide for the Personal Info Management System, a Laravel Livewire application with Tailwind CSS.

## 📋 About This Guide

This interactive HTML guide provides step-by-step instructions for setting up the Personal Info Management System from scratch on a new Windows laptop/computer. The guide is designed to be user-friendly with features like:

- 🎯 **Interactive sidebar navigation** for easy section jumping
- 📋 **One-click copy-to-clipboard** for all commands and configurations
- 🎨 **Professional styling** with responsive design
- 💡 **Helpful tips and warnings** throughout the setup process
- 📱 **Mobile-friendly** layout

## 🚀 Quick Start

### Prerequisites Overview
The guide covers installation of:
- **Laragon** - All-in-one development environment (PHP, MySQL, Apache, Composer)
- **Git** - Version control
- **GitHub Desktop** - User-friendly Git interface (optional)
- **Node.js & NPM** - Frontend asset compilation
- **Laravel** - PHP framework

### Setup Steps
1. Install Laragon development environment
2. Install Git and GitHub Desktop
3. Install Node.js
4. Clone the project repository
5. Install PHP and Node.js dependencies
6. Configure environment variables
7. Create and migrate database
8. Build frontend assets
9. Access the application

## 📁 Files in This Repository

- `SYSTEM_SETUP_GUIDE.html` - Interactive HTML setup guide
- `SYSTEM_SETUP_GUIDE.md` - Markdown version of the guide
- `SYSTEM_SETUP_GUIDE.pdf` - PDF version for offline viewing
- `README_SETUP_GUIDE.md` - This file

## 🖥️ How to Use the Guide

### HTML Version (Recommended)
1. Download `SYSTEM_SETUP_GUIDE.html`
2. Open in any modern web browser (Chrome, Firefox, Edge, Safari)
3. Use the sidebar to navigate between sections
4. Click "Copy" buttons to copy commands instantly
5. Follow the step-by-step instructions

### PDF Version
1. Download `SYSTEM_SETUP_GUIDE.pdf`
2. Open with any PDF reader
3. Suitable for printing or offline viewing

### Markdown Version
1. View `SYSTEM_SETUP_GUIDE.md` on GitHub
2. Copy to your notes or documentation system
3. Convert to other formats as needed

## 🛠️ Technology Stack

The Personal Info Management System is built with:

- **Backend:**
  - Laravel 10.x
  - PHP 8.1+
  - Livewire 3.x
  - MySQL/MariaDB

- **Frontend:**
  - Blade Templates
  - Tailwind CSS 3.x
  - Alpine.js
  - Vite

- **Additional Features:**
  - PDF Generation (DomPDF, MPDF)
  - Excel Import/Export (Maatwebsite Excel)
  - File Uploads
  - Authentication System

## 📝 Environment Configuration

The guide includes a complete `.env` configuration template:

```env
APP_NAME="Personal Info Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/personal-info-management-system
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=personal_info_system
DB_USERNAME=root
DB_PASSWORD=
# ... and more
```

## 🔧 Common Issues & Solutions

The guide includes troubleshooting for:
- Composer memory limit errors
- Node.js version conflicts
- Database connection issues
- File permission problems
- Vite build errors
- Livewire not working

## 💡 Tips for New Users

1. **Use Laragon** - Simplifies Windows development setup
2. **Run `migrate:fresh --seed`** - Clean setup with sample data
3. **No need for `php artisan serve`** with Laragon
4. **Always copy commands** - Use the copy buttons to avoid typos
5. **Check logs** - `storage/logs/laravel.log` for debugging

## 🌐 Access Points

After setup:
- **Main Application:** `http://localhost/personal-info-management-system`
- **Login Page:** `http://127.0.0.1:8000/login`
- **phpMyAdmin:** `http://localhost/phpmyadmin`

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Laragon Documentation](https://laragon.org/docs/)

## 🤝 Contributing

Found an issue or improvement? Please:
1. Check existing issues
2. Create a new issue with details
3. Submit pull requests for improvements

## 📄 License

This setup guide is part of the Personal Info Management System project. Please refer to the main project's license.

## 📞 Support

If you encounter issues during setup:
1. Review the troubleshooting section in the guide
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify all prerequisites are installed
4. Ensure all steps were followed in order

---

**Happy Coding! 🎉**

This guide will help you get your Personal Info Management System running in no time!
