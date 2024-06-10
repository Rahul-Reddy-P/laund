-- Drop the existing database (WARNING: This will delete all data in the existing database)
DROP DATABASE IF EXISTS LaundryServiceDB;

-- Create the database again
CREATE DATABASE LaundryServiceDB;

-- Use the database
USE LaundryServiceDB;

-- Create Users table
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL,
    PhoneNumber VARCHAR(15),
    Address VARCHAR(255),
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Services table
CREATE TABLE Services (
    ServiceID INT AUTO_INCREMENT PRIMARY KEY,
    ServiceName VARCHAR(100) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2) NOT NULL
);

-- Create Orders table
CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Status ENUM('Pending', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Pending',
    TotalAmount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create OrderDetails table to link Orders with Services
CREATE TABLE OrderDetails (
    OrderDetailID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT,
    ServiceID INT,
    Quantity INT NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (ServiceID) REFERENCES Services(ServiceID)
);

-- Create Payments table
CREATE TABLE Payments (
    PaymentID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT,
    PaymentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Amount DECIMAL(10, 2) NOT NULL,
    PaymentMethod ENUM('Credit Card', 'Debit Card', 'PayPal', 'Cash') NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID)
);


-- Insert sample data into Services table
INSERT INTO Services (ServiceName, Description, Price)
VALUES 
('Wash & Fold', 'Standard wash and fold service', 10.00),
('Dry Cleaning', 'Dry cleaning for delicate garments', 20.00),
('Ironing', 'Ironing service for clothes', 5.00),
('Wash & Iron', 'Wash and iron service', 15.00);


-- Insert sample data into Users table
INSERT INTO Users (FirstName, LastName, Email, PasswordHash, PhoneNumber, Address)
VALUES 
('John', 'Doe', 'john.doe@example.com', 'hashedpassword123', '1234567890', '123 Main St, Anytown, USA'),
('Jane', 'Smith', 'jane.smith@example.com', 'hashedpassword456', '0987654321', '456 Elm St, Othertown, USA');

-- Example: Insert an order and its details
INSERT INTO Orders (UserID, TotalAmount)
VALUES (1, 30.00);

INSERT INTO OrderDetails (OrderID, ServiceID, Quantity, Price)
VALUES 
(LAST_INSERT_ID(), 1, 2, 20.00),
(LAST_INSERT_ID(), 3, 1, 10.00);

-- Example: Insert a payment for the order
INSERT INTO Payments (OrderID, Amount, PaymentMethod)
VALUES (LAST_INSERT_ID(), 30.00, 'Credit Card');
