use library_management_system;

DELETE FROM reservations;
DELETE FROM rentals;
DELETE FROM user_profile;
DELETE FROM staff;
DELETE FROM events;
DELETE FROM user;
DELETE FROM books;

INSERT INTO user (UserID, username, password, Name) VALUES (1, 'overdue', '$2y$10$.xPPzcnWsDEGLOhyfISuWucybmPADLoh9DOByQ29z6CzsptZwmvoq', 'Overdue Owen');
INSERT INTO user (UserID, username, password, Name) VALUES (2, 'test', '$2y$10$hfOy.ORsX8PgRdR9TkY42ee.H4eDxC0kX3bYJTFgfvFp6nACfp0rq', 'test');
INSERT INTO user (UserID, username, password, Name) VALUES (3, 'user3', 'password2', 'Jane Doe');
INSERT INTO user (UserID, username, password, Name) VALUES (4, 'user4', 'password4', 'Alice Johnson');
INSERT INTO user (UserID, username, password, Name) VALUES (5, 'user5', 'password5', 'Michael Green');
INSERT INTO user (UserID, username, password, Name) VALUES (6, 'user6', 'password6', 'Linda White');

INSERT INTO books VALUES (1, 'To Kill a Mockingbird', 'Harper Lee', 'Fiction', '9780060935467', '1960-07-11', 'J.B. Lippincott & Co.', 'https://upload.wikimedia.org/wikipedia/commons/4/4f/To_Kill_a_Mockingbird_%28first_edition_cover%29.jpg', 'rented');
INSERT INTO books VALUES (2, 'The Catcher in the Rye', 'J.D. Salinger', 'Fiction', '9780316769488', '1951-07-16', 'Little, Brown and Company', 'https://m.media-amazon.com/images/I/8125BDk3l9L._AC_UF894,1000_QL80_.jpg', 'rented');
INSERT INTO books VALUES (3, 'The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', '9780743273565', '1925-04-10', 'Scribner', 'https://m.media-amazon.com/images/I/81QuEGw8VPL._AC_UF894,1000_QL80_.jpg', 'available');

INSERT INTO books VALUES (4, '1984', 'George Orwell', 'Dystopian', '9780451524935', '1949-06-08', 'Secker & Warburg', 'https://www.theoriginalunderground.com/cdn/shop/files/1984-george-orwell-cover-print-579534_1500x.jpg?v=1729242194', 'available');
INSERT INTO books VALUES (5, 'Brave New World', 'Aldous Huxley', 'Dystopian', '9780060850524', '1932-08-31', 'Harper & Brothers', 'https://m.media-amazon.com/images/I/71GNqqXuN3L.jpg', 'available');
INSERT INTO books VALUES (6, 'Fahrenheit 451', 'Ray Bradbury', 'Dystopian', '9781451673319', '1953-10-19', 'Ballantine Books', 'https://images.booksense.com/images/319/673/9781451673319.jpg', 'available');

INSERT INTO books VALUES (7, 'Moby-Dick', 'Herman Melville', 'Adventure', '9781503280786', '1851-10-18', 'Harper & Brothers', 'https://m.media-amazon.com/images/I/610qaD5PskL.jpg', 'available');
INSERT INTO books VALUES (8, 'The Hobbit', 'J.R.R. Tolkien', 'Adventure', '9780547928227', '1937-09-21', 'George Allen & Unwin', 'https://m.media-amazon.com/images/I/712cDO7d73L.jpg', 'available');
INSERT INTO books VALUES (9, 'The Alchemist', 'Paulo Coelho', 'Adventure', '9780062315007', '1988-05-01', 'HarperOne', 'https://m.media-amazon.com/images/I/71zHDXu1TaL._AC_UF894,1000_QL80_.jpg', 'available');

INSERT INTO books VALUES (10, 'Pride and Prejudice', 'Jane Austen', 'Romance', '9780141439518', '1813-01-28', 'T. Egerton', 'https://m.media-amazon.com/images/I/81Scutrtj4L._UF1000,1000_QL80_.jpg', 'available');
INSERT INTO books VALUES (11, 'The Fault in Our Stars', 'John Green', 'Romance', '9780525478812', '2012-01-10', 'Dutton Books', 'https://m.media-amazon.com/images/I/61gq65lOUQL._AC_UF894,1000_QL80_.jpg', 'available');
INSERT INTO books VALUES (12, 'Emma', 'Jane Austen', 'Romance', '9780141439501', '1815-12-23', 'T. Egerton', 'https://m.media-amazon.com/images/I/81WX67s6LqL.jpg', 'available');

INSERT INTO books VALUES (13, 'The Shining', 'Stephen King', 'Horror', '9780385121675', '1977-01-28', 'Doubleday', 'https://m.media-amazon.com/images/I/81CuEX3W9UL.jpg', 'available');
INSERT INTO books VALUES (14, 'Dracula', 'Bram Stoker', 'Horror', '9780141439842', '1897-05-26', 'Penguin Classics', 'https://m.media-amazon.com/images/I/91JCn5GWCjL.jpg', 'available');
INSERT INTO books VALUES (15, 'It', 'Stephen King', 'Horror', '9780452281841', '1986-09-15', 'Viking Press', 'https://m.media-amazon.com/images/I/71ZIovNjw+L.jpg', 'available');

INSERT INTO books VALUES (16, 'Of Mice and Men', 'John Steinbeck', 'Fiction', '9780140177398', '1937-02-06', 'Penguin Books', 'https://m.media-amazon.com/images/I/71xXwHUrsxL.jpg', 'available');
INSERT INTO books VALUES (17, 'The Road', 'Cormac McCarthy', 'Fiction', '9780307387899', '2006-09-26', 'Alfred A. Knopf', 'https://m.media-amazon.com/images/I/51M7XGLQTBL.jpg', 'available');
INSERT INTO books VALUES (18, 'Beloved', 'Toni Morrison', 'Fiction', '9781400033416', '1987-09-16', 'Knopf', 'https://m.media-amazon.com/images/I/51Qj9kPD4CL.jpg', 'available');
INSERT INTO books VALUES (19, 'The Book Thief', 'Markus Zusak', 'Fiction', '9780375842207', '2005-09-01', 'Knopf Books', 'https://m.media-amazon.com/images/I/914cHl9v7oL.jpg', 'available');
INSERT INTO books VALUES (20, 'Life of Pi', 'Yann Martel', 'Fiction', '9780156027328', '2001-09-11', 'Knopf Canada', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1631251689i/4214.jpg', 'available');
INSERT INTO books VALUES (21, 'The Kite Runner', 'Khaled Hosseini', 'Fiction', '9781594631931', '2003-05-29', 'Riverhead Books', 'https://m.media-amazon.com/images/I/81LVEH25iJL.jpg', 'available');
INSERT INTO books VALUES (22, 'A Thousand Splendid Suns', 'Khaled Hosseini', 'Fiction', '9781594483859', '2007-05-22', 'Riverhead Books', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1655336738i/128029.jpg', 'available');
INSERT INTO books VALUES (23, 'The Help', 'Kathryn Stockett', 'Fiction', '9780399155345', '2009-02-10', 'Amy Einhorn Books', 'https://m.media-amazon.com/images/I/61taK-2tICL.jpg', 'available');
INSERT INTO books VALUES (24, 'The Night Circus', 'Erin Morgenstern', 'Fiction', '9780307744432', '2011-09-13', 'Doubleday','https://m.media-amazon.com/images/I/71+whvJjE3L.jpg', 'available');

INSERT INTO staff VALUES (1, 'Linda Green', '123 Library Ave, Springfield', 'Librarian', 45000.00);
INSERT INTO staff VALUES (2, 'Tom Carter', '456 Paper St, Shelbyville', 'Assistant Librarian', 35000.00);
INSERT INTO staff VALUES (3, 'Sarah Miles', '789 Book Ln, Capital City', 'Event Coordinator', 40000.00);
INSERT INTO staff VALUES (4, 'Michael Reid', '101 Stack Rd, Ogdenville', 'Archivist', 42000.00);
INSERT INTO staff VALUES (5, 'Emily White', '202 Story Ct, North Haverbrook', 'Library Technician', 30000.00);

INSERT INTO events VALUES (1, '2024-03-01', '2024-03-03', 'Library Hall A', 'Spring Book Fair');
INSERT INTO events VALUES (2, '2024-05-15', '2024-05-15', 'Library Conference Room', 'Author Meet-and-Greet with Harper Lee');
INSERT INTO events VALUES (3, '2024-07-20', '2024-07-20', 'Library Main Hall', 'Summer Reading Challenge Finale');
INSERT INTO events VALUES (4, '2024-10-10', '2024-10-10', 'Library Hall B', 'Fall Lecture Series: Classics Revisited');
INSERT INTO events VALUES (5, '2024-12-05', '2024-12-05', 'Library Auditorium', 'Holiday Book Drive Gala');

INSERT INTO rentals VALUES (1, 1, '2024-02-01', '2024-02-15', NULL, 1);
INSERT INTO rentals VALUES (2, 2, '2024-03-01', '2024-03-15', NULL, 1);
INSERT INTO rentals VALUES (3, 3, '2024-04-01', '2024-04-15', '2024-04-14', 3);

INSERT INTO reservations VALUES (1, 1, 1, '2024-02-15');
INSERT INTO reservations VALUES (2, 2, 2, '2024-03-15');
INSERT INTO reservations VALUES (3, 3, 3, '2024-04-15');
