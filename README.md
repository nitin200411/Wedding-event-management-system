# 💒 Wedding Event Management System

This is a dynamic web-based application designed to help users plan and manage wedding events effortlessly. It creates a streamlined and user-friendly planning environment, targeting users who may feel overwhelmed by the numerous tasks and complexities associated with coordinating a wedding.

From booking venues and vendors to selecting between full and custom packages, this platform simplifies wedding planning with an intuitive interface.

---

## ✨ Key Features

* **User Authentication:** Secure Sign Up and Login system for users to manage their plans.
* **Package Selection:** Choose between two distinct planning options:
    * 🎁 **Full Package:** An all-inclusive plan that covers all major events (Engagement, Reception, Marriage) with pre-selected vendor categories.
    * 🧩 **Custom Package:** A flexible plan that allows users to select and plan only the specific events they need.
* [cite_start]**Venue & Vendor Exploration:** A dynamic catalog to browse and select venues (like resorts, temples, banquet halls) [cite: 773-953] and vendors for specific events.
* [cite_start]**Detailed Event Planning:** Interactive forms to input crucial event details, including dates, times, and the number of guests for each ceremony [cite: 552-632].
* [cite_start]**Automatic Budget Calculation:** A summary page that automatically calculates and displays an itemized budget based on all user selections (venue costs, food costs, etc.) [cite: 357-364].

---

## 🛠️ Technologies Used

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL

---

## 📸 Screenshots


**Example:**
* **Homepage:**
    <img width="1819" height="892" alt="image" src="https://github.com/user-attachments/assets/87d1be57-8957-4f89-9137-e5923907f20a" />

* **Full Wedding Package Page:**
   <img width="1904" height="895" alt="image" src="https://github.com/user-attachments/assets/01eace4e-e30e-47ed-bf64-2c85ba0971ac" />

* **Venues Page:**
    <img width="1897" height="889" alt="image" src="https://github.com/user-attachments/assets/747a5ef2-6927-43a5-90b5-cc0c4d475511" />

---

## 🏁 Getting Started

To get a local copy up and running, follow these simple steps.

### 🔧 Prerequisites

You will need a local server environment to run the PHP and MySQL database.
* [**XAMPP**](https://www.apachefriends.org/index.html) (or any other WAMP/MAMP stack)

### 🚀 Installation

1.  **Clone the Repository**
    ```sh
    git clone [https://github.com/nitin200411/Wedding-event-management-system.git](https://github.com/nitin200411/Wedding-event-management-system.git)
    ```

2.  **Place the Folder**
    * Move the cloned project folder (`Wedding-event-management-system`) into your server's `htdocs` (for XAMPP) or `www` (for WAMP) directory.

3.  **Set Up the Database**
    * Open **phpMyAdmin** from your XAMPP control panel.
    * [cite_start]Create a new database named `wonder_wedding`[cite: 27].
    * Select the `wonder_wedding` database and go to the **Import** tab.
    * [cite_start]Upload and import the `.sql` file provided in the repository (or set up the tables as per the code, e.g., `user_info` [cite: 1055] [cite_start]and `user_packages` [cite: 1005]).

4.  **Run the Application**
    * Start **Apache** and **MySQL** from your XAMPP control panel.
    * Open your web browser and navigate to:
        `http://localhost/Wedding-event-management-system/`
    * You should see the homepage. You can now sign up and start planning!

---

## 👨‍💻 Authors

[cite_start]This project was built as part of the Modern Web Applications (CBS3014) course [cite: 12] [cite_start]at Vellore Institute of Technology[cite: 9].

* **Ch Anish** - 22BBS0047
* **Sisnu SK** - 22BBS0063
* **Nitin D** - 22BBS0200
