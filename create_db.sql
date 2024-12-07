DROP DATABASE IF EXISTS library_management_system;
CREATE DATABASE library_management_system;
USE library_management_system;

DROP TABLE IF EXISTS penalties;
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS rentals;
DROP TABLE IF EXISTS user_profile;
DROP TABLE IF EXISTS staff;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS books;

CREATE TABLE books ( 
    BookID INT PRIMARY KEY, 
    Title VARCHAR(255), 
    Author VARCHAR(255), 
    Genre VARCHAR(100), 
    ISBN INT, 
    PublishDate DATE, 
    Publisher VARCHAR(255) 
); 

CREATE TABLE user ( 
    UserID INT PRIMARY KEY, 
    Name VARCHAR(255), 
    Address VARCHAR(255), 
    PhoneNumber VARCHAR(15), 
    PenaltyBalance DECIMAL(10,2) DEFAULT 0.00 
); 

CREATE TABLE rentals ( 
    RentalID INT PRIMARY KEY, 
    BookID INT, 
    CheckoutDate DATE, 
    DueDate DATE, 
    DateReturned DATE, 
    UserID INT, 
    FOREIGN KEY (BookID) REFERENCES books(BookID), 
    FOREIGN KEY (UserID) REFERENCES user(UserID) 
); 

CREATE TABLE user_profile ( 
    UserID INT PRIMARY KEY, 
    Name VARCHAR(255), 
    Description VARCHAR(255), 
    FaveGenre VARCHAR(100), 
    FaveBook VARCHAR(255), 
    ProfilePicture VARCHAR(255), 
    FOREIGN KEY (UserID) REFERENCES user(UserID) 
); 

CREATE TABLE events ( 
    EventID INT PRIMARY KEY, 
    StartDate DATE, 
    EndDate DATE, 
    Location VARCHAR(255), 
    Description VARCHAR(255) 
); 

CREATE TABLE staff ( 
    StaffID INT PRIMARY KEY, 
    Name VARCHAR(255), 
    Address VARCHAR(255), 
    Position VARCHAR(100), 
    Salary DECIMAL(10,2) 
); 

CREATE TABLE reservations ( 
    ReserveID INT PRIMARY KEY, 
    BookID INT, 
    UserID INT, 
    StaffID INT, 
    ReserveDate DATE, 
    ReserveEndDate DATE, 
    FOREIGN KEY (BookID) REFERENCES books(BookID), 
    FOREIGN KEY (UserID) REFERENCES user(UserID), 
    FOREIGN KEY (StaffID) REFERENCES staff(StaffID) 
); 

CREATE TABLE penalties ( 
    PenaltyID INT PRIMARY KEY, 
    BookID INT, 
    UserID INT, 
    PenaltyDate DATE, 
    PenaltyAmt DECIMAL(10,2), 
    FOREIGN KEY (BookID) REFERENCES books(BookID), 
    FOREIGN KEY (UserID) REFERENCES user(UserID) 
); 
