# Agence Immobilière Website

## 🚀 Overview

This project is a comprehensive and dynamic **Agence Immobilière (Real Estate Agency) Website** designed to connect property owners, real estate agents, and prospective buyers/renters. It offers a robust platform for managing various types of real estate listings including **villas, houses, and lands**, facilitating both **rental and sale transactions**.

Built with a strong focus on security, data management, and user experience, the website features distinct functionalities for **Administrators** and **Regular Users**, ensuring efficient property management and seamless user interaction.

---

## ✨ Features

This website boasts a rich set of functionalities, catering to both administrative control and user-facing interactions.

### General Features:

* **Responsive Design:** Optimized for various devices (desktops, tablets, mobiles).
* **Secure Authentication:** Robust user login and registration system.
* **Database Integration:** Efficient data storage and retrieval using MySQL.
* **Interactive UI:** Enhanced user experience with dynamic elements.

### User Side (Client-Facing):

* **Property Listings:** Browse and view detailed listings for villas, houses, and lands available for rent or sale.
* **Advanced Search & Filtering:** Easily find properties based on various criteria (e.g., location, price, type, number of rooms).
* **Property Detail Pages:** Comprehensive information for each property, including descriptions, amenities, image galleries, and pricing.
* **Offer Submission:** Users can make offers or inquiries on properties.
* **Contact Forms:** Direct communication channels for inquiries, appointments, or general questions.
* **My Houses/Properties:** A dedicated section for users to manage their own listed properties (if applicable).
* **Reservation Functionality:** Potential for users to reserve properties or schedule viewings.
* **Payment History (if implemented):** Tracking of payment-related activities for rentals or purchases.

### Admin Side (Management & Control):

* **Dashboard & Analytics:** Centralized overview of website activity, key metrics, and statistics (e.g., total properties, new inquiries, user activity).
* **Property Management:**
    * **Add/Edit/Delete Properties:** Full CRUD (Create, Read, Update, Delete) operations for all property types (villas, houses, lands).
    * **Manage Property Images:** Upload, organize, and delete images for each listing.
    * **Property Status Control:** Update property availability (e.g., sold, rented, available).
* **User Management:**
    * **View/Edit User Accounts:** Manage user profiles and permissions.
    * **User Lists:** Overview of all registered users.
* **Offer/Inquiry Management:** Review and respond to user offers and contact form submissions.
* **Reservation Management:** Oversee and manage property reservations made by users.
* **Payment History Management:** Access and manage payment records and histories.
* **Security Controls:** Implement and manage security settings.
* **Component Management:** Potentially manage reusable UI components or content sections.

---

## 🛠️ Technologies Used

* **Frontend:**
    * **HTML5:** Structure and content of web pages.
    * **CSS3:** Styling and visual presentation (including `style.css`, `StyleAll.css`, `squelette.css`).
    * **JavaScript (JS):** Client-side interactivity, dynamic content loading, form validation (including `com.js`, `res.js`, `users.js`, `offers.js`, `pay.js`, `searchbar.js`, `app.js`, `send_message.js`, `index.js`, `loginAdmin.js`, `DashboardJS.js`, `admin_js.js`, `changePassword.js`, `MainPage.js`, `offres.js`).
* **Backend:**
    * **PHP:** Server-side logic, routing, and database interaction.
* **Database:**
    * **MySQL:** Relational database management system for storing property data, user information, offers, reservations, etc.
* **Database Interaction:**
    * **PHP Data Objects (PDO):** Secure and efficient database access from PHP.


## 🚀 Getting Started

To get this project up and running on your local machine, follow these steps.

### Prerequisites

Before you begin, ensure you have the following installed:

* **Web Server:** Apache or Nginx (often bundled with XAMPP, WAMP, MAMP)
* **PHP:** Version 7.4 or higher (or the specific version you used)
* **MySQL:** Database server
* **Git:** For cloning the repository

### Installation Steps

1.  **Clone the Repository:**
    ```bash
    git clone [https://github.com/MohammedYessineKraiem/real-estate-agency.git](https://github.com/MohammedYessineKraiem/real-estate-agency.git)
    cd agence-immobilier
    ```
    *(Replace `MohammedYessineKraiem/real-estate-agency` with your actual GitHub repository path)*

2.  **Set up Database:**
    * Create a new MySQL database (e.g., `bzbzd`).
    * Import your database schema and initial data. If you have an `.sql` file, you can import it using phpMyAdmin or the MySQL command line:
        ```bash
        mysql -u your_username -p bzbzd < path/to/your/database_dump.sql
        ```
        *(Replace `your_username`, `bzbzd`, and `path/to/your/database_dump.sql` with your credentials and file path)*

3.  **Configure Database Connection:**
    * Locate your database connection file (likely `db_connection.php` or a similar file in `includes/`).
    * Update the database credentials (hostname, username, password, database name) to match your MySQL setup.
    * *(If you use environment variables, create a `.env` file based on `.env.example` and fill in the details.)*

4.  **Place Project on Web Server:**
    * Move the cloned `agence-immobilier` folder into your web server's document root (e.g., `htdocs` for Apache/XAMPP, `www` for WAMP, `public_html` for MAMP).

5.  **Start Web Server:**
    * Ensure your Apache/Nginx and MySQL services are running.

---

## 🖥️ Usage

1.  **Access the Website:**
    * Open your web browser and navigate to the project's URL. If you placed it directly in your web server's root, it might be `http://localhost/agence-immobilier/` or `http://localhost/` depending on your setup.

2.  **User Access:**
    * Users can browse properties immediately.
    * To make offers or access personalized features, users will need to register and log in via the designated forms.

3.  **Admin Access:**
    * Navigate to the admin login page (e.g., `http://localhost/agence-immobilier/admin/LoginAdmin.php`).
    * Log in with administrator credentials to access the admin dashboard and management functionalities.

---

## 💡 Future Enhancements

Here are some potential features and improvements that could be added to the website:

* **Advanced Image Management:** Implement drag-and-drop image uploads, image resizing, and watermarking.
* **Map Integration:** Integrate Google Maps or OpenStreetMap to display property locations.
* **User Reviews/Ratings:** Allow users to leave reviews or ratings for properties or agents.
* **Chat/Messaging System:** Enable direct communication between users and agents within the platform.
* **Push Notifications:** Notify users about new listings or status updates.
* **Property Alerts:** Allow users to set up alerts for properties matching their criteria.
* **Multi-language Support:** Implement internationalization (i18n) for multiple languages.
* **API for Mobile Apps:** Create a RESTful API to support potential mobile applications.
* **Improved Search Algorithm:** Enhance search relevancy and speed.
* **Analytics Integration:** Integrate with tools like Google Analytics for deeper insights.
* **Automated Email Notifications:** For inquiries, offer updates, etc.
* **Frontend Framework:** Consider integrating a modern JavaScript framework (e.g., React, Vue, Angular) for a more dynamic and modular frontend, while still using PHP for the backend.

---

## 🤝 Contributing

At this time, we are not actively accepting external contributions. However, if you find any bugs or have suggestions, please feel free to open an issue on this repository.

---

## 📄 License

This project is licensed under the **MIT License**. See the `LICENSE` file for more details.

---

## 📧 Contact

For any questions or support, please reach out to:

* **Email:** moyesskr@gmail.com

---
