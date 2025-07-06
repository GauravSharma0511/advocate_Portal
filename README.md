create tables on mysql like these
advocate_users – Stores registration details
sql
Copy
Edit
CREATE TABLE advocate_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    enrollment_no VARCHAR(20) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    mobile_encrypted TEXT NOT NULL,
    email_encrypted TEXT NOT NULL,
    state VARCHAR(50) NOT NULL,
    district VARCHAR(50) NOT NULL,
    pin_code VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

advocate profiles 
advocate_profiles – Stores additional profile info
sql
Copy
Edit
CREATE TABLE advocate_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    dob DATE NOT NULL,
    enrollment_date DATE NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES advocate_users(id) ON DELETE CASCADE
);
