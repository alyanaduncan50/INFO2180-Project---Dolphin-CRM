-- Create Contacts Table
CREATE TABLE Contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,        
    title VARCHAR(50),                            
    firstname VARCHAR(100) NOT NULL,               
    lastname VARCHAR(100) NOT NULL,               
    email VARCHAR(150) NOT NULL UNIQUE,           
    telephone VARCHAR(15),                         
    company VARCHAR(150),                           
    type VARCHAR(50),                             
    assigned_to INT,                               
    created_by INT NOT NULL,                        
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
);

-- Create Users Table
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,              
    firstname VARCHAR(100) NOT NULL,                
    lastname VARCHAR(100) NOT NULL,                
    email VARCHAR(150) NOT NULL UNIQUE,             
    password VARCHAR(255) NOT NULL,                 
    role VARCHAR(50),                               
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP   
);



-- Create the Notes table
CREATE TABLE Notes (
    id INT AUTO_INCREMENT PRIMARY KEY,            
    contact_id INT NOT NULL,                     
    comment TEXT NOT NULL,                   
    created_by INT NOT NULL,                      
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert a sample admin user
INSERT INTO Users (firstname, lastname, email, password, role)
VALUES (
    'Admin', 
    'User', 
    'admin@project2.com', 
    '$2y$10$N.VcqiJdiTiHYaadIBhFT.8VQggvIjwSyrGpHJK0CXTk7nYJGb7lm',
    'Admin'
);
